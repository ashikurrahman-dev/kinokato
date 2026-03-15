<?php

namespace App\Http\Controllers;

use App\Models\Paymenttype;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;
use DataTables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.content.supplier.supplier');
    }


    public function supplierLedger($id)
    {
        $supplier =  Supplier::where('id', $id)->first();
        $payment_types = Paymenttype::where('status', 'Active')->get();
        $supplierPayments = SupplierPayment::where('supplier_id', $supplier->id)->get();
        $orders =  Purchase::where('supplier_id', $id)->get();

        return view('admin.content.supplier.ledger', ['orders' => $orders, 'supplier' => $supplier, 'supplierPayments' => $supplierPayments, 'payment_types' => $payment_types]);
    }

    public function supplierdata()
    {
        $suppliers = Supplier::all();
        return Datatables::of($suppliers)
            ->addColumn('action', function ($suppliers) {
                $ledgerRoute = route('purchase.ledger', $suppliers->id);

                return '<a href=" ' . $ledgerRoute . ' "  id=""    class="btn btn-primary btn-sm"  style="margin-bottom:2px;"><i class="bi bi-eye"></i></a>
                <a href="#" type="button" id="editSupplierBtn" data-id="' . $suppliers->id . '" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editmainSupplier" ><i class="bi bi-pencil-square" ></i></a>';
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
        $supplier = Supplier::create($request->all());
        return response()->json($supplier, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrfail($id);
        return response()->json($supplier, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::where('id', $id)->first();
        $supplier->supplierName = $request->supplierName;
        $supplier->supplierPhone = $request->supplierPhone;
        $supplier->supplierEmail  = $request->supplierEmail;
        $supplier->supplierAddress  = $request->supplierAddress;
        $supplier->supplierCompanyName = $request->supplierCompanyName;
        $supplier->save();
        return response()->json($supplier, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::where('id', $id)->first();
        $supplier->delete();
        return response()->json('delete success');
    }

    public function updatestatus(Request $request)
    {

        $supplier = Supplier::Where('id', $request->supplier_id)->first();
        $supplier->status = $request->status;
        $supplier->save();

        return response()->json($supplier, 200);
    }
}
