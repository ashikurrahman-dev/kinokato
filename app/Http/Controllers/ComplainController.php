<?php

namespace App\Http\Controllers;

use App\Models\Complain;
use App\Models\Complanenote;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Orderproduct;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\Comment;
use DataTables;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;

class ComplainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = 'all';
        return view('admin.content.complain.complain', ['status' => $status]);
    }

    public function subindex($status)
    {
        return view('admin.content.complain.complain', ['status' => $status]);
    }

  public function complaindata(Request $request, $status)
{
    if ($status === 'complainall') {
        $complains = Complain::with('admins');
    } else {
        if ($status == 'Pending') {
            $complains = Complain::with('admins')->where('status', '=', $status);
        } else {
            if ($status == 'Solved') {
                $complains = Complain::with('admins')->where('status', '=', $status);
                if ($request['startDate'] != '' && $request['endDate'] != '') {
                    $complains = $complains->whereBetween('complains.solved_date', [$request['startDate'] . ' 00:00:00', $request['endDate'] . ' 23:59:59']);
                }
            } else {
                $complains = Complain::with('admins')->where('status', '=', $status);
                if ($request['startDate'] != '' && $request['endDate'] != '') {
                    $complains = $complains->whereBetween('complains.created_at', [$request['startDate'] . ' 00:00:00', $request['endDate'] . ' 23:59:59']);
                }
            }
        }
    }
    
    // Add orderBy before passing to DataTables
    $complains = $complains->orderBy('complains.id', 'DESC');
    
    return Datatables::of($complains)
        ->editColumn('user', function ($complains) {
            if ($complains->admins) {
                return $complains->admins->name;
            } else {
                return 'user not assign';
            }
        })
        ->editColumn('notes', function ($complains) {
            $order = Order::where('invoiceID', $complains->order_invoice_id)->first();
            $comments = Comment::where('order_id', $order->id ?? null)->where('status', 0)->latest()->first();
            if (isset($comments)) {
                $timestamp = strtotime($comments->created_at);
                return $comments->comment.'<br>'.$comments->created_at->format('Y-m-d') . ' ' . date("h.i A", $timestamp);
            } else {
                return '';
            }
        })
        ->addColumn('status', function ($complains) {
            return $complains->status = $this->statusList($complains->status, $complains->id);
        })
        ->addColumn('date', function ($complains) {
            $timestamp = strtotime($complains->created_at);
            return $complains->created_at->format('Y-m-d') . ' ' . date("h.i A", $timestamp);
        })
        ->addColumn('solved', function ($complains) {
            if ($complains->solved_date != null) {
                $timestamp = strtotime($complains->updated_at);
                return $complains->updated_at->format('Y-m-d') . ' ' . date("h.i A", $timestamp);
            } else {
                return $complains->solved_date;
            }
        })
        ->addColumn('action', function ($complains) {
            return "<a href='javascript:void(0);' data-id='" . $complains->id . "' class='action-icon btn-editcomplain'> <i class='fas fa-1x fa-edit'></i></a>
            <a href='javascript:void(0);' data-id='" . $complains->id . "' id='deleteComplainBtn' class='action-icon btn-delete'> <i class='fas fa-trash-alt'></i></a>";
        })
        ->escapeColumns([])
        ->make(true);
}

    public function statusList($status, $id)
    {
        $allStatus = array(
            'order' => array(
                "Pending" => array(
                    "name" => "Pending",
                    "icon" => "fe-tag",
                    "color" => "bg-light"
                ),
                "On Progress" => array(
                    "name" => "On Progress",
                    "icon" => "fe-tag",
                    "color" => "bg-info"
                ),
                "Issue" => array(
                    "name" => "Issue",
                    "icon" => "far fa-stop-circle",
                    "color" => "bg-warning"
                ),
                "Solved" => array(
                    "name" => "Solved",
                    "icon" => "far fa-stop-circle",
                    "color" => "bg-success"
                )
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
            <a href='javascript: void(0);' style='color:white'  class=' btn-sm table-action-btn dropdown-toggle arrow-none btn " . $args[$status]['color'] . " btn-xs' data-bs-toggle='dropdown' aria-expanded='false' >" . $args[$status]['name'] . " <i class='mdi mdi-chevron-down'></i></a>
            <div class='dropdown-menu dropdown-menu-right'>
            " . $html . "
            </div>
        </div>";

        return $response;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $complainExist = Complain::query()->where([
            ['order_invoice_id', '=', $request->order_invoice_id]
        ])->get()->first();

        if (!$complainExist) {
            $complain = new Complain();
            if ($request->order_invoice_id) {
                $complain->order_invoice_id = $request->order_invoice_id;
            }
            $complain->store_id = 1;
            $complain->site_name = env('APP_NAME');
            $complain->admin_id = Auth::guard('admin')->user()->id;
            $complain->customer_phone = $request->customer_phone;
            $complain->complain_message = $request->complain_message;
            $complain->complainDate = date('Y-m-d');
            $complain->save();
        } else {
            return back()->with('error', 'Duplicate entry');
        }

        return back()->with('message', 'Complain create successfully;');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $complain = Complain::where('id', $id)->first();
        $idin = Order::where('invoiceID', $complain->order_invoice_id)->first()->id;
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
            ->where('orders.id', '=', $idin)->get()->first();
        $products = DB::table('orderproducts')->where('order_id', $idin)->get();
        $orders->products = $products;
        $orders->id = $idin;
        return view('admin.content.complain.edit')->with('order', $orders);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Complain $complain)
    {
        //
    }

    //status update


    public function updatestatus(Request $request)
    {
        $complain = Complain::Where('id', $request->complain_id)->first();
        if ($request->status == 'Solved') {
            if (isset($complain->solved_date)) {
            } else {
                $complain->solved_by = Auth::guard('admin')->user()->name;
                $complain->solved_date = Carbon::now();
            }
        }
        $complain->status = $request->status;
        $complain->save();

        return response()->json($complain, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $complain = Complain::where('id', $id)->first();
        $complain->delete();
        return response()->json('complain delete successfully');
    }
}
