@extends('backend.master')

@section('maincontent')

@section('title')
    {{ env('APP_NAME') }}-Admin
@endsection
@php
$admin=App\Models\Admin::where('id',Auth::guard('admin')->user()->id)->first();
@endphp

<div class="container-fluid pt-4 px-4">
    @if ($admin->hasRole('superadmin'))
    <div class="row g-4 mb-4">
        <div class="card card-body">
            <div class="col-12 d-flex justify-content-between">
                <h4 style="margin: 0;margin-top: 8px;">INFORMATION</h4>
                <div class="d-flex justify-content-between" style="font-size: 24px;">
                    From: &nbsp;<input type="text" style="width:200px" class="form-control datepicker" id="infodate" value="{{date('Y-m-d')}}" name="infodate">
                     &nbsp;  &nbsp; &nbsp; To: &nbsp;<input type="text" style="width:200px" class="form-control datepicker" id="infodateto" value="{{date('Y-m-d')}}" name="infodateto">
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                    <div class="ms-3">
                        <p class="mb-2">Account Balance</p>
                        <h6 class="mb-0" id="account">{{ \App\Models\Basicinfo::first()->account_balance }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-4"> 
        <div class="card card-body">
            <div class="row">
                <div class="col-sm-6 col-xl-3">
                    <a href="{{url('admin/account-deposit/Courier')}}">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <img src="{{asset('public/icon/income.png')}}" style="width:50px;">
                            <div class="ms-3">
                                <p class="mb-2">Courier Payment</p>
                                <h6 class="mb-0"><span id="courierpayment">0</span> TK</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <a href="{{url('admin/account-deposit/Office Sale')}}">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <img src="{{asset('public/icon/income.png')}}" style="width:50px;">
                            <div class="ms-3">
                                <p class="mb-2">Office Sale Payment</p>
                                <h6 class="mb-0"><span id="officesalepayment">0</span> TK</h6>
                            </div>
                        </div>
                     </a>
                </div>
                
                <div class="col-sm-6 col-xl-3">
                    <a href="{{url('admin/account-deposit/Wholesale')}}">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <img src="{{asset('public/icon/income.png')}}" style="width:50px;">
                            <div class="ms-3">
                                <p class="mb-2">Wholesale Payment</p>
                                <h6 class="mb-0"><span id="wholesalepayment">0</span> TK</h6>
                            </div>
                        </div>
                     </a>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <a href="{{url('admin/account-deposit/Total')}}">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <img src="{{asset('public/icon/income.png')}}" style="width:50px;">
                            <div class="ms-3">
                                <p class="mb-2">Total Payment </p>
                                <h6 class="mb-0"><span id="totalpayment">0</span> TK</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-4"> 
        <div class="card card-body">
            <div class="row">
                <div class="col-sm-6 col-xl-3">
                    <a href="{{url('admin/expense-cost/Boost Cost')}}">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <img src="{{asset('public/icon/income.png')}}" style="width:50px;">
                            <div class="ms-3">
                                <p class="mb-2">Boost Cost</p>
                                <h6 class="mb-0"><span id="bostcost">0</span> TK</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-xl-3"> 
                    <a href="{{url('admin/expense-cost/Office Cost')}}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <img src="{{asset('public/icon/income.png')}}" style="width:50px;">
                        <div class="ms-3">
                            <p class="mb-2">Office Cost</p>
                            <h6 class="mb-0"><span id="officecost">0</span> TK</h6>
                        </div>
                    </div>
                    </a>
                </div>
                
                <div class="col-sm-6 col-xl-3"> 
                    <a href="{{url('admin/expense-cost/Bank Deposit')}}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <img src="{{asset('public/icon/income.png')}}" style="width:50px;">
                        <div class="ms-3">
                            <p class="mb-2">Bank Deposit</p>
                            <h6 class="mb-0"><span id="bankcost">0</span> TK</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <a href="{{url('admin/expense-cost/Total Cost')}}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <img src="{{asset('public/icon/income.png')}}" style="width:50px;">
                        <div class="ms-3">
                            <p class="mb-2">Total Cost</p>
                            <h6 class="mb-0"><span id="totalcost">0</span> TK</h6>
                        </div>
                    </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <br>
    <hr>
    <br>
    @endif
    @if ($admin->hasRole('superadmin') || $admin->hasRole('user'))
    <div class="col-12 d-flex justify-content-between mb-4">
        <h4 style="margin: 0;margin-top: 8px;">ORDERS</h4>
        <div class="d-flex justify-content-between" style="font-size: 24px;">
            From: &nbsp;<input type="text" style="width:200px" class="form-control datepicker" id="orderdate" value="{{date('Y-m-d')}}" name="orderdate">
             &nbsp;  &nbsp; &nbsp; To: &nbsp;<input type="text" style="width:200px" class="form-control datepicker" id="orderdateto" value="{{date('Y-m-d')}}" name="orderdateto">
        </div>
    </div>
    <div class="row g-4 mb-4">
        <div class="card card-body">
            <div class="row">
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ url('admin_order/orderall') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <img src="{{asset('public/icon/order.png')}}" style="width:50px;">
                        <div class="ms-3">
                            <p class="mb-2">Total</p>
                            <h6 class="mb-0" id="total">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-9">
                     
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="card card-body">
            <div class="row">
                <div class="col-sm-6 col-xl-3 mb-2">
                    <a href="{{ url('admin_order/orderall') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Today Order</p>
                            <h6 class="mb-0" id="all">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-sm-6 col-xl-3 mb-2">
                    <a href="{{ url('admin_order/Pending') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Pending</p>
                            <h6 class="mb-0" id="pending">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-sm-6 col-xl-3 mb-2">
                    <a href="{{ url('admin_order/Hold') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Hold</p>
                            <h6 class="mb-0" id="hold">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-sm-6 col-xl-3 mb-2">
                    <a href="{{ url('admin_order/Cancelled') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Cancelled</p>
                            <h6 class="mb-0" id="cancelled">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
                 
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="card card-body">
            <div class="row"> 
                <div class="col-sm-6 col-xl-3 mb-2">
                    <a href="{{ url('admin_order/Ready to Ship') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Ready to Ship</p>
                            <h6 class="mb-0" id="readytoship">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-sm-6 col-xl-3 mb-2">
                    <a href="{{ url('admin_order/Packaging') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Packaging</p>
                            <h6 class="mb-0" id="packaging">0</h6>
                        </div>
                    </div>
                    </a>
                </div> 
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="card card-body">
            <div class="row"> 
                <div class="col-sm-6 col-lg-3 mb-2">
                    <a href="{{ url('admin_order/Shipped') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Shipped</p>
                            <h6 class="mb-0" id="shipped">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3 mb-2">
                    <a href="{{ url('admin_order/Completed') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Completed</p>
                            <h6 class="mb-0" id="completed">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
                
                <div class="col-sm-6 col-lg-3 mb-2">
                    <a href="{{ url('admin_order/Del. Failed') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Del. Failed</p>
                            <h6 class="mb-0" id="delfailed">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="form-group col-md-2 p-2">
                                    <label for="inputCity" class="col-form-label">Start Date</label>
                                    <input type="text" class="form-control datepicker" id="startDate"  value="<?php echo date('Y-m-d')?>" placeholder="Select Date">
                                </div>
                                <div class="form-group col-md-2 p-2">
                                    <label for="inputCity" class="col-form-label">End Date</label>
                                    <input type="text" class="form-control datepicker" id="endDate" value="<?php echo date('Y-m-d')?>" placeholder="Select Date">
                                </div>
                                <div class="form-group col-md-3 p-2">
                                    <label for="inputState" class="col-form-label">Select User</label>
                                    <select id="userID" class="form-control"></select>
                                </div> 
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="webreportTable" class="table table-centered table-nowrap mb-0" style="width: 100%">
                            <thead class="thead-light" >
                            <tr>
                                <th>Date</th>
                                <th>User Name</th>
                                <th>Total Order</th> 
                                <th>Pending</th>
                                <th>Ready to Ship</th>
                                <th>Hold</th>
                                <th>Packaging</th>
                                <th>Shipped</th>
                                <th>Cancelled</th>
                                <th>Completed</th>
                                <th>Del. Failed</th>
                                <th>Success (%)</th>
                                <th>Faild  (%)</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                            <tr> 
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @endif
    @if ($admin->hasRole('superadmin'))
    <br>
    <hr>
    <br>
    <div class="col-12 d-flex justify-content-between mb-4">
        <h4 style="margin: 0;margin-top: 8px;">OTHERS INFO</h4>
    </div>
    <div class="row g-4">
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <img src="{{asset('public/icon/category1.png')}}" style="width:50px;">
                <div class="ms-3">
                    <p class="mb-2">Category</p>
                    <h6 class="mb-0">{{ \App\Models\Product::get()->count() }}</h6>
                </div>
            </div>
        </div> 
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <img src="{{asset('public/icon/products.png')}}" style="width:50px;">
                <div class="ms-3">
                    <p class="mb-2">Products</p>
                    <h6 class="mb-0">{{ \App\Models\Product::get()->count() }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <img src="{{asset('public/icon/varient.png')}}" style="width:50px;">
                <div class="ms-3">
                    <p class="mb-2">Varient</p>
                    <h6 class="mb-0">{{ \App\Models\Product::get()->count() }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <img src="{{asset('public/icon/category1.png')}}" style="width:50px;">
                <div class="ms-3">
                    <p class="mb-2">Admins</p>
                    <h6 class="mb-0">{{ \App\Models\Product::get()->count() }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <img src="{{asset('public/icon/user.png')}}" style="width:50px;">
                <div class="ms-3">
                    <p class="mb-2">User</p>
                    <h6 class="mb-0">{{ \App\Models\Product::get()->count() }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <img src="{{asset('public/icon/customer.png')}}" style="width:50px;">
                <div class="ms-3">
                    <p class="mb-2">Customer</p>
                    <h6 class="mb-0">{{ \App\Models\Product::get()->count() }}</h6>
                </div>
            </div>
        </div> 
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <img src="{{asset('public/icon/retailer.png')}}" style="width:50px;">
                <div class="ms-3">
                    <p class="mb-2">W-Customer</p>
                    <h6 class="mb-0">{{ \App\Models\Product::get()->count() }}</h6>
                </div>
            </div>
        </div> 
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <img src="{{asset('public/icon/complain.png')}}" style="width:50px;">
                <div class="ms-3">
                    <p class="mb-2">Complain</p>
                    <h6 class="mb-0">{{ \App\Models\Product::get()->count() }}</h6>
                </div>
            </div>
        </div>


    </div>
    <br>
    <hr>
    <br>
    <div class="col-12 d-flex justify-content-between mb-4">
        <h4 style="margin: 0;margin-top: 8px;">STOCK INFO</h4>
    </div>
    <div class="row g-4">

        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <img src="{{asset('public/icon/buysale.png')}}" style="width:50px;">
                <div class="ms-3">
                    <p class="mb-2">Total</p>
                    <h6 class="mb-0">{{ \App\Models\Size::get()->sum('total_stock') }} &nbsp;(pics)</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <img src="{{asset('public/icon/buysale.png')}}" style="width:50px;">
                <div class="ms-3">
                    <p class="mb-2">Available</p>
                    <h6 class="mb-0">{{ \App\Models\Size::get()->sum('available_stock') }} &nbsp;(pics)</h6>
                </div>
            </div>
        </div> 
        @php
        $orps=App\Models\Order::whereIn('status',['Ready to Ship','Packaging','Shipped','Completed'])->get();
        $orpqty=0;
        foreach($orps as $orp){
            $orpqty+=App\Models\Orderproduct::where('order_id',$orp->id)->get()->sum('quantity');
        }
        @endphp
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <img src="{{asset('public/icon/buysale.png')}}" style="width:50px;">
                <div class="ms-3">
                    <p class="mb-2">Retail</p>
                    <h6 class="mb-0">{{$orpqty}} &nbsp;(pics)</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <img src="{{asset('public/icon/buysale.png')}}" style="width:50px;">
                <div class="ms-3">
                    <p class="mb-2">Wholesale</p>
                    <h6 class="mb-0">{{ \App\Models\Wsaleproduct::get()->sum('quantity') }}</h6>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if ($admin->hasRole('accounts') || $admin->hasRole('store') || $admin->hasRole('support'))
    <div class="col-12 d-flex justify-content-between mb-4">
        <h4 style="margin: 0;margin-top: 8px;">ORDERS</h4>
        <div class="d-flex justify-content-between" style="font-size: 24px;">
            From: &nbsp;<input type="text" style="width:200px" class="form-control datepicker" id="orderdate" value="{{date('Y-m-d')}}" name="orderdate">
             &nbsp;  &nbsp; &nbsp; To: &nbsp;<input type="text" style="width:200px" class="form-control datepicker" id="orderdateto" value="{{date('Y-m-d')}}" name="orderdateto">
        </div>
    </div>
    <div class="row g-4 mb-4">
        <div class="card card-body">
            <div class="row">
                <div class="col-sm-6 col-xl-3">
                    <a href="{{ url('admin_order/orderall') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <img src="{{asset('public/icon/order.png')}}" style="width:50px;">
                        <div class="ms-3">
                            <p class="mb-2">Total</p>
                            <h6 class="mb-0" id="total">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-lg-9">
                     
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="card card-body">
            <div class="row">
                <div class="col-sm-6 col-xl-3 mb-2">
                    <a href="{{ url('admin_order/orderall') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Today Order</p>
                            <h6 class="mb-0" id="all">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-sm-6 col-xl-3 mb-2">
                    <a href="{{ url('admin_order/Pending') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Pending</p>
                            <h6 class="mb-0" id="pending">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-sm-6 col-xl-3 mb-2">
                    <a href="{{ url('admin_order/Hold') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Hold</p>
                            <h6 class="mb-0" id="hold">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-sm-6 col-xl-3 mb-2">
                    <a href="{{ url('admin_order/Cancelled') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Cancelled</p>
                            <h6 class="mb-0" id="cancelled">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
                 
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="card card-body">
            <div class="row"> 
                <div class="col-sm-6 col-xl-3 mb-2">
                    <a href="{{ url('admin_order/Ready to Ship') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Ready to Ship</p>
                            <h6 class="mb-0" id="readytoship">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-sm-6 col-xl-3 mb-2">
                    <a href="{{ url('admin_order/Packaging') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Packaging</p>
                            <h6 class="mb-0" id="packaging">0</h6>
                        </div>
                    </div>
                    </a>
                </div> 
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="card card-body">
            <div class="row"> 
                <div class="col-sm-6 col-lg-3 mb-2">
                    <a href="{{ url('admin_order/Shipped') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Shipped</p>
                            <h6 class="mb-0" id="shipped">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3 mb-2">
                    <a href="{{ url('admin_order/Completed') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Completed</p>
                            <h6 class="mb-0" id="completed">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
                
                <div class="col-sm-6 col-lg-3 mb-2">
                    <a href="{{ url('admin_order/Del. Failed') }}">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                        <div class="ms-3">
                            <p class="mb-2">Del. Failed</p>
                            <h6 class="mb-0" id="delfailed">0</h6>
                        </div>
                    </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="form-group col-md-2 p-2">
                                    <label for="inputCity" class="col-form-label">Start Date</label>
                                    <input type="text" class="form-control datepicker" id="startDate"  value="<?php echo date('Y-m-d')?>" placeholder="Select Date">
                                </div>
                                <div class="form-group col-md-2 p-2">
                                    <label for="inputCity" class="col-form-label">End Date</label>
                                    <input type="text" class="form-control datepicker" id="endDate" value="<?php echo date('Y-m-d')?>" placeholder="Select Date">
                                </div>
                                <div class="form-group col-md-3 p-2">
                                    <label for="inputState" class="col-form-label">Select User</label>
                                    <select id="userID" class="form-control"></select>
                                </div> 
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="webreportTable" class="table table-centered table-nowrap mb-0" style="width: 100%">
                            <thead class="thead-light" >
                            <tr>
                                <th>Date</th>
                                <th>User Name</th>
                                <th>Total Order</th> 
                                <th>Pending</th>
                                <th>Ready to Ship</th>
                                <th>Hold</th>
                                <th>Packaging</th>
                                <th>Shipped</th>
                                <th>Cancelled</th>
                                <th>Completed</th>
                                <th>Del. Failed</th>
                                <th>Success (%)</th>
                                <th>Faild  (%)</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                            <tr> 
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @endif
</div>
<!-- Sale & Revenue End -->

<script>
    $(document).ready(function(){
        $(".datepicker").flatpickr(); 
        loadinfo();
        loadorder();
        
        $(document).on('change', '#infodate', function(){
            loadinfo();
        });
        $(document).on('change', '#infodateto', function(){
            loadinfo();
        });
        $(document).on('change', '#orderdate', function(){
            loadorder();
        });
        $(document).on('change', '#orderdateto', function(){
            loadorder();
        });
    });
    
    function loadinfo(){
        var infodate=$('#infodate').val();
        var infodateto=$('#infodateto').val();
        $.ajax({
            type: "get",
            url: "{{ url('admin/get/information') }}",
            data:{
                'infodate':infodate,
                'infodateto':infodateto
            },
            contentType: "application/json",
            success: function(response) {
                var data = JSON.parse(response);

                if (data["status"] == "success") { 
                    $('#courierpayment').text(data["courierpayment"]);
                    $('#officesalepayment').text(data["officesalepayment"]);
                    $('#wholesalepayment').text(data["wholesalepayment"]);
                    $('#totalpayment').text(data["totalpayment"]); 
                    
                    $('#totalcost').text(data["totalcost"]);
                    $('#bankcost').text(data["bankcost"]); 
                    $('#officecost').text(data["officecost"]); 
                    $('#bostcost').text(data["bostcost"]); 
                } else {
                    if (data["status"] == "failed") {
                        swal(data["message"]);
                    } else {
                        swal("Something wrong ! Please try again.");
                    }
                }
            }
        });
    }
    function loadorder(){
        var orderdate=$('#orderdate').val();
        var orderdateto=$('#orderdateto').val();
        $.ajax({
            type: "get",
            url: "{{ url('admin/get/order/information') }}",
            data:{
                'orderdate':orderdate,
                'orderdateto':orderdateto
            },
            contentType: "application/json",
            success: function(response) {
                var data = JSON.parse(response);

                if (data["status"] == "success") { 
                    $('#total').text(data["total"]);
                    $('#all').text(data["all"]);
                    $('#pending').text(data["pending"]);
                    $('#readytoship').text(data["readytoship"]);
                    $('#hold').text(data["hold"]);
                    $('#shipped').text(data["shipped"]);
                    $('#cancelled').text(data["cancelled"]);
                    $('#completed').text(data["completed"]);
                    $('#packaging').text(data["packaging"]);
                    $('#delfailed').text(data["delFailed"]);
                } else {
                    if (data["status"] == "failed") {
                        swal(data["message"]);
                    } else {
                        swal("Something wrong ! Please try again.");
                    }
                }
            }
        });
    }
</script>

<script>

        $(document).ready(function() {
            //token
            var token = $("input[name='_token']").val();
            //date picker
            $(".datepicker").flatpickr();

            var table = $("#webreportTable").DataTable({
                type: "get",
                ajax: {
                    url: "{{url('admin/user/report/data')}}",
                    data: {
                        startDate: function() { return $('#startDate').val() },
                        endDate: function() { return $('#endDate').val() },
                        userID: function() { return $('#userID').val() }
                    }
                },
                ordering: false,
                paging: false,
                lengthChange: false,
                bFilter:false,
                search:false,
                info:false,
                dom: '<"row"<"col-sm-6"Bl><"col-sm-6"f>>' +
                    '<"row"<"col-sm-12"<"table-responsive"tr>>>' +
                    '<"row"<"col-sm-5"i><"col-sm-7"p>>',
                buttons: {
                    buttons: [{
                        extend: 'print',
                        text: 'Print',
                        footer: true,
                        title: function(){
                            return 'User Report';
                        },
                        customize: function (win) {
                            $(win.document.body).find('h1').css('text-align','center');
                            $(win.document.body).find('h1').after('<p style="text-align: center">From : '+$('#startDate').val()+' - To : '+$('#endDate').val()+'</p>');

                        }
                    }]
                },
                columns: [
                    {data: "date"},
                    {data: "name"},
                    {data: "all"}, 
                    {data: "pending"},
                    {data: "readytoship"},
                    {data: "hold"},
                    {data: "packaging"},
                    {data: "shipped"},
                    {data: "cancelled"},
                    {data: "completed"},
                    {data: "delfailed"},
                    {data: "success"},
                    {data: "faild"}
                ],
                language: {
                    paginate: {
                        previous: "<i class='fas fa-chevron-left'>",
                        next: "<i class='fas fa-chevron-right'>"
                    }
                },
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-sm");
                    $('.dt-buttons').hide();
                },
                footerCallback: function ( ) {
                    var api = this.api();
                    var numRows = api.rows().count();
                    $('.total').empty().append(numRows);

                    var intVal = function (i) {
                        return typeof i === "string" ? i.replace(/[\$,]/g, "") * 1 : typeof i === "number" ? i : 0;
                    };
                    for(var i = 2; i<=10;i++){
                        OrderTotal = api.column(i, { page: "current" }).data().reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        $(api.column(i).footer()).html(OrderTotal);
                    }
                    Total = api.column(11, { page: "current" }).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    $(api.column(11).footer()).html(Total + " %");
                    Total = api.column(12, { page: "current" }).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    $(api.column(12).footer()).html(Total + " %");
                }

            });

            $("#userID").select2({
                placeholder: "Select a User",
                allowClear:true,
                ajax: {
                    url:'{{url('admin_order/users')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            }).trigger("change").on("select2:select", function (e) {
                table.ajax.reload();
            });
 
            $(document).on('change', '#startDate', function(){
                table.ajax.reload();
            });
            $(document).on('change', '#endDate', function(){
                table.ajax.reload();
            });
            $(document).on('change', '#orderStatus', function(){
                table.ajax.reload();
            });


        });





    </script>
@endsection
