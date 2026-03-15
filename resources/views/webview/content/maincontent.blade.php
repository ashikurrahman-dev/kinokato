@extends('webview.master')

@section('maincontent')
    @section('title')
        {{ env('APP_NAME') }}-Best online shop in Bangladesh
    @endsection

    @section('meta')
        <meta name="description"
            content="Online shopping in Bangladesh for beauty products, men, women, kids, fashion items, clothes, electronics, home appliances, gadgets, watch, many more.">
        <meta name="keywords"
            content="{{ env('APP_NAME') }}, online store bd, online shop bd, Organic fruits, Thai, UK, Korea, China, cosmetics, Jewellery, bags, dress, mobile, accessories, automation Products,">


        <meta itemprop="name" content="Best Online Shopping in Bangladesh | {{ env('APP_NAME') }}">
        <meta itemprop="description"
            content="Best online shopping in Bangladesh for beauty products, men, women, kids, fashion items, clothes, electronics, home appliances, gadgets, watch, many more.">
        <meta itemprop="image" content="{{ env('APP_URL') }}/public/rankone1.png">

        <meta property="og:url" content="{{ env('APP_URL') }}">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Best Online Shopping in Bangladesh | {{ env('APP_NAME') }}">
        <meta property="og:description"
            content="Online shopping in BD for beauty products, men, women, kids, fashion items, clothes, electronics, home appliances, gadgets, watch, many more.">
        <meta property="og:image" content="{{ env('APP_URL') }}/public/rankone1.png">
        <meta property="image" content="{{ env('APP_URL') }}/public/rankone1.png" />
        <meta property="url" content="{{ env('APP_URL') }}/">
        <meta itemprop="image" content="{{ env('APP_URL') }}/public/rankone1.png">
        <meta property="twitter:card" content="{{ env('APP_URL') }}/public/rankone1.png" />
        <meta property="twitter:title" content="Best Online Shopping in Bangladesh | {{ env('APP_NAME') }}" />
        <meta property="twitter:url" content="{{ env('APP_URL') }}">
        <meta name="twitter:image" content="{{ env('APP_URL') }}/public/rankone1.png">
    @endsection

    <style>
        .product {
            margin-top: 4px !important;

        }

        #featureimagess {
            width: 100%;
            padding: 0px;
            padding-top: 0;
            /*max-height:200px;*/
        }

        #checked {
            color: orange;
        }

        .star {
            font-size: 12px !important;
        }

        .category-title {
            background: #212129;
            padding: 10px;
        }

        .category-list {
            background: #fff;
            border-radius: 5px;
            /*padding: 15px;*/
            height: 445px;
            /*overflow: auto;*/
        }

        .category-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .category-list ul li {
            padding: 0px 20px;
            display: flex;
            align-items: center;
        }

        .category-list ul li:hover {
            /* background: #ffbe9e; */
            color: #000 !important;
            font-weight: 600;
        }

        .today-deal {
            background: #ff650c;
            border-radius: 5px;
            padding: 15px;
            height: 400px;
        }

        .today-deal h5 {
            margin-bottom: 15px;
        }

        .deal-item {
            background: #fff;
            margin-bottom: 15px;
            padding-left: 15px;
            padding-top: 15px;
            border-radius: 8px;
        }

        .deal-item img {
            width: 110px;
            border-radius: 5px;
        }

        .deal-price {
            color: #f60;
            font-weight: bold;
        }

        .deal-old-price {
            text-decoration: line-through;
            color: #888;
        }

        .custom-carousel {
            position: relative;
        }

        .custom-carousel .carousel-indicators button {
            background-color: #000;
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .carousel-caption {
            background: rgba(0, 0, 0, 0.6);
            padding: 10px;
            border-radius: 5px;
        }

        .category-list li:hover>.subcategory-dropdown {
            display: block !important;
        }

        .subcategory-dropdown li a:hover {
            color: #000 !important;
            font-weight: 600;
        }
    </style>
    <style>
        /* Owl Nav Buttons */


        #categorySlide .owl-nav {
            position: absolute;
            top: 30%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            pointer-events: none;
        }

        #categorySlide .owl-nav button {
            pointer-events: all;
            background: rgba(0, 0, 0, 0.6);
            color: black;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>


    <div class="container p-0" style="margin-top:30px;">
        <div class="row">
            <!-- Categories -->
            <div class="p-0 mb-3 col-lg-2 col-12 d-sm-none">
                <!-- <div class="category-title">
                        <h5 class="m-0 text-white fw-bold">Categories</h5>
                    </div> -->
                <div class="category-list">
                    <ul class="p-0 m-0 list-unstyled">
                        @forelse ($categories as $category)
                            <li class=" position-relative">
                                @php
                                    $subcategories = App\Models\Subcategory::where('status', 'Active')->where('category_id', $category->id)->get();
                                @endphp

                                <a href="{{ url('products/category/' . $category->slug) }}"
                                    class="text-dark d-flex align-items-center py-2 {{ $category->subcategories->count() ? 'dropdown-toggle' : '' }}">

                                    <img src="{{ asset($category->category_icon) }}" alt="" width="20">

                                    <span class="mx-2">
                                        {{ $category->category_name }}
                                    </span>

                                </a>



                                @if($subcategories->isNotEmpty())
                                    <ul class="top-0 p-2 bg-white shadow-sm subcategory-dropdown list-unstyled position-absolute start-100 "
                                        style="min-width:180px; display:none; z-index:999;">
                                        @foreach($subcategories as $value)
                                            <li class="py-1">
                                                <a href="{{ url('products/sub/category/' . $value->slug) }}"
                                                    class="text-muted small d-block">{{ $value->sub_category_name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @empty
                            <li><span class="text-muted">No categories found</span></li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Banner  -->
            <div class="mb-3 col-lg-10 col-12" style="padding-left:15px;">
                <div class="owl-carousel owl-theme" id="slider">
                    @forelse ($sliders as $slider)
                        <div class="item" style="margin:0 !important;">
                            <a href="{{ $slider->slider_btn_link }}">
                                <img src="{{ asset($slider->slider_image) }}" class="w-100">
                            </a>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>

            <!-- Today's Deal -->

        </div>
    </div>


    <!-- flash sale -->
    <div class="container">
        @if (count($topproducts) > 0)
            <div class="pb-2 bg-white row">
                <div class="col-12">
                    <div class="section-title">
                        <div class="title-top">
                            <span class="bar"></span>
                            <span class="category-text">Todays </span>
                        </div>
                        <div class="flash-wrapper">
                            <h2 class="m-0 flashsale-main-title">Flash Sales</h2>

                            <div class="flashsale-countdown">

                                <div class="flashsale-time-box">
                                    <span id="flashsale-days">00</span>
                                    <small>Days</small>
                                </div>

                                <span class="flashsale-dot">:</span>

                                <div class="flashsale-time-box">
                                    <span id="flashsale-hours">00</span>
                                    <small>Hours</small>
                                </div>

                                <span class="flashsale-dot">:</span>

                                <div class="flashsale-time-box">
                                    <span id="flashsale-minutes">00</span>
                                    <small>Minutes</small>
                                </div>

                                <span class="flashsale-dot">:</span>

                                <div class="flashsale-time-box">
                                    <span id="flashsale-seconds">00</span>
                                    <small>Seconds</small>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="owl-carousel " id="promotionalofferSlide">
                        @forelse ($topproducts as $promotional)
                            @php
                                $firstpro = App\Models\Product::with([
                                    'sizes' => function ($query) {
                                        $query
                                            ->select('id', 'product_id', 'Discount', 'RegularPrice', 'SalePrice')
                                            ->take(1);
                                    },
                                ])
                                    ->where('id', json_decode($promotional->RelatedProductIds)[0]->productID)
                                    ->select('id', 'ProductName')
                                    ->first();

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
                                                                    <span> -
                                                                        {{ ($firstpro->sizes[0]->RegularPrice > 0) ? round((($firstpro->sizes[0]->RegularPrice - $firstpro->sizes[0]->SalePrice) / $firstpro->sizes[0]->RegularPrice) * 100) : 0 }}%
                                                                        <!-- <span class="pip_pip_1s">ছাড়</span>  -->
                                                                    </span>
                                                                </div>
                                                                <div class="wishlist-eye-btn">
                                                                    <form action="{{ route('wishlist.add') }}" method="POST"
                                                                        class="p-0 m-0">
                                                                        @csrf
                                                                        <input type="hidden" name="product_id"
                                                                            value="{{ $promotional->id }}">

                                                                        <button type="submit">
                                                                            @php
                                                                                $wishlist = session()->get('wishlist', []);
                                                                                $inWishlist = in_array($promotional->id, $wishlist);
                                                                            @endphp

                                                                            @if($inWishlist)
                                                                                <i class="fa-solid fa-heart fs-5"
                                                                                    style="font-size: 18px;color:black;"></i>
                                                                            @else
                                                                                <i class="fa-regular fa-heart fs-5"
                                                                                    style="font-size: 18px;color:black;"></i>
                                                                            @endif
                                                                        </button><br>
                                                                    </form>
                                                                    <button class="quick-shop-btn" type="button"
                                                                        data-product-id="{{ $promotional->id }}">
                                                                        <i class="fa-regular fa-eye"
                                                                            style="font-size: 18px;color:black;"></i>
                                                                    </button>
                                                                </div>
                                                                <a href="{{ url('view-product/' . $promotional->ProductSlug) }}">
                                                                    <img src="{{ asset($promotional->ProductImage) }}">
                                                                </a>

                                                                <form>
                                                                    <button class="addtocartbtn">
                                                                        Add To Cart
                                                                    </button>
                                                                </form>
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

                                                            <div class="price-box" style="padding-top: 5px;">
                                                                <del class="old-product-price strong-400"
                                                                    style="color:#db4444">৳{{ round($firstpro->sizes[0]->RegularPrice) }}</del>
                                                                <span class="product-price strong-600"
                                                                    style="color:black;margin-left:7px;">৳{{ round($firstpro->sizes[0]->SalePrice) }}</span>
                                                            </div>

                                                            <div class="d-flex" style="justify-content:space-between">
                                                                <div class="star" style="padding-top: 5px;">

                                                                    <span class="fas fa-star" id="checked"></span>
                                                                    <span class="fas fa-star" id="checked"></span>
                                                                    <span class="fas fa-star" id="checked"></span>
                                                                    <span class="fas fa-star" id="checked"></span>
                                                                    <span class="fas fa-star" id="checked"></span>
                                                                    <span
                                                                        style="font-weight: bold;color:black;font-size:12px">({{ App\Models\Review::where('product_id', $promotional->id)->get()->count() }})</span>

                                                                </div>

                                                            </div>



                                                        </div>
                                                        <!-- <a href="{{ url('view-product/' . $promotional->ProductSlug) }}">
                                                                                                                                                                                                                                                                        <button class="mb-0 btn btn-danger btn-sm btn-block"
                                                                                                                                                                                                                                                                            style="width: 100%;border-radius: 0%;"
                                                                                                                                                                                                                                                                            id="purcheseBtn">অর্ডার করুন</button>
                                                                                                                                                                                                                                                                    </a> -->

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

                </div>
            </div>
            <div class="text-center" style="margin-top:30px">
                <a href="{{ url('promotional/products') }}"
                    style="padding:15px 35px;color:white;font-weight:bold;font-size:15px;background:#db4444;border-radius:4px">
                    View All Products
                </a>
            </div>

        @else
        @endif
    </div>

    <div class="container">
        <hr style="border: 1px solid #999;margin: 50px 0;">
    </div>

    <!-- Categories -->
    <div class="container p-0 my-2">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <div class="title-top">
                        <span class="bar"></span>
                        <span class="category-text">Categories</span>
                    </div>

                    <h2 class="main-title">Browse By Category</h2>
                </div>

                <div class="owl-carousel " id="categorySlide">
                    @forelse ($categories as $category)
                        <div class="item">
                            <a href="{{ url('products/category/' . $category->slug) }}">
                                <div id="cath">
                                    <div class="d-flex justify-content-center">
                                        <img src="{{ asset($category->category_icon) }}" id="catimg">
                                    </div>

                                    <p id="catp" style="font-weight:bold;">{{ $category->category_name }}</p>
                                </div>
                            </a>
                        </div>
                    @empty

                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <hr style="border: 1px solid #999;margin: 50px 0;">
    </div>

    <!-- Best Selling and all Products -->
    <div class="container p-0 pb-2 ">
        @if (count($topproducts) > 0)
            <div class="pb-2 bg-white">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title">
                            <div class="title-top">
                                <span class="bar"></span>
                                <span class="category-text">This Month</span>
                            </div>
                            <div style="display:flex; align-items:center; justify-content: space-between;margin:20px 0">
                                <h2 class="m-0 main-title">Best Selling Product</h2>
                                <a href="{{ url('promotional/products') }}" class="mb-0 btn btn-sm"
                                    style="padding: 12px 30px;color: white;font-weight: bold;font-size:12px; background:#db4444;">VIEW
                                    ALL</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <!-- <div class="owl-carousel " id="promotionalofferSlide"> -->
                    @forelse ($topproducts->take(4) as $promotional)
                        @php
                            $firstpro = App\Models\Product::with([
                                'sizes' => function ($query) {
                                    $query
                                        ->select('id', 'product_id', 'Discount', 'RegularPrice', 'SalePrice')
                                        ->take(1);
                                },
                            ])
                                ->where('id', json_decode($promotional->RelatedProductIds)[0]->productID)
                                ->select('id', 'ProductName')
                                ->first();

                        @endphp
                        @if (isset($firstpro))
                            <div class="col-6 col-lg-3">
                                <div class="products best-product">
                                    <div class="product">
                                        <div class="product-micro">
                                            <div class="row product-micro-row">
                                                <div class="col-12">
                                                    <div class="product-image" style="position: relative;">
                                                        <div class="text-center image">
                                                            <div class="frs_discount">
                                                                <span> -
                                                                    {{ ($firstpro->sizes[0]->RegularPrice > 0) ? round((($firstpro->sizes[0]->RegularPrice - $firstpro->sizes[0]->SalePrice) / $firstpro->sizes[0]->RegularPrice) * 100) : 0 }}%
                                                                    <!-- <span class="pip_pip_1s">ছাড়</span>  -->
                                                                </span>
                                                            </div>
                                                            <div class="wishlist-eye-btn">
                                                                <form action="{{ route('wishlist.add') }}" method="POST"
                                                                    class="p-0 m-0">
                                                                    @csrf
                                                                    <input type="hidden" name="product_id"
                                                                        value="{{ $promotional->id }}">

                                                                    <button type="submit">
                                                                        @php
                                                                            $wishlist = session()->get('wishlist', []);
                                                                            $inWishlist = in_array($promotional->id, $wishlist);
                                                                        @endphp

                                                                        @if($inWishlist)
                                                                            <i class="fa-solid fa-heart fs-5"
                                                                                style="font-size: 18px;color:black;"></i>
                                                                        @else
                                                                            <i class="fa-regular fa-heart fs-5"
                                                                                style="font-size: 18px;color:black;"></i>
                                                                        @endif
                                                                    </button><br>
                                                                </form>
                                                                <button class="quick-shop-btn" type="button"
                                                                    data-product-id="{{ $promotional->id }}">
                                                                    <i class="fa-regular fa-eye"
                                                                        style="font-size: 18px;color:black;"></i>
                                                                </button>
                                                            </div>
                                                            <a href="{{ url('view-product/' . $promotional->ProductSlug) }}">
                                                                <img src="{{ asset($promotional->ProductImage) }}">
                                                            </a>

                                                            <form>
                                                                <button class="addtocartbtn">
                                                                    Add To Cart
                                                                </button>
                                                            </form>
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

                                                        <div class="price-box" style="padding-top: 5px;">
                                                            <del class="old-product-price strong-400"
                                                                style="color:#db4444">৳{{ round($firstpro->sizes[0]->RegularPrice) }}</del>
                                                            <span class="product-price strong-600"
                                                                style="color:black;margin-left:7px;">৳{{ round($firstpro->sizes[0]->SalePrice) }}</span>
                                                        </div>

                                                        <div class="d-flex" style="justify-content:space-between">
                                                            <div class="star" style="padding-top: 5px;">

                                                                <span class="fas fa-star" id="checked"></span>
                                                                <span class="fas fa-star" id="checked"></span>
                                                                <span class="fas fa-star" id="checked"></span>
                                                                <span class="fas fa-star" id="checked"></span>
                                                                <span class="fas fa-star" id="checked"></span>
                                                                <span
                                                                    style="font-weight: bold;color:black;font-size:12px">({{ App\Models\Review::where('product_id', $promotional->id)->get()->count() }})</span>

                                                            </div>

                                                        </div>



                                                    </div>
                                                    <!-- <a href="{{ url('view-product/' . $promotional->ProductSlug) }}">
                                                        <button class="mb-0 btn btn-danger btn-sm btn-block"
                                                            style="width: 100%;border-radius: 0%;"
                                                            id="purcheseBtn">অর্ডার করুন</button>
                                                    </a> -->

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
                    <!-- </div> -->
                </div>
            </div>
        @else
        @endif


        <section class="mt-4 music-banner-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="music-banner-box">
                            <img src="{{ asset($basicinfo->banner_img) }}" class="music-banner-bg">

                            <div class="music-banner-overlay-content">

                                <p class="m-0 music-banner-category">{{ $basicinfo->banner_subtitle }}</p>

                                <h2 class="m-0 music-banner-title">
                                    {!! $basicinfo->banner_title !!}
                                </h2>

                                <div class="music-banner-timer">

                                    <div class="music-banner-time">
                                        <span id="mb-days">00</span>
                                        <p>Days</p>
                                    </div>

                                    <div class="music-banner-time">
                                        <span id="mb-hours">00</span>
                                        <p>Hours</p>
                                    </div>

                                    <div class="music-banner-time">
                                        <span id="mb-minutes">00</span>
                                        <p>Minutes</p>
                                    </div>

                                    <div class="music-banner-time">
                                        <span id="mb-seconds">00</span>
                                        <p>Seconds</p>
                                    </div>
                                </div>
                                <a href="{{ $basicinfo->banner_link }}" class="music-banner-btn d-none d-lg-inline-block">Buy Now!</a>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </section>

        <div class="container">
            <hr style="border: 1px solid #999;margin: 50px 0;">
        </div>

        <!-- our products -->
        @if (count($our_products) > 0)
            <div class="pb-2 bg-white">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title">
                            <div class="title-top">
                                <span class="bar"></span>
                                <span class="category-text">Our Products</span>
                            </div>
                            <div style="display:flex; align-items:center; justify-content: space-between;margin:20px 0">
                                <h2 class="m-0 main-title">Explore Our Products</h2>
                                <!-- <a href="{{ url('promotional/products') }}" class="mb-0 btn btn-sm"
                                            style="padding: 12px 30px;color: white;font-weight: bold;font-size:12px; background:#db4444;">VIEW
                                            ALL</a> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <!-- <div class="owl-carousel " id="promotionalofferSlide"> -->
                    @forelse ($our_products as $promotional)
                        @php
                            $firstpro = App\Models\Product::with([
                                'sizes' => function ($query) {
                                    $query
                                        ->select('id', 'product_id', 'Discount', 'RegularPrice', 'SalePrice')
                                        ->take(1);
                                },
                            ])
                                ->where('id', json_decode($promotional->RelatedProductIds)[0]->productID)
                                ->select('id', 'ProductName')
                                ->first();

                        @endphp
                        @if (isset($firstpro))
                            <div class="col-6 col-lg-3">
                                <div class="products best-product">
                                    <div class="product">
                                        <div class="product-micro">
                                            <div class="row product-micro-row">
                                                <div class="col-12">
                                                    <div class="product-image" style="position: relative;">
                                                        <div class="text-center image">
                                                            <div class="frs_discount">
                                                                <span> -
                                                                    {{ ($firstpro->sizes[0]->RegularPrice > 0) ? round((($firstpro->sizes[0]->RegularPrice - $firstpro->sizes[0]->SalePrice) / $firstpro->sizes[0]->RegularPrice) * 100) : 0 }}%
                                                                    <!-- <span class="pip_pip_1s">ছাড়</span>  -->
                                                                </span>
                                                            </div>
                                                            <div class="wishlist-eye-btn">
                                                                <div class="product-wishlist">
                                                                    <form action="{{ route('wishlist.add') }}" method="POST"
                                                                        class="p-0 m-0">
                                                                        @csrf
                                                                        <input type="hidden" name="product_id"
                                                                            value="{{ $promotional->id }}">

                                                                        <button type="submit">
                                                                            @php
                                                                                $wishlist = session()->get('wishlist', []);
                                                                                $inWishlist = in_array($promotional->id, $wishlist);
                                                                            @endphp

                                                                            @if($inWishlist)
                                                                                <i class="fa-solid fa-heart fs-5"
                                                                                    style="font-size: 18px;color:black;"></i>
                                                                            @else
                                                                                <i class="fa-regular fa-heart fs-5"
                                                                                    style="font-size: 18px;color:black;"></i>
                                                                            @endif
                                                                        </button><br>
                                                                    </form>
                                                                </div>

                                                                <button class="quick-shop-btn" type="button"
                                                                    data-product-id="{{ $promotional->id }}">
                                                                    <i class="fa-regular fa-eye"
                                                                        style="font-size: 18px;color:black;"></i>
                                                                </button>
                                                            </div>
                                                            <a href="{{ url('view-product/' . $promotional->ProductSlug) }}">
                                                                <img src="{{ asset($promotional->ProductImage) }}">
                                                            </a>

                                                            <form>
                                                                <button class="addtocartbtn">
                                                                    Add To Cart
                                                                </button>
                                                            </form>
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

                                                        <div class="price-box" style="padding-top: 5px;">
                                                            <del class="old-product-price strong-400"
                                                                style="color:#db4444">৳{{ round($firstpro->sizes[0]->RegularPrice) }}</del>
                                                            <span class="product-price strong-600"
                                                                style="color:black;margin-left:7px;">৳{{ round($firstpro->sizes[0]->SalePrice) }}</span>
                                                        </div>

                                                        <div class="d-flex" style="justify-content:space-between">
                                                            <div class="star" style="padding-top: 5px;">

                                                                <span class="fas fa-star" id="checked"></span>
                                                                <span class="fas fa-star" id="checked"></span>
                                                                <span class="fas fa-star" id="checked"></span>
                                                                <span class="fas fa-star" id="checked"></span>
                                                                <span class="fas fa-star" id="checked"></span>
                                                                <span
                                                                    style="font-weight: bold;color:black;font-size:12px">({{ App\Models\Review::where('product_id', $promotional->id)->get()->count() }})</span>

                                                            </div>

                                                        </div>



                                                    </div>
                                                    <!-- <a href="{{ url('view-product/' . $promotional->ProductSlug) }}">
                                                                        <button class="mb-0 btn btn-danger btn-sm btn-block"
                                                                            style="width: 100%;border-radius: 0%;"
                                                                            id="purcheseBtn">অর্ডার করুন</button>
                                                                    </a> -->

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
                    <!-- </div> -->
                </div>
                <div class="text-center" style="margin-top:30px">
                    <a href="{{ url('promotional/products') }}"
                        style="padding:15px 35px;color:white;font-weight:bold;font-size:15px;background:#db4444;border-radius:4px">
                        View All Products
                    </a>
                </div>
            </div>
        @else
        @endif

        <div class="container">
            <hr style="border: 1px solid #999;margin: 50px 0;">
        </div>

        <!-- banner -->
        <!-- <div class="row gutters-10">
            @if (count($adds) == '2')
                @forelse ($adds as $add)
                    <div class="col-lg-6 col-6 ps-lg-0">
                        <div class="mb-1 media-banner mb-lg-0">
                            <a href="{{ $add->add_link }}" target="_blank" class="banner-container">
                                <img src="{{ asset($add->add_image) }}" alt="{{ env('APP_NAME') }}"
                                    class="img-fluid ls-is-cached lazyloaded">
                            </a>
                        </div>
                    </div>
                @empty
                @endforelse
            @else
                @forelse ($adds as $add)
                    <div class="col-lg-12 col-12 ps-0">
                        <div class="mb-1 media-banner mb-lg-0">
                            <a href="{{ $add->add_link }}" target="_blank" class="banner-container">
                                <img src="{{ asset($add->add_image) }}" alt="{{ env('APP_NAME') }}"
                                    class="img-fluid ls-is-cached lazyloaded">
                            </a>
                        </div>
                    </div>
                @empty
                @endforelse
            @endif
        </div> -->

        <!-- category wise product -->
        <!-- @forelse ($categoryproducts as $key => $categoryproduct)
            @if (count($categoryproduct->mainproducts) > 0)
                <div class="pb-0 bg-white row">
                    <div class="col-12"
                        style="border-bottom: 1px solid #212129;padding-left: 0;display: flex;justify-content: space-between;">
                        <div class="px-2 pt-0 p-md-3 d-flex justify-content-between"
                            style="padding-bottom:4px !important;padding-top: 8px !important;">
                            <h4 class="m-0"><b>{{ $categoryproduct->category_name }}</b></h4>
                        </div>
                        <a href="{{ url('products/category/' . $categoryproduct->slug) }}"
                            class="mb-0 btn btn-danger btn-sm"
                            style="padding: 4px;height: 26px;color: white;font-weight: bold;margin-top:9px;font-size:12px;background: #212129;border: 1px solid #212129;">VIEW
                            ALL</a>
                    </div>
                    @if(App\Models\Category::find($categoryproduct->id)->category_banner)
                        <div class="mt-1 mb-2 col-12">
                            <img src="{{ asset(App\Models\Category::find($categoryproduct->id)->category_banner) }}" alt="" class="category_Banner_Image">
                        </div>
                    @endif

                    @forelse ($categoryproduct->mainproducts as $product)
                            @php
                                $relatedProducts = json_decode($product->RelatedProductIds);
                                $firstcatepro = null;

                                if (is_array($relatedProducts) && isset($relatedProducts[0]->productID)) {
                                    $firstcatepro=App\Models\Product::with([
                                    'sizes' => function ($query) {
                                        $query->select('id','product_id','Discount','RegularPrice','SalePrice')->take(1);
                                    }
                                    ])->where('id',json_decode($product->RelatedProductIds)[0]->productID)->select('id','ProductName')->first();
                                }
                            @endphp

                            @if (isset($firstcatepro))
                            @php
                                $category = App\Models\Category::find($product->category_id);
                            @endphp

                                <div class="mb-3 col-6 col-md-4 col-lg-2">
                                        <div class="product">
                                            <div class="product-micro">
                                                <div class="row product-micro-row">
                                                    <div class="col-12">
                                                        <div class="product-image" style="position: relative; overflow: hidden;">
                                                            <div class="text-center image" style="position: relative;">
                                                                <div class="frs_discount">
                                                                    <span>
                                                                        {{ ($firstcatepro->sizes[0]->RegularPrice > 0) ? round((($firstcatepro->sizes[0]->RegularPrice - $firstcatepro->sizes[0]->SalePrice) / $firstcatepro->sizes[0]->RegularPrice) * 100) : 0 }}%
                                                                        <br>
                                                                        <span class="pip_pip_1s">ছাড়</span>
                                                                    </span>
                                                                </div>

                                                                <a href="{{ url('view-product/' . $product->ProductSlug) }}" class="hover-switch"
                                                                    data-main="{{ asset($product->ProductImage) }}"
                                                                    data-hover="{{ !empty($product->ProductHoverImage) ? asset($product->ProductHoverImage) : '' }}">
                                                                    <img src="{{ asset($product->ProductImage) }}"
                                                                            alt="{{ $product->ProductName }}"
                                                                            class="switchable-img"
                                                                            style="width:100%; transition: opacity 0.3s ease;">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>



                                                    <div class="col-12">
                                                        <div class="p-2 infofe p-md-2"
                                                            style="padding-bottom: 4px !important;background: white;">
                                                            <div class="product-info">
                                                                <h2 class="name text-truncate" id="f_name"><a
                                                                        href="{{ url('view-product/' . $product->ProductSlug) }}"
                                                                        id="f_pro_name">{{ $product->ProductName }}</a>
                                                                </h2>
                                                            </div>


                                                            <div class="d-flex" style="justify-content:space-between">
                                                                <div class="star" style="padding-top: 5px;">
                                                                    <span
                                                                        style="font-weight: bold;color:black;font-size:10px">({{ App\Models\Review::where('product_id', $product->id)->get()->count() }})</span>

                                                                    <span class="fas fa-star" id="checked"></span>
                                                                    <span class="fas fa-star" id="checked"></span>
                                                                    <span class="fas fa-star" id="checked"></span>
                                                                    <span class="fas fa-star" id="checked"></span>
                                                                    <span class="fas fa-star" id="checked"></span>

                                                                </div>

                                                            </div>

                                                            <div class="price-box">
                                                                <del class="old-product-price strong-400"
                                                                    style="color:red">৳
                                                                    {{ round($firstcatepro->sizes[0]->RegularPrice) }}</del>
                                                                <span class="product-price strong-600"
                                                                    style="color:black">৳
                                                                    {{ round($firstcatepro->sizes[0]->SalePrice) }}</span>
                                                            </div>

                                                        </div>
                                                        <a href="{{ url('view-product/' . $product->ProductSlug) }}">
                                                            <button class="mb-0 btn btn-danger btn-sm btn-block"
                                                                style="width: 100%;border-radius: 0%;"
                                                                id="purcheseBtn">অর্ডার করুন</button>
                                                        </a>

                                                    </div>

                                                </div>

                                            </div>


                                        </div>
                                    </div>
                            @endif

                    @empty
                    @endforelse


                </div>
            @else
            @endif

        @empty
        @endforelse -->

        <div class="row gutters-10">
            @if (count($addbottoms) == '2')
                @forelse ($addbottoms as $add)
                    <div class="col-lg-6 col-6 ps-lg-0">
                        <div class="mb-1 media-banner mb-lg-0">
                            <a href="{{ $add->add_link }}" target="_blank" class="banner-container">
                                <img src="{{ asset($add->add_image) }}" alt="{{ env('APP_NAME') }}"
                                    class="img-fluid ls-is-cached lazyloaded">
                            </a>
                        </div>
                    </div>
                @empty
                @endforelse
            @else
                @forelse ($addbottoms as $add)
                    <div class="col-lg-12 col-12 pr-lg-0">
                        <div class="mb-1 media-banner mb-lg-0">
                            <a href="{{ $add->add_link }}" target="_blank" class="banner-container">
                                <img src="{{ asset($add->add_image) }}" alt="{{ env('APP_NAME') }}"
                                    class="img-fluid ls-is-cached lazyloaded">
                            </a>
                        </div>
                    </div>
                @empty
                @endforelse
            @endif


        </div>

    </div>

    <style>
  .features-section { background: #fff; padding: 50px 20px; }
  .feature-icon-wrap {
    width: 70px; height: 70px;
    border-radius: 50%;
    border: 2px solid #ccc;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
    background: #111;
    transition: background .3s;
  }
  .feature-icon-wrap i { font-size: 26px; color: #fff; }
  .feature-item:hover .feature-icon-wrap { background: #333; }
  .feature-title { font-size: 14px; font-weight: 800; letter-spacing: .5px; text-transform: uppercase; color: #111; margin-bottom: 8px; }
  .feature-desc { font-size: 13px; color: #888; margin: 0; }
</style>

<section class="features-section">
  <div class="container">
    <div class="row text-center g-4">

      <div class="col-12 col-md-4 feature-item">
        <div class="feature-icon-wrap">
          <i style="color:white;font-size:22px;" class="fa-solid fa-truck-fast"></i>
        </div>
        <h6 class="feature-title m-0">Free and Fast Delivery</h6>
        <p class="feature-desc">Free delivery for all orders over $140</p>
      </div>

      <div class="col-12 col-md-4 feature-item">
        <div class="feature-icon-wrap">
          <i style="color:white;font-size:22px;" class="fa-solid fa-headset"></i>
        </div>
        <h6 class="feature-title m-0">24/7 Customer Service</h6>
        <p class="feature-desc">Friendly 24/7 customer support</p>
      </div>

      <div class="col-12 col-md-4 feature-item">
        <div class="feature-icon-wrap">
          <i style="color:white;font-size:22px;" class="fa-solid fa-shield-halved"></i>
        </div>
        <h6 class="feature-title m-0">Money Back Guarantee</h6>
        <p class="feature-desc">We return money within 30 days</p>
      </div>

    </div>
  </div>
</section>


    @if (Auth::id())
        <input type="hidden" name="user_id" id="user_id" value="{{ Auth::id() }}">
    @else
        <input type="hidden" name="user_id" id="user_id">
    @endif

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
                        $('#promotionalofferSlide #likereactof' + id).text(data.total);
                        $('#promotionalofferSlide #likereactdone' + id).css('color', 'green');
                        $('#propro #likereactof' + id).text(data.total);
                        $('#propro #likereactdone' + id).css('color', 'green');
                    } else if (data.sigment == 'unlike') {
                        $('#promotionalofferSlide #likereactof' + id).text(data.total);
                        $('#promotionalofferSlide #likereactdone' + id).css('color', 'black');
                        $('#propro #likereactof' + id).text(data.total);
                        $('#propro #likereactdone' + id).css('color', 'black');
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
                        $('#promotionalofferSlide #lovereactof' + id).text(data.total);
                        $('#promotionalofferSlide #lovereactdone' + id).css('color', 'red');
                        $('#propro #lovereactof' + id).text(data.total);
                        $('#propro #lovereactdone' + id).css('color', 'red');
                    } else {
                        $('#promotionalofferSlide #lovereactof' + id).text(data.total);
                        $('#promotionalofferSlide #lovereactdone' + id).css('color', 'black');
                        $('#propro #lovereactof' + id).text(data.total);
                        $('#propro #lovereactdone' + id).css('color', 'black');
                    }
                },
                error: function (error) {
                    console.log('error');
                }
            });
        }
    </script>
    <script>
        $(document).ready(function () {
            $('.hover-switch').each(function () {
                var $link = $(this);
                var mainImg = $link.data('main');
                var hoverImg = $link.data('hover');
                var $img = $link.find('.switchable-img');

                if (hoverImg && hoverImg !== '') {
                    $link.on('mouseenter', function () {
                        $img.attr('src', hoverImg);
                    }).on('mouseleave', function () {
                        $img.attr('src', mainImg);
                    });
                }
            });
        });
    </script>


    <script>

        let countDownDate = new Date("Jun 30, 2026 23:59:59").getTime();

        let x = setInterval(function () {

            let now = new Date().getTime();
            let distance = countDownDate - now;

            let days = Math.floor(distance / (1000 * 60 * 60 * 24));
            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("days").innerHTML = days;
            document.getElementById("hours").innerHTML = hours;
            document.getElementById("minutes").innerHTML = minutes;
            document.getElementById("seconds").innerHTML = seconds;

            if (distance < 0) {
                clearInterval(x);
            }

        }, 1000);

    </script>

    <script>

        function musicBannerCountdown(endTime) {

            let end = new Date(endTime).getTime();

            let x = setInterval(function () {

                let now = new Date().getTime();
                let distance = end - now;

                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("mb-days").innerHTML = "00";
                    document.getElementById("mb-hours").innerHTML = "00";
                    document.getElementById("mb-minutes").innerHTML = "00";
                    document.getElementById("mb-seconds").innerHTML = "00";
                    return;
                }

                let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                let hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
                let minutes = Math.floor((distance / (1000 * 60)) % 60);
                let seconds = Math.floor((distance / 1000) % 60);

                document.getElementById("mb-days").innerHTML = days;
                document.getElementById("mb-hours").innerHTML = hours;
                document.getElementById("mb-minutes").innerHTML = minutes;
                document.getElementById("mb-seconds").innerHTML = seconds;

            }, 1000);
        }

        // Laravel blade থেকে date pass করা
        let bannerTime = "{{ $basicinfo->banner_time }}";

        musicBannerCountdown(bannerTime);

    </script>
    <script>
        const targetDate = new Date("{{ $basicinfo->flash_sale_time }}").getTime();

        const timer = setInterval(function () {

            const now = new Date().getTime();
            const distance = targetDate - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000*60*60*24)) / (1000*60*60));
            const minutes = Math.floor((distance % (1000*60*60)) / (1000*60));
            const seconds = Math.floor((distance % (1000*60)) / 1000);

            document.getElementById("flashsale-days").innerHTML = String(Math.max(days,0)).padStart(2,'0');
            document.getElementById("flashsale-hours").innerHTML = String(Math.max(hours,0)).padStart(2,'0');
            document.getElementById("flashsale-minutes").innerHTML = String(Math.max(minutes,0)).padStart(2,'0');
            document.getElementById("flashsale-seconds").innerHTML = String(Math.max(seconds,0)).padStart(2,'0');

            if(distance < 0){
                clearInterval(timer);
            }

        },1000);
    </script>



@endsection
