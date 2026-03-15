<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\Admin;
use Illuminate\Http\Request;
use DB;
use DataTables;

class ReportController extends Controller
{
    public function accountreport()
    {
        return view('admin.content.report.account');
    }

    public function accountreportdata(Request $request)
    {
        $accounts  = DB::table('accounts');

        if ($request['startDate'] != '' && $request['endDate'] != '') {
            $accounts = $accounts->whereBetween('accounts.created_at', [$request['startDate'] . ' 00:00:00', $request['endDate'] . ' 23:59:59']);
        }
        $accounts = $accounts->latest()->get();

        return DataTables::of($accounts)
            ->escapeColumns([])
            ->make(true);
    }

    public function courieruserreport()
    {
        return view('admin.content.report.courieruserreport');
    }

    public function expensereportdata(Request $request)
    {

        $expenses  = DB::table('expenses');


        if ($request['startDate'] != '' && $request['endDate'] != '') {
            $expenses = $expenses->whereBetween('expenses.created_at', [$request['startDate'] . ' 00:00:00', $request['endDate'] . ' 23:59:59']);
        }

        if ($request['expenseTypeid'] != 'All') {
            $expenses = $expenses->where('expenses.expense_type_id', 'like', $request['expenseTypeid']);
        }

        if ($request['payment_type'] != 'All') {
            $expenses = $expenses->where('expenses.account_name', 'like', $request['payment_type']);
        }


        $expenses = $expenses->get()->reverse();

        return DataTables::of($expenses)
            ->escapeColumns([])
            ->make(true);
    }


    public function expensereport()
    {
        return view('admin.content.report.expense');
    }

    public function paymentreport()
    {
        return view('admin.content.report.paymentreport');
    }

    public function productreport()
    {
        return view('admin.content.report.productreport');
    }
    public function courierreport()
    {
        return view('admin.content.report.courierreport');
    }
    public function userreport()
    {
        return view('admin.content.report.userreport');
    }


