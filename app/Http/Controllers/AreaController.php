<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Courier;
use App\Models\Area;
use Illuminate\Http\Request;
use DataTables;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $couriers = Courier::where('status', 'Active')->get();
        $zones = Zone::where('status', 'Active')->get();
        return view('admin.content.area.area', ['couriers' => $couriers,'zones'=>$zones]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function areadata()
    {
        $areas = Area::with(['couriers','zones']);
        return Datatables::of($areas)
            ->addColumn('action', function ($areas) {
                return '<a href="#" type="button" id="editAreaBtn" data-id="' . $areas->id . '"   class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editmainArea" ><i class="bi bi-pencil-square"></i></a>
                <a href="#" type="button" id="deleteAreaBtn" data-id="' . $areas->id . '" class="btn btn-danger btn-sm" ><i class="bi bi-archive" ></i></a>';
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
        $area = Area::create($request->all());
        return response()->json($area, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $area = Area::findOrfail($id);
        return response()->json($area, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $area = Area::findOrfail($id)->update($request->all());
        return response()->json($area, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $area = Area::findOrfail($id);
        $area->delete();
        return response()->json('delete success', 200);
    }

    public function updatestatus(Request $request)
    {

        $area = Area::Where('id', $request->area_id)->first();
        $area->status = $request->status;
        $area->save();

        return response()->json($area, 200);
    }


}
