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

<div class="outer-top-xs outer-bottom-xs">
    <div class="container pt-4 mt-4">
        <div class="row">
            <div class="col-lg-13">
                <div class="p-2 pt-0">
                    <div class="container">
                        <div class="row">
                            @forelse ($orders as $order)
                                <div class="col-lg-12 col-12 mb-2" id="mainitem">
                                    <a href="{{ url('order-details') }}/{{ $order->invoiceID }}">
                                        <div class="card">
                                            <div style="display: flex;justify-content:space-between">

                                                <div class="info ps-2">
                                                    <p class="m-0 pt-2" style="color:black ;">
                                                        <b>ID: <span>{{ $order->invoiceID }}</span></b>
                                                    </p>
                                                    <p class="m-0" style="color:rgb(26, 142, 214) ;">
                                                        <b style="color: gray">Ordered:
                                                            <span>{{ $order->created_at->format('d M') }},
                                                                {{ $order->created_at->format('Y') }}
                                                                ,{{ $order->created_at->format('H:i:s') }}</span></b>
                                                    </p>
                                                    <p class="m-0 pb-2" style="color:rgb(26, 142, 214) ;">
                                                        <b>Delivery:
                                                            <span> With in 3 to 7 days.</span></b>
                                                    </p>
                                                </div>
                                                <div class="delivery"
                                                    style="padding-top: 10px;text-align: right;padding-right: 10px;">
                                                    <p class="m-0 pt-2" style="color:black ;">
                                                        <b>৳ <span>{{ $order->subTotal }}</span></b>
                                                    </p>
                                                    <h6 class="m-0" style="color: rgb(22, 128, 6);">
                                                        <b>{{ $order->status }}</b>
                                                    </h6>
                                                </div>
                                            </div>

                                        </div>
                                    </a>
                                </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
