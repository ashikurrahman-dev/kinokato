@extends('webview.master')

@section('maincontent')
    @section('title')
        {{ env('APP_NAME') }}-{{ $productdetails->ProductName }}
    @endsection

    @section('meta')
        <meta name="description"
            content="Online shopping in Bangladesh for beauty products, men, women, kids, fashion items, clothes, electronics, home appliances, gadgets, watch, many more.">
        <meta name="keywords"
            content="{{ env('APP_NAME') }}, online store bd, online shop bd, Organic fruits, Thai, UK, Korea, China, cosmetics, Jewellery, bags, dress, mobile, accessories, automation Products,">
        <meta itemprop="name" content="{{ $productdetails->ProductName }}">
        <meta itemprop="description"
            content="Best online shopping in Bangladesh for beauty products, men, women, kids, fashion items, clothes, electronics, home appliances, gadgets, watch, many more.">
        <meta itemprop="image" content="{{ env('APP_URL') }}{{ $productdetails->ProductImage }}">

        <meta property="og:url" content="{{ env('APP_URL') }}view-product/{{ $productdetails->ProductSlug }}">
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ $productdetails->ProductName }}">
        <meta property="og:description"
            content="Online shopping in BD for beauty products, men, women, kids, fashion items, clothes, electronics, home appliances, gadgets, watch, many more.">
        <meta property="og:image" content="{{ env('APP_URL') }}{{ $productdetails->ProductImage }}">
        <meta property="image" content="{{ env('APP_URL') }}{{ $productdetails->ProductImage }}" />
        <meta property="url" content="{{ env('APP_URL') }}view-product/{{ $productdetails->ProductSlug }}">
        <meta itemprop="image" content="{{ env('APP_URL') }}{{ $productdetails->ProductImage }}">
        <meta property="twitter:card" content="{{ env('APP_URL') }}{{ $productdetails->ProductImage }}" />
        <meta property="twitter:title" content="{{ $productdetails->ProductName }}" />
        <meta property="twitter:url" content="{{ env('APP_URL') }}product/{{ $productdetails->ProductSlug }}">
        <meta name="twitter:image" content="{{ env('APP_URL') }}{{ $productdetails->ProductImage }}">
    @endsection
    <style>
        @media only screen and (max-width: 600px) {

            .description img {
                width: 260px !important;
            }
        }

        .star {
            font-size: 8px !important;
        }

        .animate-charcter {
            text-transform: uppercase;
            background-image: linear-gradient(-225deg, #231557 0%, #44107a 29%, #ff1361 67%, #fff800 100%);
            background-size: auto auto;
            background-clip: border-box;
            background-size: 200% auto;
            color: #fff;
            background-clip: text;
            text-fill-color: transparent;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: textclip 2s linear infinite;
        }

        #formTextBtn {
            width: 92%;
            font-size: 18px;
            padding: 4px;
            border-radius: 0;
            border-image: linear-gradient(to right, #2d2dff, #ff2601) 1;
        }

        #formText {
            width: 95%;
            font-size: 18px;
            padding: 4px;
            border-radius: 0;
            border-image: linear-gradient(to right, #2d2dff, #ff2601) 1;
        }

        .stss {
            font-size: 14px;
            padding: 2px 5px;
            background: green;
            border-radius: 50%;
            margin-right: 2px;
            color: white;
            font-weight: bold;
        }

        .sizetext {
            color: 000;
            background: #fff;
        }

        .colortext {
            color: #000;
            background: #fff;
        }

        #buttonplus {
            font-size: 18px;
            border: 1px solid;
            padding: 3px 14px;
            border-radius: 0px;
            height: 34px;
            margin: 0;
            line-height: 4px;
            color: #000;
            border: none;
        }

        #buttonminus {
            font-size: 18px;
            padding: 3px 14px;
            border-radius: 0px;
            height: 34px;
            margin: 0;
            line-height: 4px;
            color: #000;
            border: none;
        }

        #buttonminus:hover {
            background: #db4444;
            color: #fff;
        }

        #buttonplus:hover {
            background: #db4444;
            color: #fff;
        }

        #checked {
            color: orange;
        }

        .delivery-box {
            border: 1px solid #ccc;
            border-radius: 5px;
            max-width: 100%;
        }

        .delivery-item {
            padding: 20px;
        }

        .delivery-item+.delivery-item {
            border-top: 1px solid #ccc;
        }

        .delivery-icon {
            font-size: 28px;
            color: #000;
        }

        .delivery-title {
            font-weight: 600;
            font-size: 18px;
            text-align: start;
        }

        .delivery-link {
            color: #000;
            text-decoration: underline;
            font-size: 14px;
            display: flex;
        }
        .product-wishlist{
            border: 1px solid #000;
            padding: 6px;
            border-radius: 5px;
            margin-right: 15px;
        }
    </style>
    <!-- Body -->

    <div class="mt-2 body-content" id="top-banner-and-menu">
        <div class='container' id="loadproduct">
            <div class='row single-product'>
                <div class='p-0 col-md-12'>
                    <div class="detail-block">
                        <div class="row wow fadeInUp">

                            <div class="col-xs-12 col-sm-12 col-md-6 gallery-holder">
                                <div class="product-item-holder size-big single-product-gallery small-gallery">

                                    <!--@if (json_decode($productdetails->PostImage))-->
                                    <!--    <div id="sync1" class="owl-carousel owl-theme">-->
                                    <!--        <div class="items">-->
                                    <!--            <img class="w-100 h-100" src="{{ asset($productdetails->ProductImage) }}"-->
                                    <!--                alt="" style="border-radius: 4px;">-->
                                    <!--        </div>-->
                                    <!--        @forelse (json_decode($productdetails->PostImage) as $image)-->
                                    <!--            <div class="items">-->
                                    <!--                <img class="w-100 h-100"-->
                                    <!--                    src="{{ asset('public/images/product/slider') }}/{{ $image }}"-->
                                    <!--                    alt="" style="border-radius: 4px;">-->
                                    <!--            </div>-->
                                    <!--        @empty-->
                                    <!--        @endforelse-->
                                    <!--    </div>-->
                                    <!--    <div id="sync2" class="owl-carousel owl-theme" style="padding-top: 10px;">-->
                                    <!--        <div class="items">-->
                                    <!--            <img class="w-100 h-100"-->
                                    <!--                style="padding:6px;border:1px solid;border-radius: 4px;"-->
                                    <!--                src="{{ asset($productdetails->ProductImage) }}" alt="">-->
                                    <!--        </div>-->
                                    <!--        @forelse (json_decode($productdetails->PostImage) as $image)-->
                                    <!--            <div class="items">-->
                                    <!--                <img class="w-100 h-100"-->
                                    <!--                    style="padding:6px;border:1px solid;border-radius: 4px;"-->
                                    <!--                    src="{{ asset('public/images/product/slider') }}/{{ $image }}"-->
                                    <!--                    alt="">-->
                                    <!--            </div>-->
                                    <!--        @empty-->
                                    <!--        @endforelse-->
                                    <!--    </div>-->
                                    <!--@else-->
                                    <!--    <div class="items">-->
                                    <!--        <img class="w-100 h-100" src="{{ asset($productdetails->ProductImage) }}"-->
                                    <!--            alt="" style="border-radius: 4px;">-->
                                    <!--    </div>-->
                                    <!--@endif-->
                                    @if (json_decode($productdetails->PostImage))
                                        <div id="sync1" class="owl-carousel owl-theme">
                                            <div class="items">
                                                <img class="w-100 h-100 block__pic"
                                                    src="{{ asset($productdetails->ProductImage) }}" alt=""
                                                    style="border-radius: 4px;">
                                            </div>
                                            @forelse (json_decode($productdetails->PostImage) as $image)
                                                <div class="items">
                                                    <img class="w-100 h-100 block__pic"
                                                        src="{{ asset('public/images/product/slider') }}/{{ $image }}" alt=""
                                                        style="border-radius: 4px;">
                                                </div>
                                            @empty
                                            @endforelse
                                        </div>

                                        <div id="sync2" class="owl-carousel owl-theme" style="padding-top: 10px;">
                                            <div class="items">
                                                <img class="w-100 h-100 "
                                                    style="padding:6px;border:1px solid;border-radius: 4px;"
                                                    src="{{ asset($productdetails->ProductImage) }}" alt="">
                                            </div>
                                            @forelse (json_decode($productdetails->PostImage) as $image)
                                                <div class="items">
                                                    <img class="w-100 h-100"
                                                        style="padding:6px;border:1px solid;border-radius: 4px;"
                                                        src="{{ asset('public/images/product/slider') }}/{{ $image }}" alt="">
                                                </div>
                                            @empty
                                            @endforelse
                                        </div>
                                    @else
                                        <div class="items">
                                            <img class="w-100 h-100" src="{{ asset($productdetails->ProductImage) }}" alt=""
                                                style="border-radius: 4px;">
                                        </div>
                                    @endif

                                </div>
                                <!-- /.single-product-gallery -->
                            </div>
                            <!-- /.gallery-holder -->
                            <div class="col-sm-12 col-md-6 product-info-block" id="paddingnone">
                                <div class="product-info" id="productinfo">
                                    <h1 class="name"
                                        style="margin-top:16px !important;padding-bottom: 6px;font-size: 20px !important; line-height: 25px;">
                                        {{ $productdetails->ProductName }}
                                    </h1>

                                    <div class="stock-container info-container m-t-10" style="margin-top:5px;">
                                        <div class="row" style="margin-bottom:5px;">
                                            <div class="col-12">
                                                @if (App\Models\Size::where('product_id', $productdetails->id)->first())
                                                    <div class="product-price strong-700"
                                                        style="color:black;font-weight:bold;padding-top: 6px;"
                                                        id="productPriceAmount">
                                                        <span
                                                            id="salePrice">{{ App\Models\Size::where('product_id', $productdetails->id)->first()->SalePrice }}</span>
                                                        TK
                                                        @if (App\Models\Size::where('product_id', $productdetails->id)->first()->Discount > 0)
                                                            &nbsp;<del class="old-product-price strong-400"
                                                                style="color: #fe0909;font-size: 20px;">{{ round(App\Models\Size::where('product_id', $productdetails->id)->first()->RegularPrice) }}</del>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="product-price strong-700"
                                                        style="color:black;font-weight:bold;padding-top: 6px;"
                                                        id="productPriceAmount">
                                                        <span id="salePrice"
                                                            style="color:black;font-weight:bold;">{{ App\Models\Weight::where('product_id', $productdetails->id)->first()->SalePrice }}</span>
                                                        TK
                                                    </div>
                                                @endif
                                            </div>

                                        </div>
                                        <!-- /.row -->
                                    </div>

                                    <div class="mt-2 mb-2 row">
                                        <div class=" col-12 col-md-12 colorpart">
                                            <div id="breaftext">
                                                {!! $productdetails->ProductBreaf !!}
                                            </div>
                                        </div>

                                        @if (empty(json_decode($singlemain->RelatedProductIds)))
                                        @else
                                            <div class="mb-2 col-12 col-md-12 colorpart">
                                                <h4 id="productselect" class="m-0"><b style="font-size:14px">Select Product
                                                        Colours: </b></h4>
                                                <div class="d-flex">
                                                    <div class="colorinfo">
                                                        @forelse (json_decode($singlemain->RelatedProductIds) as $key => $ids)
                                                            @php
                                                                $prodinfo = App\Models\Product::where('id', $ids->productID)
                                                                    ->where('status', 'Active')
                                                                    ->first();
                                                            @endphp
                                                            @if (isset($prodinfo))
                                                                <input type="radio" class="m-0" id="relproduct{{ $prodinfo->id }}"
                                                                    hidden name="relproduct"
                                                                    onclick="getrelproduct('{{ $prodinfo->id }}','{{ $singlemain->id }}')">
                                                                <label class="relproduct ms-0" id="relproducttext{{ $prodinfo->id }}"
                                                                    for="relproduct{{ $prodinfo->id }}"
                                                                    style="border: 1px solid #000;padding: 0px;"
                                                                    onclick="getrelproduct('{{ $prodinfo->id }}','{{ $singlemain->id }}')">
                                                                    <img src="{{ asset($prodinfo->ProductImage) }}" alt=""
                                                                        style="width:60px;">
                                                                </label>
                                                            @endif
                                                        @empty
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if (count($sizesolds) < 1)
                                        @else
                                            <div class="col-12 col-md-12 colorpart">
                                                <h4 id="resellerprice" class="m-0"><b style="font-size:14px">Select Product
                                                        Sizes: </b></h4>
                                                <div class="sizeinfo">
                                                    @forelse ($sizesolds as $sizesold)
                                                        @if ($sizesold->available_stock > 0)
                                                            <input type="hidden" name="regularpriceofsize"
                                                                id="regularpriceofsize{{ $sizesold->id }}"
                                                                value="{{ $sizesold->RegularPrice }}">
                                                            <input type="hidden" name="salepriceofsize"
                                                                id="salepriceofsize{{ $sizesold->id }}"
                                                                value="{{ $sizesold->SalePrice }}">
                                                            <input type="radio" class="m-0" hidden id="size{{ $sizesold->id }}"
                                                                name="size" onclick="getsize('{{ $sizesold->id }}')">
                                                            <label class="sizetext ms-0" id="sizetext{{ $sizesold->id }}"
                                                                for="size{{ $sizesold->id }}"
                                                                style="border: 1px solid #e4e4e4;font-size:18px;font-weight:bold;padding: 0px 8px;border-radius: 2px;margin-right:4px;margin-bottom:4px;"
                                                                onclick="getsize('{{ $sizesold->id }}')">{{ $sizesold->size }}</label>
                                                        @else
                                                            <input type="hidden" name="regularpriceofsize"
                                                                id="regularpriceofsize{{ $sizesold->id }}"
                                                                value="{{ $sizesold->RegularPrice }}">
                                                            <input type="hidden" name="salepriceofsize"
                                                                id="salepriceofsize{{ $sizesold->id }}"
                                                                value="{{ $sizesold->SalePrice }}">
                                                            <input type="radio" class="m-0" hidden id="size{{ $sizesold->id }}"
                                                                name="size">
                                                            <label class="sizetext ms-0" id="sizetext{{ $sizesold->id }}"
                                                                for="size{{ $sizesold->id }}"
                                                                style="border: 1px solid #e4e4e4;    color: rgb(151 150 150) !important;font-size:18px;font-weight:bold;padding: 0px 8px;border-radius: 2px;margin-right:4px;margin-bottom:4px;"><del>{{ $sizesold->size }}
                                                                </del> </label>
                                                        @endif
                                                    @empty
                                                    @endforelse
                                                </div>
                                            </div>
                                        @endif
                                        @if (count($weightolds) < 1)
                                        @else
                                            <div class="col-12 col-md-12 colorpart">
                                                <h4 id="resellerprice" class="m-0"><b style="font-size:14px">সিলেক্ট
                                                        করে কনফার্ম করুনঃ</b></h4>
                                                <div class="sizeinfo">
                                                    @forelse ($weightolds as $weight)
                                                        <input type="hidden" name="regularpriceofsize"
                                                            id="regularpriceofsize{{ $weight->id }}"
                                                            value="{{ $weight->RegularPrice }}">
                                                        <input type="hidden" name="salepriceofsize"
                                                            id="salepriceofsize{{ $weight->id }}" value="{{ $weight->SalePrice }}">
                                                        <input type="hidden" name="weightsigmrnt"
                                                            id="weightsigmrnt{{ $weight->id }}" value="{{ $weight->weight }}">
                                                        <input type="radio" class="m-0" hidden id="size{{ $weight->id }}"
                                                            name="size" onclick="getweight('{{ $weight->id }}')">
                                                        <label class="weighttext ms-0" id="weighttext{{ $weight->id }}"
                                                            for="size{{ $weight->id }}"
                                                            style="border: 1px solid #e4e4e4;font-size:16px;font-weight:bold;padding: 0px 6px;border-radius: 2px;margin-right:4px;margin-bottom:4px;"
                                                            onclick="getweight('{{ $weight->id }}')">{{ $weight->weight }}</label>
                                                    @empty
                                                    @endforelse
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <p for="" style=" margin: 0; padding-top: 1px;text-align:left">Product Code :
                                        {{ $productdetails->ProductSku }}
                                    </p>


                                    <!-- /.stock-container -->
                                    <div class="text-center quantity-container info-container"
                                        style="width: 100%; float: left;">

                                        <div class="row">
                                            <div class="col-4 col-lg-4 my-2">
                                                <div class="pr-2 d-flex"
                                                    style="justify-content: start;padding-right: 4px;border:1px solid #000;width:89%">
                                                    <button class="btn btn-sm" id="buttonminus" onclick="minus()"><i
                                                            class="fa-solid fa-minus"></i></button>
                                                    <div class="cart-quantity" style="height: 33px;">
                                                        <div class="quant-input">
                                                            <input type="text" class="form-control"
                                                                style="font-size: 20px;height: fit-content;height: 34px;padding:0px;text-align: center;border-left:1px solid #000; border-right:1px solid #000;border-radius:0"
                                                                value="1" id="qtyval">
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-sm" id="buttonplus" onclick="plus()"><i
                                                            class="fa-solid fa-plus"></i></button>
                                                </div>
                                            </div>
                                            <div class="col-6 col-lg-6 my-2">
                                                <form name="form" action="{{ url('add-to-cart') }}" id="submitaddtocart"
                                                    method="POST" enctype="multipart/form-data" style="text-align: center;">
                                                    @method('POST')
                                                    @csrf
                                                    <input type="hidden" name="color" id="product_colororder"
                                                        value="{{ $varients[0]->color }}">
                                                    <input type="hidden" name="size" id="product_sizeordernew" value="">
                                                    <input type="hidden" name="sigment" id="product_sigmentorder" value="">
                                                    <input type="hidden" name="price" id="product_priceorder" value="">

                                                    <input type="hidden" name="product_id"
                                                        value=" {{ $productdetails->id }}" hidden>
                                                    <input type="hidden" name="qty" value="1" id="qtyoror">
                                                    <button type="submit"
                                                        class="mb-0 ml-2 btn btn-styled btn-base-1 btn-icon-left strong-700 hov-bounce hov-shaddow buy-now"
                                                        style="background:#db4444;color:white;width: 100%;font-size: 15px;">
                                                        Buy Now
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="col-2 col-lg-2 my-2">
                                                <div class="d-flex justify-content-end">
                                                    <div class="product-wishlist">
                                                        <form action="{{ route('wishlist.add') }}" method="POST" class="p-0 m-0">
                                                            @csrf
                                                            <input type="hidden" name="product_id" value="{{ $productdetails->id }}">

                                                            <button type="submit" class="p-0 m-0 bg-transparent border-0">
                                                                @php
                                                                    $wishlist = session()->get('wishlist', []);
                                                                    $inWishlist = in_array($productdetails->id, $wishlist);
                                                                @endphp

                                                                @if($inWishlist)
                                                                    <i class="fa-solid fa-heart fs-5" style="color: #120D3F"></i>
                                                                @else
                                                                    <i class="fa-regular fa-heart fs-5" style="color: #120D3F"></i>
                                                                @endif
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <section>
                                            <div class="mt-3">
                                                <div class="delivery-box">
                                                    <div class="delivery-item">
                                                        <div class="row align-items-center">

                                                            <div class="col-2 text-center">
                                                                <i class="fa-solid fa-truck delivery-icon"></i>
                                                            </div>

                                                            <div class="col-10">
                                                                <div class="delivery-title">Free Delivery</div>
                                                                <a href="#" class="delivery-link mt-2">Enter your postal
                                                                    code for
                                                                    Delivery Availability</a>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="delivery-item">
                                                        <div class="row align-items-center">
                                                            <div class="col-2 text-center">
                                                                <i class="fa-solid fa-rotate delivery-icon"></i>
                                                            </div>

                                                            <div class="col-10">
                                                                <div class="delivery-title">Return Delivery</div>
                                                                <span class="d-flex mt-2">Free 30 Days Delivery Returns.
                                                                    <a href="#" class="delivery-link">Details</a></span>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </section>
                                    </div>
                                </div>
                                <!-- /.product-info -->
                            </div>
                            <!-- /.col-sm-7 -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
                <!-- /.col -->
                <div class="clearfix"></div>
            </div>
            <div class="row single-product">
                <div class="p-0 col-md-12">
                    <div class="product-tabs inner-bottom-xs wow fadeInUp">
                        <div class="row">
                            <div class="col-sm-12">
                                <ul id="product-tabs" class="nav nav-tabs nav-tab-cell" style="display: inline-flex;">
                                    <li class="active"><a data-bs-toggle="tab" id="istteb"
                                            href="#description">DESCRIPTION</a></li>
                                </ul>
                                <!-- /.nav-tabs #product-tabs -->
                            </div>
                            <div class="col-sm-12">

                                <div class="tab-content">

                                    <div id="description" class="tab-pane active">
                                        <div class="product-tab">
                                            <p class="text">{!! $productdetails->ProductDetails !!}</p>
                                            @if (isset($productdetails->youtube_embade))
                                                <br>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <iframe width="100%" height="315"
                                                            src="https://www.youtube.com/embed/{{ $productdetails->youtube_embade }}">
                                                        </iframe>
                                                    </div>
                                                </div>
                                            @else
                                            @endif
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->



                                </div>
                                <!-- /.tab-content -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>


                </div>
            </div>

            @if (App\Models\Size::where('product_id', $productdetails->id)->first())
                <input type="hidden" id="gtmprice"
                    value="{{ App\Models\Size::where('product_id', $productdetails->id)->first()->SalePrice }}">
                <input type="hidden" id="gtmdiscount"
                    value="{{ App\Models\Size::where('product_id', $productdetails->id)->first()->RegularPrice - App\Models\Size::where('product_id', $productdetails->id)->first()->SalePrice }}">
            @else
                <input type="hidden" id="gtmprice"
                    value="{{ App\Models\Weight::where('product_id', $productdetails->id)->first()->SalePrice }}">
                <input type="hidden" id="gtmdiscount"
                    value="{{ App\Models\Weight::where('product_id', $productdetails->id)->first()->RegularPrice - App\Models\Weight::where('product_id', $productdetails->id)->first()->SalePrice }}">
            @endif

            <input type="hidden" id="gtmproductname" value="{{ $productdetails->ProductName }}">
            <input type="hidden" id="gtmcategory"
                value="{{ App\Models\Category::where('id', $productdetails->category_id)->first()->category_name }}">
            <input type="hidden" id="gtmproductid" value="{{ $productdetails->id }}">
            <input type="hidden" id="gtmproductsku" value="{{ $productdetails->ProductSku }}">

            <script>
                $(document).ready(function () {

                    var sync1 = $("#sync1");
                    var sync2 = $("#sync2");
                    var slidesPerPage = 4; //globaly define number of elements per page
                    var syncedSecondary = true;

                    sync1.owlCarousel({
                        items: 1,
                        slideSpeed: 2000,
                        autoplay: false,
                        dots: false,
                        loop: true,
                        responsiveRefreshRate: 200,
                        navText: [
                            '<svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg>',
                            '<svg width="100%" height="100%" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg>'
                        ],
                    }).on('changed.owl.carousel', syncPosition);

                    sync2
                        .on('initialized.owl.carousel', function () {
                            sync2.find(".owl-item").eq(0).addClass("current");
                        })
                        .owlCarousel({
                            margin: 6,
                            items: slidesPerPage,
                            dots: false,
                            nav: true,
                            smartSpeed: 200,
                            slideSpeed: 500,
                            slideBy: slidesPerPage, //alternatively you can slide by 1, this way the active slide will stick to the first item in the second carousel
                            responsiveRefreshRate: 100
                        }).on('changed.owl.carousel', syncPosition2);

                    function syncPosition(el) {

                        var count = el.item.count - 1;
                        var current = Math.round(el.item.index - (el.item.count / 2) - .5);

                        if (current < 0) {
                            current = count;
                        }
                        if (current > count) {
                            current = 0;
                        }

                        //end block

                        sync2
                            .find(".owl-item")
                            .removeClass("current")
                            .eq(current)
                            .addClass("current");
                        var onscreen = sync2.find('.owl-item.active').length - 1;
                        var start = sync2.find('.owl-item.active').first().index();
                        var end = sync2.find('.owl-item.active').last().index();

                        if (current > end) {
                            sync2.data('owl.carousel').to(current, 100, true);
                        }
                        if (current < start) {
                            sync2.data('owl.carousel').to(current - onscreen, 100, true);
                        }
                    }

                    function syncPosition2(el) {
                        if (syncedSecondary) {
                            var number = el.item.index;
                            sync1.data('owl.carousel').to(number, 100, true);
                        }
                    }

                    sync2.on("click", ".owl-item", function (e) {
                        e.preventDefault();
                        var number = $(this).index();
                        sync1.data('owl.carousel').to(number, 300, true);
                    });


                    $('#AddToCartForm').submit(function (e) {
                        e.preventDefault();
                        $('#processing').css({
                            'display': 'flex',
                            'justify-content': 'center',
                            'align-items': 'center'
                        })
                        $('#processing').modal('show');
                        $.ajax({
                            type: 'POST',
                            url: '{{ url('add-to-cart') }}',
                            processData: false,
                            contentType: false,
                            data: new FormData(this),

                            success: function (data) {
                                updatecart();
                                $.ajax({
                                    type: 'GET',
                                    url: '{{ url('get-cart-content') }}',

                                    success: function (response) {
                                        $('#cartViewModal .modal-body').empty().append(
                                            response);
                                    },
                                    error: function (error) {
                                        console.log('error');
                                    }
                                });
                                $('#processing').modal('hide');
                                $('#cartViewModal').modal('show');
                            },
                            error: function (error) {
                                console.log('error');
                            }
                        });
                    });

                    // document.getElementById("istteb").click();
                    $('#owl-single-product').owlCarousel({
                        items: 1,
                        itemsTablet: [768, 1],
                        itemsDesktop: [1199, 1],
                        autoplay: true,
                        loop: true,
                        autoplayTimeout: 1000,
                        autoplayHoverPause: true,
                        responsiveClass: true,
                        dots: true,

                    });
                });

                var gtmprice = $('#gtmprice').val();
                var gtmqty = $('#proQuantity').val();
                var gtmid = $('#gtmproductid').val();
                var gtmsku = $('#gtmproductsku').val();
                var gtmproductname = $('#gtmproductname').val();
                var gtmcategory = $('#gtmcategory').val();
                var gtmdiscount = $('#gtmdiscount').val();

                window.dataLayer = window.dataLayer || [];
                dataLayer.push({
                    ecommerce: null
                });
                dataLayer.push({
                    event: "view_item",
                    ecommerce: {
                        currency: "BDT",
                        value: gtmprice,
                        items: [{
                            item_id: gtmid,
                            item_name: gtmproductname,
                            index: 0,
                            price: gtmprice,
                            discount: gtmdiscount,
                            item_brand: 'Bluemart.com.bd',
                            item_category: gtmcategory,
                            currency: "BDT",
                            quantity: 1,
                        }]

                    }
                });
            </script>
            <script type="text/javascript">
                $(document).ready(function () {
                    document.getElementById('submitaddtocart').addEventListener('submit', function (event) {
                        window.dataLayer = window.dataLayer || [];
                        dataLayer.push({
                            ecommerce: null
                        });
                        dataLayer.push({
                            event: "add_to_cart",
                            ecommerce: {
                                currency: "BDT",
                                value: gtmprice,
                                items: [{
                                    item_id: gtmid,
                                    item_name: gtmproductname,
                                    index: 0,
                                    price: gtmprice,
                                    discount: gtmdiscount,
                                    item_brand: 'Bluemart.com.bd',
                                    item_category: gtmcategory,
                                    currency: "BDT",
                                    quantity: $('#qtyoror').val()
                                }]
                            }
                        });
                    });
                });
            </script>

        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- ============================================== UPSELL PRODUCTS ============================================== -->
                    <section class="pb-2 section featured-product wow fadeInUp" id="cateoryPro"
                        style="margin-bottom:0px !important">
                        <h3 class="section-title" style="border-bottom: 1px solid #212129; padding: 8px;margin-bottom: 0;">
                            Related
                            products</h3>
                        <div class="owl-carousel related-owl-carousel featured-carousel owl-theme outer-top-xs"
                            id="relatedCarousel">
                            @forelse ($relatedproducts as $promotional)
                                @php
                                    $relatedIds = json_decode($promotional->RelatedProductIds);
                                    $firstpro = null;

                                    if (!empty($relatedIds) && isset($relatedIds[0]->productID)) {
                                        $firstpro = App\Models\Product::with([
                                            'sizes' => function ($query) {
                                                $query
                                                    ->select('id', 'product_id', 'Discount', 'RegularPrice', 'SalePrice')
                                                    ->take(1);
                                            },
                                        ])
                                            ->where('id', $relatedIds[0]->productID)
                                            ->select('id', 'ProductName')
                                            ->first();
                                    }

                                    $review = $firstpro
                                        ? App\Models\Review::where('product_id', $firstpro->id)->avg('rating')
                                        : 0;
                                @endphp

                                @if (isset($firstpro))
                                    <div class="item" id="featuredproduct">
                                        <div class="products best-product">
                                            <div class="product">
                                                <div class="product-micro">
                                                    <div class="row product-micro-row">
                                                        <div class="col-12">
                                                            <div class="product-image" style="position: relative;">
                                                                <div class="text-center image">
                                                                    <div class="frs_discount">
                                                                        <span>
                                                                            {{ ($firstpro->sizes[0]->RegularPrice > 0) ? round((($firstpro->sizes[0]->RegularPrice - $firstpro->sizes[0]->SalePrice) / $firstpro->sizes[0]->RegularPrice) * 100) : 0 }}%<br>
                                                                            <span class="pip_pip_1s">ছাড়</span> </span>
                                                                    </div>
                                                                    <a
                                                                        href="{{ url('view-product/' . $promotional->ProductSlug) }}">
                                                                        <img src="{{ asset($promotional->ProductImage) }}"
                                                                            alt="{{ $promotional->ProductName }}"
                                                                            id="featureimagess">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <!-- /.product-image -->
                                                        </div>
                                                        <!-- /.col -->
                                                        <div class="col-12">
                                                            <div class="p-2 infofe p-md-2"
                                                                style="padding-bottom: 4px !important;background: white;">
                                                                <div class="product-info">
                                                                    <h2 class="name text-truncate" id="f_name"><a
                                                                            href="{{ url('view-product/' . $promotional->ProductSlug) }}"
                                                                            id="f_pro_name">{{ $promotional->ProductName }}</a>
                                                                    </h2>
                                                                </div>


                                                                <div class="d-flex" style="justify-content:space-between">
                                                                    <div class="star" style="padding-top: 5px;">
                                                                        <span
                                                                            style="font-weight: bold;color:black;font-size:10px">({{ App\Models\Review::where('product_id', $promotional->id)->get()->count() }})</span>
                                                                        @if (intval($review) == 1)
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star"></span>
                                                                            <span class="fas fa-star"></span>
                                                                            <span class="fas fa-star"></span>
                                                                            <span class="fas fa-star"></span>
                                                                        @elseif(intval($review) == 2)
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star"></span>
                                                                            <span class="fas fa-star"></span>
                                                                            <span class="fas fa-star"></span>
                                                                        @elseif(intval($review) == 3)
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star"></span>
                                                                            <span class="fas fa-star"></span>
                                                                        @elseif(intval($review) == 4)
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star"></span>
                                                                        @elseif(intval($review) == 5)
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                        @else
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                            <span class="fas fa-star" id="checked"></span>
                                                                        @endif
                                                                    </div>

                                                                </div>

                                                                <div class="price-box">
                                                                    <del class="old-product-price strong-400">৳
                                                                        {{ round($firstpro->sizes[0]->RegularPrice) }}</del>
                                                                    <span class="product-price strong-600">৳
                                                                        {{ round($firstpro->sizes[0]->SalePrice) }}</span>
                                                                </div>

                                                            </div>
                                                            <a href="{{ url('view-product/' . $promotional->ProductSlug) }}">
                                                                <button class="mb-0 btn btn-danger btn-sm btn-block"
                                                                    style="width: 100%;border-radius: 0%;" id="purcheseBtn">অর্ডার
                                                                    করুন</button>
                                                            </a>

                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                    <!-- /.product-micro-row -->
                                                </div>
                                                <!-- /.product-micro -->

                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                            @endforelse
                        </div>
                        <!-- /.home-owl-carousel -->
                    </section>
                    <!-- ============================================== UPSELL PRODUCTS : END ============================================== -->

                </div>
            </div>
        </div>
        <!-- /.container -->
    </div>



    {{-- csrf --}}
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    @if (Auth::id())
        <input type="hidden" name="user_id" id="user_id" value="{{ Auth::id() }}">
    @else
        <input type="hidden" name="user_id" id="user_id">
    @endif

    <script>
        function givereactlike(id) {
            $.ajax({
                type: 'GET',
                url: '{{ url('give/react/') }}' + '/like',
                data: {
                    'user_id': $('#user_id').val(),
                    'product_id': id,
                },

                success: function (data) {
                    if (data.sigment == 'like') {
                        $('#relatedCarousel #likereactof' + id).text(data.total);
                        $('#relatedCarousel #likereactdone' + id).css('color', 'green');
                    } else if (data.sigment == 'unlike') {
                        $('#relatedCarousel #likereactof' + id).text(data.total);
                        $('#relatedCarousel #likereactdone' + id).css('color', 'black');
                    } else {

                    }

                    if (data.sigment == 'like') {
                        $('#productinfo #likereactof' + id).text(data.total);
                        $('#productinfo #likereactdone' + id).css('color', 'green');
                    } else if (data.sigment == 'unlike') {
                        $('#productinfo #likereactof' + id).text(data.total);
                        $('#productinfo #likereactdone' + id).css('color', 'black');
                    } else {

                    }

                },
                error: function (error) {
                    console.log('error');
                }
            });
        }

        function givereactlove(id) {
            $.ajax({
                type: 'GET',
                url: '{{ url('give/react/') }}' + '/love',
                data: {
                    'user_id': $('#user_id').val(),
                    'product_id': id,
                },

                success: function (data) {
                    if (data.sigment == 'love') {
                        $('#relatedCarousel #lovereactof' + id).text(data.total);
                        $('#relatedCarousel #lovereactdone' + id).css('color', 'red');
                    } else {
                        $('#relatedCarousel #lovereactof' + id).text(data.total);
                        $('#relatedCarousel #lovereactdone' + id).css('color', 'black');
                    }
                    if (data.sigment == 'love') {
                        $('#productinfo #lovereactof' + id).text(data.total);
                        $('#productinfo #lovereactdone' + id).css('color', 'red');
                    } else {
                        $('#productinfo #lovereactof' + id).text(data.total);
                        $('#productinfo #lovereactdone' + id).css('color', 'black');
                    }
                },
                error: function (error) {
                    console.log('error');
                }
            });
        }
    </script>

    <script>
        function getrelproduct(product_id, mainpro_id) {
            $('#processing').css({
                'display': 'flex',
                'justify-content': 'center',
                'align-items': 'center'
            })
            $('#processing').modal('show');
            $.ajax({
                type: 'GET',
                url: '{{ url('load/related-product') }}',
                data: {
                    'product_id': product_id,
                    'mainproduct_id': mainpro_id
                },
                success: function (response) {
                    $('#processing').modal('hide');
                    $('#loadproduct').empty().append(response);
                    $('.color-label').css('border', '1px solid #000');
                    $('#relproducttext' + product_id).css('border', '2px solid #db4444');
                },
                error: function (error) {
                    console.log('error');
                }
            });
        }

        function givelike(id) {
            $.ajax({
                type: 'GET',
                url: '{{ url('give/like') }}',
                data: {
                    'user_id': $('#user_id').val(),
                    'product_id': $('#product_id').val(),
                    'review_id': id,
                },

                success: function (data) {
                    if (data.status == 'like') {
                        $('#likeof' + data.review_id).text(data.total);
                        $('#likedone' + data.review_id).css('color', 'green');
                    } else {
                        $('#likeof' + data.review_id).text(data.total);
                        $('#likedone' + data.review_id).css('color', 'black');
                    }
                },
                error: function (error) {
                    console.log('error');
                }
            });
        }

        function giveshare(id) {
            $.ajax({
                type: 'GET',
                url: '{{ url('give/share') }}',
                data: {
                    'user_id': $('#user_id').val(),
                    'product_id': $('#product_id').val(),
                    'review_id': id,
                },

                success: function (data) {
                    if (data.status == 'share') {
                        $('#shareof' + data.review_id).text(data.total);
                        $('#sharedone' + data.review_id).css('color', 'red');
                    } else {
                        $('#shareof' + data.review_id).text(data.total);
                        $('#sharedone' + data.review_id).css('color', 'black');
                    }
                },
                error: function (error) {
                    console.log('error');
                }
            });
        }


        function checked(id) {
            if (id == 1) {
                $('#checked' + id).css('color', 'orange');
                $('#checked2').css('color', 'black');
                $('#checked3').css('color', 'black');
                $('#checked4').css('color', 'black');
                $('#checked5').css('color', 'black');
            } else if (id == 2) {
                $('#checked1').css('color', 'orange');
                $('#checked' + id).css('color', 'orange');
                $('#checked3').css('color', 'black');
                $('#checked4').css('color', 'black');
                $('#checked5').css('color', 'black');
            } else if (id == 3) {
                $('#checked1').css('color', 'orange');
                $('#checked2').css('color', 'orange');
                $('#checked' + id).css('color', 'orange');
                $('#checked4').css('color', 'black');
                $('#checked5').css('color', 'black');
            } else if (id == 4) {
                $('#checked1').css('color', 'orange');
                $('#checked2').css('color', 'orange');
                $('#checked3').css('color', 'orange');
                $('#checked' + id).css('color', 'orange');
                $('#checked5').css('color', 'black');
            } else if (id == 5) {
                $('#checked1').css('color', 'orange');
                $('#checked2').css('color', 'orange');
                $('#checked3').css('color', 'orange');
                $('#checked4').css('color', 'orange');
                $('#checked' + id).css('color', 'orange');
            } else {

            }

            $('#rating').val(id);
        }

        function loadreview() {
            $.ajax({
                type: 'GET',
                url: '{{ url('load/review') }}',

                success: function (response) {
                    $('#reviewload').empty().append(response);
                },
                error: function (error) {
                    console.log('error');
                }
            });
        }

        $(document).ready(function () {

            loadreview();

            $('#AddReview').submit(function (e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '{{ url('review/store') }}',
                    processData: false,
                    contentType: false,
                    data: new FormData(this),

                    success: function (data) {
                        swal({
                            title: "Success!",
                            icon: "success",
                        });
                    },
                    error: function (error) {
                        console.log('error');
                    }
                });
            });

            $('#relatedCarousel').owlCarousel({
                loop: true,
                margin: 10,
                autoplay: true,
                autoplayTimeout: 1000,
                autoplayHoverPause: true,
                responsiveClass: true,
                nav: true,
                dots: false,
                responsive: {
                    0: {
                        items: 2,
                    },
                    600: {
                        items: 2,
                    },
                    1000: {
                        items: 6,
                    }
                }
            });

        });


        function minus() {
            var avqty = $('#qtyval').val();
            if (avqty == 1) {

            } else {
                qty = Number(avqty) - 1;
                $('#qtyval').val(qty);
                $('#qtyor').val(qty);
                $('#qtyoror').val(qty);
            }
        }

        function plus() {
            var avqty = $('#qtyval').val();
            if (avqty == 10) {

            } else {
                qty = Number(avqty) + 1;
                $('#qtyval').val(qty);
                $('#qtyor').val(qty);
                $('#qtyoror').val(qty);
            }
        }



        function getcolor(color, key) {
            $("#sync1").data('owl.carousel').to(key, 300, true);
            $('#product_color').val(color);
            $('#product_colororder').val(color);
            $('.colortext').css('color', '#000');
            $('.colortext').css('border', '1px solid');
            $('#colortext' + color).css('border', '2px solid');
            $('.sizetext').css('color', '#000');
            $('.sizetext').css('background', '#fff');
        }

        function getsize(size) {
            $('#product_sizeorder').val(size);
            $('#product_sizeordernew').val(size);
            var reg = $('#regularpriceofsize' + size).val();
            var sale = $('#salepriceofsize' + size).val();
            $('#product_price').val(sale);
            $('#product_priceorder').val(sale);
            $('#product_priceneworder').val(sale);
            $('#salePrice').html(sale);

            $('.sizetext').css('color', '#000');
            $('.sizetext').css('background', '#fff');
            $('#sizetext' + size).css('color', '#fff');
            $('#sizetext' + size).css('background', '#db4444');
            $('#product_sigmentorder').val('');
        }

        function getweight(weight) {
            var sig = $('#weightsigmrnt' + weight).val();
            $('#product_sigmentorder').val(sig);
            var reg = $('#regularpriceofsize' + weight).val();
            var sale = $('#salepriceofsize' + weight).val();
            $('#product_price').val(sale);
            $('#product_priceorder').val(sale);
            $('#product_priceneworder').val(sale);
            $('#salePrice').html(sale);

            $('.weighttext').css('color', '#000');
            $('.weighttext').css('background', '#fff');
            $('#weighttext' + weight).css('color', '#fff');
            $('#weighttext' + weight).css('background', '#613EEA');
        }
    </script>

@endsection
