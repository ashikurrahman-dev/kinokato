@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-User Dashboard
@endsection

<style>
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
        <div class="container">
            <div class="row">

                <div class="mb-2 col-lg-2 col-6">
                    <div class="text-center card bg-light">
                        <i class="pt-4 fa-solid fa-cart-shopping text-dark" style="font-size: 26px;"></i>
                        <p class="pt-4 pb-3 mb-0 text-dark"> <span>{{ Cart::count() }}</span> Cart</p>
                    </div>
                </div>

                <div class="mb-2 col-lg-2 col-6">
                    <a href="{{ url('user/purchase_history') }}">
                        <div class="pt-4 text-center card bg-light" style="font-size: 26px;">
                            <i class="fa-solid fa-building text-dark"></i>
                            <p class="pt-4 pb-3 mb-0 text-dark">
                                <span>{{ App\Models\Order::where('user_id', Auth::user()->id)->get()->count() }}</span>
                                Order
                            </p>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
