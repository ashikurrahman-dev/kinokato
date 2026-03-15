<?php

namespace App\Http\Controllers;

use App\Models\Paymenttype;
use App\Models\Product;
use App\Models\Wsale;
use App\Models\Wcustomer;
use App\Models\Wpayment;
use Illuminate\Http\Request;
use DataTables;

class WcustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.content.wcustomer.wcustomer');
    }


    public function wcustomerLedger($id)
    {
        $wcustomer =  Wcustomer::where('id', $id)->first();
        $payment_types = Paymenttype::where('status', 'Active')->get();
        $wpayment = Wpayment::where('wcustomer_id', $wcustomer->id)->get();
        $orders =  Wsale::where('wcustomer_id', $id)->get();

        return view('admin.content.wcustomer.ledger', ['orders' => $orders, 'wcustomer' => $wcustomer, 'wpayment' => $wpayment, 'payment_types' => $payment_types]);
    }

    public function wcustomerdata()
    {
        $wcustomers = Wcustomer::all();
        return Datatables::of($wcustomers)
            ->addColumn('action', function ($wcustomers) {
                $ledgerRoute = route('wsale.ledger', $wcustomers->id);

                return '<a href=" ' . $ledgerRoute . ' "  id=""    class="btn btn-primary btn-sm"  style="margin-bottom:2px;"><i class="bi bi-eye"></i></a>
                <a href="#" type="button" id="editWcustomerBtn" data-id="' . $wcustomers->id . '" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editmainWcustomer" ><i class="bi bi-pencil-square" ></i></a>';
            })

            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $wcustomer = Wcustomer::create($request->all());
        return response()->json($wcustomer, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wcustomer  $wcustomer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $wcustomer = Wcustomer::findOrfail($id);
        return response()->json($wcustomer, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wcustomer  $wcustomer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $wcustomer = Wcustomer::where('id', $id)->first();
        $wcustomer->wcustomerName = $request->wcustomerName;
        $wcustomer->wcustomerPhone = $request->wcustomerPhone;
        $wcustomer->wcustomerEmail  = $request->wcustomerEmail;
        $wcustomer->wcustomerAddress  = $request->wcustomerAddress;
        $wcustomer->wcustomerCompanyName = $request->wcustomerCompanyName;
        $wcustomer->save();
        return response()->json($wcustomer, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wcustomer  $wcustomer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $wcustomer = Wcustomer::where('id', $id)->first();
        $wcustomer->delete();
        return response()->json('delete success');
    }

    public function updatestatus(Request $request)
    {

        $wcustomer = Wcustomer::Where('id', $request->wcustomer_id)->first();
        $wcustomer->status = $request->status;
        $wcustomer->save();

        return response()->json($wcustomer, 200);
    }
}
