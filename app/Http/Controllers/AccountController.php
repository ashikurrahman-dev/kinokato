<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Basicinfo;
use App\Models\Costhistory;
use App\Models\Admin;
use App\Models\Incomehistory;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.content.account.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function accountslug($slug)
    {
        return view('backend.content.account.index',['slug'=>$slug]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $account = new Account();
        $account->title = $request->title;
        $account->amount = $request->amount;
        $account->form = $request->form;
        $account->account_name = $request->payment_type;
        $account->date = date('Y-m-d');
        $account->admin_id = Auth::guard('admin')->user()->id;
        $account_icon = $request->file('file');
        $name = time() . "_" . $account_icon->getClientOriginalName();
        $uploadPath = ('public/account/');
        $account_icon->move($uploadPath, $name);
        $account_iconImgUrl = $uploadPath . $name;
        $account->file = $account_iconImgUrl;
        $result = $account->save();
        if ($result) {
            $basicinfo = Basicinfo::first();
            $basicinfo->account_balance = $basicinfo->account_balance + $request->amount;
            $basicinfo->update();
            $income = new Incomehistory();
            $income->from = 'Cash In';
            $income->date = date('Y-m-d');
            $income->amount = $request->amount;
            $income->admin_id = Auth::guard('admin')->user()->id;
            $income->comments = 'Deposit for: ' . $request->title . ' Deposit ID: #' . $account->ID;
        }
        return response()->json($account, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function accountdata(Request $request,$slug)
    { 
        $startDate =$request->startDate;
        $endDate =$request->endDate; 
        
        if($slug=='Total'){
            $accounts = Account::all();
        }else{
            $accounts = Account::where('form',$slug);
        }
        
        if ($startDate != '' && $endDate != '') {
            $accounts = $accounts->whereBetween('date', [$startDate, $endDate]);
        }
        
        return Datatables::of($accounts)
            ->addColumn('action', function ($accounts) {
                return '<a href="#" type="button" id="editAccountBtn" data-id="' . $accounts->id . '"   class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editmainAccount" ><i class="bi bi-pencil-square"></i></a>
                <a href="#" type="button" id="deleteAccountBtn" data-id="' . $accounts->id . '" class="btn btn-danger btn-sm" ><i class="bi bi-archive" ></i></a>';
            })
            ->addColumn('admin', function ($accounts) {
                return Admin::where('id',$accounts->admin_id)->first()->name;
            })
            ->addColumn('titleinfo', function ($accounts) {
                if($accounts->edit=='Yes'){
                   return '<span style="color:red">'.$accounts->title.'</span>'; 
                }else{
                    return $accounts->title; 
                }
            })
            ->escapeColumns([])->make();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $account = Account::findOrfail($id);
        return response()->json($account, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $account = Account::findOrfail($id);
        $basicinfo = Basicinfo::first();
        if ($account->amount == $request->amount) {
            $account->edit = 'Yes';
        } else {
            $am = $account->amount - ($request->amount);
            $basicinfo->account_balance = $basicinfo->account_balance - ($am);
            if ($request->amount > $account->amount) {
                $cost = new Incomehistory();
                $cost->from = 'Cash In';
                $cost->date = date('Y-m-d');
                $cost->amount = $request->amount - $account->amount;
                $cost->admin_id = Auth::guard('admin')->user()->id;
                $cost->comments = 'Cash In Adjustment Income , Cash in ID: ' . $account->id;
                $cost->save();
                $account->edit = 'Yes';
            } elseif ($request->amount < $account->amount) {
                $cost = new Costhistory();
                $cost->paymesnt_for = 'Cash In Expense';
                $cost->date = date('Y-m-d');
                $cost->amount = $account->amount - $request->amount;
                $cost->admin_id = Auth::guard('admin')->user()->id;
                $cost->comments = 'Cash In Adjustment Expense , Cash in ID: ' . $account->id;
                $cost->save();
                $account->edit = 'Yes';
            } else {
            }
        }
        $basicinfo->update();
        $account->admin_id = Auth::guard('admin')->user()->id;
        $account->title = $request->title;
        $account->amount = $request->amount;
        $account->account_name = $request->payment_type;
        $account->date = date('Y-m-d');
        if ($request->file) {
            $account_icon = $request->file('file');
            $name = time() . "_" . $account_icon->getClientOriginalName();
            $uploadPath = ('public/account/');
            $account_icon->move($uploadPath, $name);
            $account_iconImgUrl = $uploadPath . $name;
            $account->file = $account_iconImgUrl;
        }
        $result = $account->update();
        return response()->json($account, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $account = Account::findOrfail($id);
        $basicinfo = Basicinfo::first(); 
        $am = $account->amount;
        $basicinfo->account_balance = $basicinfo->account_balance - ($am); 
        $basicinfo->update();
        $account->delete();
        return response()->json('success', 200);
    }
}
