<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <link href="{{ asset('public/admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/admin/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        * {
            margin: 0px;
            padding: 0px;
        }

        table {
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid gray;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        table.table-with-info tr:nth-child(even) {
            background-color: #eee;
        }

        table.table-with-info tr:nth-child(odd) {
            background-color: #fff;
        }

        table.table-with-info th {
            background-color: black;
            color: white;
        }

        hr {
            border-top: 1px dashed red;
        }

        table.table-with-info,
        table.table-with-info td,
        table.table-with-info th {
            border: 0px solid black;
        }

        @media print {

            .section {
                display: flex;
                flex-direction: column;
                width: 100%;
                height: 100vh;
                justify-content: space-between;
            }
        }
    </style>
</head>

<body>
    <?php
    use Illuminate\Support\Facades\DB;
    $orderIDs = unserialize($invoice->order_id); ?>


    <?php $count = 1; foreach ($orderIDs as $orderID) {


    $order  = DB::table('orders')
        ->select('orders.*', 'customers.customerName', 'customers.customerPhone', 'customers.customerAddress', 'couriers.courierName', 'cities.cityName', 'zones.zoneName', 'users.name', 'paymenttypes.paymentTypeName', 'payments.paymentNumber')
        ->leftJoin('customers', 'orders.id', '=', 'customers.order_id')
        ->leftJoin('couriers', 'orders.courier_id', '=', 'couriers.id')
        ->leftJoin('paymenttypes', 'orders.payment_type_id', '=', 'paymenttypes.id')
        ->leftJoin('payments', 'orders.payment_id', '=', 'payments.id')
        ->leftJoin('cities', 'orders.city_id', '=', 'cities.id')
        ->leftJoin('zones', 'orders.zone_id', '=', 'zones.id')
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')
        ->where('orders.id', '=', $orderID)->get()->first();
    if($count == 1) {
        echo '<div class="section">';
        $last = true;
    }
     ?>
    <div class="div-section" style="    font-size: 17px;">
        <table class="table-with-info" cellspacing="0" cellpadding="0">
            <tr>
                 <td style="width: 25%;text-align:left">
                    <h4>CUSTOMER INFO</h4>
                    Name:&nbsp;&nbsp;{{ $order->customerName }} <br>
                    Phone:&nbsp;&nbsp;{{ $order->customerPhone }}<br>
                    Address:&nbsp;&nbsp;{{ $order->customerAddress }}

                </td>


                <td style="width: 50%;text-align:center">

                    <h4>Invoice #{{ $order->invoiceID }}</h4>
                    <?php
                    echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->invoiceID, 'C39') . '" alt="barcode"   />';
                    ?>
                    <br>Visit: www.premiershopbd.com

                </td>
                <td style="width: 25%;">
                    <strong>
                    <img src="{{asset(App\Models\Basicinfo::first()->logo)}}" style="width:70%;margin-bottom:10px">
                    </strong>
                    <p>
                        Address : {{App\Models\Basicinfo::first()->address}}
                        <br>HotLine: {{App\Models\Basicinfo::first()->phone_one}}

                    </p>
                </td>

            </tr>
        </table>
        <table class="table-borde">
            <tr style="">
                <th style="width: 10%;text-align:center">Image</th>
                <th style="width: 40%;text-align:center">Product Name</th>
                <th style="width: 10%;text-align:center">Size</th>
                <th style="width: 10%;text-align:center">Varient</th>
                <th style="width: 10%;text-align:center">Quantity</th>
                <th style="width: 20%;text-align:center">Price</th>
            </tr>
            <?php
            $products = DB::table('orderproducts')->where('order_id', '=', $orderID)->get();
            foreach ($products as $product) { ?>
            <tr>
                <td><img src="{{asset(App\Models\Product::where('id',$product->product_id)->first()->ProductImage)}}" style="width:60px"></td>
                <td>{{ $product->productName }}</td>
                <td style="text-align:center">{{ $product->size }}</td>
                <td style="text-align:center">
                    @if ($product->color && $product->sigment)
                        {{ $product->color }}, {{$product->sigment}}
                    @else
                    @endif
                </td>
                <td style="text-align:center">{{ $product->quantity }}</td>
                <td style="text-align:center;">{{ $product->productPrice }} Tk</td>
            </tr>
            <?php } ?>
            <tfoot>
                <tr>
                    <td colspan="3" style="border: none;"></td>
                    <th colspan="2">Courier Charge : </th>
                    <td style="text-align:center">{{ $order->deliveryCharge }} Tk</td>
                </tr>
                <tr>
                    <td colspan="3" style="border: none;text-align:center;">
                    </td>
                    <th colspan="2">Total : </th>
                    <td style="text-align:center">{{$order->subTotal+$order->paymentAmount+$order->discountCharge }} Tk</td>
                </tr>
                <tr>
                    <td colspan="3" style="border: none;text-align:center;font-size:26px;">
                        @if($order->consigment_id) Courier ID: &nbsp; <span style="font-size:26px;font-weight:bold">{{$order->consigment_id}}</span> @endif
                    </td>
                    <th colspan="2">Discount : </th>
                    <td style="text-align:center">@if($order->discountCharge>0){{ $order->discountCharge }}@else 0 @endif Tk</td>
                </tr>
                <tr>
                    <td colspan="3" style="border: none;">

                    </td>
                    <th colspan="2">Paid : </th>
                    <td style="text-align:center">@if($order->paymentAmount>0){{ $order->paymentAmount }}@else 0 @endif Tk</td>
                </tr>
                <tr>
                    <td colspan="3" style="border: none;">
                        @if(isset($order->customerNote)) <p class="mb-0 text-center" style="font-size:20px;">[ Note: {{$order->customerNote}} ]</p> @endif
                    </td>
                    <th colspan="2">Cash on Delivery : </th>
                    <td style="text-align:center">{{ $order->subTotal }} Tk</td>
                </tr>

        </table>
        <br>
        <div style=" display: flex; flex-direction: row; justify-content: space-between; ">
            <p>Order Date : {{ $order->orderDate }} ,&nbsp;Payment Method : Cash On Delivery </p>
            <p>Source: {{$order->web_id}}</p>
            <p>
                @php
                    $ad=App\Models\Admin::where('id',$order->admin_id)->first();
                @endphp

                @if(isset($ad))
                    Order Recived By : {{ $ad->name }}
                @endif
                </p>
        </div>
    </div>
    <hr>
    <div class="div-section" style="    font-size: 17px;">
        <table class="table-with-info" cellspacing="0" cellpadding="0">
            <tr>
                 <td style="width: 25%;text-align:left">
                    <h4>CUSTOMER INFO</h4>
                    Name:&nbsp;&nbsp;{{ $order->customerName }} <br>
                    Phone:&nbsp;&nbsp;{{ $order->customerPhone }}<br>
                    Address:&nbsp;&nbsp;{{ $order->customerAddress }}

                </td>


                <td style="width: 50%;text-align:center">

                    <h4>Invoice #{{ $order->invoiceID }}</h4>
                    <?php
                    echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->invoiceID, 'C39') . '" alt="barcode"   />';
                    ?>
                    <br>Visit: www.premiershopbd.com

                </td>
                <td style="width: 25%;">
                    <strong>
                    <img src="{{asset(App\Models\Basicinfo::first()->logo)}}" style="width:70%;margin-bottom:10px">
                    </strong>
                    <p>
                        Address : {{App\Models\Basicinfo::first()->address}}
                        <br>HotLine: {{App\Models\Basicinfo::first()->phone_one}}

                    </p>
                </td>

            </tr>
        </table>
        <table class="table-borde">
            <tr style="">
                <th style="width: 10%;text-align:center">Image</th>
                <th style="width: 40%;text-align:center">Product Name</th>
                <th style="width: 10%;text-align:center">Size</th>
                <th style="width: 10%;text-align:center">Varient</th>
                <th style="width: 10%;text-align:center">Quantity</th>
                <th style="width: 20%;text-align:center">Price</th>
            </tr>
            <?php
            $products = DB::table('orderproducts')->where('order_id', '=', $orderID)->get();
            foreach ($products as $product) { ?>
            <tr>
                <td><img src="{{asset(App\Models\Product::where('id',$product->product_id)->first()->ProductImage)}}" style="width:60px"></td>
                <td>{{ $product->productName }}</td>
                <td style="text-align:center">{{ $product->size }}</td>
                <td style="text-align:center">
                    @if ($product->color && $product->sigment)
                        {{ $product->color }}, {{$product->sigment}}
                    @else
                    @endif
                </td>
                <td style="text-align:center">{{ $product->quantity }}</td>
                <td style="text-align:center;">{{ $product->productPrice }} Tk</td>
            </tr>
            <?php } ?>
            <tfoot>
                <tr>
                    <td colspan="3" style="border: none;"></td>
                    <th colspan="2">Courier Charge : </th>
                    <td style="text-align:center">{{ $order->deliveryCharge }} Tk</td>
                </tr>
                <tr>
                    <td colspan="3" style="border: none;text-align:center;">
                    </td>
                    <th colspan="2">Total : </th>
                    <td style="text-align:center">{{$order->subTotal+$order->paymentAmount+$order->discountCharge }} Tk</td>
                </tr>
                <tr>
                    <td colspan="3" style="border: none;text-align:center;font-size:26px;">
                        @if($order->consigment_id) Courier ID: &nbsp; <span style="font-size:26px;font-weight:bold">{{$order->consigment_id}}</span> @endif
                    </td>
                    <th colspan="2">Discount : </th>
                    <td style="text-align:center">@if($order->discountCharge>0){{ $order->discountCharge }}@else 0 @endif Tk</td>
                </tr>
                <tr>
                    <td colspan="3" style="border: none;">

                    </td>
                    <th colspan="2">Paid : </th>
                    <td style="text-align:center">@if($order->paymentAmount>0){{ $order->paymentAmount }}@else 0 @endif Tk</td>
                </tr>
                <tr>
                    <td colspan="3" style="border: none;">
                        @if(isset($order->customerNote)) <p class="mb-0 text-center" style="font-size:20px;">[ Note: {{$order->customerNote}} ]</p> @endif
                    </td>
                    <th colspan="2">Cash on Delivery : </th>
                    <td style="text-align:center">{{ $order->subTotal }} Tk</td>
                </tr>

        </table>
        <br>
        <div style=" display: flex; flex-direction: row; justify-content: space-between; ">
            <p>Order Date : {{ $order->orderDate }} ,&nbsp;Payment Method : Cash On Delivery </p>
            <p>Source: {{$order->web_id}}</p>
            <p>
                @php
                    $ad=App\Models\Admin::where('id',$order->admin_id)->first();
                @endphp

                @if(isset($ad))
                    Order Recived By : {{ $ad->name }}
                @endif
                </p>
        </div>
    </div>
    <hr>
    <?php
    if($count == 2 ) {
        echo '</div>';
        $count = 1;
    }else{
        $count++;
    }
    } ?>
    </div>

    <script src="{{ asset('public/admin/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/admin/js/vendor.min.js') }}"></script>
    <!-- App js -->
    <script src="{{ asset('public/admin/js/app.min.js') }}"></script>
    <script>
        $(function() {
            window.print();
            window.onfocus = function() {
                window.close();
            }
            window.onafterprint = function() {
                window.close();
            };


        });
    </script>
</body>

</html>
