<?php

namespace App\Http\Controllers;

use App\Models\Wpayment;
use App\Models\Wsale;
use App\Models\Wcustomer;
use App\Models\Wcustomercomment;
use App\Models\Basicinfo;
use App\Models\Incomehistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WpaymentController extends Controller
{
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
    public function store(Request $request)
    {

        $orders = Wsale::where('wcustomer_id', $request->wcustomer_id)->where('status', 'Active')->get();

        if ($orders->sum('totalAmount') - $orders->sum('paid') < $request->amount) {
            return redirect()->back()->with('error', 'Payment amount should not greater then due');
        }
        $blance = 0;
        $blance = $request->amount;
        foreach ($orders as $order) {
            $thisdue = $order->totalAmount - $order->paid;
            if ($blance > 0) {
                if ($thisdue >= $blance) {
                    $wpayment = new Wpayment();
                    $wpayment->wcustomer_id = $request->wcustomer_id;
                    $wpayment->wsale_id = $order->id;
                    $wpayment->date = $request->date;
                    $wpayment->amount = $blance;
                    $wpayment->trx_id = $request->trx_id;
                    $wpayment->payment_type_id = $request->payment_type_id;
                    $wpayment->admin_id = Auth::guard('admin')->user()->id;
                    $wpayment->comments = $request->comments;
                    $wpayment->save();

                    $order->paid = $order->paid + $blance;
                    $order->due = $order->due - $blance;
                    if ($order->paid >= $order->totalAmount) {
                        $order->status = 'Paid';
                    } else {
                        $order->status = 'Active';
                    }
                    $order->update();
                    $blance = 0;
                } else {
                    $wpayment = new Wpayment();
                    $wpayment->wcustomer_id = $request->wcustomer_id;
                    $wpayment->wsale_id = $order->id;
                    $wpayment->date = $request->date;
                    $wpayment->amount = $thisdue;
                    $wpayment->trx_id = $request->trx_id;
                    $wpayment->payment_type_id = $request->payment_type_id;
                    $wpayment->admin_id = Auth::guard('admin')->user()->id;
                    $wpayment->comments = $request->comments;
                    $wpayment->save();

                    $order->paid = $order->paid + $thisdue;
                    $order->due = $order->due - $thisdue;
                    if ($order->paid >= $order->totalAmount) {
                        $order->status = 'Paid';
                    } else {
                        $order->status = 'Active';
                    }
                    $order->update();
                    $blance = $blance - $thisdue;
                }
            }
        }

        $wcustomer = Wcustomer::find($request->wcustomer_id);
        $wcustomer->wcustomerPaidAmount += $request->amount;
        $wcustomer->wcustomerDueAmount -= $request->amount;
        $result = $wcustomer->update();
        if ($result) {
            $account = Basicinfo::first();
            $account->account_balance += $request->amount;
            $account->update();
            $income = new Incomehistory();
            $income->from = 'Wholesale';
            $income->date = date('Y-m-d');
            $income->amount = $request->amount;
            $income->admin_id = Auth::guard('admin')->user()->id;
            $income->comments = 'Payment receive from wholesale INV: ' . $order->invoiceID . 'CT-Paid: ' . $wcustomer->wcustomerPaidAmount . 'CT-Due: ' + $wcustomer->wcustomerDueAmount;
            $income->save();
            $paycomment = new Wcustomercomment();
            $paycomment->wcustomer_id = $request->wcustomer_id;
            $paycomment->date = $request->date;
            $paycomment->amount = $request->amount;
            $paycomment->trx_id = $request->trx_id;
            $paycomment->payment_type_id = $request->payment_type_id;
            $paycomment->admin_id = Auth::guard('admin')->user()->id;
            $paycomment->comments = $request->comments;
            $paycomment->save();
        }


        return redirect()->back()->with('success', 'Payment Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wpayment  $wpayment
     * @return \Illuminate\Http\Response
     */
    public function show(Wpayment $wpayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wpayment  $wpayment
     * @return \Illuminate\Http\Response
     */
    public function edit(Wpayment $wpayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wpayment  $wpayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wpayment $wpayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wpayment  $wpayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wpayment $wpayment)
    {
        //
    }
}
