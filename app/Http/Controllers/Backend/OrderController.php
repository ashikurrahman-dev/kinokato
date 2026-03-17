<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\City;
use App\Models\Comment;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\Area;
use App\Models\SupplierPayment;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Varient;
use App\Models\Orderproduct;
use App\Models\Wsale;
use App\Models\Payment;
use App\Models\Wpayment;
use App\Models\Expense;
use App\Models\Paymentcomplete;
use App\Models\Paymenttype;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Store;
use App\Models\Admin;
use App\Models\Size;
use App\Models\Zone;
use App\Models\Account;
use App\Models\User;
use App\Models\Basicinfo;
use App\Models\Costhistory;
use App\Models\Incomehistory;
use App\Models\Incompleteorder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Session;
use Codeboxr\PathaoCourier\Facade\PathaoCourier;

class OrderController extends Controller
{
    public function citydata($id)
    {
        $ex = Order::where('city_id', $id)->get();
        if (count($ex) > 0) {
            $data = [
                'total' => Order::where('city_id', $id)->get()->count(),
                'rts' => Order::where('city_id', $id)->where('status', 'Ready to Ship')->get()->count(),
                'd' => Order::where('city_id', $id)->where('status', 'Completed')->get()->count(),
                'c' => Order::where('city_id', $id)->where('status', 'Cancelled')->get()->count(),
                'df' => Order::where('city_id', $id)->where('status', 'Del. Failed')->get()->count(),
            ];
        } else {
            $data = [
                'total' => 0,
                'rts' => 0,
                'd' => 0,
                'c' => 0,
                'df' => 0,
            ];
        }
        return view('backend.mapdata', ['data' => $data]);
    }
    public function maps()
    {
        $cities = City::where('status', 'Active')->get();
        return view('backend.content.maps.index', ['cities' => $cities]);
    }

    public function fraudcheck(Request $request)
    {
        $data = Http::get('https://dash.hoorin.com/api/courier/sheet.php?apiKey=482d1b4abe32db2cecf22e&searchTerm=' . $request->number . '');
        $datainfos = Http::get('https://dash.hoorin.com/api/courier/search.php?apiKey=482d1b4abe32db2cecf22e&searchTerm=' . $request->number . '');

        if ($data['Summaries']['Total Parcels'] > 0) {
            $success = intval(($data['Summaries']['Total Delivered'] / $data['Summaries']['Total Parcels']) * 100);
            $cancel = intval(($data['Summaries']['Total Canceled'] / $data['Summaries']['Total Parcels']) * 100);
        } else {
            $success = 0;
            $cancel = 0;
        }

        $st = $datainfos['Summaries']['Steadfast Old']['Total Parcels'];
        $sd = $datainfos['Summaries']['Steadfast Old']['Delivered Parcels'];
        $sc = $datainfos['Summaries']['Steadfast Old']['Canceled Parcels'];

        $snt = $datainfos['Summaries']['Steadfast New']['Total Parcels'];
        $snd = $datainfos['Summaries']['Steadfast New']['Delivered Parcels'];
        $snc = $datainfos['Summaries']['Steadfast New']['Canceled Parcels'];

        $rt = $datainfos['Summaries']['RedX']['Total Parcels'];
        $rd = $datainfos['Summaries']['RedX']['Delivered Parcels'];
        $rc = $datainfos['Summaries']['RedX']['Canceled Parcels'];

        $pt = $datainfos['Summaries']['Paperfly']['Total Parcels'];
        $pd = $datainfos['Summaries']['Paperfly']['Delivered Parcels'];
        $pc = $datainfos['Summaries']['Paperfly']['Canceled Parcels'];

        $ptt = $datainfos['Summaries']['Pathao']['Total Delivery'];
        $ptd = $datainfos['Summaries']['Pathao']['Successful Delivery'];
        $ptc = $datainfos['Summaries']['Pathao']['Canceled Delivery'];

        return view('auth.fraud', [
            'success' => $success,
            'cancel' => $cancel,
            'ptt' => $ptt,
            'ptd' => $ptd,
            'ptc' => $ptc,
            'pt' => $pt,
            'pd' => $pd,
            'pc' => $pc,
            'rc' => $rc,
            'rd' => $rd,
            'rt' => $rt,
            'snt' => $snt,
            'snd' => $snd,
            'snc' => $snc,
            'st' => $st,
            'sd' => $sd,
            'sc' => $sc
        ]);
    }

    public function sendsms(Request $request)
    {
        if (isset($request->phone) && isset($request->message)) {
            $sendstatus = Http::get('http://bulksmsbd.net/api/smsapi?api_key=3z2e9owl4PGXLakGMAmv&type=text&number=' . $request->phone . '&senderid=RASHIBD.COM&message=' . $request->message . '');

            $response['status'] = 'success';
            $response['message'] = 'SMS Send Successfully';
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Data can not be null';
        }

        return json_encode($response);
    }




    public function incompleteorder($status)
    {
        $admin = Admin::where('email', Auth::guard('admin')->user()->email)->first();
        $status = $status;
        return view('admin.content.order.incompleteorder', ['admin' => $admin, 'status' => $status]);
    }

    public function deleteinc($id)
    {
        $admin = Incompleteorder::where('id', $id)->delete();
        return response()->json('success',200);
    }

    public function incompletedata(Request $request, $abc)
    {
        $admin = Admin::where('email', Auth::guard('admin')->user()->email)->first();

        $status = $abc;

        if ($abc == 'orderall') {
            $orders =  Incompleteorder::get();
        } else {
            $orders =  Incompleteorder::where('status', $status);
        }



        return Datatables::of($orders)
            ->addColumn('customerInfo', function ($orders) {
                return '<p style="font-weight:bold;color:black">' . $orders->customerName . '<br>' . $orders->customerAddress . '</p><b>' . $orders->customerPhone . '</b>';
            })

            ->editColumn('products', function ($orders) {
                $orderProducts = '';
                foreach (json_decode($orders->cartproducts) as $product) {
                    $prods = Product::where('id', $product->id)->first();
                    if (isset($prods)) {
                        $orderProducts = $orderProducts . '<a target="_blank" href="../view-product/' . Product::where('id', $product->id)->first()->ProductSlug . '"><img src="../' . Product::where('id', $product->id)->first()->ProductImage . '" style="width:60px"></a><br>' . $product->qty . ' x ' . $product->name;
                    } else {
                        $orderProducts = $orderProducts . '<span style="color:red">Produt is Deleted</span> , ';
                    }
                }
                return rtrim($orderProducts, '<br>');
            })
            ->editColumn('statusbutton', function ($orders) {
                return $orders->status = $this->statusListInc($orders->status, $orders->id);
            })
            ->editColumn('price', function ($orders) {
                return '<span style="color:black;font-size: 22px;font-weight:bold">৳&nbsp;' . $orders->subTotal . '<br></span>';
            })

            ->addColumn('action', function ($orders) {
                return '<a href="#" type="button" id="deleteIncompleteBtn" data-id="' . $orders->id . '" class="btn btn-danger btn-sm" ><i class="bi bi-archive" ></i></a>';
            })
            ->escapeColumns([])->make();
    }

    public function incups(Request $request)
    {
        $id = $request['id'];

        $status = $request['status'];
        $order = Incompleteorder::find($id);
        $order->status = $status;
        $result = $order->update();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Update Status to ' . $request['status'];
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to update Status ' . $request['status'];
        }

