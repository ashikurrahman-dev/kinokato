@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-Track Order
@endsection

<!-- /.breadcrumb -->
<div class="body-content outer-top-xs">
    <div class="pt-2 breadcrumb">
        <div class="container">
            <div class="row">
                <div class="p-0 breadcrumb-inner">
                    <ul class="mb-0 list-inline list-unstyled">
                        <li><a href="#"
                                style="text-transform: capitalize !important;color: #888;padding-right: 12px;font-size: 12px;">Home
                                > Track > <span class="active"></span>Order</span>
                            </a></li>
                    </ul>
                </div>
                <!-- /.breadcrumb-inner -->
            </div>
        </div>
        <!-- /.container -->
    </div>
    <section class="mt-1 mb-3">
        <div class="container">
            <div class="px-2 py-1 bg-white shadow-sm p-md-3">
                <div class="pb-4 search-area">
                    <h4 class="pb-4 m-0 text-center"> <b>Track You Order Now</b> </h4>
                    <form method="POST" action="{{ url('track-now') }}">
                        @csrf
                        <div class="control-group d-flex">
                            <input class="m-0 search-field" name="invoiceID" placeholder="Enter your phone number">
                            <button type="submit" class="search-button"></button>
                        </div>
                    </form>
                </div>
            </div>
            @if(count($orders)>0)
                @forelse($orders as $order)
                    <div class="m-auto col-md-8">
                        <div class="mt-4 card">
                            <div class="clearfix px-3 py-2 card-header heading-6 strong-600">
                                <div class="float-left" style="color: red;text-align:center"> <b>Current Status : Order has been {{$order->status}}</b> </div>
                            </div>
                            <div class="pb-0 card-body">
                                <table class="table details-table" style="border:none">
                                    <tbody style="border:none">
                                        <tr style="border:none">
                                            <td class="w-50 strong-600" style="border:none;font-size: 18px;">Order ID:</td>
                                            <td style="border:none;font-size: 18px;">{{ $order->invoiceID }}</td>
                                        </tr>
                                        <tr style="border:none">
                                            <td class="w-50 strong-600" style="border:none;font-size: 18px;">Order Status:</td>
                                            <td style="border:none;font-size: 18px;">Order has been {{ $order->status }}</td>
                                        </tr>
                                        <tr style="border:none">
                                            <td class="w-50 strong-600" style="border:none;font-size: 18px;">Customer Name:</td>
                                            <td style="border:none;font-size: 18px;">{{ $order->customers->customerName }}</td>
                                        </tr>
                                        <tr style="border:none">
                                            <td class="w-50 strong-600" style="border:none;font-size: 18px;">Customer Phone:</td>
                                            <td style="border:none;font-size: 18px;">{{ $order->customers->customerPhone }}</td>
                                        </tr>
                                        <tr style="border:none">
                                            <td class="w-50 strong-600" style="border:none;font-size: 18px;">Customer address:</td>
                                            <td style="border:none;font-size: 18px;">{{ $order->customers->customerAddress }} </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="card">
                                    <div class="clearfix px-3 py-2 card-header heading-6 strong-600">
                                        <ul class="clearfix process-steps">
                                            @if ($order->status == 'Pending' || $order->status == 'Hold')
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
                                            @if ($order->status == 'Ready to Ship' ||
                                                $order->status == 'Packaging')
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

                                            @if ($order->status == 'Shipped')
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

                                            @if ($order->status == 'Completed')
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
                                            $products = DB::table('orderproducts')->where('order_id', '=', $order->id)->get();
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
                                                    <td>{{ $order->deliveryCharge }} Tk</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="1" style="border: none;"></td>
                                                    <th>Total : </th>
                                                    <td>{{$order->subTotal+$order->paymentAmount+$order->discountCharge }} Tk</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="1" style="border: none;"></td>
                                                    <th>Discount : </th>
                                                    <td>@if($order->discountCharge>0){{ $order->discountCharge }}@else 0 @endif Tk</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="1" style="border: none;"></td>
                                                    <th>Paid : </th>
                                                    <td>@if($order->paymentAmount>0){{ $order->paymentAmount }}@else 0 @endif Tk</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="1" style="border: none;"></td>
                                                    <th>Due : </th>
                                                    <td>{{ $order->subTotal }} Tk</td>
                                                </tr>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            @empty
                    <div class="mt-4 card">
                        <div class="clearfix px-3 py-2 card-header heading-6 strong-600">
                            <div class="float-left" style="color: red;text-align:center">No Records Found.Please call
                                our customer care or use Live Chat
                            </div>
                        </div>
                    </div>
            @endforelse
            @else

            @endif
        </div>
    </section>

</div>

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
        background-color: #000339;
        border: 1px solid #000339;
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

@endsection
