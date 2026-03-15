<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Wsalestock;
use App\Models\Wcustomer;
use App\Models\Wsale;
use Illuminate\Http\Request;
use DataTables;

class WsalestockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.content.wsalestock.wsalestock');
    }


    public function wsalestockdata()
    {
        $wsalestocks = Wsalestock::all();
        return Datatables::of($wsalestocks)
            ->addColumn('date', function ($wsalestocks) {
                return $wsalestocks->created_at->format('Y-m-d');
            })
            ->addColumn('wcustomer', function ($wsalestocks) {
                return 'ID: ' . $wsalestocks->wsale_product_id . '<br> ' . Wcustomer::where('id', Wsale::where('id', $wsalestocks->wsale_product_id)->first()->wcustomer_id)->first()->wcustomerName;
            })
            ->escapeColumns([])->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wsalestock  $wsalestock
     * @return \Illuminate\Http\Response
     */
    public function show(Wsalestock $wsalestock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wsalestock  $wsalestock
     * @return \Illuminate\Http\Response
     */
    public function edit(Wsalestock $wsalestock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wsalestock  $wsalestock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wsalestock $wsalestock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wsalestock  $wsalestock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wsalestock $wsalestock)
    {
        //
    }
}