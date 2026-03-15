@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-User Orders
@endsection

<style>
    #profileImage {
        border-radius: 50%;
        padding: 65px;
        padding-bottom: 8px;
        padding-top: 10px;
    }

    .sidebar-widget-title {
        position: relative;
    }

    .sidebar-widget-title:before {
        content: "";
        width: 100%;
        height: 1px;
        background: #eee;
        position: absolute;
        left: 0;
        right: 0;
        top: 50%;
    }

    .py-3 {
        padding-bottom: 1rem !important;
    }

    .sidebar-widget-title span {
        background: #fff;
        text-transform: uppercase;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.2em;
        position: relative;
        padding: 8px;
        color: #dadada;
    }

    ul.categories {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    ul.categories--style-3>li {
        border: 0;
    }

    ul.categories>li {
        border-bottom: 1px solid #f1f1f1;
    }

    .widget-profile-menu a i {
        opacity: 0.6;
        font-size: 13px !important;
        top: 0 !important;
        width: 18px;
        height: 18px;
        text-align: center;
        line-height: 18px;
        display: inline-block;
        margin-right: 0.5rem !important;
    }

    .category-name {
        color: black;
        font-size: 18px;
    }

    .category-icon {
        font-size: 18px;
        color: black;
    }
</style>

<style>
    .process-steps {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .process-steps li {
        width: 25%;
        float: left;
        text-align: center;
        position: relative;
    }

    .process-steps li .icon {
        height: 30px;
        width: 30px;
        margin: auto;
        background: #fff;
        border-radius: 50%;
        line-height: 30px;
        font-size: 14px;
        font-weight: 700;
        color: #adadad;
        position: relative;
    }

    .process-steps li .title {
        font-weight: 600;
        font-size: 13px;
        color: #777;
        margin-top: 8px;
        margin-bottom: 0;
    }

    .process-steps li+li:after {
        position: absolute;
        content: "";
        height: 3px;
        width: calc(100% - 30px);
        background: #fff;
        top: 14px;
        z-index: 0;
        right: calc(50% + 15px);
    }

    .breadcrumb {
        padding: 5px 0;
        border-bottom: 1px solid #e9e9e9;
        background-color: #fafafa;
    }

    .search-area .search-button {
        border-radius: 0px 3px 3px 0px;
        display: inline-block;
        float: left;
        margin: 0px;
        padding: 5px 15px 6px;
        text-align: center;
        background-color: #e62e04;
        border: 1px solid #e62e04;
    }

    .search-area .search-button:after {
        color: #fff;
        content: "\f002";
        font-family: fontawesome;
        font-size: 16px;
        line-height: 9px;
        vertical-align: middle;
    }
</style>
<div class="outer-top-xs outer-bottom-xs">
    <div class="container pt-4 mt-4">
        <div class="row">
            <div class="col-lg-13">
                <div class="p-2 pt-0">
                    <div class="container">
                        <div class="row">
                            <div class="card mt-4 p-0">
                                <div class="card-header py-2 heading-6 strong-600 clearfix" style="display: flex;justify-content: space-between;">
                                    <div class="float-left" style="color: red;"> <b>Order History</b> </div>
                                    <div class="track">
                                        <a target="_blank" href="{{ $orders->courier_tracking_link }}" class="btn btn-info btn-sm"> Track On Courier</a>
                                    </div>
                                </div>
                                <div class="card-body pb-0 ps-0 pe-0">
                            <div class="row">
                                 <div class="col-md-8 m-auto">
                                    <div class="card mt-4">
                                        <div class="card-header py-2 px-3 heading-6 strong-600 clearfix">
                                            <div class="float-left" style="color: red;text-align:center"> <b>Current Status : Order has been {{$orders->status}}</b> </div>
                                        </div>
                                        <div class="card-body pb-0">
                                            <table class="details-table table" style="border:none">
                                                <tbody style="border:none">
                                                    <tr style="border:none">
                                                        <td class="w-50 strong-600" style="border:none;font-size: 18px;">Order ID:</td>
                                                        <td style="border:none;font-size: 18px;">{{ $orders->invoiceID }}</td>
                                                    </tr>
                                                    <tr style="border:none">
                                                        <td class="w-50 strong-600" style="border:none;font-size: 18px;">Order Status:</td>
                                                        <td style="border:none;font-size: 18px;">Order has been {{ $orders->status }}</td>
                                                    </tr>
                                                    <tr style="border:none">
                                                        <td class="w-50 strong-600" style="border:none;font-size: 18px;">Customer Name:</td>
                                                        <td style="border:none;font-size: 18px;">{{ $orders->customers->customerName }}</td>
                                                    </tr>
                                                    <tr style="border:none">
                                                        <td class="w-50 strong-600" style="border:none;font-size: 18px;">Customer Phone:</td>
                                                        <td style="border:none;font-size: 18px;">{{ $orders->customers->customerPhone }}</td>
                                                    </tr>
                                                    <tr style="border:none">
                                                        <td class="w-50 strong-600" style="border:none;font-size: 18px;">Customer address:</td>
                                                        <td style="border:none;font-size: 18px;">{{ $orders->customers->customerAddress }} </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="card">
                                                <div class="card-header py-2 px-3 heading-6 strong-600 clearfix">
                                                    <ul class="process-steps clearfix">
                                                        @if ($orders->status == 'Pending' || $orders->status == 'Hold')
                                                            <li>
                                                                <div class="icon" style="background:#e62e04;color:white">1</div>
                                                                <div class="title" style="color:red">Pending</div>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <div class="icon">1</div>
                                                                <div class="title">Pending</div>
                                                            </li>
                                                        @endif
                                                        @if ($orders->status == 'Ready to Ship' ||
                                                            $orders->status == 'Packaging')
                                                            <li>
                                                                <div class="icon" style="background:#e62e04;color:white">2</div>
                                                                <div class="title" style="color:red">Confirmed</div>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <div class="icon">2</div>
                                                                <div class="title">Confirmed</div>
                                                            </li>
                                                        @endif
                        
                                                        @if ($orders->status == 'Shipped')
                                                            <li>
                                                                <div class="icon" style="background:#e62e04;color:white">3</div>
                                                                <div class="title" style="color:red">On Going</div>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <div class="icon">3</div>
                                                                <div class="title">On Going</div>
                                                            </li>
                                                        @endif
                        
                                                        @if ($orders->status == 'Completed')
                                                            <li>
                                                                <div class="icon" style="background:#e62e04;color:white">4</div>
                                                                <div class="title" style="color:red">Delivered</div>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <div class="icon">4</div>
                                                                <div class="title">Canceled</div>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                                <div class="card-body">
                                                     <table class="">
                                                        <tr>
                                                            <th style="width: 60%">Product</th>
                                                            <th style="width: 20%">Quantity</th>
                                                            <th style="width: 20%">Price</th>
                                                        </tr>
                                                        <?php
                                                        $products = DB::table('orderproducts')->where('order_id', '=', $orders->id)->get();
                                                        foreach ($products as $product) { ?>
                                                        <tr>
                                                            <td><img src="{{asset(App\Models\Product::where('id',$product->product_id)->first()->ProductImage)}}" style="width:60px">
                                                                {{ $product->productName }} @if ($product->color && $product->size)
                                                                    (Colour: {{ $product->color }} , Size: {{ $product->size }})
                                                                @elseif($product->size)
                                                                    (Size: {{ $product->size }})
                                                                @elseif($product->color)
                                                                    (Size: {{ $product->color }})
                                                                @else
                                                                @endif
                                                            </td>
                                                            <td>{{ $product->quantity }}</td>
                                                            <td>{{ $product->productPrice }} Tk</td>
                                                        </tr>
                                                        <?php } ?>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="1" style="border: none;"></td>
                                                                <th>Delivery : </th>
                                                                <td>{{ $orders->deliveryCharge }} Tk</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="1" style="border: none;"></td>
                                                                <th>Total : </th>
                                                                <td>{{$orders->subTotal+$orders->paymentAmount+$orders->discountCharge }} Tk</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="1" style="border: none;"></td>
                                                                <th>Discount : </th>
                                                                <td>@if($orders->discountCharge>0){{ $orders->discountCharge }}@else 0 @endif Tk</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="1" style="border: none;"></td>
                                                                <th>Paid : </th>
                                                                <td>@if($orders->paymentAmount>0){{ $orders->paymentAmount }}@else 0 @endif Tk</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="1" style="border: none;"></td>
                                                                <th>Due : </th>
                                                                <td>{{ $orders->subTotal }} Tk</td>
                                                            </tr>
                                            
                                                    </table>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                              
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
