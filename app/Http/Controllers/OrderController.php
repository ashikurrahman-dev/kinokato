<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Orderproduct;
use App\Models\Comment;
use App\Models\Product;
use DB;
use App\Models\Admin;
use App\Models\Usecoupon;
use App\Models\User;
use App\Models\Basicinfo;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Incompleteorder;
use App\Models\Zone;
use Cart;
use Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function incomplete(Request $request)
    {
        $products = Cart::content();
        $exinc = Incompleteorder::where('status', 'Incomplete')->where('customerPhone', $request->customerPhone)->first();
        if (isset($exinc)) {
            return response()->json('Exist');
        } else {
            $incorder = new Incompleteorder();
            $incorder->customerName = $request->customerName;
            $incorder->customerAddress = $request->customerAddress;
            $incorder->customerPhone = $request->customerPhone;
            $incorder->customerNote = $request->customerNote;
            $incorder->subTotal = $request->ordersubtotalprice;
            $incorder->deliveryCharge = $request->deliveryCharge;
            $incorder->orderDate = date('Y-m-d');
            $incorder->status = 'Incomplete';
            $incorder->cartproducts = $products;
            $incorder->save();
        }
        return response()->json($incorder);
    }

    public function pressorder(Request $request)
    {
        $block = User::where('ip', \Request::ip())->where('status', 'Block')->first();
        if ($block) {
            return redirect('ip-block');
        }
        $products = Cart::content();
        $excutomer = Customer::where('customerPhone', $request->customerPhone)->latest()->first();
        if (isset($excutomer)) {
            $exorder = Order::where('id', $excutomer->order_id)->first();
            if ($exorder->status == 'Pending' || $exorder->status == 'Packaging' || $exorder->status == 'Ready to Ship' || $exorder->status == 'Hold') {
                return redirect('/exist-order');
            }
        }

        if (!Session::has('cart')) {
            return redirect('/empty-cart');
        } elseif (Cart::count() == 0) {
            return redirect('/empty-cart');
        } else {
            $admin = Admin::whereHas('roles', function ($q) {
                $q->where('name', 'user');
            })->where('status', 'Active')->inRandomOrder()->first();

            $order = new Order();


            $exuser = User::where('email', $request->customerPhone)->first();
            if (isset($exuser)) {
                Auth::login($exuser);
                $order->user_id = $exuser->id;
            } else {
                $user = new User();
                $user->name = $request->customerName;
                $user->email = $request->customerPhone;
                $otp = random_int(100000, 999999);
                $user->otp = $otp;
                $otppass = $otp;
                $user->active_status = 0;
                $user->ip = \Request::ip();
                $user->password = Hash::make($request->customerPhone);
                $user->save();

                Auth::login($user);
                $order->user_id = $user->id;
            }


            $order->store_id = 1;
            $order->web_id = 'Website';
            $order->invoiceID = $this->uniqueID();
            $order->deliveryCharge = $request->deliveryCharge;
            $order->city_id = $request->city_id;
            $order->zone_id = $request->zone_id;
            $vat = Basicinfo::first();
            if ($vat->vat_status == 'On') {
                $vat = intval($request->subTotal * ($vat->vat / 100));
            } else {
                $vat = 0;
            }
            $total = $request->subTotal + $request->deliveryCharge + $vat;
            $order->vat = $vat;
            $order->orderDate = date('Y-m-d');
            if (isset($request->coupon_code)) {
                $use = Usecoupon::where('user_id', Auth::id())->where('code', $request->coupon_code)->first();
                if (isset($use)) {
                } else {
                    $couponuse = new Usecoupon();
                    $couponuse->user_id = Auth::id();
                    $couponuse->coupon_id = Coupon::where('code', $request->coupon_code)->first()->id;
                    $couponuse->code = $request->coupon_code;
                    $couponuse->date = date('Y-m-d');
                    $couponuse->save();

                    $order->coupon_code = $request->coupon_code;
                    $coupon = Session::get('availablecoupon');
                    if ($coupon->type == 'Amount') {
                        $discount = $coupon->amount;
                    } else {
                        $discount = $total * ($coupon->amount / 100);
                    }
                    $order->discountCharge = $discount;
                    $order->subTotal = $total - $discount;
                }
            } else {
                $order->subTotal = $total;
            }

            if ($request->paymentType == 1) {
                $order->payment_type_id = $request->paymentType;
                $paymentuser = User::where('id', Auth::id())->first();
                if ($paymentuser->available_coin > $total) {
                    $paymentuser->available_coin = $paymentuser->available_coin - $total;
                    $paymentuser->used_coin = $paymentuser->used_coin + $total;
                    $order->paymentAmount = $total;
                    $paymentuser->update();
                    $order->subTotal = 0;
                } else {
                    $paymentuser->used_coin = $paymentuser->used_coin + $paymentuser->available_coin;
                    $order->paymentAmount = $paymentuser->available_coin;
                    $order->subTotal = $request->subTotal - $paymentuser->available_coin;
                    $paymentuser->available_coin = 0;
                    $paymentuser->update();
                }
            } elseif ($request->paymentType == 3) {
            } else {
                $order->payment_type_id = $request->paymentType;
            }

            $order->customerNote = $request->customerNote;

            $result = $order->save();
            if ($result) {
                $customer = new Customer();
                $customer->order_id = $order->id;
                $customer->customerName = $request->customerName;
                $customer->customerPhone = $request->customerPhone;
                $city = City::where('id', $request->city_id)->first();
                $zone = Zone::where('id', $request->zone_id)->first();
                if (isset($city) && isset($zone)) {
                    $customer->customerAddress = $request->customerAddress . ', ' . $zone->zoneName . ', ' . $city->cityName;
                } else {
                    $customer->customerAddress = $request->customerAddress;
                }
                $customer->save();
                foreach ($products as $product) {
                    $orderProducts = new Orderproduct();
                    $orderProducts->order_id = $order->id;
                    $orderProducts->product_id = $product->id;
                    $orderProducts->productCode = $product->code;
                    if ($product->options['color'] == 'undefined') {
                    } else {
                        $orderProducts->color = $product->options['color'];
                    }

                    if ($product->options['size'] == 'undefined') {
                    } else {
                        $orderProducts->size = $product->options['size'];
                    }

                    if ($product->options['sigment'] == 'undefined') {
                    } else {
                        $orderProducts->sigment = $product->options['sigment'];
                    }

                    $orderProducts->productName = $product->name;
                    $orderProducts->quantity = $product->qty;
                    $orderProducts->productPrice = $product->price;
                    $orderProducts->save();
                }

                // $sendstatus = Http::get('http://bulksmsbd.net/api/smsapi?api_key=3z2e9owl4PGXLakGMAmv&type=text&number='.$customer->customerPhone.'&senderid=RASHIBD.COM&message=আপনার অর্ডারটি প্লেস হয়েছে, শীগ্রই আমাদের প্রতিনিধি কল করে অর্ডারটি কনফার্ম করবেন। ORDER ID : '.$order->invoiceID.' , Hotline: 01888173003 , Visit: www.rashibd.com');
                $exinc = Incompleteorder::where('status', 'Incomplete')->where('customerPhone', $request->customerPhone)->first();
                if (isset($exinc)) {
                    $exinc->delete();
                }
                $notification = new Comment();
                $notification->order_id = $order->id;
                $notification->comment =  $order->invoiceID ;
                $notification->admin_id = $order->admin_id;
                $notification->save();
                Cart::destroy();
                Session::forget('couponcode');
                Session::forget('availablecoupon');
                Session::put('ordersubtotal', $request->subTotal);
                Session::put('orderdeliverycharge', $request->deliveryCharge);
                Session::put('order_id', $order->id);
                toastr()->info('Order Press Successfully', 'Complete', ["positionClass" => "toast-top-center"]);
                return redirect('order-received');
            } else {
                Customer::where('order_id', '=', $order->id)->delete();
                Orderproduct::where('order_id', '=', $order->id)->delete();
                Comment::where('order_id', '=', $order->id)->delete();
                Order::where('id', '=', $order->id)->delete();
                $response['status'] = 'failed';
                $response['message'] = 'Unsuccessful to press order';
            }
        }
    }

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

    public function updatepaymentmethood(Request $request)
    {
        $order = Order::where('id', $request->order_id)->first();
        $order->Payment = $request->payment_option;
        $order->update();
        Session::put('successfulor', 'successfulor');
        return redirect('order/complete');
    }

    public function getorder()
    {
        $from = date('Y-m-d' . ' 00:00:00', time()); //need a space after dates.
        $to = date('Y-m-d' . ' 24:60:60', time());


        $now = Carbon::now();
        $yesterday = Carbon::now()->subDays(5);

        $orders = DB::table('orders')->orderBy('id', 'DESC')->whereBetween('created_at', [$yesterday, $now])->take(200)->get();

        $orders->map(function ($order) {
            $order->products = DB::table('orderproducts')
                ->leftjoin('products', 'orderproducts.product_id', '=', 'products.id')
                ->where('orderproducts.order_id', $order->id)->select('products.*', 'orderproducts.*')->get();
            return $order;
        });

        $orders->map(function ($order) {
            $order->customers = DB::table('customers')->where('customers.order_id', $order->id)->select('customers.id', 'customers.order_id', 'customers.customerName', 'customers.customerPhone', 'customers.customerAddress')->get();
            return $order;
        });

        return response()->json($orders, 201);
    }
    
    public function landingorder(Request $request)
     {
        $block = User::where('ip', \Request::ip())->where('status', 'Block')->first();
        if ($block) {
            return redirect('ip-block');
        }
        $products = Cart::content();
        $excutomer = Customer::where('customerPhone', $request->customerPhone)->latest()->first();
        if (isset($excutomer)) {
            $exorder = Order::where('id', $excutomer->order_id)->first();
            if ($exorder->status == 'Pending' || $exorder->status == 'Packaging' || $exorder->status == 'Ready to Ship' || $exorder->status == 'Hold') {
                return redirect('/exist-order');
            }
        }
        if (!Session::has('cart')) {
            return redirect('/empty-cart');
        } elseif (Cart::count() == 0) {
            return redirect('/empty-cart');
        } else {
            $admin = Admin::whereHas('roles', function ($q) {
                $q->where('name', 'user');
            })->where('status', 'Active')->inRandomOrder()->first();
            $order = new Order();
            $exuser = User::where('email', $request->customerPhone)->first();
            if (isset($exuser)) {
                Auth::login($exuser);
                $order->user_id = $exuser->id;
            } else {
                $user = new User();
                $user->name = $request->customerName;
                $user->email = $request->customerPhone;
                $otp = random_int(100000, 999999);
                $user->otp = $otp;
                $otppass = $otp;
                $user->active_status = 0;
                $user->ip = \Request::ip();
                $user->password = Hash::make($request->customerPhone);
                $user->save();
                Auth::login($user);
                $order->user_id = $user->id;
            }
            $order->store_id = 1;
            $order->web_id = 'Website';
            $order->invoiceID = $this->uniqueID();
            $order->deliveryCharge = $request->deliveryCharge;
            $order->city_id = $request->city_id;
            $order->zone_id = $request->zone_id;
            $vat = Basicinfo::first();
            if ($vat->vat_status == 'On') {
                $vat = intval($request->subTotal * ($vat->vat / 100));
            } else {
                $vat = 0;
            }
            $total = $request->subTotal + $request->deliveryCharge + $vat;
            $order->vat = $vat;
            $order->orderDate = date('Y-m-d');
            if (isset($request->coupon_code)) {
                $use = Usecoupon::where('user_id', Auth::id())->where('code', $request->coupon_code)->first();
                if (isset($use)) {
                } else {
                    $couponuse = new Usecoupon();
                    $couponuse->user_id = Auth::id();
                    $couponuse->coupon_id = Coupon::where('code', $request->coupon_code)->first()->id;
                    $couponuse->code = $request->coupon_code;
                    $couponuse->date = date('Y-m-d');
                    $couponuse->save();

                    $order->coupon_code = $request->coupon_code;
                    $coupon = Session::get('availablecoupon');
                    if ($coupon->type == 'Amount') {
                        $discount = $coupon->amount;
                    } else {
                        $discount = $total * ($coupon->amount / 100);
                    }
                    $order->discountCharge = $discount;
                    $order->subTotal = $total - $discount;
                }
            } else {
                $order->subTotal = $total;
            }

            if ($request->paymentType == 1) {
                $order->payment_type_id = $request->paymentType;
                $paymentuser = User::where('id', Auth::id())->first();
                if ($paymentuser->available_coin > $total) {
                    $paymentuser->available_coin = $paymentuser->available_coin - $total;
                    $paymentuser->used_coin = $paymentuser->used_coin + $total;
                    $order->paymentAmount = $total;
                    $paymentuser->update();
                    $order->subTotal = 0;
                } else {
                    $paymentuser->used_coin = $paymentuser->used_coin + $paymentuser->available_coin;
                    $order->paymentAmount = $paymentuser->available_coin;
                    $order->subTotal = $request->subTotal - $paymentuser->available_coin;
                    $paymentuser->available_coin = 0;
                    $paymentuser->update();
                }
            } elseif ($request->paymentType == 3) {
            } else {
                $order->payment_type_id = $request->paymentType;
            }

            $order->customerNote = $request->customerNote;

            $result = $order->save();
            if ($result) {
                $customer = new Customer();
                $customer->order_id = $order->id;
                $customer->customerName = $request->customerName;
                $customer->customerPhone = $request->customerPhone;
                $city = City::where('id', $request->city_id)->first();
                $zone = Zone::where('id', $request->zone_id)->first();
                if (isset($city) && isset($zone)) {
                    $customer->customerAddress = $request->customerAddress . ', ' . $zone->zoneName . ', ' . $city->cityName;
                } else {
                    $customer->customerAddress = $request->customerAddress;
                }
                $customer->save();
                foreach ($products as $product) {
                    $orderProducts = new Orderproduct();
                    $orderProducts->order_id = $order->id;
                    $orderProducts->product_id = $product->id;
                    $orderProducts->productCode = $product->code;

                    if ($product->options['size'] == 'undefined') {
                    } else {
                        $orderProducts->size = $product->options['size'];
                    }


                    $orderProducts->productName = $product->name;
                    $orderProducts->quantity = $product->qty;
                    $orderProducts->productPrice = $product->price;
                    $orderProducts->save();
                }
                // $sendstatus = Http::get('http://bulksmsbd.net/api/smsapi?api_key=3z2e9owl4PGXLakGMAmv&type=text&number='.$customer->customerPhone.'&senderid=RASHIBD.COM&message=আপনার অর্ডারটি প্লেস হয়েছে, শীগ্রই আমাদের প্রতিনিধি কল করে অর্ডারটি কনফার্ম করবেন। ORDER ID : '.$order->invoiceID.' , Hotline: 01888173003 , Visit: www.rashibd.com');
                $exinc = Incompleteorder::where('status', 'Incomplete')->where('customerPhone', $request->customerPhone)->first();
                if (isset($exinc)) {
                    $exinc->delete();
                }
                $notification = new Comment();
                $notification->order_id = $order->id;
                $notification->comment =  $order->invoiceID . ' Order Has Been Created';
                $notification->admin_id = $order->admin_id;
                $notification->save();
                Cart::destroy();
                Session::forget('couponcode');
                Session::forget('availablecoupon');
                Session::put('ordersubtotal', $request->subTotal);
                Session::put('orderdeliverycharge', $request->deliveryCharge);
                Session::put('order_id', $order->id);
                toastr()->info('Order Press Successfully', 'Complete', ["positionClass" => "toast-top-center"]);
                return redirect('order-received');
            } else {
                Customer::where('order_id', '=', $order->id)->delete();
                Orderproduct::where('order_id', '=', $order->id)->delete();
                Comment::where('order_id', '=', $order->id)->delete();
                Order::where('id', '=', $order->id)->delete();
                $response['status'] = 'failed';
                $response['message'] = 'Unsuccessful to press order';
            }
        }
    }

    public function getproduct()
    {
        $products = Product::select('id', 'ProductName', 'ProductSlug', 'ProductSku', 'ProductRegularPrice', 'ProductSalePrice', 'ProductImage', 'ViewProductImage', 'status')->where('status', 'Active')->get();
        $response = [
            'status' => 's',
            'products' => $products,
        ];
        return $products;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
