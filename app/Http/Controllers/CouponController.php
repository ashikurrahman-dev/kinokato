<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Courier;
use Illuminate\Http\Request;
use DataTables;


class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        return view('admin.content.coupon.coupon');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function coupondata()
    {
        $cities = Coupon::all();
        return Datatables::of($cities)
            ->addColumn('action', function ($cities) {
                return '<a href="#" type="button" id="editCouponBtn" data-id="' . $cities->id . '"   class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editmainCoupon" ><i class="bi bi-pencil-square"></i></a>
                <a href="#" type="button" id="deleteCouponBtn" data-id="' . $cities->id . '" class="btn btn-danger btn-sm" ><i class="bi bi-archive" ></i></a>';
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
        $coupon =Coupon::create($request->all());
        return response()->json($coupon, 200);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrfail($id);
        return response()->json($coupon, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrfail($id)->update($request->all());
        return response()->json($coupon, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrfail($id);
        $coupon->delete();
        return response()->json('delete success', 200);
    }

    public function updatestatus(Request $request)
    {

        $coupon = Coupon::Where('id', $request->coupon_id)->first();
        $coupon->status = $request->status;
        $coupon->save();

        return response()->json($coupon, 200);
    }











}