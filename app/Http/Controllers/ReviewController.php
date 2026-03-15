<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;


class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.content.review.review');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reviewdata()
    {
        $reviews = Review::all();
        return Datatables::of($reviews)
            ->addColumn('action', function ($reviews) {
                return '<a href="#" type="button" id="deleteReviewBtn" data-id="' . $reviews->id . '" class="btn btn-danger btn-sm" ><i class="bi bi-archive" ></i></a>';
            })
            ->addColumn('user', function ($reviews) {
                return User::where('id',$reviews->user_id)->first()->name;
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
        $review =Review::create($request->all());
        return response()->json($review, 200);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $review = Review::findOrfail($id);
        return response()->json($review, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $review = Review::findOrfail($id)->update($request->all());
        return response()->json($review, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = Review::findOrfail($id);
        $review->delete();
        return response()->json('delete success', 200);
    }

    public function updatestatus(Request $request)
    {

        $review = Review::Where('id', $request->review_id)->first();
        $review->status = $request->status;
        $review->save();

        return response()->json($review, 200);
    }











}