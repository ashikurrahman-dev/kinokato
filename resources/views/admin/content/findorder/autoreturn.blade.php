@extends('backend.master')

@section('maincontent')
    <div class="container-fluid pt-4 px-4">

        <div class="pagetitle row">
            <div class="col-6">
                <h1><a href="{{ url('/admindashboard') }}">Dashboard</a></h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/admindashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Barcode</li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->

        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 p-4 m-2" style="background: white">
                <div class="card">
                    <div class="card-header text-center">
                        <h6 class="mt-2">QTY: <span style="font-weight:bold;">{{App\Models\Order::where('last_updated',date('Y-m-d'))->where('status','Del. Failed')->get()->count()}}</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please scan your invoice barcode and process it for Del. Failed</h4>
                    </div>
                    <div class="card-body pb-4 pt-4" style="    text-align: -webkit-center;">
                        <br>
                        <form id="invoiceForm" method="POST" action="{{url('admin/return-status')}}" class="pb-4 pt-4">
                            @csrf
                            @if(Session::get('unknownparcel'))
                                <img src="{{asset('public/icon/warning.jpg')}}" alt="" style="width:200px;padding:0px">
                                <h4 style="text-align: center;color: red !important;font-size: 18px;width: 265px;">Opps! Unknown percel we can not identify this please reset and process again.</h4>
                            @elseif(Session::get('repeatparcel'))
                                <img src="{{asset('public/icon/warning.jpg')}}" alt="" style="width:200px;padding:0px">
                                <h4 style="text-align: center;color: red !important;font-size: 18px;width: 265px;">Opps! Repeat parcel. This parcel is already in shipment. Please reset and process again</h4>
                            @else
                                <label for="invoiceID" style="float: left;">Scan Your Barcode</label>
                                <div class="form-group container1">
                                    <div>
                                        <input type="text" class="form-control mb-2" name="invoiceID" id="invoiceID"
                                        onchange="this.form.submit()" placeholder="Please Scan Your Barcode" required>
                                    </div>
                                </div>
                            @endif

                            <br>
                            <br>
                        </form>
                    </div>
                    <div class="card-footer text-end">
                            <a href="{{url('admin/refresh-barcode')}}" id="orderbybarcode" class="btn btn-primary">Refresh</a>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>

    </div>

    <script>

        $(document).ready(function() {
            $('#invoiceID').focus();


        });
    </script>
@endsection
