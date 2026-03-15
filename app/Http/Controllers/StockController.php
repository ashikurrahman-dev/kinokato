<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Purchase;
use Illuminate\Http\Request;
use DataTables;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.content.stock.stock');
    }


    public function stockdata()
    {
        $stocks = Stock::all();
        return Datatables::of($stocks)
            ->addColumn('date', function ($stocks) {
                return $stocks->created_at->format('Y-m-d');
            })
            ->addColumn('purchese', function ($stocks) {
                return 'ID: ' . $stocks->purchese_product_id . '<br> ' . Supplier::where('id', Purchase::where('id', $stocks->purchese_product_id)->first()->supplier_id)->first()->supplierName;
            })
            ->escapeColumns([])->make(true);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCityByCurier($id)
    {
        $citys = City::Where('courier_id', $id)->get();
        return response()->json($citys, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        //
    }
}
