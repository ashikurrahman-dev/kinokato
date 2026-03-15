@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-Best online shop in Bangladesh
@endsection

@section('meta')
    <meta name="description" content="Online shopping in Bangladesh for beauty products, men, women, kids, fashion items, clothes, electronics, home appliances, gadgets, watch, many more.">
    <meta name="keywords" content="Rashibd, online store bd, online shop bd, Organic fruits, Thai, UK, Korea, China, cosmetics, Jewellery, bags, dress, mobile, accessories, automation Products,">


    <meta itemprop="name" content="Best Online Shopping in Bangladesh | Rashibd">
    <meta itemprop="description" content="Best online shopping in Bangladesh for beauty products, men, women, kids, fashion items, clothes, electronics, home appliances, gadgets, watch, many more.">
    <meta itemprop="image" content="{{env('APP_URL')}}{{\App\Models\Basicinfo::first()->logo}}">

    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Best Online Shopping in Bangladesh | Rashibd">
    <meta property="og:description" content="Online shopping in BD for beauty products, men, women, kids, fashion items, clothes, electronics, home appliances, gadgets, watch, many more.">
    <meta property="og:image" content="{{env('APP_URL')}}{{\App\Models\Basicinfo::first()->logo}}">
    <meta property="image" content="{{env('APP_URL')}}{{\App\Models\Basicinfo::first()->logo}}" />
    <meta property="url" content="{{env('APP_URL')}}">
    <meta itemprop="image" content="{{env('APP_URL')}}{{\App\Models\Basicinfo::first()->logo}}">
    <meta property="twitter:card" content="{{env('APP_URL')}}{{\App\Models\Basicinfo::first()->logo}}" />
    <meta property="twitter:title" content="Best Online Shopping in Bangladesh | Rashibd" />
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta name="twitter:image" content="{{env('APP_URL')}}{{\App\Models\Basicinfo::first()->logo}}">
@endsection
<style>
    .product{
            margin-top: 4px !important;

    }

    #featureimagess{
        width: 100%;
        padding: 0px;
        padding-top: 0;
        /*max-height:200px;*/
    }
    #checked {
        color: orange;
    }
    .star{
        font-size: 8px !important;
    }
</style>


<div class="p-0 row">
    <div class="col-12 col-lg-9">
        <div class="owl-carousel owl-theme" id="slider">
            @forelse ($sliders as $slider)
                <div class="item" style="margin:0 !important;">
                    <a href="{{ $slider->slider_btn_link }}">
                    <img  src="{{ asset($slider->slider_image) }}"
                        alt="{{ $slider->slider_title }}">
                    </a>
                </div>
            @empty
            @endforelse
        </div>

    </div>
    <div class="col-lg-3" id="d-sm-none">
    @php
    $ad=App\Models\Addbanner::where('id',5)->first();
    $ad2=App\Models\Addbanner::where('id',6)->first();
    @endphp
        <div class="media-banner">
            <a href="{{ $ad->add_link }}" target="_blank" class="banner-container">
                <img src="{{ asset($ad->add_image) }}" alt="{{ env('APP_NAME') }}"
                    class="img-fluid ls-is-cached lazyloaded mb-lg-3">
            </a>
            <a href="{{ $ad2->add_link }}" target="_blank" class="banner-container">
                <img src="{{ asset($ad2->add_image) }}" alt="{{ env('APP_NAME') }}"
                    class="img-fluid ls-is-cached lazyloaded">
            </a>
        </div>
    </div>
</div>




<div class="container p-0 mt-4 mb-2 mt-lg-4 pt-lg-4">
    <div class="row">
        <div class="col-12">
            <div class="owl-carousel " id="categorySlide">
                @forelse ($categories as $category)
                    <div class="item">
                        <a href="{{ url('products/category/' . $category->slug) }}" >
                            <div id="cath">
                                <div class="d-flex justify-content-center" >
                                    <img  src="{{ asset($category->category_icon) }}" id="catimg">
                                </div>

                                <p id="catp" style="font-weight:bold;color: black;">{{ $category->category_name }}</p>
                            </div>
                        </a>
                    </div>
                @empty

                @endforelse
            </div>
        </div>
    </div>
</div>