        return json_encode($response);
    }

    public function statusListInc($status, $id)
    {
        $allStatus = array(
            'order' => array(
                "Incomplete" => array(
                    "name" => "Incomplete",
                    "icon" => "fe-tag",
                    "color" => " bg-light"
                ),
                "Hold" => array(
                    "name" => "Hold",
                    "icon" => "fe-tag",
                    "color" => " bg-warning"
                ),
                "Done" => array(
                    "name" => "Done",
                    "icon" => "fe-tag",
                    "color" => " bg-success"
                ),
                "Cancelled" => array(
                    "name" => "Cancelled",
                    "icon" => "far fa-stop-circle",
                    "color" => " bg-danger"
                ),
            )
        );


        $temp = 'order';
        foreach ($allStatus as $key => $value) {
            foreach ($value as $kes => $val) {
                if ($kes == $status) {
                    $temp = $key;
                }
            }
        }
        $args = $allStatus[$temp];
        $html = '';
        foreach ($args as $value) {
            if ($args[$status]['name'] != $value['name']) {

                $html = $html . "<a class=' btn-sm dropdown-item btn-status' data-id='" . $id . "' data-status='" . $value['name'] . "' href='#'>" . $value['name'] . "</a>";
            }
        }
        $response = "<div class='btn-group dropdown'>
            <a href='javascript: void(0);' style='color:white'  class=' btn-sm table-action-btn dropdown-toggle arrow-none btn" . $args[$status]['color'] . " btn-xs' data-bs-toggle='dropdown' aria-expanded='false' >" . $args[$status]['name'] . " <i class='mdi mdi-chevron-down'></i></a>
            <div class='dropdown-menu dropdown-menu-right'>
            " . $html . "
            </div>
        </div>";

        return $response;
    }

    public function orderByproductindex()
    {
        return view('admin.content.order.productfindbyorder');
    }

    public function findByproduct(Request $request)
    {
        $admin = Admin::where('email', Auth::guard('admin')->user()->email)->first();
        $order_ids = Orderproduct::where('productName', 'LIKE', "%{$request->product_name}%")->select('order_id')->get();

        if ($request->date) {
            if ($admin->hasRole('superadmin') || $admin->hasRole('admin') || $admin->hasRole('manager')) {
                if (isset($request->user_id)) {
                    foreach ($order_ids as $order_id) {
                        $orders[] =  Order::with([
                            'orderproducts' => function ($query) {
                                $query->select('id', 'order_id', 'productName', 'quantity', 'color', 'size');
                            },
                            'admins' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'couriers' => function ($query) {
                                $query->select('id', 'courierName');
                            },
                            'products' => function ($query) {
                                $query->select('id', 'ProductName', 'ProductRegularPrice');
                            },
                            'comments' => function ($query) {
                                $query->select('id', 'order_id', 'comment', 'admin_id', 'status', 'created_at')->where('status', 0);
                            },
                            'cities' => function ($query) {
                                $query->select('id', 'courier_id', 'cityName');
                            },
                            'zones' => function ($query) {
                                $query->select('id', 'courier_id', 'city_id', 'zoneName');
                            }
                        ])->where('orders.id', $order_id->order_id)
                            ->where('orders.orderDate', $request->date)
                            ->where('admin_id', $request->user_id)
                            ->where('status', $request->status)
                            ->join('customers', 'customers.order_id', '=', 'orders.id')
                            ->select('orders.*', 'customers.customerPhone', 'customers.customerName', 'customers.customerAddress')->first();
                    }
                } else {
                    foreach ($order_ids as $order_id) {
                        $orders[] =  Order::with([
                            'orderproducts' => function ($query) {
                                $query->select('id', 'order_id', 'productName', 'quantity', 'color', 'size');
                            },
                            'admins' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'couriers' => function ($query) {
                                $query->select('id', 'courierName');
                            },
                            'products' => function ($query) {
                                $query->select('id', 'ProductName', 'ProductRegularPrice');
                            },
                            'comments' => function ($query) {
                                $query->select('id', 'order_id', 'comment', 'admin_id', 'status', 'created_at')->where('status', 0);
                            },
                            'cities' => function ($query) {
                                $query->select('id', 'courier_id', 'cityName');
                            },
                            'zones' => function ($query) {
                                $query->select('id', 'courier_id', 'city_id', 'zoneName');
                            }
                        ])->where('orders.id', $order_id->order_id)
                            ->where('orders.orderDate', $request->date)
                            ->where('status', $request->status)
                            ->join('customers', 'customers.order_id', '=', 'orders.id')
                            ->select('orders.*', 'customers.customerPhone', 'customers.customerName', 'customers.customerAddress')->first();
                    }
                }
            } else {
                foreach ($order_ids as $order_id) {
                    $orders[] =  Order::with(
                        [
                            'orderproducts' => function ($query) {
                                $query->select('id', 'order_id', 'productName', 'quantity', 'color', 'size');
                            },
                            'admins' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'couriers' => function ($query) {
                                $query->select('id', 'courierName');
                            },
                            'products' => function ($query) {
                                $query->select('id', 'ProductName', 'ProductRegularPrice');
                            },
                            'comments' => function ($query) {
                                $query->select('id', 'order_id', 'comment', 'admin_id', 'status', 'created_at')->where('status', 0);
                            },
                            'cities' => function ($query) {
                                $query->select('id', 'courier_id', 'cityName');
                            },
                            'zones' => function ($query) {
                                $query->select('id', 'courier_id', 'city_id', 'zoneName');
                            }
                        ]
                    )
                        ->where('orders.id', $order_id->order_id)
                        ->where('orders.orderDate', $request->date)
                        ->where('status', $request->status)
                        ->where('admin_id', Auth::guard('admin')->user()->id)
                        ->join('customers', 'customers.order_id', '=', 'orders.id')
                        ->select('orders.*', 'customers.customerPhone', 'customers.customerName', 'customers.customerAddress')->first();
                }
            }
            return view('admin.content.order.productbyorder', ['orders' => $orders]);
        } else {
            if ($admin->hasRole('superadmin') || $admin->hasRole('admin') || $admin->hasRole('manager')) {
                if (isset($request->user_id)) {
                    foreach ($order_ids as $order_id) {
                        $orders[] =  Order::with([
                            'orderproducts' => function ($query) {
                                $query->select('id', 'order_id', 'productName', 'quantity', 'color', 'size');
                            },
                            'admins' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'couriers' => function ($query) {
                                $query->select('id', 'courierName');
                            },
                            'products' => function ($query) {
                                $query->select('id', 'ProductName', 'ProductRegularPrice');
                            },
                            'comments' => function ($query) {
                                $query->select('id', 'order_id', 'comment', 'admin_id', 'status', 'created_at')->where('status', 0);
                            },
                            'cities' => function ($query) {
                                $query->select('id', 'courier_id', 'cityName');
                            },
                            'zones' => function ($query) {
                                $query->select('id', 'courier_id', 'city_id', 'zoneName');
                            }
                        ])->where('orders.id', $order_id->order_id)
                            ->where('status', $request->status)
                            ->where('admin_id', $request->user_id)
                            ->join('customers', 'customers.order_id', '=', 'orders.id')
                            ->select('orders.*', 'customers.customerPhone', 'customers.customerName', 'customers.customerAddress')->first();
                    }
                } else {
                    foreach ($order_ids as $order_id) {
                        $orders[] =  Order::with([
                            'orderproducts' => function ($query) {
                                $query->select('id', 'order_id', 'productName', 'quantity', 'color', 'size');
                            },
                            'admins' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'couriers' => function ($query) {
                                $query->select('id', 'courierName');
                            },
                            'products' => function ($query) {
                                $query->select('id', 'ProductName', 'ProductRegularPrice');
                            },
                            'comments' => function ($query) {
                                $query->select('id', 'order_id', 'comment', 'admin_id', 'status', 'created_at')->where('status', 0);
                            },
                            'cities' => function ($query) {
                                $query->select('id', 'courier_id', 'cityName');
                            },
                            'zones' => function ($query) {
                                $query->select('id', 'courier_id', 'city_id', 'zoneName');
                            }
                        ])->where('orders.id', $order_id->order_id)
                            ->where('status', $request->status)
                            ->join('customers', 'customers.order_id', '=', 'orders.id')
                            ->select('orders.*', 'customers.customerPhone', 'customers.customerName', 'customers.customerAddress')->first();
                    }
                }
            } else {
                foreach ($order_ids as $order_id) {
                    $orders[] =  Order::with(
                        [
                            'orderproducts' => function ($query) {
                                $query->select('id', 'order_id', 'productName', 'quantity', 'color', 'size');
                            },
                            'admins' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'couriers' => function ($query) {
                                $query->select('id', 'courierName');
                            },
                            'products' => function ($query) {
                                $query->select('id', 'ProductName', 'ProductRegularPrice');
                            },
                            'comments' => function ($query) {
                                $query->select('id', 'order_id', 'comment', 'admin_id', 'status', 'created_at')->where('status', 0);
                            },
                            'cities' => function ($query) {
                                $query->select('id', 'courier_id', 'cityName');
                            },
                            'zones' => function ($query) {
                                $query->select('id', 'courier_id', 'city_id', 'zoneName');
                            }
                        ]
                    )
                        ->where('orders.id', $order_id->order_id)
                        ->where('status', $request->status)
                        ->where('admin_id', Auth::guard('admin')->user()->id)
                        ->join('customers', 'customers.order_id', '=', 'orders.id')
                        ->select('orders.*', 'customers.customerPhone', 'customers.customerName', 'customers.customerAddress')->first();
                }
            }
            return view('admin.content.order.productbyorder', ['orders' => $orders]);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin = Admin::where('email', Auth::guard('admin')->user()->email)->first();
        $status = "";
        return view('admin.content.order.order', ['admin' => $admin, 'status' => $status]);
    }

    public function complain()
    {
        $admin = Admin::where('email', Auth::guard('admin')->user()->email)->first();
        $status = "orderall";
        return view('admin.content.order.complain', ['admin' => $admin, 'status' => $status]);
    }

    public function complane(Request $request)
    {
        $admin = Admin::where('email', Auth::guard('admin')->user()->email)->first();
        $columns = $request->input('columns');
        $status = $request->input('status');


        $orders =  Order::with(
            [
                'orderproducts' => function ($query) {
                    $query->select('id', 'order_id', 'product_id', 'productName', 'quantity', 'color', 'size');
                },
                'admins' => function ($query) {
                    $query->select('id', 'name');
                },
                'couriers' => function ($query) {
                    $query->select('id', 'courierName');
                },
                'products' => function ($query) {
                    $query->select('id', 'ProductName');
                },
                'comments' => function ($query) {
                    $query->select('id', 'order_id', 'comment', 'admin_id', 'status', 'created_at')->where('status', 0);
                },
                'cities' => function ($query) {
                    $query->select('id', 'courier_id', 'cityName');
                },
                'zones' => function ($query) {
                    $query->select('id', 'courier_id', 'city_id', 'zoneName');
                }
            ]
        )->join('customers', 'customers.order_id', '=', 'orders.id')->select('orders.*', 'customers.customerPhone', 'customers.customerName', 'customers.customerAddress');


        if ($columns[1]['search']['value']) {
            $orders = $orders->Where('orders.invoiceID', 'like', "%{$columns[1]['search']['value']}%")
                ->orWhere('orders.web_ID', 'like', "%{$columns[1]['search']['value']}%");
        }
        if ($columns[2]['search']['value']) {
            $orders = $orders->Where('customers.customerPhone', 'like', "%{$columns[2]['search']['value']}%");
        }
        if ($columns[5]['search']['value']) {
            $orders = $orders->Where('orders.courier_id', '=', $columns[5]['search']['value']);
        }
        if ($columns[6]['search']['value']) {
            if ($status == 'Completed') {
                $orders = $orders->Where('orders.completeDate', 'like', "%{$columns[6]['search']['value']}%");
            } else {
                $orders = $orders->Where('orders.orderDate', 'like', "%{$columns[6]['search']['value']}%");
            }
        }



        if ($columns[8]['search']['value']) {
            $orders = $orders->Where('orders.admin_id', '=', $columns[8]['search']['value']);
        }


        return Datatables::of($orders->orderBy('orders.id', 'DESC'))
            ->addColumn('customerInfo', function ($orders) {
                return '<span style="font-weight:bold;color:black">' . $orders->customerName . '<br><button class="btn btn-success btn-sm" style="margin: 4px;padding: 0px 4px;" data-num="' . $orders->customerPhone . '" data-inv="' . $orders->invoiceID . '" id="checkfraud">' . $orders->customerPhone . '</button><br>' . $orders->customerAddress . '</span>';
            })
            ->addColumn('invoice', function ($orders) {
                return '<span style="font-weight:bold;color:black">' . $orders->invoiceID . '</span><br>' . $orders->web_ID . '<br>' . $orders->created_at->diffForhumans() . '<br>' . $orders->created_at;
            })
            ->editColumn('products', function ($orders) {
                $orderProducts = '';
                foreach ($orders->orderproducts as $product) {
                    if (isset($product->color) && isset($product->size)) {
                        $orderProducts = $orderProducts . '<a target="_blank" href="../view-product-load/' . Product::where('id', $product->product_id)->first()->ProductSlug . '"><img src="../' . Product::where('id', $product->product_id)->first()->ProductImage . '" style="width:60px"></a>' . $product->quantity . ' x ' . $product->productName . '<br> <span style="color:blue;"> Colour: ' . $product->color . ' , Size: ' . $product->size . '</span> &nbsp;&nbsp;' . $product->sigment . '<br>';
                    } elseif (isset($product->size)) {
                        $orderProducts = $orderProducts . '<a target="_blank" href="../view-product-load/' . Product::where('id', $product->product_id)->first()->ProductSlug . '"><img src="../' . Product::where('id', $product->product_id)->first()->ProductImage . '" style="width:60px"></a>' . $product->quantity . ' x ' . $product->productName . '<br> <span style="color:blue;"> Size: ' . $product->size . '</span> &nbsp;&nbsp;' . $product->sigment . '<br>';
                    } else if ($product->color) {
                        $orderProducts = $orderProducts . '<a target="_blank" href="../view-product-load/' . Product::where('id', $product->product_id)->first()->ProductSlug . '"><img src="../' . Product::where('id', $product->product_id)->first()->ProductImage . '" style="width:60px"></a>' . $product->quantity . ' x ' . $product->productName . '<br> <span style="color:blue;"> Colour: ' . $product->color . '<span> &nbsp;&nbsp;' . $product->sigment . '<br>';
                    } else {
                        $orderProducts = $orderProducts . '<a target="_blank" href="../view-product-load/' . Product::where('id', $product->product_id)->first()->ProductSlug . '"><img src="../' . Product::where('id', $product->product_id)->first()->ProductImage . '" style="width:60px"></a>' . $product->quantity . ' x ' . $product->productName . '<br> &nbsp;&nbsp;' . $product->sigment;
                    }
                }
                return rtrim($orderProducts, '<br>');
            })
            ->editColumn('user', function ($orders) {
                if (isset($orders->admin_id)) {
                    return $orders->admins->name;
                } else {
                    return 'user not assign';
                }
            })
            ->editColumn('price', function ($orders) {
                if (isset($orders->consigment_id)) {
                    return '<span style="color:black;font-size: 22px;">' . $orders->subTotal . '</span><br> <span style="background:green;color:white;font-weight:bold;border-radius:30px;">&nbsp;&nbsp;' . $orders->consigment_id . '&nbsp;&nbsp;</span><br>' . "<a href='" . $orders->courier_tracking_link . "' target='_blank' class='btn btn-info action-icon btn-viecourier'> Track </a>";
                } else {
                    return '<span style="color:black;font-size: 22px;">' . $orders->subTotal . '</span>';
                }
            })
            ->editColumn('history', function ($orders) {
                if (isset($orders->admin_id) && isset($orders->packing_by) && isset($orders->shipped_by) && isset($orders->completed_by) && isset($orders->delfaild_by)) {
                    return '<span style="color:green;"> Assigned By: ' . Admin::where('id', $orders->admin_id)->first()->name . '</span><br><span style="color:blue;"> Packaging By: ' . Admin::where('id', $orders->packing_by)->first()->name . '</span><br><span style="color:gray;"> Shipped By: ' . Admin::where('id', $orders->shipped_by)->first()->name . '</span><br><span style="color:darkgreen;"> Completed By: ' . Admin::where('id', $orders->completed_by)->first()->name . '</span><br><span style="color:red;"> Delfaild By: ' . Admin::where('id', $orders->delfaild_by)->first()->name . '</span';
                } elseif (isset($orders->admin_id) && isset($orders->packing_by) && isset($orders->shipped_by) && isset($orders->completed_by)) {
                    return '<span style="color:green;"> Assigned By: ' . Admin::where('id', $orders->admin_id)->first()->name . '</span><br><span style="color:blue;">  Packaging By: ' . Admin::where('id', $orders->packing_by)->first()->name . '</span><br><span style="color:gray;">  Shipped By: ' . Admin::where('id', $orders->shipped_by)->first()->name . '</span><br><span style="color:darkgreen;"> Completed By: ' . Admin::where('id', $orders->completed_by)->first()->name . '</span';
                } elseif (isset($orders->admin_id) && isset($orders->packing_by) && isset($orders->shipped_by)) {
                    return '<span style="color:green;"> Assigned By: ' . Admin::where('id', $orders->admin_id)->first()->name . '</span><br><span style="color:blue;">  Packaging By: ' . Admin::where('id', $orders->packing_by)->first()->name . '</span><br><span style="color:gray;">  Shipped By: ' . Admin::where('id', $orders->shipped_by)->first()->name . '</span';
                } elseif (isset($orders->admin_id) && isset($orders->packing_by)) {
                    return '<span style="color:green;"> Assigned By: ' . Admin::where('id', $orders->admin_id)->first()->name . '</span><br><span style="color:blue;">  Packaging By: ' . Admin::where('id', $orders->packing_by)->first()->name . '</span';
                } elseif (isset($orders->admin_id)) {
                    return '<span style="color:blue;"> Assigned By: ' . Admin::where('id', $orders->admin_id)->first()->name . '</span';
                } else {
                    return 'user not assign';
                }
            })
            ->editColumn('notification', function ($orders) {
                if (isset($orders->comments)) {
                    if (isset($orders->customerNote)) return $orders->comments->comment . '<br>' . $orders->comments->created_at->diffForhumans() . '<br><span style="color:red;font-weight:bold;">[ Note:' . $orders->customerNote . ' ]</span>';
                    return $orders->comments->comment . '<br>' . $orders->comments->created_at->diffForhumans();
                } else {
                    return 'No Comments';
                }
            })
            ->addColumn('statusButton', function ($orders) {
                if (last(request()->segments()) == 'Completed') {
                    $adm = Admin::where('email', Auth::guard('admin')->user()->email)->first();
                    if ($adm->hasrole('superadmin')) {
                        return $orders->status = $this->statusList($orders->status, $orders->id);
                    } else {
                        return '<span class="badge bg-soft-success text-success">Completed</span>';
                    }
                } else {
                    $adm = Admin::where('email', Auth::guard('admin')->user()->email)->first();
                    if ($adm->hasrole('superadmin')) {
                        return $orders->status = $this->statusList($orders->status, $orders->id);
                    } elseif ($adm->hasrole('user')) {
                        if ($orders->status == 'Pending' || $orders->status == 'Hold' || $orders->status == 'Ready to Ship' || $orders->status == 'Cancelled') {
                            return $orders->status = $this->statusList($orders->status, $orders->id);
                        } else {
                            return '<span class="btn btn-light btn-sm" style="min-width:100px;color:white">' . $orders->status . '</span>';
                        }
                    } elseif ($adm->hasrole('accounts')) {
                        if ($orders->status == 'Ready to Ship' || $orders->status == 'Packaging') {
                            return $orders->status = $this->statusList($orders->status, $orders->id);
                        } else {
                            return '<span class="btn btn-light btn-sm" style="min-width:100px;color:white">' . $orders->status . '</span>';
                        }
                    } elseif ($adm->hasrole('store')) {
                        if ($orders->status == 'Packaging' || $orders->status == 'Shipped' || $orders->status == 'Completed' || $orders->status == 'Del. Failed') {
                            return $orders->status = $this->statusList($orders->status, $orders->id);
                        } else {
                            return '<span class="btn btn-light btn-sm" style="min-width:100px;color:white">' . $orders->status . '</span>';
                        }
                    } elseif ($adm->hasrole('support')) {
                        if ($orders->status == 'Shipped' || $orders->status == 'Completed' || $orders->status == 'Del. Failed') {
                            return $orders->status = $this->statusList($orders->status, $orders->id);
                        } else {
                            return '<span class="btn btn-light btn-sm" style="min-width:100px;color:white">' . $orders->status . '</span>';
                        }
                    } else {
                        return '<span class="btn btn-light btn-sm" style="min-width:100px;color:white">' . $orders->status . '</span>';
                    }
                }
            })
            ->addColumn('action', function ($orders) {
                return "<a href='javascript:void(0);' data-id='" . $orders->id . "' class='action-icon btn-editorder'> <i class='fas fa-1x fa-edit' style='font-size: 32px;'></i></a>";
            })
            ->escapeColumns([])->make();
    }

    public function ordersByStatus($status)
    {
        $admin = Admin::where('email', Auth::guard('admin')->user()->email)->first();
        $status = $status;
        return view('admin.content.order.order', ['admin' => $admin, 'status' => $status]);
    }

    public function createorder()
    {
        $uniqueId = $this->uniqueID();
        $couriers = Courier::all();
        return view('admin.content.order.createorder', ['couriers' => $couriers, 'uniqueId' => $uniqueId]);
    }

    public function storeorder(Request $request)
    {
        $excutomer = Customer::where('customerPhone', $request['data']['customerPhone'])->latest()->first();
        if (isset($excutomer)) {
            $exorder = Order::where('id', $excutomer->order_id)->first();
            if ($exorder->status == 'Pending' || $exorder->status == 'Packaging' || $exorder->status == 'Ready to Ship' || $exorder->status == 'Hold') {
                $response['status'] = 'failed';
                $response['message'] = 'Opps! already have an order to this number.please try again';
                return json_encode($response);
                die();
            }
        }
        $order = new Order();
        $order->invoiceID = $this->uniqueID();
        $order->web_id = $request['data']['web_id'];
        $order->store_id = $request['data']['storeID'];
        $order->subTotal = $request['data']['total'];
        if (isset($request['data']['customerNote'])) {
            $order->customerNote = $request['data']['customerNote'];
        }
        $order->deliveryCharge = $request['data']['deliveryCharge'];
        $order->discountCharge = $request['data']['discountCharge'];
        $order->payment_type_id = $request['data']['paymentTypeID'];
        $order->payment_id = $request['data']['paymentID'];
        $order->paymentAmount = $request['data']['paymentAmount'];
        $order->paymentAgentNumber = $request['data']['paymentAgentNumber'];
        $order->orderDate = $request['data']['orderDate'];
        $order->courier_id = $request['data']['courierID'];
        $order->city_id = $request['data']['cityID'];
        $order->zone_id = $request['data']['zoneID'];
        $products = $request['data']['products'];
        $order->admin_id = Auth::guard('admin')->user()->id;
        $result = $order->save();


        if ($result) {
            $customer = new Customer();
            $customer->order_id = $order->id;
            $customer->customerName = $request['data']['customerName'];
            $customer->customerPhone = $request['data']['customerPhone'];
            $customer->customerAddress = $request['data']['customerAddress'];
            $customer->save();
            foreach ($products as $product) {
                $orderProducts = new Orderproduct();
                $orderProducts->order_id = $order->id;
                $orderProducts->product_id = $product['productID'];
                $orderProducts->productCode = $product['productCode'];
                $orderProducts->productName = $product['productName'];
                $orderProducts->color = $product['productColor'];
                $orderProducts->size = $product['productSize'];
                $orderProducts->sigment = $product['sigment'];
                $orderProducts->quantity = $product['productQuantity'];
                $orderProducts->productPrice = $product['productPrice'];
                $orderProducts->save();
            }

            $notification = new Comment();
            $notification->order_id = $order->id;
            $notification->comment = '#PR001' . $order->id . ' Order Has Been Created by ' . Auth::guard('admin')->user()->name;
            $notification->admin_id = Auth::guard('admin')->user()->id;
            $notification->save();

            if ($request['data']['paymentID'] != '' && $request['data']['paymentTypeID'] != '') {
                $paymentComplete = new Paymentcomplete();
                $paymentComplete->order_id = $order->id;
                $paymentComplete->payment_type_id = $request['data']['paymentTypeID'];
                $paymentComplete->payment_id = $request['data']['paymentID'];
                $paymentComplete->trid = $request['data']['paymentAgentNumber'];
                $paymentComplete->amount = $request['data']['paymentAmount'];
                $paymentComplete->date = date('Y-m-d');
                $paymentComplete->userID = Auth::guard('admin')->user()->id;
                $paymentComplete->save();
                $account = Basicinfo::first();
                $account->account_balance += $request['data']['paymentAmount'];
                $account->update();
                if ($request['data']['paymentAmount'] > 0) {
                    $income = new Incomehistory();
                    $income->from = 'Retail Sale';
                    $income->date = date('Y-m-d');
                    $income->amount = $request['data']['paymentAmount'];
                    $income->admin_id = Auth::guard('admin')->user()->id;
                    $income->comments = 'Payment receive from Retail Sale INV: ' . $order->invoiceID . 'Paid: ' . $order->paymentAmount . 'Due: ' . $order->subTotal;
                    $income->update();
                }
            }

            if ($order->status != 'Ready to Ship' || $order->status != 'Packaging' || $order->status != 'Shipped' || $order->status != 'Completed' || $order->status != 'Del. Failed') {
                if ($request['data']['status'] == 'Ready to Ship') {
                    $ops = Orderproduct::where('order_id', $order->id)->get();
                    foreach ($ops as $op) {
                        $size = Size::where('product_id', $op->product_id)->where('size', $op->size)->first();
                        if ($size->available_stock >= $op->quantity) {
                            $size->sold += $op->quantity;
                            $size->available_stock -= $op->quantity;
                            $size->update();
                        } else {
                            $response['status'] = 'failed';
                            $response['message'] = 'You don not have enough stock in your store for Ready to Ship Please check the parcel again !';
                            return json_encode($response);
                            break;
                        }
                    }
                }
            }

            if ($order->status == 'Ready to Ship' || $order->status == 'Packaging' || $order->status == 'Shipped' || $order->status == 'Completed' || $order->status == 'Del. Failed') {
                if ($request['data']['status'] != 'Ready to Ship' || $request['data']['status'] != 'Packaging' || $request['data']['status'] != 'Shipped' || $request['data']['status'] != 'Completed' || $request['data']['status'] != 'Del. Failed') {
                    $ops = Orderproduct::where('order_id', $order->id)->get();
                    foreach ($ops as $op) {
                        $size = Size::where('product_id', $op->product_id)->where('size', $op->size)->first();
                        $size->sold -= $op->quantity;
                        $size->available_stock += $op->quantity;
                        $size->update();
                    }
                }
            }

            if ($request['data']['status'] == 'Shipped') {
                $order->deliveryDate = date('Y-m-d');
            }

            if ($order->status == 'Pending' && $request['data']['status'] == 'Pending') {
            } else {
                if ($request['data']['status'] == 'Hold' || $request['data']['status'] == 'Ready to Ship' || $request['data']['status'] == 'Cancelled') {
                    if (isset($order->admin_id)) {
                    } else {
                        $order->admin_id = Auth::guard('admin')->user()->id;
                    }
                    if ($request['data']['status'] == 'Ready to Ship') {
                        $cu = Customer::where('order_id', $order->id)->first();
                        $sendstatus = Http::get('http://bulksmsbd.net/api/smsapi?api_key=3z2e9owl4PGXLakGMAmv&type=text&number=' . $cu->customerPhone . '&senderid=RASHIBD.COM&message=ধন্যবাদ, আপনার অর্ডারটি ID:' . $order->invoiceID . ' কনফার্ম হয়েছে - মোটঃ ' . $order->subTotal . ' টাকা।প্যাকেজিং এর জন্য প্রস্তুত , Hotline: 01888173003');

                        if ($sendstatus) {
                            $comment = new Comment();
                            $comment->order_id = $order->id;
                            $comment->comment = 'Successfully send a sms to this customer';
                            $comment->admin_id = Auth::guard('admin')->user()->id;
                            $comment->status = 1;
                            $comment->save();
                        }
                    }
                }
                if ($request['data']['status'] == 'Packaging') {
                    if (isset($order->packing_by)) {
                    } else {
                        $order->packing_by = Auth::guard('admin')->user()->id;
                    }
                }
                if ($request['data']['status'] == 'Shipped') {
                    $order->deliveryDate = date('Y-m-d');
                    if (isset($order->shipped_by)) {
                    } else {
                        $order->shipped_by = Auth::guard('admin')->user()->id;
                        $cu = Customer::where('order_id', $order->id)->first();
                        $sendstatus = Http::get('http://bulksmsbd.net/api/smsapi?api_key=3z2e9owl4PGXLakGMAmv&type=text&number=' . $cu->customerPhone . '&senderid=RASHIBD.COM&message= অভিনন্দন,আপনার অর্ডারটি ' . $order->invoiceID . ' কুরিয়ার করা হয়েছে।মোটঃ' . $order->subTotal . ' টাকা। ডেলিভারির সময়ঃ ২-৩ দিন। ট্র্যাক পার্সেলঃ ' . $order->courier_tracking_link . ' , Hotline: 01888173003');

                        if ($sendstatus) {
                            $comment = new Comment();
                            $comment->order_id = $order->id;
                            $comment->comment = 'Successfully send a sms to this customer';
                            $comment->admin_id = Auth::guard('admin')->user()->id;
                            $comment->status = 1;
                            $comment->save();
                        }
                    }
                }
                if ($request['data']['status'] == 'Completed' || $request['data']['status'] == 'Del. Failed') {
                    if (isset($order->completed_by)) {
                    } else {
                        $order->completeDate = date('Y-m-d');
                        $order->completed_by = Auth::guard('admin')->user()->id;
                    }
                }
            }
            $order->status = $request['data']['status'];
            $order->update();



            $response['status'] = 'success';
            $response['message'] = 'Successfully Add Order';
        } else {
            Customer::where('order_id', '=', $order->id)->delete();
            Orderproduct::where('order_id', '=', $order->id)->delete();
            Comment::where('order_id', '=', $order->id)->delete();
            Order::where('id', '=', $order->id)->delete();
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Order';
        }
        return json_encode($response);
        die();
    }

    //user all ordeer

    public function userorderall(Request $request)
    {

        $columns = $request->input('columns');
        $status = $request->input('status');

        $orders =  Order::with(
            [
                'orderproducts' => function ($query) {
                    $query->select('id', 'order_id', 'productName', 'quantity', 'color', 'size', 'sigment', 'product_id');;
                },
                'admins' => function ($query) {
                    $query->select('id', 'name');
                },
                'couriers' => function ($query) {
                    $query->select('id', 'courierName');
                },
                'products' => function ($query) {
                    $query->select('id', 'productName', 'productPrice');
                },
                'comments' => function ($query) {
                    $query->select('id', 'order_id', 'comment', 'admin_id', 'status', 'created_at')->where('status', 0);
                },
                'cities' => function ($query) {
                    $query->select('id', 'courier_id', 'cityName');
                },
                'zones' => function ($query) {
                    $query->select('id', 'courier_id', 'city_id', 'zoneName');
                }
            ]
        )->where('admin_id', Auth::guard('admin')->user()->id)
            ->join('customers', 'customers.order_id', '=', 'orders.id')
            ->select('orders.*', 'customers.customerPhone', 'customers.customerName', 'customers.customerAddress');


        if ($columns[1]['search']['value']) {
            $orders = $orders->Where('orders.invoiceID', 'like', "%{$columns[1]['search']['value']}%")
                ->orWhere('orders.web_ID', 'like', "%{$columns[1]['search']['value']}%");
        }
        if ($columns[2]['search']['value']) {
            $orders = $orders->Where('customers.customerPhone', 'like', "%{$columns[2]['search']['value']}%");
        }
        if ($columns[5]['search']['value']) {
            $orders = $orders->Where('orders.courier_id', '=', $columns[5]['search']['value']);
        }
        if ($columns[6]['search']['value']) {
            if ($status == 'Completed') {
                $orders = $orders->Where('orders.completeDate', 'like', "%{$columns[6]['search']['value']}%");
            } else {
                $orders = $orders->Where('orders.orderDate', 'like', "%{$columns[6]['search']['value']}%");
            }
        }


        if (Auth::user()->role == 0) {
            if ($columns[8]['search']['value']) {
                $orders = $orders->Where('orders.memo', '=', $columns[8]['search']['value']);
            }
        } else {
            if ($columns[8]['search']['value']) {
                $orders = $orders->Where('orders.admin_id', '=', $columns[8]['search']['value']);
            }
        }

        return Datatables::of($orders->orderBy('orders.id', 'DESC'))
            ->addColumn('customerInfo', function ($orders) {
                return $orders->customerName . '<br><button class="btn btn-success btn-sm" style="margin: 4px;padding: 0px 4px;" data-num="' . $orders->customerPhone . '" data-inv="' . $orders->invoiceID . '" id="checkfraud">' . $orders->customerPhone . '</button><br>' . $orders->customerAddress . '<br>' . $orders->entry_complete;
            })
            ->addColumn('invoice', function ($orders) {
                return $orders->invoiceID . '<br>' . $orders->web_ID . '<br>' . $orders->created_at->diffForhumans();
            })
            ->editColumn('products', function ($orders) {
                $orderProducts = '';
                foreach ($orders->orderproducts as $product) {
                    if (isset($product->color) && isset($product->size)) {
                        $orderProducts = $orderProducts . $product->quantity . ' x ' . $product->productName . '<br><span style="color:blue;"> Colour: ' . $product->color . ' , Size: ' . $product->size . '</span><br>';
                    } elseif (isset($product->size)) {
                        $orderProducts = $orderProducts . $product->quantity . ' x ' . $product->productName . '<br><span style="color:blue;"> Size: ' . $product->size . '</span><br>';
                    } else if ($product->color) {
                        $orderProducts = $orderProducts . $product->quantity . ' x ' . $product->productName . '<br><span style="color:blue;"> Colour: ' . $product->color . '<span><br>';
                    } else {
                        $orderProducts = $orderProducts . $product->quantity . ' x ' . $product->productName . '<br>';
                    }
                }
                return rtrim($orderProducts, '<br>');
            })
            ->editColumn('user', function ($orders) {
                if ($orders->admins) {
                    return $orders->admins->name;
                } else {
                    return 'user not assign';
                }
            })
            ->editColumn('courier', function ($orders) {
                if (isset($orders->couriers->courierName)) {
                    return $orders->couriers->courierName;
                } else {
                    return 'Not Selected';
                }
            })
            ->editColumn('notification', function ($orders) {
                if (isset($orders->customerNote)) return $orders->comments->comment . '<br>' . $orders->comments->created_at->diffForhumans() . '<br><span style="color:red;font-weight:bold;">[ Note:' . $orders->customerNote . ' ]</span>';
                return $orders->comments->comment . '<br>' . $orders->comments->created_at->diffForhumans();
            })
            ->addColumn('statusButton', function ($orders) {
                if (last(request()->segments()) == 'Completed') {
                    return '<span class="badge bg-soft-success text-success">Completed</span>';
                } else {
                    return $orders->status = $this->statusList($orders->status, $orders->id);
                }
            })

            ->addColumn('action', function ($orders) {
                return "<a href='javascript:void(0);' data-id='" . $orders->id . "' class='action-icon btn-editorder'> <i class='fas fa-1x fa-edit'></i></a>
                <a href='javascript:void(0);' data-id='" . $orders->id . "' class='action-icon btn-delete'> <i class='fas fa-trash-alt'></i></a>";
            })
            ->escapeColumns([])->make();
    }

    //all order

    public function orderdata(Request $request, $abc)
    {
        $admin = Admin::where('email', Auth::guard('admin')->user()->email)->first();
        $columns = $request->input('columns');
        $status = $request->input('status');

        if ($abc == 'orderall') {

            $orders =  Order::with(
                [
                    'orderproducts' => function ($query) {
                        $query->select('id', 'order_id', 'productName', 'quantity', 'color', 'size', 'sigment', 'product_id');;
                    },
                    'admins' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'couriers' => function ($query) {
                        $query->select('id', 'courierName');
                    },
                    'products' => function ($query) {
                        $query->select('id', 'ProductName');
                    },
                    'comments' => function ($query) {
                        $query->select('id', 'order_id', 'comment', 'admin_id', 'status', 'created_at')->where('status', 0);
                    },
                    'cities' => function ($query) {
                        $query->select('id', 'courier_id', 'cityName');
                    },
                    'zones' => function ($query) {
                        $query->select('id', 'courier_id', 'city_id', 'zoneName');
                    }
                ]
            )
                ->join('customers', 'customers.order_id', '=', 'orders.id')
                ->select('orders.*', 'customers.customerPhone', 'customers.customerName', 'customers.customerAddress');
        } else {
            if ($abc == 'Pending') {
                $orders =  Order::with(
                    [
                        'orderproducts' => function ($query) {
                            $query->select('id', 'order_id', 'productName', 'quantity', 'color', 'size', 'sigment', 'product_id');;
                        },
                        'admins' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'couriers' => function ($query) {
                            $query->select('id', 'courierName');
                        },
                        'products' => function ($query) {
                            $query->select('id', 'ProductName');
                        },
                        'comments' => function ($query) {
                            $query->select('id', 'order_id', 'comment', 'admin_id', 'status', 'created_at')->where('status', 0);
                        },
                        'cities' => function ($query) {
                            $query->select('id', 'courier_id', 'cityName');
                        },
                        'zones' => function ($query) {
                            $query->select('id', 'courier_id', 'city_id', 'zoneName');
                        }
                    ]
                )
                    ->join('customers', 'customers.order_id', '=', 'orders.id')
                    ->select('orders.*', 'customers.customerPhone', 'customers.customerName', 'customers.customerAddress');
            } else {

                $orders =  Order::with(
                    [
                        'orderproducts' => function ($query) {
                            $query->select('id', 'order_id', 'productName', 'quantity', 'color', 'size', 'sigment', 'product_id');;
                        },
                        'admins' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'couriers' => function ($query) {
                            $query->select('id', 'courierName');
                        },
                        'products' => function ($query) {
                            $query->select('id', 'ProductName');
                        },
                        'comments' => function ($query) {
                            $query->select('id', 'order_id', 'comment', 'admin_id', 'status', 'created_at')->where('status', 0);
                        },
                        'cities' => function ($query) {
                            $query->select('id', 'courier_id', 'cityName');
                        },
                        'zones' => function ($query) {
                            $query->select('id', 'courier_id', 'city_id', 'zoneName');
                        }
                    ]
                )
                    ->join('customers', 'customers.order_id', '=', 'orders.id')
                    ->select('orders.*', 'customers.customerPhone', 'customers.customerName', 'customers.customerAddress');
            }
        }


        if ($abc != 'orderall') {
            $orders = $orders->where('orders.status', 'like', $abc);
        }


        if ($columns[1]['search']['value']) {
            $orders = $orders->Where('orders.invoiceID', 'like', "%{$columns[1]['search']['value']}%")
                ->orWhere('orders.web_ID', 'like', "%{$columns[1]['search']['value']}%");
        }
        if ($columns[2]['search']['value']) {
            $orders = $orders->Where('customers.customerPhone', 'like', "%{$columns[2]['search']['value']}%");
        }
        if ($columns[5]['search']['value']) {
            $orders = $orders->Where('orders.courier_id', '=', $columns[5]['search']['value']);
        }
        if ($columns[6]['search']['value']) {
            if ($status == 'Completed') {
                $orders = $orders->Where('orders.completeDate', 'like', "%{$columns[6]['search']['value']}%");
            } else {
                $orders = $orders->Where('orders.orderDate', 'like', "%{$columns[6]['search']['value']}%");
            }
        }



        if ($columns[8]['search']['value']) {
            $orders = $orders->Where('orders.admin_id', '=', $columns[8]['search']['value']);
        }


        return Datatables::of($orders->orderBy('orders.id', 'DESC'))
            ->addColumn('customerInfo', function ($orders) {
                if ($orders->store_id == '1') {
                    if ($orders->web_id == 'Website') {
                        return '<span style="font-weight:bold;color:black">' . $orders->customerName . '<br>' . $orders->customerPhone . '<br>' . $orders->customerAddress . '</span><br><button class="btn btn-success btn-sm" style="margin: 4px;padding: 0px 4px;" data-num="' . $orders->customerPhone . '" data-inv="' . $orders->invoiceID . '" id="checkfraud">Check</button>';
                    } else {
                        return '<span style="font-weight:bold;color:black">' . $orders->customerName . '<br>' . $orders->customerPhone . '<br>' . $orders->customerAddress . '</span><br><p class="btn btn-info btn-sm" style="margin-top:50px;margin-bottom:0"><b>' . $orders->web_id . '</b> </p><br><button class="btn btn-success btn-sm" style="margin: 4px;padding: 0px 4px;" data-num="' . $orders->customerPhone . '" data-inv="' . $orders->invoiceID . '" id="checkfraud">Check</button>';
                    }
                } else {
                    return '<span style="font-weight:bold;color:black">' . $orders->customerName . '<br>' . $orders->customerPhone . '<br>' . $orders->customerAddress . '</span><br><p class=" btn btn-info btn-sm" style="margin-top:50px;margin-bottom:0">Rashibd : <b>' . $orders->web_id . '</b></p><br><button class="btn btn-success btn-sm" style="margin: 4px;padding: 0px 4px;" data-num="' . $orders->customerPhone . '" data-inv="' . $orders->invoiceID . '" id="checkfraud">Check</button>';
                }
            })
            ->addColumn('invoice', function ($orders) {
                $old = Customer::where('customerPhone', $orders->customerPhone)->get()->count();
                if ($old > 1) {
                    $ex = $old - 1;
                    return '<span style="font-weight:bold;color:black;text-align-center"><p class="imgcbtn">' . $orders->invoiceID . '</p></span>' . $orders->web_ID  . '<br><p style="margin-bottom:15px;font-weight:bold;">' . date('Y-m-d', strtotime($orders->created_at)) . '</p><p style="margin-bottom:15px;font-weight:bold;">' . date('h:i A', strtotime($orders->created_at)) . '<p style="font-weight:bold;">' . $orders->created_at->diffForhumans() . '</p></p><button class="btn btn-danger">OLD: ' . $ex . '</button>';
                } else {
                    return '<span style="font-weight:bold;color:black;text-align-center"><p class="imgcbtn">' . $orders->invoiceID . '</p></span>' . $orders->web_ID  . '<br><p style="margin-bottom:15px;font-weight:bold;">' . date('Y-m-d', strtotime($orders->created_at)) . '</p><p style="margin-bottom:15px;font-weight:bold">' . date('h:i A', strtotime($orders->created_at)) . '</p><p style="font-weight:bold;">' . $orders->created_at->diffForhumans() . '</p>';
                }
            })
            ->editColumn('products', function ($orders) {
                $orderProducts = '';
                foreach ($orders->orderproducts as $product) {
                    $prods = Product::where('id', $product->product_id)->first();
                    if (isset($prods)) {
                        if (isset($product->color) && isset($product->size)) {
                            $orderProducts = $orderProducts . '<a target="_blank" href="../view-product-load/' . Product::where('id', $product->product_id)->first()->ProductSlug . '"><img src="../' . Product::where('id', $product->product_id)->first()->ProductImage . '" style="width:60px"></a><br>' . $product->quantity . ' x ' . $product->productName . '<br> <span style="color:blue;font-size: 18px;"> Colour: ' . $product->color . ' </span><br> <span style="font-size: 24px;color:red;font-weight:bold;"> Size: ' . $product->size . '</span><br>' . $product->sigment . '<br>';
                        } elseif (isset($product->size)) {
                            $orderProducts = $orderProducts . '<a target="_blank" href="../view-product-load/' . Product::where('id', $product->product_id)->first()->ProductSlug . '"><img src="../' . Product::where('id', $product->product_id)->first()->ProductImage . '" style="width:60px"></a><br>' . $product->quantity . ' x ' . $product->productName . '<br> <span style="color:red;font-weight:bold;font-size: 18px;"> Size: ' . $product->size . '</span><br>' . $product->sigment . '<br>';
                        } else if ($product->color) {
                            $orderProducts = $orderProducts . '<a target="_blank" href="../view-product-load/' . Product::where('id', $product->product_id)->first()->ProductSlug . '"><img src="../' . Product::where('id', $product->product_id)->first()->ProductImage . '" style="width:60px"></a><br>' . $product->quantity . ' x ' . $product->productName . '<br> <span style="color:blue;font-size: 18px;"> Colour: ' . $product->color . '</span><br>' . $product->sigment . '<br>';
                        } else {
                            $orderProducts = $orderProducts . '<a target="_blank" href="../view-product-load/' . Product::where('id', $product->product_id)->first()->ProductSlug . '"><img src="../' . Product::where('id', $product->product_id)->first()->ProductImage . '" style="width:60px"></a><br>' . $product->quantity . ' x ' . $product->productName . '<br>' . $product->sigment;
                        }
                    } else {
                        $orderProducts = $orderProducts . '<span style="color:red">Produt is Deleted</span> , ';
                    }
                }
                return rtrim($orderProducts, '<br>');
            })
            ->editColumn('user', function ($orders) {
                if (isset($orders->admin_id)) {
                    return $orders->admins->name;
                } else {
                    return 'user not assign';
                }
            })
            ->editColumn('price', function ($orders) {
                if (isset($orders->consigment_id)) {
                    return '<span style="color:black;font-size: 22px;font-weight:bold">৳&nbsp;' . $orders->subTotal . '<br></span><br> <p class="imgcbtn2">' . $orders->consigment_id . '</p><br>' . "<a href='" . $orders->courier_tracking_link . "' target='_blank' class='imgcbtn3'> Track </a>";
                } else {
                    return '<span style="color:black;font-size: 22px;font-weight:bold">৳&nbsp;' . $orders->subTotal . '<br></span>';
                }
            })
            ->editColumn('history', function ($orders) {
                if (isset($orders->admin_id) && isset($orders->packing_by) && isset($orders->shipped_by) && isset($orders->completed_by) && isset($orders->delfaild_by)) {
                    return '<span style="color:green;"> Assigned By: ' . Admin::where('id', $orders->admin_id)->first()->name . '</span><br><span style="color:blue;"> Packaging By: ' . Admin::where('id', $orders->packing_by)->first()->name . '</span><br><span style="color:gray;"> Shipped By: ' . Admin::where('id', $orders->shipped_by)->first()->name . '</span><br><span style="color:darkgreen;"> Completed By: ' . Admin::where('id', $orders->completed_by)->first()->name . '</span><br><span style="color:red;"> Delfaild By: ' . Admin::where('id', $orders->delfaild_by)->first()->name . '</span';
                } elseif (isset($orders->admin_id) && isset($orders->packing_by) && isset($orders->shipped_by) && isset($orders->completed_by)) {
                    return '<span style="color:green;"> Assigned By: ' . Admin::where('id', $orders->admin_id)->first()->name . '</span><br><span style="color:blue;">  Packaging By: ' . Admin::where('id', $orders->packing_by)->first()->name . '</span><br><span style="color:gray;">  Shipped By: ' . Admin::where('id', $orders->shipped_by)->first()->name . '</span><br><span style="color:darkgreen;"> Completed By: ' . Admin::where('id', $orders->completed_by)->first()->name . '</span';
                } elseif (isset($orders->admin_id) && isset($orders->packing_by) && isset($orders->shipped_by)) {
                    return '<span style="color:green;"> Assigned By: ' . Admin::where('id', $orders->admin_id)->first()->name . '</span><br><span style="color:blue;">  Packaging By: ' . Admin::where('id', $orders->packing_by)->first()->name . '</span><br><span style="color:gray;">  Shipped By: ' . Admin::where('id', $orders->shipped_by)->first()->name . '</span';
                } elseif (isset($orders->admin_id) && isset($orders->packing_by)) {
                    return '<span style="color:green;"> Assigned By: ' . Admin::where('id', $orders->admin_id)->first()->name . '</span><br><span style="color:blue;">  Packaging By: ' . Admin::where('id', $orders->packing_by)->first()->name . '</span';
                } elseif (isset($orders->admin_id)) {
                    return '<span style="color:blue;"> Assigned By: ' . Admin::where('id', $orders->admin_id)->first()->name . '</span';
                } else {
                    return 'user not assign';
                }
            })
            ->editColumn('notification', function ($orders) {
                if (isset($orders->comments)) {
                    return $orders->comments->comment . '<br>' . $orders->comments->created_at->diffForhumans();
                } else {
                    return 'No Comments';
                }
            })
            ->editColumn('customerNote', function ($orders) {
                if (isset($orders->customerNote)) return '<span style="color:red;font-weight:bold;">[ Note:' . $orders->customerNote . ' ]</span>';
                return '';
            })
            ->addColumn('statusButton', function ($orders) {
                if (last(request()->segments()) == 'Completed') {
                    $adm = Admin::where('email', Auth::guard('admin')->user()->email)->first();
                    if ($adm->hasrole('superadmin')) {
                        return $orders->status = $this->statusList($orders->status, $orders->id);
                    } else {
                        return '<span class="badge bg-soft-success text-success">Completed</span>';
                    }
                } else {
                    $adm = Admin::where('email', Auth::guard('admin')->user()->email)->first();
                    if ($adm->hasrole('superadmin')) {
                        return $orders->status = $this->statusList($orders->status, $orders->id);
                    } elseif ($adm->hasrole('user')) {
                        if ($orders->status == 'Pending' || $orders->status == 'Hold' || $orders->status == 'Ready to Ship' || $orders->status == 'Cancelled') {
                            return $orders->status = $this->statusList($orders->status, $orders->id);
                        } else {
                            return '<span class="btn btn-light btn-sm" style="min-width:100px;color:white">' . $orders->status . '</span>';
                        }
                    } elseif ($adm->hasrole('accounts')) {
                        if ($orders->status == 'Ready to Ship' || $orders->status == 'Packaging') {
                            return $orders->status = $this->statusList($orders->status, $orders->id);
                        } else {
                            return '<span class="btn btn-light btn-sm" style="min-width:100px;color:white">' . $orders->status . '</span>';
                        }
                    } elseif ($adm->hasrole('store')) {
                        if ($orders->status == 'Packaging' || $orders->status == 'Shipped' || $orders->status == 'Ready to Ship' || $orders->status == 'Completed' || $orders->status == 'Del. Failed') {
                            return $orders->status = $this->statusList($orders->status, $orders->id);
                        } else {
                            return '<span class="btn btn-light btn-sm" style="min-width:100px;color:white">' . $orders->status . '</span>';
                        }
                    } elseif ($adm->hasrole('support')) {
                        if ($orders->status == 'Shipped' || $orders->status == 'Completed' || $orders->status == 'Del. Failed') {
                            return $orders->status = $this->statusList($orders->status, $orders->id);
                        } else {
                            return '<span class="btn btn-light btn-sm" style="min-width:100px;color:white">' . $orders->status . '</span>';
                        }
                    } else {
                        return '<span class="btn btn-light btn-sm" style="min-width:100px;color:white">' . $orders->status . '</span>';
                    }
                }
            })
            ->addColumn('action', function ($orders) {
                return "<a href='javascript:void(0);' data-id='" . $orders->id . "' class='action-icon btn-editorder'> <i class='fas fa-1x fa-edit' style='font-size: 32px;'></i></a>";
            })
            ->escapeColumns([])->make();
    }

    //update status
    public function updateorderstatus(Request $request)
    {
        $id = $request['id'];

        $status = $request['status'];
        $order = Order::find($id);
        $orderproduct = Orderproduct::where('order_id', $order->id)->whereNull('size')->first();
        $orderproductn = Orderproduct::where('order_id', $order->id)->Where('size', '')->first();
        $admin = Admin::where('email', Auth::guard('admin')->user()->email)->first();

        if ($admin->hasRole('superadmin')) {
            if ($orderproduct || $orderproductn) {
                if ($request['status'] != 'Cancelled') {
                    if ($request['status'] == 'Pending' || $request['status'] == 'Hold') {
                    } else {
                        $response['status'] = 'failed';
                        $response['message'] = 'Unsuccessful to change status Please select product size first';
                        return json_encode($response);
                    }
                }
            }
            if ($order->st_deduct == 'No') {
                if ($request['status'] == 'Pending' || $request['status'] == 'Hold' || $request['status'] == 'Cancelled') {
                } else {
                    $order->st_deduct = 'Yes';
                    $ops = Orderproduct::where('order_id', $order->id)->get();
                    foreach ($ops as $op) {
                        $size = Size::where('product_id', $op->product_id)->where('size', $op->size)->first();
                        if ($size->available_stock >= $op->quantity) {
                            $size->sold = $size->sold + $op->quantity;
                            $size->available_stock = $size->available_stock - $op->quantity;
                            $size->update();
                        } else {
                            $response['status'] = 'failed';
                            $response['message'] = 'You don not have enough stock in your store for Ready to Ship Please check the parcel again !';
                            return json_encode($response);
                            break;
                        }
                    }
                }
            } else {
                if ($request['status'] == 'Pending' || $request['status'] == 'Hold' || $request['status'] == 'Cancelled') {
                    $order->st_deduct = 'No';
                    $ops = Orderproduct::where('order_id', $order->id)->get();
                    foreach ($ops as $op) {
                        $size = Size::where('product_id', $op->product_id)->where('size', $op->size)->first();
                        $size->sold = $size->sold - $op->quantity;
                        $size->available_stock = $size->available_stock + $op->quantity;
                        $size->update();
                    }
                } else {
                }
            }

            $order->status = $status;
        } else {
            if ($orderproduct || $orderproductn) {
                if ($request['status'] != 'Cancelled') {
                    if ($request['status'] == 'Pending' || $request['status'] == 'Hold') {
                    } else {
                        $response['status'] = 'failed';
                        $response['message'] = 'Unsuccessful to change status Please select product size first';
                        return json_encode($response);
                    }
                }
            }

            if ($request['status'] != $order->status) {
                if ($admin->hasRole('user')) {
                    if ($order->status == 'Hold' || $order->status == 'Ready to Ship' || $order->status == 'Cancelled' || $order->status == 'Pending') {
                        if ($request['status'] == 'Del. Failed' || $request['status'] == 'Completed' || $request['status'] == 'Shipped' || $request['status'] == 'Packaging') {
                            $response['status'] = 'failed';
                            $response['message'] = 'You do not have permission to update this order status';
                            return json_encode($response);
                        }
                    } else {
                        $response['status'] = 'failed';
                        $response['message'] = 'You do not have permission to update this order status';
                        return json_encode($response);
                    }
                }
                if ($admin->hasRole('admin')) {
                    if ($order->status == 'Ready to Ship' || $order->status == 'Packaging' || $order->status == 'Completed' || $order->status == 'Del. Failed' || $order->status == 'Shipped') {
                        if ($request['status'] == 'Cancelled' || $request['status'] == 'Hold' || $request['status'] == 'Shipped' || $request['status'] == 'Pending') {
                            $response['status'] = 'failed';
                            $response['message'] = 'You do not have permission to update this order status';
                            return json_encode($response);
                        } elseif ($request['status'] == 'Completed' || $request['status'] == 'Del. Failed') {
                            if ($order->status == 'Shipped') {
                            } else {
                                $response['status'] = 'failed';
                                $response['message'] = 'You do not have permission to change status directly';
                                return json_encode($response);
                            }
                        } else {
                        }
                    } else {
                        $response['status'] = 'failed';
                        $response['message'] = 'You do not have permission to update this order status';
                        return json_encode($response);
                    }
                }
                if ($admin->hasRole('accounts')) {
                    if ($order->status == 'Packaging' || $order->status == 'Ready to Ship') {
                        if ($request['status'] == 'Completed' || $request['status'] == 'Del. Failed' || $request['status'] == 'Cancelled' || $request['status'] == 'Hold' || $request['status'] == 'Pending') {
                            $response['status'] = 'failed';
                            $response['message'] = 'You do not have permission to update this order status';
                            return json_encode($response);
                        } elseif ($request['status'] == 'Packaging' || $request['status'] == 'Shipped') {
                        } else {
                        }
                    } else {
                        $response['status'] = 'failed';
                        $response['message'] = 'You do not have permission to update this order status';
                        return json_encode($response);
                    }
                }
                if ($admin->hasRole('support')) {
                    if ($order->status == 'Completed' || $order->status == 'Del. Failed') {
                        if ($order->status == 'Ready to Ship' || $order->status == 'Packaging' || $request['status'] == 'Cancelled' || $request['status'] == 'Hold' || $request['status'] == 'Pending') {
                            $response['status'] = 'failed';
                            $response['message'] = 'You do not have permission to update this order status';
                            return json_encode($response);
                        } elseif ($request['status'] == 'Packaging' || $request['status'] == 'Shipped') {
                        } else {
                        }
                    } else {
                        $response['status'] = 'failed';
                        $response['message'] = 'You do not have permission to update this order status';
                        return json_encode($response);
                    }
                }
            }

            if ($order->status != 'Ready to Ship' || $order->status != 'Packaging' || $order->status != 'Shipped' || $order->status != 'Completed' || $order->status != 'Del. Failed') {
                if ($request['status'] == 'Ready to Ship') {
                    $ops = Orderproduct::where('order_id', $order->id)->get();
                    foreach ($ops as $op) {
                        $size = Size::where('product_id', $op->product_id)->where('size', $op->size)->first();
                        if ($size->available_stock >= $op->quantity) {
                            $size->sold += $op->quantity;
                            $size->available_stock -= $op->quantity;
                            $size->update();
                        } else {
                            $response['status'] = 'failed';
                            $response['message'] = 'You don not have enough stock in your store for Ready to Ship Please check the parcel again !';
                            return json_encode($response);
                            break;
                        }
                    }
                }
            }

            if ($order->status == 'Ready to Ship' || $order->status == 'Packaging' || $order->status == 'Shipped' || $order->status == 'Completed' || $order->status == 'Del. Failed') {
                if ($request['status'] != 'Ready to Ship' || $request['status'] != 'Packaging' || $request['status'] != 'Shipped' || $request['status'] != 'Completed' || $request['status'] != 'Del. Failed') {
                    $ops = Orderproduct::where('order_id', $order->id)->get();
                    foreach ($ops as $op) {
                        $size = Size::where('product_id', $op->product_id)->where('size', $op->size)->first();
                        $size->sold -= $op->quantity;
                        $size->available_stock += $op->quantity;
                        $size->update();
                    }
                }
            }

            $order->status = $status;
        }

        if ($order->status == 'Pending' && $request['status'] == 'Pending') {
        } else {
            if ($request['status'] == 'Hold' || $request['status'] == 'Ready to Ship' || $request['status'] == 'Cancelled') {
                if (isset($order->admin_id)) {
                } else {
                    $order->admin_id = Auth::guard('admin')->user()->id;
                }
            }
            if ($request['status'] == 'Packaging') {
                if (isset($order->packing_by)) {
                } else {
                    $order->packing_by = Auth::guard('admin')->user()->id;
                }
            }
            if ($request['status'] == 'Shipped') {
                $order->deliveryDate = date('Y-m-d');
                if (isset($order->shipped_by)) {
                } else {
                    $order->shipped_by = Auth::guard('admin')->user()->id;
                }
            }
            if ($request['status'] == 'Completed' || $request['status'] == 'Del. Failed') {
                if (isset($order->completed_by)) {
                } else {
                    $order->completeDate = date('Y-m-d');
                    $order->completed_by = Auth::guard('admin')->user()->id;
                }
            }
        }

        if ($request['status'] == 'Shipped') {
            $cu = Customer::where('order_id', $order->id)->first();
            $sendstatus = Http::get('http://bulksmsbd.net/api/smsapi?api_key=3z2e9owl4PGXLakGMAmv&type=text&number=' . $cu->customerPhone . '&senderid=RASHIBD.COM&message= অভিনন্দন,আপনার অর্ডারটি ' . $order->invoiceID . ' কুরিয়ার করা হয়েছে।মোটঃ' . $order->subTotal . ' টাকা। ডেলিভারির সময়ঃ ২-৩ দিন। ট্র্যাক পার্সেলঃ ' . $order->courier_tracking_link . ' , Hotline: 01888173003');

            if ($sendstatus) {
                $comment = new Comment();
                $comment->order_id = $id;
                $comment->comment = 'Successfully send a sms to this customer';
                $comment->admin_id = Auth::guard('admin')->user()->id;
                $comment->status = 1;
                $comment->save();
            } else {
            }
        }

        if ($request['status'] == 'Ready to Ship') {
            $cu = Customer::where('order_id', $order->id)->first();
            $sendstatus = Http::get('http://bulksmsbd.net/api/smsapi?api_key=3z2e9owl4PGXLakGMAmv&type=text&number=' . $cu->customerPhone . '&senderid=RASHIBD.COM&message=ধন্যবাদ, আপনার অর্ডারটি ID:' . $order->invoiceID . ' কনফার্ম হয়েছে - মোটঃ ' . $order->subTotal . ' টাকা।প্যাকেজিং এর জন্য প্রস্তুত , Hotline: 01888173003');

            if ($sendstatus) {
                $comment = new Comment();
                $comment->order_id = $id;
                $comment->comment = 'Successfully send a sms to this customer';
                $comment->admin_id = Auth::guard('admin')->user()->id;
                $comment->status = 1;
                $comment->save();
            } else {
            }
        }

        $result = $order->save();
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Successfully Update Status to ' . $request['status'];
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to update Status ' . $request['status'];
        }

        $comment = new Comment();
        $comment->order_id = $id;
        $comment->comment = Auth::guard('admin')->user()->name . ' Successfully Update #PR001' . $id . ' Order status to ' . $status;
        $comment->admin_id = Auth::guard('admin')->user()->id;
        $comment->status = 1;
        $comment->save();

        return json_encode($response);
    }

    //change status by checkbox
    public function statusUpdateByCheckbox(Request $request)
    {

        $status = $request['status'];
        $ids = $request['orders_id'];
        if ($ids) {
            foreach ($ids as $id) {
                $order = Order::find($id);
                $orderproduct = Orderproduct::where('order_id', $order->id)->whereNull('size')->first();
                $orderproductn = Orderproduct::where('order_id', $order->id)->Where('size', '')->first();
                if ($orderproduct || $orderproductn) {
                    if ($request['status'] != 'Cancelled') {
                        if ($request['status'] == 'Pending') {
                        } else {
                            $response['status'] = 'failed';
                            $response['message'] = 'Unsuccessful to change status Please select product size first';
                            return json_encode($response);
                            break;
                        }
                    }
                }

                if ($request['status'] != $order->status) {
                    $admin = Admin::where('email', Auth::guard('admin')->user()->email)->first();
                    if ($admin->hasRole('user')) {
                        if ($order->status == 'Hold' || $order->status == 'Ready to Ship' || $order->status == 'Cancelled' || $order->status == 'Pending') {
                            if ($request['status'] == 'Del. Failed' || $request['status'] == 'Completed' || $request['status'] == 'Shipped' || $request['status'] == 'Packaging') {
                                $response['status'] = 'failed';
                                $response['message'] = 'You do not have permission to update this order status';
                                return json_encode($response);
                                break;
                            }
                        } else {
                            $response['status'] = 'failed';
                            $response['message'] = 'You do not have permission to update this order status';
                            return json_encode($response);
                            break;
                        }
                    }
                    if ($admin->hasRole('admin')) {
                        if ($order->status == 'Ready to Ship' || $order->status == 'Packaging' || $order->status == 'Completed' || $order->status == 'Del. Failed' || $order->status == 'Shipped') {
                            if ($request['status'] == 'Cancelled' || $request['status'] == 'Hold' || $request['status'] == 'Shipped' || $request['status'] == 'Pending') {
                                $response['status'] = 'failed';
                                $response['message'] = 'You do not have permission to update this order status';
                                return json_encode($response);
                                break;
                            } elseif ($request['status'] == 'Completed' || $request['status'] == 'Del. Failed') {
                                if ($order->status == 'Shipped') {
                                } else {
                                    $response['status'] = 'failed';
                                    $response['message'] = 'You do not have permission to change status directly';
                                    return json_encode($response);
                                    break;
                                }
                            } else {
                            }
                        } else {
                            $response['status'] = 'failed';
                            $response['message'] = 'You do not have permission to update this order status';
                            return json_encode($response);
                            break;
                        }
                    }
                    if ($admin->hasRole('manager')) {
                        if ($order->status == 'Packaging' || $order->status == 'Shipped') {
                            if ($request['status'] == 'Ready to Ship' || $request['status'] == 'Completed' || $request['status'] == 'Del. Failed' || $request['status'] == 'Cancelled' || $request['status'] == 'Hold' || $request['status'] == 'Pending') {
                                $response['status'] = 'failed';
                                $response['message'] = 'You do not have permission to update this order status';
                                return json_encode($response);
                                break;
                            } elseif ($request['status'] == 'Packaging' || $request['status'] == 'Shipped') {
                            } else {
                            }
                        } else {
                            $response['status'] = 'failed';
                            $response['message'] = 'You do not have permission to update this order status';
                            return json_encode($response);
                            break;
                        }
                    }
                }

                if ($order->status == 'Pending' && $request['status'] == 'Pending') {
                } else {
                    if ($request['status'] == 'Hold' || $request['status'] == 'Ready to Ship' || $request['status'] == 'Cancelled') {
                        if (isset($order->admin_id)) {
                        } else {
                            $order->admin_id = Auth::guard('admin')->user()->id;
                        }
                    }
                    if ($request['status'] == 'Packaging') {
                        if (isset($order->packing_by)) {
                        } else {
                            $order->packing_by = Auth::guard('admin')->user()->id;
                            $cu = Customer::where('order_id', $order->id)->first();
                            $sendstatus = Http::get('http://bulksmsbd.net/api/smsapi?api_key=3z2e9owl4PGXLakGMAmv&type=text&number=' . $cu->customerPhone . '&senderid=RASHIBD.COM&message=ধন্যবাদ, আপনার অর্ডারটি ID:' . $order->invoiceID . ' কনফার্ম হয়েছে - মোটঃ ' . $order->subTotal . ' টাকা।প্যাকেজিং এর জন্য প্রস্তুত , Hotline: 01888173003');

                            if ($sendstatus) {
                                $comment = new Comment();
                                $comment->order_id = $order->id;
                                $comment->comment = 'Successfully send a sms to this customer';
                                $comment->admin_id = Auth::guard('admin')->user()->id;
                                $comment->status = 1;
                                $comment->save();
                            }
                        }
                    }
                    if ($request['status'] == 'Shipped') {
                        if (isset($order->shipped_by)) {
                        } else {
                            $order->shipped_by = Auth::guard('admin')->user()->id;
                            $cu = Customer::where('order_id', $order->id)->first();
                            $sendstatus = Http::get('http://bulksmsbd.net/api/smsapi?api_key=3z2e9owl4PGXLakGMAmv&type=text&number=' . $cu->customerPhone . '&senderid=RASHIBD.COM&message= অভিনন্দন,আপনার অর্ডারটি ' . $order->invoiceID . ' কুরিয়ার করা হয়েছে।মোটঃ' . $order->subTotal . ' টাকা। ডেলিভারির সময়ঃ ২-৩ দিন। ট্র্যাক পার্সেলঃ ' . $order->courier_tracking_link . ' , Hotline: 01888173003');

                            if ($sendstatus) {
                                $comment = new Comment();
                                $comment->order_id = $order->id;
                                $comment->comment = 'Successfully send a sms to this customer';
                                $comment->admin_id = Auth::guard('admin')->user()->id;
                                $comment->status = 1;
                                $comment->save();
                            }
                        }
                    }
                    if ($request['status'] == 'Completed' || $request['status'] == 'Del. Failed') {
                        if (isset($order->completed_by)) {
                        } else {
                            $order->completeDate = date('Y-m-d');
                            $order->completed_by = Auth::guard('admin')->user()->id;
                        }
                    }
                }

                if ($order->status != 'Ready to Ship' || $order->status != 'Packaging' || $order->status != 'Shipped' || $order->status != 'Completed' || $order->status != 'Del. Failed') {
                    if ($request['status'] == 'Ready to Ship') {
                        $ops = Orderproduct::where('order_id', $order->id)->get();
                        foreach ($ops as $op) {
                            $size = Size::where('product_id', $op->product_id)->where('size', $op->size)->first();
                            if ($size->available_stock >= $op->quantity) {
                                $size->sold += $op->quantity;
                                $size->available_stock -= $op->quantity;
                                $size->update();
                            } else {
                                $response['status'] = 'failed';
                                $response['message'] = 'You don not have enough stock in your store for Ready to Ship Please check the parcel again !';
                                return json_encode($response);
                                break;
                            }
                        }
                    }
                }

                if ($order->status == 'Ready to Ship' || $order->status == 'Packaging' || $order->status == 'Shipped' || $order->status == 'Completed' || $order->status == 'Del. Failed') {
                    if ($request['status'] != 'Ready to Ship' || $request['status'] != 'Packaging' || $request['status'] != 'Shipped' || $request['status'] != 'Completed' || $request['status'] != 'Del. Failed') {
                        $ops = Orderproduct::where('order_id', $order->id)->get();
                        foreach ($ops as $op) {
                            $size = Size::where('product_id', $op->product_id)->where('size', $op->size)->first();
                            $size->sold -= $op->quantity;
                            $size->available_stock += $op->quantity;
                            $size->update();
                        }
                    }
                }

                if($request->status == 'Cancelled'){
                    $statusnew='OrderCancelled';
                    $this->orderflow($order,$statusnew);
                }
                if($request->status == 'Completed'){
                    $statusnew='OrderDelivered';
                    $this->orderflow($order,$statusnew);
                }

                if($request->status == 'Cancelled' && $request->status == 'Del. Failed'){
                    $statusnew='OrderReturn';
                    $this->orderflow($order,$statusnew);
                }


                $order->status = $status;
                $result = $order->save();
                if ($result) {
                    $response['status'] = 'success';
                    $response['message'] = 'Successfully Update Status to ' . $request['status'];
                } else {
                    $response['status'] = 'failed';
                    $response['message'] = 'Unsuccessful to update Status ' . $request['status'];
                }
                $comment = new Comment();
                $comment->order_id = $id;
                $comment->comment = Auth::guard('admin')->user()->name . ' Successfully Update #PR001' . $id . ' Order status to ' . $status;
                $comment->admin_id = Auth::guard('admin')->user()->id;
                $comment->status = 1;
                $comment->save();
            }
            $response['status'] = 'success';
            $response['message'] = Auth::guard('admin')->user()->name . ' Successfully Update #PR001' . $id . ' Order status to ' . $status;
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to change status of this Order';
        }
        return json_encode($response);
    }

    public function statusList($status, $id)
    {
        $admin = Admin::where('id', Auth::guard('admin')->user()->id)->first();
        if ($admin->hasRole('superadmin')) {
            $allStatus = array(
                'order' => array(
                    "Pending" => array(
                        "name" => "Pending",
                        "icon" => "fe-tag",
                        "color" => " bg-light"
                    ),
                    "Ready to Ship" => array(
                        "name" => "Ready to Ship",
                        "icon" => "fe-tag",
                        "color" => " bg-info"
                    ),
                    "Hold" => array(
                        "name" => "Hold",
                        "icon" => "far fa-stop-circle",
                        "color" => " bg-warning"
                    ),
                    "Packaging" => array(
                        "name" => "Packaging",
                        "icon" => "far fa-stop-circle",
                        "color" => " bg-dark"
                    ),
                    "Shipped" => array(
                        "name" => "Shipped",
                        "icon" => "far fa-stop-circle",
                        "color" => " bg-success"
                    ),
                    "Courier Pending" => array(
                        "name" => "Courier Pending",
                        "icon" => "fe-check-circle",
                        "color" => " bg-info"
                    ),
                    "Cancelled" => array(
                        "name" => "Cancelled",
                        "icon" => "fe-trash-2",
                        "color" => " bg-danger"
                    ),
                    "Completed" => array(
                        "name" => "Completed",
                        "icon" => "fe-check-circle",
                        "color" => " bg-success"
                    ),
                    "Del. Failed" => array(
                        "name" => "Del. Failed",
                        "icon" => "fe-check-circle",
                        "color" => " bg-danger"
                    ),
                    "Partial Delivered" => array(
                        "name" => "Partial Delivered",
                        "icon" => "fe-check-circle",
                        "color" => " bg-info"
                    ),
                    "Return" => array(
                        "name" => "Return",
                        "icon" => "fe-check-circle",
                        "color" => " bg-success"
                    ),
                    "Unknown" => array(
                        "name" => "Unknown",
                        "icon" => "fe-check-circle",
                        "color" => " bg-danger"
                    )
                )
            );
        } elseif ($admin->hasRole('user')) {
            $allStatus = array(
                'order' => array(
                    "Pending" => array(
                        "name" => "Pending",
                        "icon" => "fe-tag",
                        "color" => " bg-light"
                    ),
                    "Ready to Ship" => array(
                        "name" => "Ready to Ship",
                        "icon" => "fe-tag",
                        "color" => " bg-info"
                    ),
                    "Hold" => array(
                        "name" => "Hold",
                        "icon" => "far fa-stop-circle",
                        "color" => " bg-warning"
                    ),
                    "Cancelled" => array(
                        "name" => "Cancelled",
                        "icon" => "fe-trash-2",
                        "color" => " bg-danger"
                    )
                )
            );
        } elseif ($admin->hasRole('accounts')) {
            $allStatus = array(
                'order' => array(
                    "Ready to Ship" => array(
                        "name" => "Ready to Ship",
                        "icon" => "fe-tag",
                        "color" => " bg-info"
                    ),

                    "Packaging" => array(
                        "name" => "Packaging",
                        "icon" => "far fa-stop-circle",
                        "color" => " bg-dark"
                    ),
                )
            );
        } elseif ($admin->hasRole('store')) {
            $allStatus = array(
                'order' => array(
                    "Packaging" => array(
                        "name" => "Packaging",
                        "icon" => "far fa-stop-circle",
                        "color" => " bg-dark"
                    ),
                    "Ready to Ship" => array(
                        "name" => "Ready to Ship",
                        "icon" => "far fa-stop-circle",
                        "color" => " bg-success"
                    ),
                    "Shipped" => array(
                        "name" => "Shipped",
                        "icon" => "far fa-stop-circle",
                        "color" => " bg-success"
                    ),
                    "Completed" => array(
                        "name" => "Completed",
                        "icon" => "fe-check-circle",
                        "color" => " bg-success"
                    ),
                    "Del. Failed" => array(
                        "name" => "Del. Failed",
                        "icon" => "fe-check-circle",
                        "color" => " bg-danger"
                    )
                )
            );
        } elseif ($admin->hasRole('support')) {
            $allStatus = array(
                'order' => array(
                    "Shipped" => array(
                        "name" => "Shipped",
                        "icon" => "far fa-stop-circle",
                        "color" => " bg-success"
                    ),
                    "Completed" => array(
                        "name" => "Completed",
                        "icon" => "fe-check-circle",
                        "color" => " bg-success"
                    ),
                    "Del. Failed" => array(
                        "name" => "Del. Failed",
                        "icon" => "fe-check-circle",
                        "color" => " bg-danger"
                    )
                )
            );
        } else {
            $allStatus = array(
                'order' => array(
                    "Pending" => array(
                        "name" => "Pending",
                        "icon" => "fe-tag",
                        "color" => " bg-light"
                    ),
                    "Ready to Ship" => array(
                        "name" => "Ready to Ship",
                        "icon" => "fe-tag",
                        "color" => " bg-info"
                    ),
                    "Hold" => array(
                        "name" => "Hold",
                        "icon" => "far fa-stop-circle",
                        "color" => " bg-warning"
                    ),
                    "Packaging" => array(
                        "name" => "Packaging",
                        "icon" => "far fa-stop-circle",
                        "color" => " bg-dark"
                    ),
                    "Shipped" => array(
                        "name" => "Shipped",
                        "icon" => "far fa-stop-circle",
                        "color" => " bg-success"
                    ),
                    "Cancelled" => array(
                        "name" => "Cancelled",
                        "icon" => "fe-trash-2",
                        "color" => " bg-danger"
                    ),
                    "Completed" => array(
                        "name" => "Completed",
                        "icon" => "fe-check-circle",
                        "color" => " bg-success"
                    ),
                    "Del. Failed" => array(
                        "name" => "Del. Failed",
                        "icon" => "fe-check-circle",
                        "color" => " bg-danger"
                    )
                )
            );
        }


        $temp = 'order';
        foreach ($allStatus as $key => $value) {
            foreach ($value as $kes => $val) {
                if ($kes == $status) {
                    $temp = $key;
                }
            }
        }
        $args = $allStatus[$temp];
        $html = '';
        foreach ($args as $value) {
            if ($args[$status]['name'] != $value['name']) {

                $html = $html . "<a class=' btn-sm dropdown-item btn-status' data-id='" . $id . "' data-status='" . $value['name'] . "' href='#'>" . $value['name'] . "</a>";
            }
        }
        $response = "<div class='btn-group dropdown'>
            <a href='javascript: void(0);' style='color:white'  class=' btn-sm table-action-btn dropdown-toggle arrow-none btn" . $args[$status]['color'] . " btn-xs' data-bs-toggle='dropdown' aria-expanded='false' >" . $args[$status]['name'] . " <i class='mdi mdi-chevron-down'></i></a>
            <div class='dropdown-menu dropdown-menu-right'>
            " . $html . "
            </div>
        </div>";

        return $response;
    }

    public function courier(Request $request)
    {
        if (isset($request['q'])) {
            $couriers = Courier::query()->where([
                ['courierName', 'like', '%' . $request['q'] . '%'],
                ['status', 'like', 'Active']
            ])->get();
        } else {
            $couriers = Courier::query()->where('status', 'like', 'Active')->get();
        }
        $courier = array();
        foreach ($couriers as $item) {
            $courier[] = array(
                "id" => $item['id'],
                "text" => $item['courierName']
            );
        }
        return json_encode($courier);
    }

    //get users
    public function users(Request $request)
    {
        if (isset($request['q'])) {
            $users = Admin::where('status', 'Active')->query()->where([['name', 'like', '%' . $request['q'] . '%']])->get();
        } else {
            $users = Admin::where('status', 'Active')->get();
        }
        $user = array();
        foreach ($users as $item) {
            $user[] = array(
                "id" => $item['id'],
                "text" => $item['name']
            );
        }
        return json_encode($user);
        die();
    }

    public function getinfo(Request $request)
    {
        $response['courierpayment'] = DB::table('accounts')->where('form', 'Courier')->whereBetween('date', [$request->infodate, $request->infodateto])->get()->sum('amount');
        $response['officesalepayment'] = DB::table('accounts')->where('form', 'Office Sale')->whereBetween('date', [$request->infodate, $request->infodateto])->get()->sum('amount');
        $response['wholesalepayment'] = DB::table('accounts')->where('form', 'Wholesale')->whereBetween('date', [$request->infodate, $request->infodateto])->get()->sum('amount');
        $response['totalpayment'] = DB::table('accounts')->whereBetween('date', [$request->infodate, $request->infodateto])->get()->sum('amount');
        $response['totalcost'] = DB::table('expenses')->whereBetween('date', [$request->infodate, $request->infodateto])->get()->sum('amount');
        $response['bankcost'] = DB::table('expenses')->where('type', 'Bank Deposit')->whereBetween('date', [$request->infodate, $request->infodateto])->get()->sum('amount');
        $response['officecost'] = DB::table('expenses')->where('type', 'Office Cost')->whereBetween('date', [$request->infodate, $request->infodateto])->get()->sum('amount');
        $response['bostcost'] = DB::table('expenses')->where('type', 'Boost Cost')->whereBetween('date', [$request->infodate, $request->infodateto])->get()->sum('amount');
        $response['status'] = 'success';
        return json_encode($response);
    }

    public function getorderinfo(Request $request)
    {
        $response['total'] = DB::table('orders')->count();
        $response['all'] = DB::table('orders')->whereBetween('orderDate', [$request->orderdate, $request->orderdateto])->count();
        $response['pending'] = DB::table('orders')->whereBetween('orderDate', [$request->orderdate, $request->orderdateto])->where('status', 'like', 'Pending')->count();
        $response['readytoship'] = DB::table('orders')->whereBetween('orderDate', [$request->orderdate, $request->orderdateto])->where('status', 'like', 'Ready to Ship')->count();
        $response['hold'] = DB::table('orders')->whereBetween('orderDate', [$request->orderdate, $request->orderdateto])->where('status', 'like', 'Hold')->count();
        $response['shipped'] = DB::table('orders')->whereBetween('orderDate', [$request->orderdate, $request->orderdateto])->where('status', 'like', 'Shipped')->count();
        $response['cancelled'] = DB::table('orders')->whereBetween('orderDate', [$request->orderdate, $request->orderdateto])->where('status', 'like', 'Cancelled')->count();
        $response['completed'] = DB::table('orders')->whereBetween('orderDate', [$request->orderdate, $request->orderdateto])->where('status', 'like', 'Completed')->count();
        $response['packaging'] = DB::table('orders')->whereBetween('orderDate', [$request->orderdate, $request->orderdateto])->where('status', 'like', 'Packaging')->count();
        $response['delFailed'] = DB::table('orders')->whereBetween('orderDate', [$request->orderdate, $request->orderdateto])->where('status', 'like', 'Del. Failed')->count();
        $response['courierPending'] = DB::table('orders')->whereBetween('orderDate', [$request->orderdate, $request->orderdateto])->where('status', 'like', 'Courier Pending')->count();
        $response['partialDelivered'] = DB::table('orders')->whereBetween('orderDate', [$request->orderdate, $request->orderdateto])->where('status', 'like', 'Partial Delivered')->count();
        $response['unknown'] = DB::table('orders')->whereBetween('orderDate', [$request->orderdate, $request->orderdateto])->where('status', 'like', 'Unknown')->count();
        $response['status'] = 'success';
        return json_encode($response);
    }


    public function countorder()
    {
        $admin = Admin::where('email', Auth::guard('admin')->user()->email)->first();
        $response['allorder'] = DB::table('orders')->count();


        $response['all'] = DB::table('orders')->count();
        $response['pending'] = DB::table('orders')->where('status', 'like', 'Pending')->count();
        $response['readytoship'] = DB::table('orders')->where('status', 'like', 'Ready to Ship')->count();
        $response['hold'] = DB::table('orders')->where('status', 'like', 'Hold')->count();
        $response['shipped'] = DB::table('orders')->where('status', 'like', 'Shipped')->count();
        $response['cancelled'] = DB::table('orders')->where('status', 'like', 'Cancelled')->count();
        $response['completed'] = DB::table('orders')->where('status', 'like', 'Completed')->count();
        $response['packaging'] = DB::table('orders')->where('status', 'like', 'Packaging')->count();
        $response['delFailed'] = DB::table('orders')->where('status', 'like', 'Del. Failed')->count();
        $response['courierPending'] = DB::table('orders')->where('status', 'like', 'Courier Pending')->count();
        $response['partialDelivered'] = DB::table('orders')->where('status', 'like', 'Partial Delivered')->count();
        $response['unknown'] = DB::table('orders')->where('status', 'like', 'Unknown')->count();

        $response['status'] = 'success';
        return json_encode($response);
    }

    public function countorderbyid($id)
    {

        if ($id == 0) {
            $response['title'] = '/Today';
            $response['allorder'] = DB::table('orders')->where('updated_at',  '>=', Carbon::today()->format('y-m-d'))->count();
            $response['all'] = DB::table('orders')->where('created_at',  '>=', Carbon::today()->format('y-m-d'))->count();
            $response['pending'] = DB::table('orders')->where('status', 'like', 'Pending')->where('created_at',  '>=', Carbon::today()->format('y-m-d'))->count();
            $response['readytoship'] = DB::table('orders')->where('status', 'like', 'Ready to Ship')->where('updated_at',  '>=', Carbon::today()->format('y-m-d'))->count();
            $response['hold'] = DB::table('orders')->where('status', 'like', 'Hold')->where('created_at',  '>=', Carbon::today()->format('y-m-d'))->count();
            $response['shipped'] = DB::table('orders')->where('status', 'like', 'Shipped')->where('updated_at',  '>=', Carbon::today()->format('y-m-d'))->count();
            $response['cancelled'] = DB::table('orders')->where('status', 'like', 'Cancelled')->where('updated_at',  '>=', Carbon::today()->format('y-m-d'))->count();
            $response['completed'] = DB::table('orders')->where('status', 'like', 'Completed')->where('updated_at',  '>=', Carbon::today()->format('y-m-d'))->count();
            $response['packaging'] = DB::table('orders')->where('status', 'like', 'Packaging')->where('updated_at',  '>=', Carbon::today()->format('y-m-d'))->count();
            $response['delFailed'] = DB::table('orders')->where('status', 'like', 'Del. Failed')->where('updated_at',  '>=', Carbon::today()->format('y-m-d'))->count();

            $response['status'] = 'success';
            return json_encode($response);
        } else if ($id == 1) {
            $response['title'] = '/This Month';
            $response['allorder'] = DB::table('orders')->whereYear('updated_at',  '>=', Carbon::now()->year)->whereMonth('updated_at',  '>=', Carbon::now()->month)->count();
            $response['all'] = DB::table('orders')->where('created_at', '>=', Carbon::now()->month()->format('y-m-d'))->count();
            $response['pending'] = DB::table('orders')->where('status', 'like', 'Pending')->whereYear('created_at',  '>=', Carbon::now()->year)->whereMonth('created_at',  '>=', Carbon::now()->month)->count();
            $response['readytoship'] = DB::table('orders')->where('status', 'like', 'Ready to Ship')->whereYear('created_at',  '>=', Carbon::now()->year)->whereMonth('created_at',  '>=', Carbon::now()->month)->count();
            $response['hold'] = DB::table('orders')->where('status', 'like', 'Hold')->whereYear('created_at',  '>=', Carbon::now()->year)->whereMonth('created_at',  '>=', Carbon::now()->month)->count();
            $response['shipped'] = DB::table('orders')->where('status', 'like', 'Shipped')->whereYear('updated_at',  '>=', Carbon::now()->year)->whereMonth('updated_at',  '>=', Carbon::now()->month)->count();
            $response['cancelled'] = DB::table('orders')->where('status', 'like', 'Cancelled')->whereYear('updated_at',  '>=', Carbon::now()->year)->whereMonth('updated_at',  '>=', Carbon::now()->month)->count();
            $response['completed'] = DB::table('orders')->where('status', 'like', 'Completed')->whereYear('updated_at',  '>=', Carbon::now()->year)->whereMonth('updated_at',  '>=', Carbon::now()->month)->count();
            $response['packaging'] = DB::table('orders')->where('status', 'like', 'Packaging')->orWhere('orders.status', 'like', 'Pending Invoiced')->whereYear('updated_at',  '>=', Carbon::now()->year)->whereMonth('updated_at',  '>=', Carbon::now()->month)->count();
            $response['delFailed'] = DB::table('orders')->where('status', 'like', 'Del. Failed')->orWhere('orders.status', 'like', 'Pending Invoiced')->whereYear('updated_at',  '>=', Carbon::now()->year)->whereMonth('updated_at',  '>=', Carbon::now()->month)->count();

            $response['status'] = 'success';
            return json_encode($response);
        } else {
            $response['title'] = '/This Year';
            $response['allorder'] = DB::table('orders')->whereYear('updated_at',  '>=', Carbon::now()->year)->count();
            $response['all'] = DB::table('orders')->whereYear('created_at', '>=', Carbon::now()->year)->count();
            $response['pending'] = DB::table('orders')->where('status', 'like', 'Pending')->whereYear('created_at', '>=', Carbon::now()->year)->count();
            $response['readytoship'] = DB::table('orders')->where('status', 'like', 'Ready to Ship')->whereYear('updated_at', '>=', Carbon::now()->year)->count();
            $response['hold'] = DB::table('orders')->where('status', 'like', 'Hold')->whereYear('created_at', '>=', Carbon::now()->year)->count();
            $response['shipped'] = DB::table('orders')->where('status', 'like', 'Shipped')->whereYear('updated_at', '>=', Carbon::now()->year)->count();
            $response['cancelled'] = DB::table('orders')->where('status', 'like', 'Cancelled')->whereYear('updated_at', '>=', Carbon::now()->year)->count();
            $response['completed'] = DB::table('orders')->where('status', 'like', 'Completed')->whereYear('updated_at', '>=', Carbon::now()->year)->count();
            $response['packaging'] = DB::table('orders')->where('status', 'like', 'Packaging')->whereYear('updated_at', '>=', Carbon::now()->year)->count();
            $response['delFailed'] = DB::table('orders')->where('status', 'like', 'Del. Failed')->whereYear('updated_at', '>=', Carbon::now()->year)->count();

            $response['status'] = 'success';
            return json_encode($response);
        }
    }

    //top sell product

    public function topsellpeoduct($id)
    {
        if ($id == 0) {
            $response['orders'] = DB::table('orders')->whereIn('orders.status', ['Invoiced', 'Stock Out', 'Paid', 'Return', 'Lost', 'Invoiced', 'Delivered'])
                ->where('orders.created_at',  '>=', Carbon::today()->format('y-m-d'))
                ->join('orderproducts', 'orders.id', '=', 'orderproducts.order_id')
                ->select('orders.status', 'orders.orderDate', 'orderproducts.*', DB::raw('SUM(quantity) as total_amount'))
                ->groupBy('orderproducts.product_id')->orderBy('total_amount', 'desc')->get();
            $response['status'] = 'success';
            return json_encode($response);
        } else if ($id == 1) {
            $response['orders'] = DB::table('orders')->whereIn('orders.status', ['Invoiced', 'Stock Out', 'Paid', 'Return', 'Lost', 'Invoiced', 'Delivered'])
                ->whereYear('orders.created_at',  '>=', Carbon::now()->year)->whereMonth('orders.created_at',  '>=', Carbon::now()->month)
                ->join('orderproducts', 'orders.id', '=', 'orderproducts.order_id')
                ->select('orders.status', 'orders.orderDate', 'orderproducts.*', DB::raw('SUM(quantity) as total_amount'))
                ->groupBy('orderproducts.product_id')->orderBy('total_amount', 'desc')->get();
            $response['status'] = 'success';
            return json_encode($response);
        } else {
            $response['orders'] = DB::table('orders')->whereIn('orders.status', ['Invoiced', 'Stock Out', 'Paid', 'Return', 'Lost', 'Invoiced', 'Delivered'])
                ->whereYear('orders.created_at',  '>=', Carbon::now()->year)
                ->join('orderproducts', 'orders.id', '=', 'orderproducts.order_id')
                ->select('orders.status', 'orders.orderDate', 'orderproducts.*', DB::raw('SUM(quantity) as total_amount'))
                ->groupBy('orderproducts.product_id')->orderBy('total_amount', 'desc')->get();
            $response['status'] = 'success';
            return json_encode($response);
        }
    }

    //recent sell

    public function recentsellpeoduct($id)
    {
        if ($id == 0) {
            $response['title'] = '/Today';
            $response['orders'] = Order::with(['orderproducts' => function ($query) {
                $query->select('id', 'order_id', 'productName');
            }, 'customers' => function ($query) {
                $query->select('id', 'order_id', 'customerName');
            }])->whereIn('orders.status', ['Invoiced', 'Stock Out', 'Paid', 'Return', 'Lost', 'Invoiced', 'Delivered'])
                ->where('updated_at',  '>=', Carbon::today()->format('y-m-d'))->select('id', 'invoiceID', 'subTotal', 'status')->get();
            $response['status'] = 'success';
            return json_encode($response);
        } else if ($id == 1) {
            $response['title'] = '/This Month';
            $response['orders'] = Order::with(['orderproducts' => function ($query) {
                $query->select('id', 'order_id', 'productName');
            }, 'customers' => function ($query) {
                $query->select('id', 'order_id', 'customerName');
            }])->whereIn('orders.status', ['Invoiced', 'Stock Out', 'Paid', 'Return', 'Lost', 'Invoiced', 'Delivered'])
                ->whereYear('updated_at',  '>=', Carbon::now()->year)->whereMonth('updated_at',  '>=', Carbon::now()->month)->select('id', 'invoiceID', 'subTotal', 'status')->get();
            $response['status'] = 'success';
            return json_encode($response);
        } else {
            $response['title'] = '/This Year';
            $response['orders'] = Order::with(['orderproducts' => function ($query) {
                $query->select('id', 'order_id', 'productName');
            }, 'customers' => function ($query) {
                $query->select('id', 'order_id', 'customerName');
            }])->whereIn('orders.status', ['Invoiced', 'Stock Out', 'Paid', 'Return', 'Lost', 'Completed', 'Delivered'])
                ->whereYear('updated_at',  '>=', Carbon::now()->year)->select('id', 'invoiceID', 'subTotal', 'status')->get();
            $response['status'] = 'success';
            return json_encode($response);
        }
    }

    // Get Payment Type
    public function paymenttype(Request $request)
    {
        if (isset($request['q'])) {
            $paymentTypes = Paymenttype::query()->where([
                ['paymentTypeName', 'like', '%' . $request['q'] . '%'],
                ['status', 'Active']
            ])->get();
        } else {
            $paymentTypes = PaymentType::query()->where('status', 'Active')->get();
        }
        $paymentType = array();
        foreach ($paymentTypes as $item) {
            $paymentType[] = array(
                "id" => $item['id'],
                "text" => $item['paymentTypeName']
            );
        }
        return json_encode($paymentType);
    }

    //Payment_ID
    public function payment_id(Request $request)
    {
        if (isset($request['q'])) {
            $users = Payment::query()->where('name', 'like', '%' . $request['q'] . '%')->get();
        } else {
            $users = Payment::all();
        }
        $user = array();
        foreach ($users as $item) {
            $user[] = array(
                "id" => $item['id'],
                "text" => $item['paymentNumber']
            );
        }
        return json_encode($user);
    }

    //payment number
    public function paymentnumber(Request $request)
    {
        if (isset($request['q']) && $request['paymentTypeID']) {
            $payments = Payment::query()->where([
                ['paymentNumber', 'like', '%' . $request['q'] . '%'],
                ['status', 'like', 'Active'],
                ['payment_type_id', '=', $request['paymentTypeID']]
            ])->get();
        } else {
            $payments = Payment::query()->where([
                ['status', 'like', 'Active'],
                ['payment_type_id', '=', $request['paymentTypeID']]
            ])->get();
        }
        $payment = array();
        foreach ($payments as $item) {
            $payment[] = array(
                "id" => $item['id'],
                "text" => $item['paymentNumber']
            );
        }
        return json_encode($payment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $orders = DB::table('orders')
            ->select('orders.*', 'customers.customerName', 'customers.customerPhone', 'customers.customerAddress', 'couriers.courierName', 'cities.cityName', 'zones.zoneName', 'areas.areaName', 'admins.name',  'paymenttypes.paymentTypeName', 'payments.paymentNumber')
            ->leftJoin('customers', 'orders.id', '=', 'customers.order_id')
            ->leftJoin('couriers', 'orders.courier_id', '=', 'couriers.id')
            ->leftJoin('paymenttypes', 'orders.payment_type_id', '=', 'paymenttypes.id')
            ->leftJoin('payments', 'orders.payment_id', '=', 'payments.id')
            ->leftJoin('cities', 'orders.city_id', '=', 'cities.id')
            ->leftJoin('zones', 'orders.zone_id', '=', 'zones.id')
            ->leftJoin('areas', 'orders.area_id', '=', 'areas.id')
            ->leftJoin('admins', 'orders.admin_id', '=', 'admins.id')
            ->where('orders.id', '=', $id)->get()->first();
        $products = DB::table('orderproducts')->where('order_id', '=', $id)->get();
        $orders->products = $products;
        $orders->id = $id;
        return view('admin.content.order.edit')->with('order', $orders);
    }

    //product
    public function wholeproduct(Request $request)
    {
        if (isset($request['q'])) {
            $type0 = DB::table('sizes')
                ->select('sizes.*', 'products.ProductName', 'products.ProductSku', 'products.ProductImage')
                ->join('products', 'products.id', '=', 'sizes.product_id')
                ->where('ProductSku', 'like', '%' . $request['q'] . '%')->get();
        } else {
            $type0 = DB::table('sizes')
                ->select('sizes.*', 'products.ProductName', 'products.ProductSku', 'products.ProductImage')
                ->join('products', 'products.id', '=', 'sizes.product_id')
                ->where('ProductSku', 'like', '%' . $request['q'] . '%')->get();
        }

        $products = $type0;

        foreach ($products as $item) {

            if (App::environment('local')) {
                $item->ProductImage = url($item->ProductImage);
            } else {
                $item->ProductImage = url($item->ProductImage);
            }
            $product[] = array(
                "id" => $item->product_id,
                "size_id" => $item->id,
                "text" => $item->ProductName,
                "size" => $item->size,
                "image" => $item->ProductImage,
                "productCode" => $item->ProductSku,
                "productPrice" => intval($item->SalePrice)
            );
        }

        $data['data'] = $product;
        return $data;
        die();
    }

    public function admproduct(Request $request)
    {
        if (isset($request['q'])) {
            $type0 = DB::table('sizes')
                ->select('sizes.*', 'products.ProductName', 'products.ProductSku', 'products.ProductImage')
                ->join('products', 'products.id', '=', 'sizes.product_id')
                ->where('ProductName', 'like', '%' . $request['q'] . '%')->get();
        } else {
            $type0 = DB::table('sizes')
                ->select('sizes.*', 'products.ProductName', 'products.ProductSku', 'products.ProductImage')
                ->join('products', 'products.id', '=', 'sizes.product_id')
                ->where('ProductName', 'like', '%' . $request['q'] . '%')->get();
        }

        $products = $type0;

        foreach ($products as $item) {

            if (App::environment('local')) {
                $item->ProductImage = url($item->ProductImage);
            } else {
                $item->ProductImage = url($item->ProductImage);
            }
            $product[] = array(
                "id" => $item->product_id,
                "size_id" => $item->id,
                "text" => $item->ProductName,
                "size" => $item->size,
                "image" => $item->ProductImage,
                "productCode" => $item->ProductSku,
                "productPrice" => $item->SalePrice
            );
        }

        $data['data'] = $product;
        return $data;
        die();
    }

    public function admminiproduct(Request $request)
    {
        $type0 = DB::table('products')->where('ProductSku', 'like', '%' . $request['q'] . '%')->get();

        $products = $type0;

        foreach ($products as $item) {

            if (App::environment('local')) {
                $item->ProductImage = url($item->ProductImage);
            } else {
                $item->ProductImage = url($item->ProductImage);
            }
            $product[] = array(
                "id" => $item->id,
                "text" => $item->ProductName,
                "image" => $item->ProductImage,
                "productCode" => $item->ProductSku,
            );
        }

        $data['data'] = $product;
        return $data;
        die();
    }

    //old orders
    public function previous_orders(Request $request)
    {
        $order_id = $request['id'];
        $customer = Customer::query()->where('order_id', '=', $order_id)->get()->first();

        $orders = DB::table('orders')
            ->select('orders.*', 'customers.*', 'admins.name')
            ->leftJoin('customers', 'orders.id', '=', 'customers.order_id')
            ->leftJoin('admins', 'orders.admin_id', '=', 'admins.id')
            ->where([
                ['customers.order_id', '!=', $order_id],
                ['customers.customerPhone', $customer->customerPhone]
            ])->get();

        $order['data'] = $orders->map(function ($order) {
            $products = DB::table('orderproducts')->select('orderproducts.*')->where('order_id', '=', $order->id)->get();
            $orderProducts = '';
            foreach ($products as $product) {
                $orderProducts = $orderProducts . $product->quantity . ' x ' . $product->productName . '<br>';
            }
            $order->products = rtrim($orderProducts, '<br>');
            return $order;
        });
        return json_encode($order);
    }

    //assign user
    public function assignuser(Request $request)
    {
        $user_id = $request['user_id'];
        $ids = $request['ids'];
        if ($ids) {
            foreach ($ids as $id) {
                $order = Order::find($id);
                $order->admin_id = $user_id;
                $order->save();
                $comment = new Comment();
                $user = Admin::find($user_id);
                $comment->order_id = $id;
                $comment->comment = Auth::guard('admin')->user()->name . ' Successfully Assign #PR001' . $id . ' Order to ' . $user->name;
                $comment->admin_id = Auth::guard('admin')->user()->id;
                $comment->save();
            }
            $response['status'] = 'success';
            $response['message'] = 'Successfully Assign User to this Order';
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Assign User to this Order';
        }
        return json_encode($response);
    }

    public function assigncourier(Request $request)
    {
        $courier_id = $request['courier_id'];
        $courier = Courier::find($courier_id);
        $ids = $request['ids'];

        if ($ids) {
            foreach ($ids as $id) {
                $orders = DB::table('orders')
                    ->select('orders.*', 'customers.customerName', 'customers.customerPhone', 'customers.customerAddress', 'couriers.courierName', 'cities.cityName', 'zones.zoneName', 'admins.name',  'paymenttypes.paymentTypeName', 'payments.paymentNumber')
                    ->leftJoin('customers', 'orders.id', '=', 'customers.order_id')
                    ->leftJoin('couriers', 'orders.courier_id', '=', 'couriers.id')
                    ->leftJoin('paymenttypes', 'orders.payment_type_id', '=', 'paymenttypes.id')
                    ->leftJoin('payments', 'orders.payment_id', '=', 'payments.id')
                    ->leftJoin('cities', 'orders.city_id', '=', 'cities.id')
                    ->leftJoin('zones', 'orders.zone_id', '=', 'zones.id')
                    ->leftJoin('admins', 'orders.admin_id', '=', 'admins.id')
                    ->where('orders.id', '=', $id)->get()->first();
                $products = DB::table('orderproducts')->where('order_id', '=', $id)->get();
                $orders->products = $products;
                $orders->id = $id;
                $account = Basicinfo::first();

                $api_key = 'drg4bfhw28hc14qrzl93whpd52ejn3ne';
                $secret_key = 'xher1sscogeuy4um1agevier';

                $ress = Http::withHeaders([
                    'Api-Key' => $api_key,
                    'Secret-Key' => $secret_key,
                    'Content-Type' => 'application/json'

                ])->post('https://portal.packzy.com/api/v1/create_order', [
                    'invoice' => $orders->invoiceID,
                    'recipient_name' => $orders->customerName,
                    'recipient_address' => $orders->customerAddress,
                    'recipient_phone' => $orders->customerPhone,
                    'cod_amount' => $orders->subTotal,
                    'note' => $orders->customerNote,
                ]);


                $res = json_decode($ress->getBody()->getContents());
                if (isset($res->consignment)) {

                    if ($res->consignment->status == 'in_review') {
                        $order = Order::find($id);
                        $order->courier_tracking_link = 'https://steadfast.com.bd/t' . '/' . $res->consignment->tracking_code;
                        $order->consigment_id = $res->consignment->consignment_id;
                        $order->update();
                        $comment = new Comment();
                        $comment->order_id = $id;
                        $comment->comment = Auth::guard('admin')->user()->name . ' Successfully Send To #PR001' . $id . ' Order to ' . $courier->courierName;
                        $comment->admin_id = Auth::guard('admin')->user()->id;
                        $comment->status = 1;
                        $comment->save();
                    } else {
                        $response['status'] = 'failed';
                        $response['message'] = 'This courier do not have permission for auto entry';
                    }
                } else {
                    $response['status'] = 'failed';
                    $response['message'] = 'This courier do not have permission for auto entry';
                }
            }
            $response['status'] = 'success';
            $response['message'] = 'Successfully Assign Courier to this Order';
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Assign Courier to this Order';
        }
        return json_encode($response);
    }


    //couriers
    public function couriers(Request $request)
    {
        if (isset($request['q'])) {
            $couriers = Courier::where([
                ['courierName', 'like', '%' . $request['q'] . '%'],
                ['status', 'like', 'Active']
            ])->get();
        } else {
            $couriers = Courier::where('status', 'Active')->get();
        }
        $courier = array();
        foreach ($couriers as $item) {
            $courier[] = array(
                "id" => $item['id'],
                "text" => $item['courierName']
            );
        }
        return json_encode($courier);
    }

    // Get City
    public function city(Request $request)
    {
        if (isset($request['q']) && $request['courierID']) {
            $cites = City::query()->where([
                ['cityName', 'like', '%' . $request['q'] . '%'],
                ['status', 'like', 'Active'],
                ['courier_id', '=', $request['courierID']]
            ])->get();
        } else {
            $cites = City::query()->where([
                ['status', 'Active'],
                ['courier_id', '=', $request['courierID']]
            ])->get();
        }
        $city = array();
        foreach ($cites as $item) {
            $city[] = array(
                "id" => $item['id'],
                "text" => $item['cityName']
            );
        }
        return json_encode($city);
    }

    // Get Zone
    public function zone(Request $request)
    {
        if (isset($request['q'])) {
            $zones = Zone::query()->where([
                ['zoneName', 'like', '%' . $request['q'] . '%'],
                ['courier_id', '=', $request['courierID']],
                ['status', 'Active'],
                ['city_id', 'like',  $request['cityID']]
            ])->get();
        } else {
            $zones = Zone::query()->where([
                ['courier_id', 'like',  $request['courierID']],
                ['city_id', 'like',  $request['cityID']],
                ['status', 'Active']
            ])->get();
        }
        $zone = array();
        foreach ($zones as $item) {
            $zone[] = array(
                "id" => $item['id'],
                "text" => $item['zoneName']
            );
        }
        return json_encode($zone);
    }

    // Get area
    public function area(Request $request)
    {
        if (isset($request['q'])) {
            $areas = Area::query()->where([
                ['areaName', 'like', '%' . $request['q'] . '%'],
                ['courier_id', '=', $request['courierID']],
                ['status', 'Active'],
                ['zone_id', 'like',  $request['zoneID']]
            ])->get();
        } else {
            $areas = Area::query()->where([
                ['courier_id', 'like',  $request['courierID']],
                ['zone_id', 'like',  $request['zoneID']],
                ['status', 'Active']
            ])->get();
        }
        $area = array();
        foreach ($areas as $item) {
            $area[] = array(
                "id" => $item['id'],
                "text" => $item['areaName']
            );
        }
        return json_encode($area);
    }


    //comments get
    public function getComments(Request $request)
    {
        $order_id = $request['id'];
        $comment = Comment::where('order_id',  $order_id)->latest()->get();

        $comment['data'] = $comment->map(function ($comment) {
            $admin = DB::table('admins')->select('admins.name')->where('id', '=', $comment->admin_id)->get()->first();
            if (isset($admin->name)) {
                $comment->name = $admin->name;
            } else {
                $comment->name = 'System';
            }
            $timestamp = strtotime($comment->created_at);

            $comment->date = $comment->created_at->format('Y-m-d') . ' ' . date("h.i A", $timestamp);
            return $comment;
        });
        return json_encode($comment);
    }

    public function updateComments(Request $request)
    {
        $id = $request['id'];
        $note = $request['comment'];
        $notification = new Comment();
        $notification->order_id = $id;
        $notification->comment = $note;
        $notification->admin_id = Auth::guard('admin')->user()->id;
        $request = $notification->save();

        if ($request) {
            $response['status'] = 'success';
            $response['message'] = 'Note Successfully Added To This Order';
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Update Order note';
        }
        return json_encode($response);
        die();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $order->store_id = $request['data']['storeID'];
        $order->consigment_id = $request['data']['consigment_id'];
        $order->courier_tracking_link = $request['data']['courier_tracking_link'];
        $order->subTotal = $request['data']['total'];
        $oldAmount = $order->paymentAmount;
        $newAmount = $request['data']['paymentAmount'];
        $order->memo = $request['data']['memo'];
        if (isset($request['data']['customerNote'])) {
            $order->customerNote = $request['data']['customerNote'];
        } else {
            $order->customerNote = null;
        }
        $order->deliveryCharge = $request['data']['deliveryCharge'];
        $order->discountCharge = $request['data']['discountCharge'];
        $order->payment_type_id = $request['data']['paymentTypeID'];
        $order->payment_id = $request['data']['paymentID'];
        $order->paymentAmount = $request['data']['paymentAmount'];
        $order->paymentAgentNumber = $request['data']['paymentAgentNumber'];
        $order->orderDate = $request['data']['orderDate'];
        if (!empty($request['data']['completeDate'])) {
            $order->completeDate = $request['data']['completeDate'];
        }
        $order->courier_id = $request['data']['courierID'];
        $order->city_id = $request['data']['cityID'];
        $order->zone_id = $request['data']['zoneID'];
        $order->area_id = $request['data']['areaID'];
        $products = $request['data']['products'];

        if ($order->status != 'Ready to Ship' || $order->status != 'Packaging' || $order->status != 'Shipped' || $order->status != 'Completed' || $order->status != 'Del. Failed') {
            if ($request['data']['status'] == 'Ready to Ship') {
                $ops = Orderproduct::where('order_id', $order->id)->get();
                foreach ($ops as $op) {
                    $size = Size::where('product_id', $op->product_id)->where('size', $op->size)->first();
                    if ($size->available_stock >= $op->quantity) {
                        $size->sold += $op->quantity;
                        $size->available_stock -= $op->quantity;
                        $size->update();
                    } else {
                        $response['status'] = 'failed';
                        $response['message'] = 'You don not have enough stock in your store for Ready to Ship Please check the parcel again !';
                        return json_encode($response);
                        break;
                    }
                }
            }
        }

        if ($order->status == 'Ready to Ship' || $order->status == 'Packaging' || $order->status == 'Shipped' || $order->status == 'Completed' || $order->status == 'Del. Failed') {
            if ($request['data']['status'] != 'Ready to Ship' || $request['data']['status'] != 'Packaging' || $request['data']['status'] != 'Shipped' || $request['data']['status'] != 'Completed' || $request['data']['status'] != 'Del. Failed') {
                $ops = Orderproduct::where('order_id', $order->id)->get();
                foreach ($ops as $op) {
                    $size = Size::where('product_id', $op->product_id)->where('size', $op->size)->first();
                    $size->sold -= $op->quantity;
                    $size->available_stock += $op->quantity;
                    $size->update();
                }
            }
        }

        if ($request['data']['status'] == 'Shipped') {
            $order->deliveryDate = date('Y-m-d');
        }

        if ($order->status == 'Pending' && $request['data']['status'] == 'Pending') {
        } else {
            if ($request['data']['status'] == 'Hold' || $request['data']['status'] == 'Ready to Ship' || $request['data']['status'] == 'Cancelled') {
                if (isset($order->admin_id)) {
                } else {
                    $order->admin_id = Auth::guard('admin')->user()->id;
                }
                if ($request['data']['status'] == 'Ready to Ship') {
                    $cu = Customer::where('order_id', $order->id)->first();
                    $sendstatus = Http::get('http://bulksmsbd.net/api/smsapi?api_key=3z2e9owl4PGXLakGMAmv&type=text&number=' . $cu->customerPhone . '&senderid=RASHIBD.COM&message=ধন্যবাদ, আপনার অর্ডারটি ID:' . $order->invoiceID . ' কনফার্ম হয়েছে - মোটঃ ' . $order->subTotal . ' টাকা।প্যাকেজিং এর জন্য প্রস্তুত , Hotline: 01888173003');

                    if ($sendstatus) {
                        $comment = new Comment();
                        $comment->order_id = $order->id;
                        $comment->comment = 'Successfully send a sms to this customer';
                        $comment->admin_id = Auth::guard('admin')->user()->id;
                        $comment->status = 1;
                        $comment->save();
                    }
                }
            }
            if ($request['data']['status'] == 'Packaging') {
                if (isset($order->packing_by)) {
                } else {
                    $order->packing_by = Auth::guard('admin')->user()->id;
                }
            }
            if ($request['data']['status'] == 'Shipped') {
                $order->deliveryDate = date('Y-m-d');
                if (isset($order->shipped_by)) {
                } else {
                    $order->shipped_by = Auth::guard('admin')->user()->id;
                    $cu = Customer::where('order_id', $order->id)->first();
                    $sendstatus = Http::get('http://bulksmsbd.net/api/smsapi?api_key=3z2e9owl4PGXLakGMAmv&type=text&number=' . $cu->customerPhone . '&senderid=RASHIBD.COM&message= অভিনন্দন,আপনার অর্ডারটি ' . $order->invoiceID . ' কুরিয়ার করা হয়েছে।মোটঃ' . $order->subTotal . ' টাকা। ডেলিভারির সময়ঃ ২-৩ দিন। ট্র্যাক পার্সেলঃ ' . $order->courier_tracking_link . ' , Hotline: 01888173003');

                    if ($sendstatus) {
                        $comment = new Comment();
                        $comment->order_id = $order->id;
                        $comment->comment = 'Successfully send a sms to this customer';
                        $comment->admin_id = Auth::guard('admin')->user()->id;
                        $comment->status = 1;
                        $comment->save();
                    }
                }
            }
            if ($request['data']['status'] == 'Completed' || $request['data']['status'] == 'Del. Failed') {
                if (isset($order->completed_by)) {
                } else {
                    $order->completeDate = date('Y-m-d');
                    $order->completed_by = Auth::guard('admin')->user()->id;
                }
            }
        }


        $order->status = $request['data']['status'];

        $result = $order->update();
        if ($result) {
            $customer = Customer::where('order_id', '=', $id)->first();
            $customer->customerName = $request['data']['customerName'];
            $customer->customerPhone = $request['data']['customerPhone'];
            $customer->customerAddress = $request['data']['customerAddress'];
            $customer->update();
            Orderproduct::where('order_id', '=', $id)->delete();
            foreach ($products as $product) {
                $orderProducts = new Orderproduct();
                $orderProducts->order_id = $id;
                $orderProducts->product_id = $product['productID'];
                $orderProducts->productCode = $product['productCode'];
                $orderProducts->productName = Product::where('id', $product['productID'])->first()->ProductName;
                $orderProducts->color = Varient::where('product_id', $product['productID'])->first()->color;
                $orderProducts->size = $product['productSize'];
                $orderProducts->sigment = $product['sigment'];
                $orderProducts->quantity = $product['productQuantity'];
                $orderProducts->productPrice = $product['productPrice'];
                $orderProducts->save();
            }
            $comment = new Comment();
            $comment->order_id = $id;
            $comment->comment = Auth::guard('admin')->user()->name . ' Successfully Update Info of #PR001' . $id;
            $comment->admin_id = Auth::guard('admin')->user()->id;
            $comment->status = 1;
            $comment->save();
            if ($request['data']['paymentAmount'] > 0) {
                $paymentComplete = Paymentcomplete::where('order_id', $order->id)->first();
                if ($paymentComplete) {
                    $paymentComplete->payment_type_id = $request['data']['paymentTypeID'];
                    $paymentComplete->payment_id = $request['data']['paymentID'];
                    if ($newAmount != $oldAmount) {
                        $paymentComplete->amount = $request['data']['paymentAmount'];
                        $paymentComplete->date = date('Y-m-d');
                        if ($newAmount > $oldAmount) {
                            $account = Basicinfo::first();
                            $account->account_balance += $newAmount - $oldAmount;
                            $account->update();
                            $income = new Incomehistory();
                            $income->from = 'Retail Sale';
                            $income->date = date('Y-m-d');
                            $income->amount = $newAmount - $oldAmount;
                            $income->admin_id = Auth::guard('admin')->user()->id;
                            $income->comments = 'Another Payment receive from Retail Sale INV: ' . $order->invoiceID . 'Paid: ' . $order->paymentAmount . 'Due: ' . $order->subTotal;
                            $income->update();
                        } else {
                            $account = Basicinfo::first();
                            $account->account_balance -= $oldAmount - $newAmount;
                            $account->update();
                            $cost = new Costhistory();
                            $cost->paymesnt_for = 'Income';
                            $cost->date = date('Y-m-d');
                            $cost->amount = $oldAmount - $newAmount;
                            $cost->admin_id = Auth::guard('admin')->user()->id;
                            $cost->comments = 'Payment Adjustment Expense Retail Sale INV: ' . $order->invoiceID;
                            $cost->update();
                        }
                    }
                    $paymentComplete->trid = $request['data']['paymentAgentNumber'];
                    $paymentComplete->userID = Auth::guard('admin')->user()->id;
                    $paymentComplete->update();
                } else {
                    $account = Basicinfo::first();
                    $account->account_balance += $request['data']['paymentAmount'];
                    $account->update();
                    $paymentComplete = new Paymentcomplete();
                    $paymentComplete->order_id = $order->id;
                    $paymentComplete->payment_type_id = $request['data']['paymentTypeID'];
                    $paymentComplete->payment_id = $request['data']['paymentID'];
                    $paymentComplete->amount = $request['data']['paymentAmount'];
                    $paymentComplete->trid = $request['data']['paymentAgentNumber'];
                    $paymentComplete->date = date('Y-m-d');
                    $paymentComplete->userID = Auth::guard('admin')->user()->id;
                    $paymentComplete->save();
                }
            }

            $response['status'] = 'success';
            $response['message'] = Auth::guard('admin')->user()->name . ' Successfully Update Info of #PR001' . $id;;
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Update Order';
        }
        return json_encode($response);
    }

    // Delete All Orders
    public function delete_selected_order(Request $request)
    {
        $admin = Admin::where('email', Auth::guard('admin')->user()->email)->first();
        $ids = $request['orders_id'];
        if ($ids) {
            foreach ($ids as $id) {

                $result = Order::find($id);
                if ($result) {
                    $result->delete();
                    Customer::query()->where('order_id', '=', $id)->delete();
                    Orderproduct::query()->where('order_id', '=', $id)->delete();
                    Comment::query()->where('order_id', '=', $id)->delete();
                }
            }
            $response['status'] = 'success';
            $response['message'] = 'Successfully Delete Order';
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Delete Order';
        }
        return json_encode($response);
    }


    public function orderflow($order,$statusnew){

        $pixelId = env('FACEBOOK_PIXEL_ID');
        $accessToken = env('FACEBOOK_ACCESS_TOKEN');
        $apiVersion = env('FACEBOOK_API_VERSION', 'v18.0');
        $shipping=Customer::where('order_id',$order->id)->first();


        $contents = [];

        foreach (Orderproduct::where('order_id',$order->id)->get() as $product) {
            $contents[] = [
                'id' => $product->id,
                'quantity' => $product->quantity,
                'item_price' => $product->productPrice,
            ];
        }


        $data = [
            'data' => [
                [
                    'event_name' => $statusnew,
                    'event_time' => strtotime(Carbon::now()),
                    'action_source' => 'website',
                    'event_id' => $order->invoice_id,
                    'user_data' => [
                        'ph' => hash('sha256', $shipping->phone),
                        'client_ip_address' => $shipping->ip,
                        'client_user_agent' => $shipping->agent,
                        'fbc' => '',
                        'fbp' => '',
                    ],
                    'custom_data' => [
                        'currency' => 'BDT',
                        'value' => $order->subTotal,
                        'shipping' => $order->deliveryCharge,
                        'tax' => 0,
                        'coupon' => '',
                        'affiliation' => '',
                        'order_id' => $order->id,
                        'transaction_id' => 'TRXAB'.$order->id,
                        'payment_method' => 'Cash On Delivery',
                        'contents' => $contents,
                        'content_type' => 'product',
                    ]
                ]
            ]
        ];



        $response = Http::post("https://graph.facebook.com/{$apiVersion}/{$pixelId}/events?access_token={$accessToken}", $data);

        if ($response->successful()) {
            return ['success' => true, 'response' => $response->json()];
        } else {
            return ['success' => false, 'error' => $response->body()];
        }
    }


    //unique id
    public function uniqueID()
    {
        $lastOrder = Order::latest('id')->first();
        if ($lastOrder) {
            $orderID = $lastOrder->id + 1;
        } else {
            $orderID = 1;
        }

        return 'PR001' . $orderID;
    }


    //Invoice View
    public function storeInvoice(Request $request)
    {
        $ids = serialize($request['orders_id']);
        $invoice = new Invoice();
        $invoice->order_id = $ids;
        $result = $invoice->save();
        if ($result) {
            $response['status'] = 'success';
            $response['link'] = url('admin_order/invoice/') . '/' . $invoice->id;
        } else {
            $response['status'] = 'failed';
            $response['message'] = 'Unsuccessful to Add Order';
        }
        return json_encode($response);
        die();
    }

    public function viewInvoice($id)
    {
        $invoice = Invoice::find($id);
        return view('admin.content.order.printinvoice', ['invoice' => $invoice]);
    }
}
