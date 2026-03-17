@extends('backend.master')

@section('maincontent')

<?php
use App\Models\Admin;
$admin = Admin::where('email', Auth::guard('admin')->user()->email)->first();
$users = Admin::whereHas('roles', function ($q) {
    $q->where('name', 'user');
})
    ->where('status', 'Active')
    ->inRandomOrder()
    ->get();
?>

<style>
    .progress-bar {
        display: flex;
        flex-direction: column;
        justify-content: center;
        overflow: hidden;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        background-color: #068032;
        transition: width 0.6s ease;
    }

    .progress {
        display: flex;
        height: 2rem;
        overflow: hidden;
        font-size: 1rem;
        background-color: #e9ecef;
        border-radius: 5px;
    }

    #discol{
        border: none;
        font-size: 24px;
        padding: 0px 8px;
        border-radius: 4px;
        background: red;
        color: white;
        line-height: 0px;
    }
    #mtitle{
        font-weight: normal;
        font-size: 16px;
        text-align: center;
        padding-top: 14px;
        color: green !important;
    }
    .imgcbtn {
    margin-right:0px;
    margin-left: 0px;
    background: #d46e6e;
    border: 1px solid #d46e6e;
    font-size: 14px;
    margin-top: 0px;
    color: #fff;
    border-radius: 5px;
    padding: 5px 15px;
    font-family: 'Lato', sans-serif;
    font-weight: 500;
    background: transparent;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    display: inline-block;
    box-shadow: inset 2px 2px 2px 0px rgba(255, 255, 255, .5), 7px 7px 20px 0px rgba(0, 0, 0, .1), 4px 4px 5px 0px rgba(0, 0, 0, .1);
    outline: none;
    background: rgb(96, 9, 240);
    background: linear-gradient(0deg, rgb(105 113 121) 0%, rgb(105 113 121) 100%);
    border: none;
    box-shadow: 4px 4px 6px 0 rgba(255, 255, 255, .5), -4px -4px 6px 0 rgba(116, 125, 136, .5), inset -4px -4px 6px 0 rgba(255, 255, 255, .2), inset 4px 4px 6px 0 rgba(0, 0, 0, .4);
}

.imgcbtn2 {
    margin-right:0px;
    margin-left: 0px;
    background: #d46e6e;
    border: 1px solid #d46e6e;
    font-size: 14px;
    margin-top: 0px;
    color: #fff;
    border-radius: 5px;
    padding: 5px 15px;
    font-family: 'Lato', sans-serif;
    font-weight: 500;
    background: transparent;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    display: inline-block;
    box-shadow: inset 2px 2px 2px 0px rgba(255, 255, 255, .5), 7px 7px 20px 0px rgba(0, 0, 0, .1), 4px 4px 5px 0px rgba(0, 0, 0, .1);
    outline: none;
    background: rgb(96, 9, 240);
    background: linear-gradient(0deg, rgb(0 0 0) 0%, rgb(8 8 8) 100%);
    border: none;
    font-weight:bold;
    box-shadow: 4px 4px 6px 0 rgba(255, 255, 255, .5), -4px -4px 6px 0 rgba(116, 125, 136, .5), inset -4px -4px 6px 0 rgba(255, 255, 255, .2), inset 4px 4px 6px 0 rgba(0, 0, 0, .4);
}

.imgcbtn3 {
    margin-right:0px;
    margin-left: 0px;
    background: #d46e6e;
    border: 1px solid #d46e6e;
    font-size: 14px;
    margin-top: 0px;
    color: #000;
    border-radius: 5px;
    padding: 5px 15px;
    font-family: 'Lato', sans-serif;
    font-weight: 500;
    background: transparent;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    display: inline-block;
    box-shadow: inset 2px 2px 2px 0px rgba(255, 255, 255, .5), 7px 7px 20px 0px rgba(0, 0, 0, .1), 4px 4px 5px 0px rgba(0, 0, 0, .1);
    outline: none;
    background: rgb(96, 9, 240);
    background: linear-gradient(0deg, rgb(0 213 248) 0%, rgb(0 222 222) 100%);
    border: none;
    font-weight: bold;
}

    .card-box {
        padding: 1.5rem;
        -webkit-box-shadow: 0 1px 4px 0 rgb(0 0 0 / 10%);
        box-shadow: 0 1px 4px 0 rgb(0 0 0 / 10%);
        margin-bottom: 24px;
        border-radius: 0.25rem;
        }
        .text-dark{
            color:white !important;
        }
        .text-muted{
            color:white !important;
        }
        #orderinfo_filter{
            display:none;
        }
        td{
                color: black !important;
        }

        input.form-control.cuID {
            background: #029620 !important;
            border: 1px solid red;
        }
        input.form-control.phone {
            background: #029620 !important;
            border: 1px solid red;
        }

        input.form-control.cuID::placeholder {
          color: white;
          opacity: 1; /* Firefox */
        }
        input.form-control.phone::placeholder {
          color: white;
          opacity: 1; /* Firefox */
        }

