<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\Expense;
use App\Models\Expensetype;
use App\Models\Basicinfo;
use App\Models\Costhistory;
use App\Models\Incomehistory;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.content.expense.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function expenseslug($slug)
    {
        return view('backend.content.expense.index',['slug'=>$slug]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $expense = new Expense();
        $expense->title = $request->title;
        $expense->amount = $request->amount;
        $expense->date = date('Y-m-d');
        $expense->account_name = $request->payment_type;
        $expense->type = $request->type;
        $expense_icon = $request->file('image');
        $name = time() . "_" . $expense_icon->getClientOriginalName();
        $uploadPath = ('public/images/expense/');
        $expense_icon->move($uploadPath, $name);
        $expense_iconImgUrl = $uploadPath . $name;
        $expense->image = $expense_iconImgUrl;
        $result = $expense->save();
        if ($result) {
            $basicinfo = Basicinfo::first();
            $basicinfo->account_balance = $basicinfo->account_balance - $request->amount;
            $basicinfo->update();
            $cost = new Costhistory();
            $cost->paymesnt_for = $request->type;
            $cost->date = date('Y-m-d');
            $cost->amount = $request->amount;
            $cost->admin_id = Auth::guard('admin')->user()->id;
            $cost->comments = $request->title;
            $cost->update();
        }
        return response()->json($expense, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function expensedata(Request $request,$slug)
    {
       
        $startDate =$request->startDate;
        $endDate =$request->endDate; 
        
        if($slug=='Total Cost'){
            $expenses = Expense::all();
        }else{
            $expenses = Expense::where('type',$slug);
        } 
        
        if ($startDate != '' && $endDate != '') {
            $expenses = $expenses->whereBetween('date', [$startDate, $endDate]);
        } 
        return Datatables::of($expenses)
            ->addColumn('action', function ($expenses) {
                return '<a href="#" type="button" id="editExpenseBtn" data-id="' . $expenses->id . '"   class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editmainExpense" ><i class="bi bi-pencil-square"></i></a>
                <a href="#" type="button" id="deleteExpenseBtn" data-id="' . $expenses->id . '" class="btn btn-danger btn-sm" ><i class="bi bi-archive" ></i></a>';
            })

            ->make(true);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expense = Expense::findOrfail($id);
        return response()->json($expense, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $expense = Expense::findOrfail($id);

        $basicinfo = Basicinfo::first();

        if ($expense->amount == $request->amount) {
        } else {
            $am = $request->amount - $expense->amount;
            $basicinfo->account_balance = $basicinfo->account_balance - ($am);
            if ($request->amount > $expense->amount) {
                $cost = new Costhistory();
                $cost->paymesnt_for = $request->type;
                $cost->date = date('Y-m-d');
                $cost->amount = $request->amount - $expense->amount;
                $cost->admin_id = Auth::guard('admin')->user()->id;
                $cost->comments = 'Expense Adjustment Payment For Expense ID: ' . $expense->id;
                $cost->save();
            } elseif ($request->amount < $expense->amount) {
                $cost = new Incomehistory();
                $cost->from = 'Expense';
                $cost->date = date('Y-m-d');
                $cost->amount = $expense->amount - $request->amount;
                $cost->admin_id = Auth::guard('admin')->user()->id;
                $cost->comments = 'Expense Adjustment Income For Expense ID: ' . $expense->id;
                $cost->save();
            } else {
            }
        }
        $basicinfo->update();

        $expense->account_name = $request->payment_type;
        $expense->type = $request->type;
        $expense->title = $request->title;
        $expense->amount = $request->amount;
        if ($request->image) {
            $expense_icon = $request->file('image');
            $name = time() . "_" . $expense_icon->getClientOriginalName();
            $uploadPath = ('public/images/expense/');
            $expense_icon->move($uploadPath, $name);
            $expense_iconImgUrl = $uploadPath . $name;
            $expense->image = $expense_iconImgUrl;
        }
        $expense->update();
        return response()->json($expense, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        $expense = Expense::findOrfail($id);

        $basicinfo = Basicinfo::first(); 
        $am = $expense->amount;
        $basicinfo->account_balance = $basicinfo->account_balance + ($am); 
        $basicinfo->update();
 
        $expense->delete();
        return response()->json('success', 200);
    }
}