@if(count($topproducts)>0)
<!-- Promotional Products -->
<div class="container p-0 pb-2 ">
    <div class="pb-2 bg-white row" style="background:#efefef !important;">
        <div class="col-12" style="border-bottom: 1px solid green;padding-left: 0;display: flex;justify-content: space-between;">
            <div class="px-2 pt-0 p-md-3 d-flex justify-content-between" style="padding-bottom:4px !important;padding-top: 8px !important;">
                <h4 class="m-0"><b>Promotional Offers</b></h4>
            </div>
            <a href="{{ url('promotional/products') }}" class="mb-0 btn btn-sm" style="padding: 4px;height: 26px;color: white;font-weight: bold;margin-top:9px;font-size:12px; background:black;">VIEW ALL</a>
        </div>
        <div class="col-12">
            <div class="owl-carousel " id="promotionalofferSlide">
                @forelse ($topproducts as $promotional)
                    @php
                        $firstpro=App\Models\Product::where('id',json_decode($promotional->RelatedProductIds)[0]->productID)->first();
                    @endphp
                    @if(isset($firstpro))
                        <div class="item" id="featuredproduct">
                            <div class="products best-product">
                                <div class="product">
                                    <div class="product-micro">
                                        <div class="row product-micro-row">
                                            <div class="col-12">
                                                <div class="product-image" style="position: relative;">
                                                    <div class="text-center image">
                                                        <a href="{{ url('view-product/' . $promotional->ProductSlug) }}">
                                                            <img src="{{ asset($promotional->ProductImage) }}"
                                                                alt="{{ $promotional->ProductName }}" id="featureimagess">
                                                        </a>
                                                    </div>

                                                    @if (App\Models\Size::where('product_id',$firstpro->id)->first())
                                                        <span id="discountpart"> <p id="pdis">SAVE ৳{{ round(App\Models\Size::where('product_id',$firstpro->id)->first()->Discount) }}</p></span>
                                                    @else
                                                        <span id="discountpart"> <p id="pdis">SAVE ৳{{ round(App\Models\Weight::where('product_id',$firstpro->id)->first()->Discount) }}</p></span>
                                                    @endif

                                                </div>
                                                <!-- /.product-image -->
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-12">
                                                <div class="p-2 infofe p-md-2" style="padding-bottom: 4px !important;background: white;">
                                                    <div class="product-info">
                                                        <h2 class="name text-truncate" id="f_name"><a
                                                                href="{{ url('view-product/' . $promotional->ProductSlug) }}"
                                                                id="f_pro_name">{{ $promotional->ProductName }}</a></h2>
                                                    </div>

                                                    @php
                                                        $review = App\Models\Review::where('product_id', $firstpro->id)->avg(
                                                            'rating',
                                                        );
                                                    @endphp
                                                    <div class="d-flex" style="justify-content:space-between">
                                                        <div class="star" style="padding-top: 5px;">
                                                            <span style="font-weight: bold;color:black;font-size:10px">({{ App\Models\Review::where('product_id', $promotional->id)->get()->count() }})</span>
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
                                                        <div class="like">
                                                            <div class="d-flex" style="justify-content: space-around;font-size: 14px;">
                                                                <span style="padding-right:14px;"><span class="sts" style="padding-right: 2px;font-size:12px;"
                                                                        id="likereactof{{ $firstpro->id }}">{{ App\Models\React::where('product_id', $firstpro->id)->where('sigment','like')->get()->count() }}</span><i
                                                                        @if(App\Models\React::where('product_id', $firstpro->id)->whereIn('user_id', [\Request::ip(),Auth::id()])->where('sigment','like')->first()) style="color:green !important" @endif class="fas fa-thumbs-up" id="likereactdone{{ $firstpro->id }}"
                                                                        onclick="givereactlike({{ $firstpro->id }})"></i></span>
                                                                <span><span class="sts" style="padding-right: 2px;font-size:12px;"
                                                                        id="lovereactof{{ $firstpro->id }}">{{ App\Models\React::where('product_id', $firstpro->id)->where('sigment','love')->get()->count() }}</span><i
                                                                        @if(App\Models\React::where('product_id', $firstpro->id)->whereIn('user_id', [\Request::ip(),Auth::id()])->where('sigment','love')->first()) style="color:red !important" @endif class="fas fa-heart" id="lovereactdone{{ $firstpro->id }}"
                                                                        onclick="givereactlove({{ $firstpro->id }})"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if (App\Models\Size::where('product_id',$firstpro->id)->first())
                                                        <div class="price-box">
                                                            <del class="old-product-price strong-400">৳
                                                                {{ round(App\Models\Size::where('product_id',$firstpro->id)->first()->RegularPrice) }}</del>
                                                            <span
                                                                class="product-price strong-600">৳ {{ round(App\Models\Size::where('product_id',$firstpro->id)->first()->SalePrice) }}</span>
                                                        </div>
                                                    @else
                                                        <div class="price-box">
                                                            <del class="old-product-price strong-400">৳
                                                                {{ round(App\Models\Weight::where('product_id',$firstpro->id)->first()->RegularPrice) }}</del>
                                                            <span
                                                                class="product-price strong-600">৳ {{ round(App\Models\Weight::where('product_id',$firstpro->id)->first()->SalePrice) }}</span>
                                                        </div>
                                                    @endif

                                                </div>
                                                <a href="{{ url('view-product/' . $promotional->ProductSlug) }}">
                                                    <button class="mb-0 btn btn-danger btn-sm btn-block"
                                                            style="width: 100%;border-radius: 0%;" id="purcheseBtn">অর্ডার করুন</button>
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
        </div>
    </div>