</style>
<div class="px-4 pt-4 container-fluid">

    <div class="pagetitle row">
        <div class="col-6">
            <h1><a href="{{ url('/admindashboard') }}">Dashboard</a></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/admindashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Orders</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <div class="row">
        @if ($admin->hasrole('user'))
        <div class="col-md-6 col-xl-2">
            <a href="{{ url('order/complain') }}">
                <div class="pt-1 pb-1 widget-rounded-circle card-box order" style="background: #0a0a7a;">
                    <div class="row">
                        <div class="col-12">
                            <div class="float-left">
                                <h3 class="mt-1 mb-0 text-dark">
                                    <span id="allss">{{App\Models\Order::get()->count()}}</span>
                                </h3>
                                <p class="mb-1 text-muted text-truncate">All Orders</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end widget-rounded-circle-->
                </a>
                </div> <!-- end col-->
            @else
        @endif
        <div class="col-md-6 col-xl-2">
            @if ($admin->hasrole('user'))
                <a href="{{ url('admin_order/orderall') }}">
                    <div class="pt-1 pb-1 widget-rounded-circle card-box order" style="background: #0a0a7a;">
                        <div class="row">
                            <div class="col-12">
                                <div class="float-left">
                                    <h3 class="mt-1 mb-0 text-dark">
                                        <span id="allss">{{App\Models\Order::where('admin_id',Auth::guard('admin')->user()->id)->get()->count()}}</span>
                                    </h3>
                                    <p class="mb-1 text-muted text-truncate">My Orders</p>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                    </a>
                @else
                    <a href="{{ url('admin_order/orderall') }}">
                        <div class="pt-1 pb-1 widget-rounded-circle card-box order" style="background: #0a0a7a;">
                            <div class="row">
                                <div class="col-12">
                                    <div class="float-left">
                                        <h3 class="mt-1 mb-0 text-dark">
                                            <span id="all">0</span>
                                        </h3>
                                        <p class="mb-1 text-muted text-truncate">All Orders</p>
                                    </div>
                                </div>
                            </div> <!-- end row-->
                        </div> <!-- end widget-rounded-circle-->
                    </a>
            @endif

        </div> <!-- end col-->

        <div class="col-md-6 col-xl-2">
            <a href="{{ url('admin_order/Pending') }}">
                <div class="pt-1 pb-1 widget-rounded-circle card-box order bg-light">
                    <div class="row">
                        <div class="col-12">
                            <div class="float-left">
                                <h3 class="mt-1 mb-0 text-dark">
                                    <span id="pending" data-plugin="counterup">0</span>
                                </h3>
                                <p class="mb-1 text-muted text-truncate">Pending</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end widget-rounded-circle-->
            </a>
        </div> <!-- end col-->
        <div class="col-md-6 col-xl-2">
            <a href="{{ url('admin_order/Hold') }}">

                <div class="pt-1 pb-1 widget-rounded-circle card-box order bg-warning">
                    <div class="row">
                        <div class="col-12">
                            <div class="float-left">
                                <h3 class="mt-1 mb-0 text-dark">
                                    <span id="hold" data-plugin="counterup">0</span>
                                </h3>
                                <p class="mb-1 text-muted text-truncate">Hold</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end widget-rounded-circle-->
            </a>
        </div> <!-- end col-->
        <div class="col-md-6 col-xl-2">
            <a href="{{ url('admin_order/Cancelled') }}">
                <div class="pt-1 pb-1 widget-rounded-circle card-box order bg-danger">
                    <div class="row">
                        <div class="col-12">
                            <div class="float-left">
                                <h3 class="mt-1 mb-0 text-dark">
                                    <span id="cancelled" data-plugin="counterup">0</span>
                                </h3>
                                <p class="mb-1 text-muted text-truncate">Cancelled</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end widget-rounded-circle-->
            </a>
        </div> <!-- end col-->
        <div class="col-md-6 col-xl-2">
            <a href="{{ url('admin_order/Ready to Ship') }}">

                <div class="pt-1 pb-1 widget-rounded-circle card-box order bg-info">
                    <div class="row">
                        <div class="col-12">
                            <div class="float-left">
                                <h3 class="mt-1 mb-0 text-dark">
                                    <span id="readytoship" data-plugin="counterup">0</span>
                                </h3>
                                <p class="mb-1 text-muted text-truncate">Ready to Ship</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end widget-rounded-circle-->
            </a>
        </div> <!-- end col-->
        <div class="col-md-6 col-xl-2">
            <a href="{{ url('admin_order/Packaging') }}">
                <div class="pt-1 pb-1 widget-rounded-circle card-box order bg-dark">
                    <div class="row">
                        <div class="col-12">
                            <div class="float-left">
                                <h3 class="mt-1 mb-0 text-dark">
                                    <span id="packaging" data-plugin="counterup">0</span>
                                </h3>
                                <p class="mb-1 text-muted text-truncate">Packaging</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end widget-rounded-circle-->
            </a>
        </div> <!-- end col-->
        <div class="col-md-6 col-xl-2">
            <a href="{{ url('admin_order/Shipped') }}">
                <div class="pt-1 pb-1 widget-rounded-circle card-box order bg-success">
                    <div class="row">
                        <div class="col-12">
                            <div class="float-left">
                                <h3 class="mt-1 mb-0 text-dark">
                                    <span id="shipped" data-plugin="counterup">0</span>
                                </h3>
                                <p class="mb-1 text-muted text-truncate">Shipped</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end widget-rounded-circle-->
            </a>
        </div> <!-- end col-->


        <div class="col-md-6 col-xl-2">
            <a href="{{ url('admin_order/Courier Pending') }}">

                <div class="pt-1 pb-1 widget-rounded-circle card-box order bg-danger" style="    background-color: #0383ff !important;">
                    <div class="row">
                        <div class="col-12">
                            <div class="float-left">
                                <h3 class="mt-1 mb-0 text-dark">
                                    <span id="courierPending" data-plugin="counterup">0</span>
                                </h3>
                                <p class="mb-1 text-muted text-truncate">Courier Pending</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end widget-rounded-circle-->
            </a>
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-2">
            <a href="{{ url('admin_order/Completed') }}">

                <div class="pt-1 pb-1 widget-rounded-circle card-box order bg-success" style="    background-color: #00c116 !important;">
                    <div class="row">
                        <div class="col-12">
                            <div class="float-left">
                                <h3 class="mt-1 mb-0 text-dark">
                                    <span id="completed" data-plugin="counterup">0</span>
                                </h3>
                                <p class="mb-1 text-muted text-truncate">Completed</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end widget-rounded-circle-->
            </a>
        </div> <!-- end col-->
        <div class="col-md-6 col-xl-2">
            <a href="{{ url('admin_order/Partial Delivered') }}">

                <div class="pt-1 pb-1 widget-rounded-circle card-box order bg-danger" style="    background-color: #66726b !important;">
                    <div class="row">
                        <div class="col-12">
                            <div class="float-left">
                                <h3 class="mt-1 mb-0 text-dark">
                                    <span id="partialDelivered" data-plugin="counterup">0</span>
                                </h3>
                                <p class="mb-1 text-muted text-truncate">Partial Delivered</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end widget-rounded-circle-->
            </a>
        </div> <!-- end col-->
        <div class="col-md-6 col-xl-2">
            <a href="{{ url('admin_order/Del. Failed') }}">

                <div class="pt-1 pb-1 widget-rounded-circle card-box order bg-danger" style="    background-color: #8d000e !important;">
                    <div class="row">
                        <div class="col-12">
                            <div class="float-left">
                                <h3 class="mt-1 mb-0 text-dark">
                                    <span id="delfailed" data-plugin="counterup">0</span>
                                </h3>
                                <p class="mb-1 text-muted text-truncate">Del. Failed</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end widget-rounded-circle-->
            </a>
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-2">
            <a href="{{ url('admin_order/Unknown') }}">

                <div class="pt-1 pb-1 widget-rounded-circle card-box order bg-danger" style="    background-color: red !important;">
                    <div class="row">
                        <div class="col-12">
                            <div class="float-left">
                                <h3 class="mt-1 mb-0 text-dark">
                                    <span id="unknown" data-plugin="counterup">0</span>
                                </h3>
                                <p class="mb-1 text-muted text-truncate">Unknown</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div> <!-- end widget-rounded-circle-->
            </a>
        </div> <!-- end col-->

    </div>

    {{-- //popup modal for edit user --}}
    <div class="modal" id="editmainOrder">
        <div class="modal-dialog" style="width: 92%;max-width: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">



                </div>

            </div>
        </div>
    </div><!-- End popup Modal-->

        {{-- //popup modal for edit user --}}
    <div class="modal" id="transfer">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="justify-content: center;">
                    <h5 class="modal-title">File Transfer On Progress</h5>
                </div>
                <div class="modal-body" style="text-align: center;">
                    <img src="{{ asset('public/tr.gif') }}" alt="" style="width: 224px;">
                </div>

            </div>
        </div>
    </div><!-- End popup Modal-->

        <div class="modal" id="froudcheck">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="display:inline !important">
                    <div class="d-flex justify-content-between">
                        <h5 class="modal-title">INV#<span id="invnum"></span></h5>
                        <button type="button" class="close" id="discol" data-bs-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <h5 class="modal-title" id="mtitle"> Parcel Receive & Cancel Ratio of :- <b><span style="color:red" id="cusnum"></span></b> </h5>
                </div>
                <div class="modal-body" style="text-align: center;">
                    <div class="text-center auto-load" style="display: none;">
                        <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                            <path fill="#000"
                                d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                                <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                                    from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                            </path>
                        </svg>
                    </div>
                    <div  id="cuslist">

                    </div>
                </div>

            </div>
        </div>
    </div><!-- End popup Modal-->

    {{-- //table section for category --}}

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="pt-4 pb-2 card-body">
                        <div class="row">
                            <div class="col-4">
                                <h4><a href="">Total <span class="total">0</span> Orders </a></h4>
                            </div>

                            <div class="col-8" style="text-align: right">
                                <a href="{{ url('admin/create/order') }}" class="btn btn-primary btn-sm"><span
                                            style="font-weight: bold;">+</span> Add New Order</a>
                                    @if ($admin->hasrole('superadmin') || $admin->hasrole('accounts') || $admin->hasrole('store'))
                                    <button type="button" class="btn btn-warning order-print-btn btn-sm">
                                        <i class="mr-1 fas fa-print"></i> Invoice Print
                                    </button>
                                    <form method="POST" action="{{ route('file-export') }}"
                                        enctype="multipart/form-data" style="width: 24%;float: right;">
                                        @csrf
                                        <input type="text" id="courier_id" name="cour_Id" value="1" hidden>
                                        <button type="submit" class="btn btn-info download-excel-btn btn-sm">
                                            <i class="mr-1 fas fa-print"></i> Download Excel
                                        </button>
                                    </form>
                                    <div class="btn-group dropdown">
                                        <a href="javascript: void(0);" style="color: white"
                                            class="table-action-btn dropdown-toggle arrow-none btn bg-danger btn-sm"
                                            data-bs-toggle="dropdown" aria-expanded="false"><i
                                                class="mr-1 fas fa-truck"></i> Assign Courier</a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @foreach (App\Models\Courier::where('status','Active')->get()->reverse() as $courier)
                                                <a class="dropdown-item assign-courier" data-id="{{ $courier->id }}"
                                                    href="#">{{ $courier->courierName }}</a>
                                            @endforeach
                                        </div>
                                    </div>

                                @else
                                    @if ($admin->hasrole('superadmin'))
                                        <button type="button" class="btn btn-danger btn-sm " id="delete_selected_order"><i class="mr-1 fas fa-trash"></i>  Delete Order</button>
                                    @else
                                        <div class="btn-group dropdown">
                                            <a href="javascript: void(0);" style="color: white"
                                                class="table-action-btn dropdown-toggle arrow-none btn bg-info btn-sm"
                                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                                    class="mr-1 fas fa-thumbtack"></i> Change Status</a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item btn-change-status" data-status="Pending"
                                                    href="#"><i
                                                        class="mr-2 fas fa-tag font-18 text-muted vertical-middle"></i>Pending</a>
                                                <a class="dropdown-item btn-change-status" data-status="Ready to Ship"
                                                    href="#"><i
                                                        class="mr-2 fas fa-tag font-18 text-muted vertical-middle"></i>Ready to Ship</a>
                                                <a class="dropdown-item btn-change-status" data-status="Hold"
                                                    href="#"><i
                                                        class="mr-2 far fa-stop-circle font-18 text-muted vertical-middle"></i>Hold</a>
                                                <a class="dropdown-item btn-change-status" data-status="Packaging"
                                                    href="#"><i
                                                        class="mr-2 fas fa-tag font-18 text-muted vertical-middle"></i>Packaging</a>

                                                @if ($admin->hasrole('superadmin'))
                                                <a class="dropdown-item btn-change-status" data-status="Shipped"
                                                    href="#"><i
                                                        class="mr-2 fas fa-tag font-18 text-muted vertical-middle"></i>Shipped</a>
                                                @endif
                                                <a class="dropdown-item btn-change-status" data-status="Cancelled"
                                                    href="#"><i
                                                        class="mr-2 fas fa-trash font-18 text-muted vertical-middle"></i>Cancelled</a>
                                                <a class="dropdown-item btn-change-status" data-status="Completed"
                                                    href="#"><i
                                                        class="mr-2 fas fa-check-circle font-18 text-muted vertical-middle"></i>Completed</a>
                                                <a class="dropdown-item btn-change-status" data-status="Del. Failed"
                                                    href="#"><i
                                                        class="mr-2 fas fa-check-circle font-18 text-muted vertical-middle"></i>Del. Failed</a>
                                            </div>
                                        </div>
                                    @endif
                                @endif







                            </div>
                        </div>
                        @if (\Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-1"></i>
                                {{ \Session::get('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Table with stripped rows -->
                        <div class="table-responsive">
                            <table class="table mb-0 cell-border" id="orderinfo"
                                width="100%" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Invoice ID</th>
                                        <th>Name</th>
                                        <th>Products</th>
                                        <th>Total</th>
                                        <th>Staff Note</th>
                                        <th>Status</th>
                                        <th style="width: 133px;">Customer Notes</th>
                                        <th style="width: 133px;">History</th>
                                        <th class="hidden-sm">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
    </section>

    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    {{-- //user role --}}
    @if ($admin->hasrole('user'))
        <input type="text" id="user_role" value="0" hidden>
    @else
        <input type="text" id="user_role" value="1" hidden>
    @endif

    @if (empty($status))
    @else
        <input type="text" id="orderstatus" value="{{ $status }}" hidden>
    @endif
</div>


<script>
    $(document).ready(function() {
        //change order status
        var token = $("input[name='_token']").val();

        var orderstatus = $('#orderstatus').val();
        var user_role = $('#user_role').val();

        if (user_role == '0') {
            var orderinfotbl = $('#orderinfo').DataTable({
                ajax: {
                    url: "{{ url('admin/admin_order/') }}" + '/' + orderstatus,
                },
                ordering: false,
                processing: true,
                serverSide: true,
                pageLength: 30,
                columnDefs: [{
                    targets: 0,
                    checkboxes: {
                        selectRow: false,
                    },
                }, ],

                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'invoice',
                        width: "15%"
                    },
                    {
                        data: 'customerInfo',
                        width: "25%",
                        className: "customerInfo"
                    },
                    {
                        data: "products",
                        width: "40%",
                    },
                    {
                        data: "price",
                        width: "5%"
                    },
                    {
                        data: "notification",
                        width: "20%",
                        searchable: false
                    },
                    {
                        data: 'statusButton',
                        width: "10%"
                    },
                    {
                        data: 'customerNote',
                        width: "15%"
                    },
                    {
                        data: "history",
                        width: "20%",
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },

                ],

                footerCallback: function() {
                    var api = this.api();
                    var numRows = api.rows().count();
                    $('.total').empty().append(numRows);

                    var intVal = function(i) {
                        return typeof i === "string" ? i.replace(/[\$,]/g, "") * 1 :
                            typeof i === "number" ? i : 0;
                    };
                    pageTotal = api.column(4, {
                        page: "current"
                    }).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    $(api.column(4).footer()).html(pageTotal + " TK");
                }

            });
        } else {
            var orderinfotbl = $('#orderinfo').DataTable({
                ajax: {
                    url: "{{ url('admin/admin_order/') }}" + '/' + orderstatus,
                },
                ordering: false,
                processing: true,
                serverSide: true,
                pageLength: 30,
                columnDefs: [{
                    targets: 0,
                    checkboxes: {
                        selectRow: false,
                    },
                }, ],

                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'invoice',
                        width: "15%"
                    },
                    {
                        data: 'customerInfo',
                        width: "25%",
                        className: "customerInfo"
                    },
                    {
                        data: "products",
                        width: "40%",
                    },
                    {
                        data: "price",
                        width: "5%"
                    },
                    {
                        data: "notification",
                        width: "20%",
                        searchable: false
                    },
                    {
                        data: 'statusButton',
                        width: "10%"
                    },
                    {
                        data: 'customerNote',
                        width: "15%"
                    },
                    {
                        data: "history",
                        width: "20%",
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },

                ],

                footerCallback: function() {
                    var api = this.api();
                    var numRows = api.rows().count();
                    $('.total').empty().append(numRows);

                    var intVal = function(i) {
                        return typeof i === "string" ? i.replace(/[\$,]/g, "") * 1 :
                            typeof i === "number" ? i : 0;
                    };
                    pageTotal = api.column(4, {
                        page: "current"
                    }).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    $(api.column(4).footer()).html(pageTotal + " TK");
                }

            });
        }

        //assign user
        $(document).on('click', '.assign-user', function(e) {
            e.preventDefault();

            var rows_selected = orderinfotbl.column(0).checkboxes.selected();
            var ids = [];
            $.each(rows_selected, function(index, rowId) {
                ids[index] = rowId;
            });
            var user_id = $(this).attr('data-id');

            jQuery.ajax({
                type: "get",
                url: "{{ url('admin_order/assign_user') }}",
                contentType: "application/json",
                data: {
                    action: "assign",
                    ids: ids,
                    user_id: user_id
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data["status"] == "success") {
                        swal(data["message"]);
                        orderinfotbl.ajax.reload();
                    } else {
                        if (data["status"] == "failed") {
                            swal(data["message"]);
                        } else {
                            swal("Something wrong ! Please try again.");
                        }
                    }
                }
            });

        });

        // update status selected item

        $(document).on('click', '.btn-change-status', function(e) {
            e.preventDefault();
            var rows_selected = orderinfotbl.column(0).checkboxes.selected();
            var ids = [];
            $.each(rows_selected, function(index, rowId) {
                ids[index] = rowId;
            });
            var status = $(this).attr('data-status');
            $.ajax({
                type: "get",
                url: "{{ url('admin_order/statusUpdateByCheckbox') }}",
                data: {
                    'status': status,
                    'orders_id': ids,
                    '_token': token
                },
                success: function(response) {
                    countorder();
                    var data = JSON.parse(response);
                    if (data['status'] == 'success') {
                        toastr.success(data["message"]);
                        orderinfotbl.ajax.reload();
                    } else {
                        if (data['status'] == 'failed') {
                            toastr.error(data["message"]);
                        } else {
                            toastr.error('Something wrong ! Please try again.');
                        }
                    }
                }
            });
        });

        //delete order selectes

        $(document).on('click', '.order-print-btn', function(e) {
            e.preventDefault();
            var rows_selected = orderinfotbl.column(0).checkboxes.selected();
            var ids = [];
            $.each(rows_selected, function(index, rowId) {
                ids[index] = rowId;
            });

            if (ids.length > 0) {

                $.ajax({
                    type: "GET",
                    url: "{{ url('admin_order/store/Invoice') }}",
                    data: {
                        orders_id: ids
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data['status'] === 'success') {
                            window.open(data['link'], "_blank");
                            swal({
                                title: "Are you sure?",
                                text: "All invoiced Printed !",
                                type: "warning",
                                buttons: true,
                                dangerMode: true,
                            }).then((t) => {
                                if (t) {
                                    $.ajax({
                                        type: "get",
                                        url: "{{ url('admin_order/statusUpdateByCheckbox') }}",
                                        data: {
                                            'status': 'Ready to Ship',
                                            'orders_id': ids,
                                            '_token': token
                                        },
                                        success: function(response) {
                                            var data = JSON.parse(
                                                response);
                                            if (data['status'] ===
                                                'success') {
                                                toastr.success(data[
                                                    "message"]);
                                                orderinfotbl.ajax
                                                    .reload();
                                            } else {
                                                if (data['status'] ===
                                                    'failed') {
                                                    toastr.error(data[
                                                        "message"
                                                    ]);
                                                } else {
                                                    toastr.error(
                                                        'Something wrong ! Please try again.'
                                                    );
                                                }
                                            }
                                        }
                                    });
                                } else {
                                    swal("Invoice Stay Pending !");
                                }
                            });

                        } else {
                            if (data['status'] === 'failed') {
                                toastr.error(data["message"]);
                            } else {
                                toastr.error('Something wrong ! Please try again.');
                            }
                        }


                    }

                });
            } else {
                swal("Oops...!", "Select at last one", "error");
            }


        });

        $(document).on('click', '#delete_selected_order', function(e) {
            e.preventDefault();
            var rows_selected = orderinfotbl.column(0).checkboxes.selected();
            var ids = [];
            $.each(rows_selected, function(index, rowId) {
                ids[index] = rowId;
            });
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this !",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "GET",
                            url: "{{ url('admin_order/delete_selected_order') }}",
                            data: {
                                orders_id: ids,
                            },
                            success: function(response) {
                                countorder();
                                var data = JSON.parse(response);
                                if (data["status"] == "success") {
                                    swal(data["message"]);
                                    orderinfotbl.ajax.reload();
                                } else {
                                    if (data["status"] == "failed") {
                                        swal(data["message"]);
                                    } else {
                                        swal("Something wrong ! Please try again.");
                                    }
                                }
                            }
                        });


                    } else {
                        swal("Your data is safe!");
                    }
                });

        });

        $('#orderinfo thead th').each(function() {
            //count orders
            countorder();
            var title = $(this).text();
            if (title != 'Status' &&
                title != '' &&
                title != 'Action' &&
                title != 'Products' &&
                title != 'Total') {
                // console.log(title);
                if (title == 'Order Date') {
                    $(this).html(
                        '<input type="text" style="width: 60px;" class="form-control datepicker" id="dateorder" placeholder="Date" />'
                    );
                }

                if (title == 'Invoice ID') {
                    $(this).html(
                        ' <input type="text" class="form-control cuID" placeholder="Invoice ID   " />');
                }
                if (title == 'Name') {
                    $(this).html(
                        ' <input type="text" class="form-control phone" placeholder="Customer Phone   " />');
                }

            }
        });

        $("#userID").select2({
            placeholder: "Select a User",
            allowClear: true,
            ajax: {
                url: '{{ url('admin_order/users') }}',
                processResults: function(data) {
                    var data = $.parseJSON(data);
                    return {
                        results: data
                    };
                }
            }
        });

        $("#courierID").select2({
            placeholder: "Select a Courier",
            allowClear: true,
            ajax: {
                url: '{{ url('admin_order/courier') }}',
                processResults: function(data) {
                    var data = $.parseJSON(data);
                    return {
                        results: data
                    };
                }
            }
        });

        $("#cityID").select2({
            placeholder: "Select a City",
            allowClear: true,
            ajax: {
                url: '{{ url('admin_order/cities') }}',
                processResults: function(data) {
                    var data = $.parseJSON(data);
                    return {
                        results: data
                    };
                }
            }
        });

         $(document).on('click', '#checkfraud', function(e) {
            e.preventDefault();
            var number = $(this).attr('data-num');
            var inv = $(this).attr('data-inv');
            $('#froudcheck').modal('show');
            $('#cuslist').empty();
            $('.auto-load').css('display','inline');
            $('#cusnum').html(number);
            $('#invnum').html(inv);
            $.ajax({
                type: "GET",
                url: "https://dokangharbd.com/api/user/fraud-check-data",
                data: {
                    'number': number,
                    '_token': token
                },
                success: function(response) {
                    $('.auto-load').css('display','none');
                    $('#cuslist').empty().append(response);
                }
            });
        });

        orderinfotbl.columns().every(function() {

            var orderinfotbl = this;
            $('input', this.header()).on('keyup change', function() {
                if (orderinfotbl.search() !== this.value) {
                    orderinfotbl.search(this.value).draw();
                }
            });

            $('select', this.header()).on('change', function() {
                if (orderinfotbl.search() !== this.value) {
                    orderinfotbl.search(this.value).draw();
                }
            });

        });


        function countorder() {
            $.ajax({
                type: "get",
                url: "{{ url('admin_order/count') }}",
                contentType: "application/json",
                success: function(response) {
                    var data = JSON.parse(response);

                    if (data["status"] == "success") {

                        $('#all').text(data["all"]);
                        $('#pending').text(data["pending"]);
                        $('#readytoship').text(data["readytoship"]);
                        $('#hold').text(data["hold"]);
                        $('#shipped').text(data["shipped"]);
                        $('#cancelled').text(data["cancelled"]);
                        $('#completed').text(data["completed"]);
                        $('#packaging').text(data["packaging"]);
                        $('#delfailed').text(data["delFailed"]);
                        $('#courierPending').text(data["courierPending"]);
                        $('#partialDelivered').text(data["partialDelivered"]);
                        $('#unknown').text(data["unknown"]);
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

        function courierid() {
            var cur = $('#courierID').select2('val');
            var id = $('#courier_id').val(cur);

            if (id == '') {
                swal("Oops...!", "Select at last one", "error");

            }
        }



        $(document).on('click', '.btn-status', function(e) {
            e.preventDefault();
            var status = $(this).attr('data-status');
            var id = $(this).attr('data-id');
            $.ajax({
                type: "GET",
                url: "{{ url('order/admin_order/status') }}",
                data: {
                    'status': status,
                    'id': id,
                    '_token': token
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    countorder();
                    if (data['status'] == 'success') {
                        toastr.success(data["message"]);
                        orderinfotbl.ajax.reload();
                    } else {
                        if (data['status'] == 'failed') {
                            toastr.error(data["message"]);
                        } else {
                            toastr.error('Something wrong ! Please try again.');
                        }
                    }
                }
            });
        });

        //order edit

        $(document).on('click', '.btn-editorder', function(e) {

            e.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax({
                type: "get",
                url: "{{ url('admin_orders') }}/" + id + "/edit",
                success: function(response) {
                    $('#editmainOrder .modal-body').html('');
                    $('#editmainOrder .modal-body').empty().append(response);
                    $('#editmainOrder').modal('toggle');
                    $('#editmainOrder .modal-footer').hide();

                    $(".datepicker").flatpickr();

                    $("#productID").select2({
                        placeholder: "Select a Product",
                        dropdownParent: $('#productTable'),
                        allowClear: true,
                        templateResult: function (state) {
                            if (!state.id) {
                                return state.text;
                            }
                            var $state = $(
                                '<span><img width="60px" src="'+state.image +'" class="img-flag" /> '+state.text+'" (Size: "'+state.size+")</span>"
                            );
                            return $state;
                        },
                        ajax: {
                            type:'GET',
                            url: '{{url('admin_order/products')}}',
                            processResults: function (data) {
                                return {
                                    results: data.data
                                };
                            }
                        }
                    }).trigger("change").on("select2:select", function (e) {
                        $("#productTable tbody").append(
                            "<tr>" +
                            '<td style="display: none"><input type="hidden" id="prd" value="new"><input type="text" class="productID" style="width:80px;" value="' + e.params.data.id + '"></td>' +
                            '<td><input type="text" name="color" id="ProductColor" value="" style="    max-width: 60px;"><br><img src="' + e.params.data.image + '" style="width:60px;margin-top:6px;"> </td>' +
                            '<td><input type="text" name="size" id="ProductSize" value="' + e.params.data.size + '" style="    max-width: 40px;"></td>' +
                            '<td><span class="productCode">' + e.params.data.productCode + '</span></td>' +
                            '<td><span class="productName">' + e.params.data.text + '</span><br><input type="text" name="sigment" id="sigment" class="form-control" value="" style="max-width: 250px;"></td>' +
                            '<td><input type="number" class="productQuantity form-control" style="width:80px;" value="1"></td>' +
                            '<td><input type="text" name="productPrice" class="productPrice" value="' + e.params.data.productPrice + '" style="max-width: 60px;"></td>' +
                            '<td><button class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i></button></td>\n' +
                            "</tr>"
                        );
                        calculation();
                        $('#productID').val(null).trigger('change');
                    });


                    $("#courierID").select2({
                        placeholder: "Select a Courier",
                        allowClear: true,
                        dropdownParent: $('#courierdatatbl'),
                        ajax: {
                            url: '{{ url('admin_order/couriers') }}',
                            processResults: function(data) {
                                var data = $.parseJSON(data);
                                return {
                                    results: data
                                };
                            }
                        }
                    }).trigger("change").on("select2:select", function(e) {
                        $("#zoneID").empty();
                        for (var i = 0; i < couriers.length; i++) {
                            if (couriers[i]['courierName'] == e.params.data.text) {
                                if (couriers[i]['hasCity'] == 'on') {
                                    jQuery(".hasCity").show();
                                } else {
                                    jQuery(".hasCity").hide();
                                }
                                if (couriers[i]["hasZone"] == 'on') {
                                    jQuery(".hasZone").show();
                                } else {
                                    jQuery(".hasZone").hide();
                                    $("#zoneID").empty();
                                }
                            }

                            if (e.params.data.text == 'Pathao') {
                                $("#cityID").empty().append(
                                    '<option value="8">Dhaka</option>');
                            } else {
                                $("#cityID").empty();
                            }
                        }

                    });

                    if ($("#courierID").text()) {
                        var courier = $("#courierID").text().trim();
                        for (var i = 0; i < couriers.length; i++) {
                            if (couriers[i]['courierName'] == courier) {
                                if (couriers[i]['hasCity'] == 'on') {
                                    jQuery(".hasCity").show();
                                } else {
                                    jQuery(".hasCity").hide();
                                }

                                if (couriers[i]["hasZone"] == 'on') {
                                    jQuery(".hasZone").show();
                                } else {
                                    jQuery(".hasZone").hide();
                                    $("#zoneID").empty();
                                }
                            }
                        }
                    }

                    $("#cityID").select2({
                        placeholder: "Select a City",
                        dropdownParent: $('#citydatatbl'),
                        allowClear: true,
                        ajax: {
                            data: function(params) {
                                var query = {
                                    q: params.term,
                                    courierID: $("#courierID").val()
                                };
                                return query;
                            },
                            type: 'GET',
                            url: '{{ url('admin_order/cities') }}',
                            processResults: function(data) {
                                var data = $.parseJSON(data);
                                return {
                                    results: data
                                };
                            }
                        }
                    });

                    $("#zoneID").select2({
                        placeholder: "Select a Zone",
                        dropdownParent: $('#xonedatatbl'),
                        allowClear: true,
                        ajax: {
                            data: function(params) {
                                var query = {
                                    q: params.term,
                                    courierID: $("#courierID").val(),
                                    cityID: $("#cityID").val()
                                };
                                return query;
                            },
                            type: 'GET',
                            url: '{{ url('admin_order/zones') }}',
                            processResults: function(data) {
                                var data = $.parseJSON(data);
                                return {
                                    results: data
                                };
                                console.log(data);
                            }
                        }
                    });


                    var orderCommentTable = $("#orderCommentTable").DataTable({
                        ajax: "{{ url('admin_order/getComment') }}?id=" + $(
                            '#orderCommentTable').attr('data-id'),
                        ordering: false,
                        lengthChange: false,
                        bFilter: false,
                        search: false,
                        info: false,
                        columns: [{
                                data: "date"
                            },
                            {
                                data: "comment"
                            },
                            {
                                data: "name"
                            }
                        ],
                    });

                    var oldOrderTable = $("#oldOrderTable").DataTable({
                        ajax: "{{ url('admin_order/previous_orders') }}?id=" + $(
                            '#oldOrderTable').attr('data-id'),
                        ordering: false,
                        lengthChange: false,
                        bFilter: false,
                        search: false,
                        info: false,
                        columns: [{
                                data: "created_at"
                            },
                            {
                                data: "invoiceID"
                            },
                            {
                                data: null,
                                width: "15%",
                                render: function(data) {
                                    return '<i class="mr-2 fas fa-user text-grey-dark"></i>' +
                                        data.customerName +
                                        '<br> <i class="mr-2 fas fa-phone text-grey-dark"></i>' +
                                        data.customerPhone +
                                        '<br><i class="mr-2 fas fa-map-marker text-grey-dark"></i>' +
                                        data.customerAddress;
                                }
                            },
                            {
                                data: "products"
                            },
                            {
                                data: "subTotal"
                            },
                            {
                                data: "status"
                            },
                            {
                                data: "name"
                            }
                        ]
                    });

                    $(document).on("click", "#updateComment", function() {
                        var note = $('#comment');
                        var id = $('#btn-update').val();
                        if (note.val() == '') {
                            note.css('border', '1px solid red');
                            return;
                        } else if (id == '') {
                            toastr.success('Something Wrong , Try again ! ');
                            return;
                        } else {
                            $.ajax({
                                type: "GET",
                                url: "{{ url('admin_order/updateComment') }}",
                                data: {
                                    'comment': note.val(),
                                    'id': id,
                                    '_token': token
                                },
                                success: function(response) {
                                    var data = JSON.parse(response);
                                    if (data['status'] == 'success') {
                                        toastr.success(data["message"]);
                                        orderCommentTable.ajax.reload();
                                    } else {
                                        if (data['status'] ==
                                            'failed') {
                                            toastr.error(data[
                                                "message"]);
                                        } else {
                                            toastr.error(
                                                'Something wrong ! Please try again.'
                                            );
                                        }
                                    }
                                }
                            });

                        }
                        return;

                    });


                    if ($("#paymentTypeID").text()) {
                        var paymentType = $("#paymentTypeID").val();
                        if (paymentType == "") {
                            $(".paymentID").hide();
                            $(".paymentAgentNumber").hide();
                            $(".paymentAmount").hide();
                        } else {
                            $(".paymentID").show();
                            $(".paymentAgentNumber").show();
                            $(".paymentAmount").show();
                        }
                    }

                    $("#paymentTypeID").select2({
                        placeholder: "Select a payment Type",
                        dropdownParent: $('#paymntidname'),
                        allowClear: true,
                        ajax: {
                            data: function(params) {
                                return {
                                    q: params.term
                                };
                                console.log(params);
                            },
                            url: '{{ url('admin_order/paymenttype') }}',
                            processResults: function(data) {

                                var data = $.parseJSON(data);
                                return {
                                    results: data
                                };
                            }
                        }
                    }).trigger("change").on("select2:select", function(e) {
                        if (e.params.data.text == "") {
                            $(".paymentID").hide();
                            $(".paymentAgentNumber").hide();
                            $(".paymentAmount").hide();
                        } else {
                            $(".paymentID").show();
                            $(".paymentAgentNumber").show();
                            $(".paymentAmount").show();
                        }
                    }).on("select2:unselect", function(e) {
                        $(".paymentID").hide();
                        $(".paymentAgentNumber").hide();
                        $(".paymentAmount").hide();
                        calculation();
                    });

                    $("#paymentID").select2({
                        placeholder: "Select a payment Number",
                        dropdownParent: $('#paymentIDname'),
                        allowClear: true,
                        ajax: {
                            data: function(params) {
                                return {
                                    q: params.term,
                                    paymentTypeID: $("#paymentTypeID").val(),
                                };
                            },
                            type: 'GET',
                            url: '{{ url('admin_order/paymentnumber') }}',

                            processResults: function(data) {
                                var data = $.parseJSON(data);
                                return {
                                    results: data
                                };
                            }
                        }
                    });

                    $(document).on("change", ".productQuantity", function() {
                        calculation();
                    });
                    $(document).on("input", ".productPrice", function() {
                        calculation();
                    });
                    $(document).on("input", "#paymentAmount", function() {
                        calculation();
                    });
                    $(document).on("input", "#deliveryCharge", function() {
                        calculation();
                    });
                    $(document).on("input", "#discountCharge", function() {
                        calculation();
                    });
                    calculation();

                    function calculation() {
                        var subtotal = 0;
                        var deliveryCharge = +$("#deliveryCharge").val();
                        var discountCharge = +$("#discountCharge").val();
                        var paymentAmount = +$("#paymentAmount").val();
                        $("#productTable tbody tr").each(function(index) {
                            subtotal = subtotal + +$(this).find(".productPrice")
                                .val() * +$(this).find(".productQuantity").val();
                        });
                        $("#subtotal").text(subtotal);
                        $("#total").text(subtotal + deliveryCharge - paymentAmount -
                            discountCharge);
                    }

                    $(document).on("click", ".delete-btn", function() {
                        $(this).closest("tr").remove();
                        calculation();
                    });


                }
            });
        });


        $(document).on("click", "#btn-update", function() {
            var id = $(this).val();
            var invoiceID = $("#invoiceID");
            var consigment_id = $("#consigment_id");
            var orderStatus = $("#orderStatus");
            var customerName = $("#customerName");
            var customerPhone = $("#customerPhone");
            var customerAddress = $("#customerAddress");
            var courier_tracking_link = $("#courier_tracking_link");
            var areaID = $("#areaID");
            var customerNote = $("#customerNote");
            var storeID = $("#storeID");
            var total = +$("#total").text();
            var deliveryCharge = +$("#deliveryCharge").val();
            var discountCharge = +$("#discountCharge").val();
            var paymentTypeID = $("#paymentTypeID").val();
            var paymentID = $("#paymentID").val();
            var paymentAmount = +$("#paymentAmount").val();
            var paymentAgentNumber = $("#paymentAgentNumber").val();
            var orderDate = $("#orderDate");
            var courierID = $("#courierID");
            var cityID = +$("#cityID").val();
            var zoneID = +$("#zoneID").val();
            var memo = $("#memo").val();
            var product = [];
            var productCount = 0;

            $("#productTable tbody tr").each(function(index, value) {
                var currentRow = $(this);
                var obj = {};
                obj.productColor = currentRow.find("#ProductColor").val();
                obj.sigment = currentRow.find("#sigment").val();
                obj.productSize = currentRow.find("#ProductSize").val();
                obj.productID = currentRow.find(".productID").val();
                obj.productCode = currentRow.find(".productCode").text();
                if(currentRow.find("#ProductSize").val()=='' && currentRow.find("#prd").val()=='old'){
                    toastr.error('please select any size');
                    return false;
                }
                obj.productName = currentRow.find(".productName").text();
                obj.productQuantity = currentRow.find(".productQuantity").val();
                obj.productPrice = currentRow.find(".productPrice").val();
                product.push(obj);
                productCount++;
            });

            if (storeID.val() == '') {
                toastr.error('Store Should Not Be Empty');
                storeID.closest('.form-group').find('.select2-selection').css('border',
                    '1px solid red');
                return;
            }
            storeID.closest('.form-group').find('.select2-selection').css('border',
                '1px solid #ced4da');

            if (invoiceID.val() == '') {
                toastr.error('Invoice ID Should Not Be Empty');
                invoiceID.css('border', '1px solid red');
                return;
            }
            invoiceID.css('border', '1px solid #ced4da');

            if (customerName.val() == '') {
                toastr.error('Customer Name Should Not Be Empty');
                customerName.css('border', '1px solid red');
                return;
            }
            customerName.css('border', '1px solid #ced4da');

            if (customerPhone.val() == '') {
                toastr.error('Customer Phone Should Not Be Empty');
                customerPhone.css('border', '1px solid red');
                return;
            }
            customerPhone.css('border', '1px solid #ced4da');

            if (customerAddress.val() == '') {
                toastr.error('Customer Address Should Not Be Empty');
                customerAddress.css('border', '1px solid red');
                return;
            }
            customerAddress.css('border', '1px solid #ced4da');

            if (orderDate.val() == '') {
                toastr.error('Order Date Should Not Be Empty');
                orderDate.css('border', '1px solid red');
                return;
            }
            orderDate.css('border', '1px solid #ced4da');

            if (courierID.val() == '') {
                toastr.error('Courier Should Not Be Empty');
                courierID.closest('.form-group').find('.select2-selection').css('border',
                    '1px solid red');
                return;
            }
            courierID.css('border', '1px solid #ced4da');

            if (productCount == 0) {
                toastr.error('Product Should Not Be Empty');
                return;
            }

            var data = {};
            data["invoiceID"] = invoiceID.val();
            data["consigment_id"] = consigment_id.val();
            data["status"] = orderStatus.val();
            data["storeID"] = storeID.val();
            data["customerName"] = customerName.val();
            data["customerPhone"] = customerPhone.val();
            data["customerAddress"] = customerAddress.val();
            data["customerNote"] = customerNote.val();
            data["courier_tracking_link"] = courier_tracking_link.val();
            data["total"] = total;
            data["deliveryCharge"] = deliveryCharge;
            data["discountCharge"] = discountCharge;
            data["paymentTypeID"] = paymentTypeID;
            data["paymentID"] = paymentID;
            data["paymentAmount"] = paymentAmount;
            data["paymentAgentNumber"] = paymentAgentNumber;
            data["orderDate"] = orderDate.val();
            data["courierID"] = +courierID.val();
            data["cityID"] = cityID;
            data["zoneID"] = zoneID;
            data["areaID"] = areaID.val();
            data["userID"] = $('#user_id').val();
            data["products"] = product;
            data["memo"] = memo;
            $.ajax({
                type: "PUT",
                url: "{{ url('admin_orders') }}/" + id,
                data: {
                    'data': data,
                    '_token': token
                },
                success: function(response) {
                    location.reload();
                    var data = JSON.parse(response);
                    if (data["status"] === "success") {
                        toastr.success(data["message"]);
                        $('#editmainOrder').modal('toggle');
                    } else {
                        toastr.error(data["message"]);
                    }
                    // orderinfotbl.ajax.reload();
                }
            });


        });

        $(document).on('click', '.assign-courier', function(e) {
                e.preventDefault();

                var rows_selected = orderinfotbl.column(0).checkboxes.selected();
                var ids = [];
                $.each(rows_selected, function(index, rowId) {
                    ids[index] = rowId;
                });
                var courier_id = $(this).attr('data-id');
                $('#transfer').modal('show');
                jQuery.ajax({
                    type: "get",
                    url: "{{ url('admin_order/assign_courier') }}",
                    contentType: "application/json",
                    data: {
                        action: "assign",
                        ids: ids,
                        courier_id: courier_id
                    },
                    success: function(response) {
                        $('#transfer').modal('hide');
                        var data = JSON.parse(response);
                        if (data["status"] == "success") {
                            swal({
                                title:data["message"],
                                icon: "success",
                                showCancelButton: true,
                                focusConfirm: false,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Yes",
                                cancelButtonText: "No",
                            });
                            orderinfotbl.ajax.reload();
                        } else {
                            if (data["status"] == "failed") {
                                swal({
                                    title:data["message"],
                                    icon: "error",
                                    showCancelButton: true,
                                    focusConfirm: false,
                                    confirmButtonColor: "#DD6B55",
                                    confirmButtonText: "Yes",
                                    cancelButtonText: "No",
                                });
                            } else {
                                swal("Something wrong ! Please try again.");
                            }
                        }
                    }
                });

            });


        $(".datepicker").flatpickr();



    });
</script>


@if ($admin->hasrole('user') || $admin->hasrole('manager'))
    <style>
        .btn-delete {
            display: none;
        }
    </style>
@else
@endif
<style>
    .card-box {
        padding: 1.5rem;
        -webkit-box-shadow: 0 1px 4px 0 rgb(0 0 0 / 10%);
        box-shadow: 0 1px 4px 0 rgb(0 0 0 / 10%);
        margin-bottom: 24px;
        border-radius: 0.25rem;
    }

    a {
        text-decoration: none;
    }
</style>


@endsection
