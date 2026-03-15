@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-Order Press Successfully
@endsection
<!-- /.breadcrumb -->
<div class="body-content outer-top-xs">
    <div class="breadcrumb pt-2">
        <div class="container">
            <div class="row">
                <div class="breadcrumb-inner p-0">
                    <ul class="list-inline list-unstyled mb-0">
                        <li><a href="#"
                                style="text-transform: capitalize !important;color: #888;padding-right: 12px;font-size: 12px;">Home
                                > Order > <span class="active"></span>Successfully</span>
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
            @if ($orders == 'Nothing')
            @else
                @if (isset($orders)) 
                    <div class="card mt-4">
                        <div class="card-header py-2 px-3 heading-6 strong-600 clearfix">
                            <div class="float-left" style="color: red;text-align:center">
                                <h2 class="h4 pb-3" style="color:#198754">আপনার অর্ডারটি সফলভাবে সম্পন্ন হয়েছে | আমাদের কল সেন্টার থেকে ফোন করে আপনার অর্ডারটি কনফার্ম করা হবে | যেকোনো প্রয়োজনে সরাসরি যোগাযোগ করুন আমাদের এই নাম্বারে : {{ App\Models\Basicinfo::first()->phone_one }}</h2>
                                <a class="btn btn-primary mt-3" style="background: black; color: white;" href="{{url('/')}}">প্রোডাক্ট বাছাই করুন</a>
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
                                                            <th style="width: 50%">Product</th>
                                                            <th style="width: 30%">Quantity</th>
                                                            <th style="width: 20%">Price</th>
                                                        </tr>
                                                        <?php
                                                        $products = DB::table('orderproducts')->where('order_id', '=', $orders->id)->get();
                                                        foreach ($products as $product) { ?>
                                                        <tr>
                                                            <td>
                                                                <a href="{{url('product',App\Models\Product::where('id',$product->product_id)->first()->ProductSlug)}}">
                                                                    <img src="{{asset(App\Models\Product::where('id',$product->product_id)->first()->ProductImage)}}" style="width:60px">
                                                                    {{ $product->productName }} @if ($product->color && $product->size)
                                                                        (Colour: {{ $product->color }} , Size: {{ $product->size }})
                                                                    @elseif($product->size)
                                                                        (Size: {{ $product->size }})
                                                                    @elseif($product->color)
                                                                        (Size: {{ $product->color }})
                                                                    @else
                                                                    @endif
                                                                </a>
                                                            </td>
                                                            <td>{{ $product->quantity }}</td>
                                                            <td>{{ $product->productPrice }} Tk</td>
                                                        </tr>
                                                        <?php } ?>
                                                        <tfoot>
                                                            <tr>
                                                                <td class="d-none d-lg-block" colspan="1" style="border: none;"></td>
                                                                <th>Courier Charge : </th>
                                                                <td>{{ $orders->deliveryCharge }} Tk</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="d-none d-lg-block" colspan="1" style="border: none;"></td>
                                                                <th>Total : </th>
                                                                <td>{{$orders->subTotal+$orders->paymentAmount-$orders->discountCharge }} Tk</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="d-none d-lg-block" colspan="1" style="border: none;"></td>
                                                                <th>Discount : </th>
                                                                <td>@if($orders->discountCharge>0){{ $orders->discountCharge }}@else 0 @endif Tk</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="d-none d-lg-block" colspan="1" style="border: none;"></td>
                                                                <th>Paid : </th>
                                                                <td>@if($orders->paymentAmount>0){{ $orders->paymentAmount }}@else 0 @endif Tk</td>
                                                            </tr> 
                                                           <tr>
                                                                <td class="d-none d-lg-block" colspan="1" style="border: none;"></td>
                                                                <th>Vat : </th>
                                                                <td>{{$orders->vat}} Tk</td>
                                                            </tr> 
                                                            <tr>
                                                                <td  class="d-none d-lg-block" colspan="1" style="border: none;"></td>
                                                                <th>Cash On Delivery : </th>
                                                                <td>{{$orders->subTotal+$orders->vat+$orders->paymentAmount+$orders->discountCharge }} Tk</td>
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
                @else
                    <div class="card mt-4">
                        <div class="card-header py-2 px-3 heading-6 strong-600 clearfix">
                            <div class="float-left" style="color: red;text-align:center">No Records Found.Please call
                                our customer care or use Live Chat
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </section>

</div>

<script>
    // Clear the previous ecommerce object.
    dataLayer.push({ ecommerce: null });

    // Push the begin_checkout event to dataLayer.
    dataLayer.push({
        event: "purchase", 
        ecommerce: { 
            currency: "BDT",  
            value: Number("<?php echo $orders->subTotal ?>"),
            shipping: "<?php echo $orders->deliveryCharge ?>",
            tax:0,
            coupon:"",
            affiliation:"", 
            external_id :"<?php echo $orders->id ?>",
            transaction_id:"<?php echo 'TRX45324'.$orders->id ?>", 
            items: [@foreach ($products as $cartInfo)
                {
                    item_name: "{{$cartInfo->productName}}",
                    item_id: "{{$cartInfo->product_id}}",
                    price: Number("{{$cartInfo->productPrice}}"),  
                    item_size: "{{$cartInfo->size}}",
                    item_color: "{{$cartInfo->color}}",
                    currency: "BDT",
                    quantity: {{$cartInfo->quantity ?? 0}}
                },
            @endforeach],
            more:[
                {
                    Customer_Name:"<?php echo $orders->customers->customerName ?>", 
                    Customer_Address:"<?php echo $orders->customers->customerAddress ?>", 
                    Customer_Phone_Number:"<?php echo $orders->customers->customerPhone ?>", 
                    Customer_Country:'Bangladesh', 
                    Customer_Visitor_ID :"<?php echo $orders->customers->id ?>", 
                    payment_method:"Cash On Delivery", 
                }
            ]
        }
    });
    
</script>

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

@endsection