</div>
@else

@endif


<!-- add section -->
<div class="container p-0 mb-2 mb-lg-3">
    <div class="row gutters-10">
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
    </div>
</div>

@forelse ($categoryproducts as $key=>$categoryproduct)
    @if (count($categoryproduct->mainproducts) > 0)
        <!-- Category Products -->
        <div class="container p-0 pb-3" id="propro">
            <div class="pb-0 bg-white row" style="background:#efefef !important;">
                <div class="col-12" style="border-bottom: 1px solid #F27336;padding-left: 0;display: flex;justify-content: space-between;">
                    <div class="px-2 pt-0 p-md-3 d-flex justify-content-between" style="padding-bottom:4px !important;padding-top: 8px !important;">
                        <h4 class="m-0"><b>{{ $categoryproduct->category_name }}</b></h4>
                    </div>
                    <a href="{{url('products/category/'.$categoryproduct->slug)}}" class="mb-0 btn btn-danger btn-sm" style="padding: 4px;height: 26px;color: white;font-weight: bold;margin-top:9px;font-size:12px;background: black;border: 1px solid black;">VIEW ALL</a>
                </div>


                @forelse ($categoryproduct->mainproducts as $product)
                    @php
                        $firstcatepro=App\Models\Product::with(['sizes','weights','reviews'])->where('id',json_decode($product->RelatedProductIds)[0]->productID)->select('id','ProductName')->first();
                        $weight=App\Models\Weight::where('product_id',$firstcatepro->id)->select('id','product_id','Discount','RegularPrice','SalePrice')->first();
                        $size=App\Models\Size::where('product_id',$firstcatepro->id)->select('id','product_id','Discount','RegularPrice','SalePrice')->first();
                        $review = App\Models\Review::where('product_id', $firstcatepro->id)->avg(
                                                        'rating',
                                                    );
                    @endphp
                    @if(isset($firstcatepro))
                        <div class="mb-2 col-6 col-md-4 col-lg-2">
                        <div class="product">
                                <div class="product-micro">
                                    <div class="row product-micro-row">
                                        <div class="col-12">
                                            <div class="product-image" style="position: relative;">
                                                <div class="text-center image">
                                                    <a href="{{ url('view-product/' . $product->ProductSlug) }}">
                                                        <img src="{{ asset($product->ProductImage) }}"
                                                            alt="{{ $product->ProductName }}" id="featureimagenew">
                                                    </a>
                                                </div>
                                                @if (isset($size))
                                                    <span id="discountpart"> <p id="pdis">SAVE ৳{{ round($size->Discount) }}</p></span>
                                                @else
                                                    <span id="discountpart"> <p id="pdis">SAVE ৳{{ round($weight->Discount) }}</p></span>
                                                @endif

                                            </div>
                                            <!-- /.product-image -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-12">
                                            <div class="p-2 infofe p-md-2" style="border-top:none;background: white;">
                                                <div class="product-info">
                                                    <h2 class="name text-truncate" id="f_name"><a
                                                            href="{{ url('view-product/' . $product->ProductSlug) }}"
                                                            id="f_pro_name">{{ $firstcatepro->ProductName }}</a>
                                                    </h2>
                                                </div>

                                                <div class="d-flex" style="justify-content:space-between">
                                                    <div class="star" style="padding-top: 5px;">
                                                        <span style="font-weight: bold;color:black;font-size:10px">({{ App\Models\Review::where('product_id', $firstcatepro->id)->select('id')->get()->count() }})</span>
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
                                                    <div class="like">
                                                        <div class="d-flex" style="justify-content: space-around;font-size: 14px;">
                                                            <span style="padding-right:14px;"><span class="sts" style="padding-right: 2px;font-size:12px;"
                                                                    id="likereactof{{ $firstcatepro->id }}">{{ App\Models\React::where('product_id', $firstcatepro->id)->where('sigment','like')->get()->count() }}</span><i
                                                                    @if(App\Models\React::where('product_id', $firstcatepro->id)->whereIn('user_id', [\Request::ip(),Auth::id()])->where('sigment','like')->first()) style="color:green !important" @endif class="fas fa-thumbs-up" id="likereactdone{{ $firstcatepro->id }}"
                                                                    onclick="givereactlike({{ $firstcatepro->id }})"></i></span>
                                                            <span><span class="sts" style="padding-right: 2px;font-size:12px;"
                                                                    id="lovereactof{{ $firstcatepro->id }}">{{ App\Models\React::where('product_id', $firstcatepro->id)->where('sigment','love')->get()->count() }}</span><i
                                                                    @if(App\Models\React::where('product_id', $firstcatepro->id)->whereIn('user_id', [\Request::ip(),Auth::id()])->where('sigment','love')->first()) style="color:red !important" @endif class="fas fa-heart" id="lovereactdone{{ $firstcatepro->id }}"
                                                                    onclick="givereactlove({{ $firstcatepro->id }})"></i></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if ($size)
                                                <div class="price-box">
                                                    <del class="old-product-price strong-400">৳
                                                        {{ round($size->RegularPrice) }}</del>
                                                    <span
                                                        class="product-price strong-600">৳ {{ round($size->SalePrice) }}</span>
                                                </div>
                                                @else
                                                <div class="price-box">
                                                    <del class="old-product-price strong-400">৳
                                                        {{ round($weight->RegularPrice) }}</del>
                                                    <span
                                                        class="product-price strong-600">৳ {{ round($weight->SalePrice) }}</span>
                                                </div>
                                                @endif

                                            </div>

                                            <a href="{{ url('view-product/' . $product->ProductSlug) }}">
                                                <button class="mb-0 btn btn-danger btn-sm btn-block"
                                                        style="width: 100%;border-radius: 0%;" id="purcheseBtn">অর্ডার করুন</button>
                                            </a>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- /.product-micro-row -->
                                </div>

                                <!-- /.product-micro -->

                            </div>
                    </div>
                    @endif
                @empty
                @endforelse

                </div>
            </div>
        </div>
    @else
    @endif

