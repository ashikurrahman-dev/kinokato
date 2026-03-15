<?php

namespace App\Http\Controllers;

use App\Models\Expensetype;
use Illuminate\Http\Request;
use DataTables;

class ExpensetypeController extends Controller
{
    public function index()
    {
        return view('admin.content.expensetype.expensetype');
    }


    public function expensetypedata()
    {
        $expensetypes = Expensetype::all();
        return Datatables::of($expensetypes)
            ->addColumn('action', function ($expensetypes) {
                return '<a href="#" type="button" id="editExpensetypeBtn" data-id="' . $expensetypes->id . '" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editmainExpensetype" ><i class="bi bi-pencil-square" ></i></a>';
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
        $expensetype = Expensetype::create($request->all());
        return response()->json($expensetype, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\expensetype  $expensetype
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expensetype = Expensetype::findOrfail($id);
        return response()->json($expensetype, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\expensetype  $expensetype
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $expensetype = Expensetype::where('id', $id)->first();
        $expensetype->expenseTypeName = $request->expenseTypeName;
        $expensetype->save();
        return response()->json($expensetype, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\expensetype  $expensetype
     * @return \Illuminate\Http\Response
     */


    public function updatestatus(Request $request)
    {

        $expensetype = Expensetype::Where('id', $request->expensetype_id)->first();
        $expensetype->status = $request->status;
        $expensetype->save();

        return response()->json($expensetype, 200);
    }
}