    public function courieruserreportdata(Request $request)
    {

        $orders  = DB::table('orders')
            ->select('orders.*', 'customers.customerName', 'customers.customerPhone', 'customers.customerAddress', 'couriers.courierName', 'cities.cityName', 'zones.zoneName', 'admins.name')
            ->join('customers', 'orders.id', '=', 'customers.order_id')
            ->leftJoin('couriers', 'orders.courier_id', '=', 'couriers.id')
            ->leftJoin('cities', 'orders.city_id', '=', 'cities.id')
            ->leftJoin('zones', 'orders.zone_id', '=', 'zones.id')
            ->leftJoin('admins', 'orders.admin_id', '=', 'admins.id');

        if ($request['startDate'] != '' && $request['endDate'] != '') {
            if ($request['orderStatus'] == 'Completed') {
                $orders = $orders->whereBetween('orders.completeDate', [$request['startDate'] . ' 00:00:00', $request['endDate'] . ' 23:59:59']);
            } else {
                $orders = $orders->whereBetween('orders.orderDate', [$request['startDate'] . ' 00:00:00', $request['endDate'] . ' 23:59:59']);
            }
        }

        if ($request['courierID'] != '') {
            $orders = $orders->where('orders.courier_id', '=', $request['courierID']);
        }
        if ($request['orderStatus'] != 'All') {
            $orders = $orders->where('orders.status', 'like', $request['orderStatus']);
        }
        if ($request['userID'] != '') {
            $orders = $orders->where('orders.admin_id', '=', $request['userID']);
        }
        $orders = $orders->latest()->get();
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

    public function courierreportdata(Request $request)
    {
        $response = [];
        if ($request['courierID'] == '') {
            $couriers = Courier::all();
            foreach ($couriers as $courier) {
                $temp['courier'] = $courier->courierName;
                $temp['date'] = $request['startDate'] . ' to ' . $request['endDate'];
                $temp['all'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, '');
                $temp['pending'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, 'Pending');
                $temp['readytoship'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, 'Ready to Ship');
                $temp['hold'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, 'Hold');
                $temp['shipped'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, 'Shipped');
                $temp['cancelled'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, 'Cancelled');
                $temp['packaging'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, 'Packaging');
                $temp['completed'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, 'Completed');
                $temp['delfailed'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, 'Del. Failed');
                $temp['paidAmount'] = $this->getDateCourierAmount($request['startDate'], $request['endDate'], $courier->id, 'Completed');
                array_push($response, $temp);
            }
        } else {
            $courier = Courier::find($request['courierID']);
            $temp['courier'] = $courier->courierName;
            $temp['date'] = $request['startDate'] . ' to ' . $request['endDate'];
            $temp['all'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, '');
            $temp['pending'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, 'Pending');
            $temp['readytoship'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, 'Ready to Ship');
            $temp['hold'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, 'Hold');
            $temp['shipped'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, 'Shipped');
            $temp['cancelled'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, 'Cancelled');
            $temp['packaging'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, 'Packaging');
            $temp['completed'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, 'Completed');
            $temp['delfailed'] = $this->getDateCourier($request['startDate'], $request['endDate'], $courier->id, 'Del. Failed');
            $temp['paidAmount'] = $this->getDateCourierAmount($request['startDate'], $request['endDate'], $courier->id, 'Completed');
            array_push($response, $temp);
        }
        $result['data'] = $response;
        return json_encode($result);
    }


    public function getDateCourier($startDate, $endDate, $courierID, $status)
    {
        $orders  = DB::table('orders')
            ->select('orders.*', 'couriers.courierName')
            ->leftJoin('couriers', 'orders.courier_id', '=', 'couriers.id');
        $orders = $orders->where('orders.courier_id', '=', $courierID);

        if ($startDate != '' && $endDate != '') {
            $orders = $orders->whereBetween('orders.orderDate', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        if (!empty($status)) {
            $orders = $orders->Where('orders.status', '=', $status);
        }
        return $orders->get()->count();
    }

    public function getDateCourierAmount($startDate, $endDate, $courierID, $status)
    {
        $orders  = DB::table('orders')
            ->select('orders.*', 'couriers.courierName')
            ->leftJoin('couriers', 'orders.courier_id', '=', 'couriers.id');
        $orders = $orders->where('orders.courier_id', '=', $courierID);

        if ($startDate != '' && $endDate != '') {
            $orders = $orders->whereBetween('orders.orderDate', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        if (!empty($status)) {
            $orders = $orders->Where('orders.status', '=', $status);
        }
        return $orders->get()->sum('subTotal');
    }



    public function userreportdata(Request $request)
    {
        $response = [];
        $response = [];
        if ($request['userID'] == '') {
            $users = Admin::where('status','Active')->get();
            foreach ($users as $user) {
                $temp['name'] = $user->name;
                $temp['date'] = $request['startDate'] . ' to ' . $request['endDate'];
                $temp['all'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, '');
                $temp['pending'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, 'Pending');
                $temp['readytoship'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, 'Ready to Ship');
                $temp['hold'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, 'Hold');
                $temp['shipped'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, 'Shipped');
                $temp['packaging'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, 'Packaging');
                $temp['cancelled'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, 'Cancelled');
                $temp['completed'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, 'Completed');
                $temp['delfailed'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, 'Del. Failed');
                $temp['paidAmount'] = $this->getDateUserAmount($request['startDate'], $request['endDate'], $user->id, 'Completed');
                $temp['success'] = $this->getDateUsersuccess($request['startDate'], $request['endDate'], $user->id);
                $temp['faild'] = $this->getDateUserfail($request['startDate'], $request['endDate'], $user->id);
                array_push($response, $temp);
            }
        } else {
            $user = Admin::find($request['userID']);
            $temp['name'] = $user->name;
            $temp['date'] = $request['startDate'] . ' to ' . $request['endDate'];
            $temp['all'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, '');
            $temp['pending'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, 'Pending');
            $temp['readytoship'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, 'Ready to Ship');
            $temp['hold'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, 'Hold');
            $temp['shipped'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, 'Shipped');
            $temp['packaging'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, 'Packaging');
            $temp['cancelled'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, 'Cancelled');
            $temp['completed'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, 'Completed');
            $temp['delfailed'] = $this->getDateUser($request['startDate'], $request['endDate'], $user->id, 'Del. Failed');
            $temp['paidAmount'] = $this->getDateUserAmount($request['startDate'], $request['endDate'], $user->id, 'Completed');
            $temp['success'] = $this->getDateUsersuccess($request['startDate'], $request['endDate'], $user->id);
            $temp['faild'] = $this->getDateUserfail($request['startDate'], $request['endDate'], $user->id);
            array_push($response, $temp);
        }
        $result['data'] = $response;
        return json_encode($result);
    }
    public function getDateUser($startDate, $endDate, $userID, $status)
    {
        $orders  = DB::table('orders')
            ->select('orders.*', 'couriers.courierName')
            ->leftJoin('couriers', 'orders.courier_id', 'couriers.id');
        $orders = $orders->where('orders.admin_id',  $userID);

        if ($startDate != '' && $endDate != '') {
            $orders = $orders->whereBetween('orders.orderDate', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        if (!empty($status)) {
            if ($status == 'Completed') {
                $orders = $orders->Where('orders.status', $status);
            } else {
                $orders = $orders->Where('orders.status', $status);
            }
        }
        return $orders->get()->count();
    }

    public function getDateUserAmount($startDate, $endDate, $userID, $status)
    {
        $orders  = DB::table('orders')
            ->select('orders.*', 'couriers.courierName')
            ->leftJoin('couriers', 'orders.courier_id',  'couriers.id');
        $orders = $orders->where('orders.admin_id',  $userID);

        if ($startDate != '' && $endDate != '') {
            $orders = $orders->whereBetween('orders.orderDate', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        if (!empty($status)) {
            if ($status == 'Completed') {
                $orders = $orders->Where('orders.status', '=', $status)->orWhere('orders.status', '=', 'Completed');
            } else {
                $orders = $orders->Where('orders.status', '=', $status);
            }
        }
        return $ar = $orders->get()->sum('subTotal');
    }
    
    public function getDateUsersuccess($startDate, $endDate, $userID)
    {
        $orders  = DB::table('orders')
            ->select('orders.*', 'couriers.courierName')
            ->leftJoin('couriers', 'orders.courier_id',  'couriers.id');
        $orders = $orders->where('orders.admin_id',  $userID);

        if ($startDate != '' && $endDate != '') {
            $orders = $orders->whereBetween('orders.orderDate', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        $total=$orders->get()->count();
        $deli  = DB::table('orders')
            ->select('orders.*', 'couriers.courierName')
            ->leftJoin('couriers', 'orders.courier_id',  'couriers.id');
        $deli = $deli->where('orders.admin_id',  $userID);

        if ($startDate != '' && $endDate != '') {
            $deli = $deli->whereBetween('orders.orderDate', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        $delis = $deli->where('orders.admin_id',  $userID);
        $deliss = $delis->Where('orders.status', 'Completed');
        $delivery=$deliss->get()->count();
        
        if($delivery>0){
            $success=($delivery/$total)*100;
        }else{
            $success=0; 
        }
        return $success." %";
    }

    public function getDateUserfail($startDate, $endDate, $userID)
    {
        $orders  = DB::table('orders')
            ->select('orders.*', 'couriers.courierName')
            ->leftJoin('couriers', 'orders.courier_id',  'couriers.id');
        $orders = $orders->where('orders.admin_id',  $userID);

        if ($startDate != '' && $endDate != '') {
            $orders = $orders->whereBetween('orders.orderDate', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        $total=$orders->get()->count();
        $deli  = DB::table('orders')
            ->select('orders.*', 'couriers.courierName')
            ->leftJoin('couriers', 'orders.courier_id',  'couriers.id');
        $deli = $deli->where('orders.admin_id',  $userID);

        if ($startDate != '' && $endDate != '') {
            $deli = $deli->whereBetween('orders.orderDate', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        $delis = $deli->where('orders.admin_id',  $userID);
        $deliss = $delis->WhereIn('orders.status', ['Cancelled','Del. Failed']);
        $deliveryfail=$deliss->get()->count();
        if($deliveryfail>0){
            $fail=($deliveryfail/$total)*100; 
        }else{
            $fail=0; 
        }
        if($fail>25){
            return '<span style="color:red">'.$fail." %".'</span>';
        }else{
            return $fail." %";
        }
    }
    
    public function paymentreportdata(Request $request)
    {
        
         $orders = DB::table('paymentcompletes') 
            ->join('admins', 'paymentcompletes.userID', '=', 'admins.id')
            ->join('paymenttypes', 'paymentcompletes.payment_type_id', '=', 'paymenttypes.id');

        if ($request['startDate'] != '' && $request['endDate'] != '') {
            $orders = $orders->whereBetween('paymentcompletes.date', [$request['startDate'] , $request['endDate']]);
        }
        if ($request['userID'] != '') {
            $orders = $orders->where('paymentcompletes.userID', '=', $request['userID']);
        }
        if ($request['paymentID'] != '') {
            $orders = $orders->where('paymentcompletes.payment_id', '=', $request['paymentID']);
        }
        if ($request['paymentTypeID'] != '') {
            $orders = $orders->where('paymentcompletes.payment_type_id', '=', $request['paymentTypeID']);
        }
        $orders = $orders->where('paymentcompletes.amount', '!=', 0);
        return DataTables::of($orders)->make();
    }


    public function productreportdata(Request $request)
    {

        $status  = $request->input('orderStatus');

        $orders = DB::table('orders')
            ->join('orderproducts', 'orders.id', '=', 'orderproducts.order_id')
            ->select('orders.status', 'orders.completeDate', 'orders.orderDate', 'orderproducts.*', DB::raw('SUM(orderproducts.quantity) as total_amount'))
            ->groupBy('orderproducts.product_id');



        if ($status != 'All') {
            $orders  = $orders->where('orders.status', 'like', $status);
        }

        if ($request['startDate'] != '' && $request['endDate'] != '') {
            if ($request['orderStatus'] == 'Completed') {
                $orders = $orders->whereBetween('orders.completeDate', [$request['startDate'], $request['endDate']]);
            } else {
                $orders = $orders->whereBetween('orders.orderDate', [$request['startDate'], $request['endDate']]);
            }
        }

        if ($request['courierID'] != '') {
            $orders = $orders->where('orders.courier_id', '=', $request['courierID']);
        }

        return DataTables::of($orders)->make();
    }
}