@empty
@endforelse


<!-- add section -->
<div class="container p-0 mb-2 mb-lg-3">
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
        <br>
        <br>
        <div class="col-12 col-lg-12">
            <div class="px-2 pt-0 p-md-3" style="padding-bottom:4px !important;padding-top: 8px !important;">
                <a href="{{url('rashi-multimedia')}}"><h4 class="m-0" style="text-align: center;padding-bottom: 12px;font-size: 30px;"><b>Danpite Tech Multimedia</b></h4></a>
            </div>
            <div class="owl-carousel owl-theme" id="youtube">
                @forelse (App\Models\Menu::where('status','Active')->get() as $yout)
                    <div class="item" style="margin:0 !important;">
                        <iframe width="100%"
                            src="https://www.youtube.com/embed/{{ $yout->menu_banner }}">
                        </iframe>
                    </div>
                @empty
                @endforelse
            </div>

        </div>
    </div>
</div>


@if (Auth::id())
    <input type="hidden" name="user_id" id="user_id" value="{{ Auth::id() }}">
@else
    <input type="hidden" name="user_id" id="user_id" >
@endif

@if (Auth::id())
    <input type="hidden" name="user_id" id="user_id" value="{{ Auth::id() }}">
@else
    <input type="hidden" name="user_id" id="user_id" >
@endif

<script>
    function givereactlike(id) {
        $.ajax({
            type: 'GET',
            url: '{{ url('give/react/') }}'+'/like',
            data: {
                'user_id': $('#user_id').val(),
                'product_id': id,
            },

            success: function(data) {
                if (data.sigment == 'like') {
                    $('#promotionalofferSlide #likereactof' + id).text(data.total);
                    $('#promotionalofferSlide #likereactdone' + id).css('color', 'green');
                    $('#propro #likereactof' + id).text(data.total);
                    $('#propro #likereactdone' + id).css('color', 'green');
                }else if (data.sigment == 'unlike') {
                    $('#promotionalofferSlide #likereactof' + id).text(data.total);
                    $('#promotionalofferSlide #likereactdone' + id).css('color', 'black');
                    $('#propro #likereactof' + id).text(data.total);
                    $('#propro #likereactdone' + id).css('color', 'black');
                }else {

                }
            },
            error: function(error) {
                console.log('error');
            }
        });
    }

    function givereactlove(id) {
        $.ajax({
            type: 'GET',
            url: '{{ url('give/react/') }}'+'/love',
            data: {
                'user_id': $('#user_id').val(),
                'product_id': id,
            },

            success: function(data) {
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
            error: function(error) {
                console.log('error');
            }
        });
    }
</script>
@endsection
