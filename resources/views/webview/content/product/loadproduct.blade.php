<link rel="stylesheet" href="{{ asset('public/webview/assets/css/zoomsl.css') }}">
<div class='row single-product'>
    <div class='p-0 col-md-12'>
        <div class="detail-block">
            <div class="row wow fadeInUp">

                <div class="col-xs-12 col-sm-12 col-md-6 gallery-holder">
                    <div class="product-item-holder size-big single-product-gallery small-gallery">

                        @if(json_decode($productdetails->PostImage))
                            <div id="sync1" class="owl-carousel owl-theme">
                                <div class="items">
                                    <img class="w-100 h-100 block__pic" src="{{ asset($productdetails->ProductImage) }}"
                                        alt="" style="border-radius: 4px;">
                                </div>
                                @forelse (json_decode($productdetails->PostImage) as $image)
                                    <div class="items">
                                        <img class="w-100 h-100 block__pic" src="{{asset('public/images/product/slider')}}/{{$image}}"
                                            alt="" style="border-radius: 4px;">
                                    </div>
                                @empty
                                @endforelse
                            </div>
                            <div id="sync2" class="owl-carousel owl-theme" style="padding-top: 10px;">
                                <div class="items">
                                    <img class="w-100 h-100"
                                        style="padding:6px;border:1px solid;border-radius: 4px;"
                                        src="{{ asset($productdetails->ProductImage) }}" alt="">
                                </div>
                                @forelse (json_decode($productdetails->PostImage) as $image)
                                    <div class="items">
                                        <img class="w-100 h-100"
                                            style="padding:6px;border:1px solid;border-radius: 4px;"
                                            src="{{asset('public/images/product/slider')}}/{{$image}}" alt="">
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        @else
                            <div class="items">
                                <img class="w-100 h-100" src="{{ asset($productdetails->ProductImage) }}"
                                    alt="" style="border-radius: 4px;">
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
                            {{ $productdetails->ProductName }}</h1>

                        <div class="stock-container info-container m-t-10"
                            style="margin-top:5px;">
                            <div class="row" style="margin-bottom:5px;">
                                <div class="col-12">
                                    @if (App\Models\Size::where('product_id', $productdetails->id)->first())
                                        <div class="product-price strong-700"
                                            style="color:black;font-weight:bold;padding-top: 6px;" id="productPriceAmount">
                                            <span id="salePrice">{{ App\Models\Size::where('product_id', $productdetails->id)->first()->SalePrice }}</span> TK
                                            @if(App\Models\Size::where('product_id', $productdetails->id)->first()->Discount>0) &nbsp;<del class="old-product-price strong-400" style="color: #fe0909;font-size: 20px;">{{ round(App\Models\Size::where('product_id',$productdetails->id)->first()->RegularPrice) }}</del>@endif
                                        </div>
                                    @else
                                        <div class="product-price strong-700"
                                            style="color:black;font-weight:bold;padding-top: 6px;" id="productPriceAmount">
                                            <span id="salePrice" style="color:black;font-weight:bold;">{{ App\Models\Weight::where('product_id', $productdetails->id)->first()->SalePrice }}</span> TK
                                        </div>
                                    @endif
                                </div>

                            </div>
                            <!-- /.row -->
                        </div>

                        <div class="mt-2 mb-2 row">

                            <div class="mt-2 col-12 col-md-12 colorpart">
                                <div id="breaftext">
                                    {!! $productdetails->ProductBreaf !!}
                                </div>
                            </div>

                            @if (empty(json_decode($singlemain->RelatedProductIds)))
                            @else
                                <div class="mb-2 col-12 col-md-12 colorpart">
                                    <h4 id="productselect" class="m-0"><b style="font-size:14px">Select Product Colours: </b></h4>
                                    <div class="d-flex">
                                        <div class="colorinfo">
                                            @forelse (json_decode($singlemain->RelatedProductIds) as $key=>$ids)
                                                @php
                                                    $prodinfo=App\Models\Product::where('id',$ids->productID)->first();
                                                @endphp
                                                <input type="radio" class="m-0" id="relproduct{{ $prodinfo->id }}" hidden name="relproduct" onclick="getrelproduct('{{ $prodinfo->id }}','{{ $singlemain->id }}')">
                                                <label class="relproduct ms-0" id="relproducttext{{ $prodinfo->id }}" for="relproduct{{ $prodinfo->id }}" style="border: 1px solid #000;padding: 0px;" onclick="getrelproduct('{{ $prodinfo->id }}','{{ $singlemain->id }}')">
                                                    <img src="{{ asset($prodinfo->ProductImage) }}" alt="" style="width:60px;">
                                                </label>
                                            @empty
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (count($sizes) < 1)
                            @else
                                <div class="col-12 col-md-12 colorpart">
                                    <h4 id="resellerprice" class="m-0"><b style="font-size:14px">Select Product Sizes: </b></h4>
                                    <div class="sizeinfo">
                                        @forelse ($sizes as $sizesold)
                                            @if ($sizesold->available_stock > 0)
                                                <input type="hidden" name="regularpriceofsize"
                                                    id="regularpriceofsize{{ $sizesold->id }}"
                                                    value="{{ $sizesold->RegularPrice }}">
                                                <input type="hidden" name="salepriceofsize"
                                                    id="salepriceofsize{{ $sizesold->id }}"
                                                    value="{{ $sizesold->SalePrice }}">
                                                <input type="radio" class="m-0" hidden
                                                    id="size{{ $sizesold->id }}" name="size"
                                                    onclick="getsize('{{ $sizesold->id }}')">
                                                <label class="sizetext ms-0"
                                                    id="sizetext{{ $sizesold->id }}"
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
                                                <input type="radio" class="m-0" hidden
                                                    id="size{{ $sizesold->id }}" name="size">
                                                <label class="sizetext ms-0"
                                                    id="sizetext{{ $sizesold->id }}"
                                                    for="size{{ $sizesold->id }}"
                                                    style="border: 1px solid #e4e4e4;    color: rgb(151 150 150) !important;font-size:18px;font-weight:bold;padding: 0px 8px;border-radius: 2px;margin-right:4px;margin-bottom:4px;"><del>{{ $sizesold->size }}
                                                    </del> </label>
                                            @endif

                                        @empty
                                        @endforelse
                                    </div>
                                </div>
                            @endif
                            @if (count($weights) < 1)
                            @else
                                <div class="col-12 col-md-12 colorpart">
                                    <h4 id="resellerprice" class="m-0"><b style="font-size:14px">সিলেক্ট করে কনফার্ম করুনঃ</b></h4>
                                    <div class="sizeinfo">
                                        @forelse ($weights as $weight)
                                            <input type="hidden" name="regularpriceofsize"
                                                id="regularpriceofsize{{ $weight->id }}"
                                                value="{{ $weight->RegularPrice }}">
                                            <input type="hidden" name="salepriceofsize"
                                                id="salepriceofsize{{ $weight->id }}"
                                                value="{{ $weight->SalePrice }}">
                                            <input type="hidden" name="weightsigmrnt"
                                                id="weightsigmrnt{{ $weight->id }}"
                                                value="{{ $weight->weight }}">
                                            <input type="radio" class="m-0" hidden
                                                id="size{{ $weight->id }}" name="size"
                                                onclick="getweight('{{ $weight->id }}')">
                                            <label class="weighttext ms-0"
                                                id="weighttext{{ $weight->id }}"
                                                for="size{{ $weight->id }}"
                                                style="border: 1px solid #e4e4e4;font-size:16px;font-weight:bold;padding: 0px 6px;border-radius: 2px;margin-right:4px;margin-bottom:4px;"
                                                onclick="getweight('{{ $weight->id }}')">{{ $weight->weight }}</label>
                                        @empty
                                        @endforelse
                                    </div>
                                </div>
                            @endif
                        </div>
                        <p for="" style=" margin: 0; padding-top: 1px;text-align:left">Product Code : {{ $productdetails->ProductSku }}</p>



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
                                        <input type="hidden" name="sigment" id="product_sigmentorder"
                                            value="">
                                        <input type="hidden" name="price" id="product_priceorder" value="">

                                        <input type="hidden" name="product_id" value=" {{ $productdetails->id }}"
                                            hidden>
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
                                                    <a href="#" class="delivery-link mt-2">Enter your postal code for
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
                                                    <span class="d-flex mt-2">Free 30 Days Delivery Returns. <a href="#"
                                                            class="delivery-link">Details</a></span>
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


{{-- modal for process and cart --}}
@if (App\Models\Size::where('product_id', $productdetails->id)->first())
    <input type="hidden" id="gtmprice" value="{{ App\Models\Size::where('product_id', $productdetails->id)->first()->SalePrice }}">
    <input type="hidden" id="gtmdiscount" value="{{App\Models\Size::where('product_id', $productdetails->id)->first()->RegularPrice-App\Models\Size::where('product_id', $productdetails->id)->first()->SalePrice}}">
@else
    <input type="hidden" id="gtmprice" value="{{ App\Models\Weight::where('product_id', $productdetails->id)->first()->SalePrice }}">
    <input type="hidden" id="gtmdiscount" value="{{App\Models\Weight::where('product_id', $productdetails->id)->first()->RegularPrice-App\Models\Weight::where('product_id', $productdetails->id)->first()->SalePrice}}">
@endif

<input type="hidden" id="gtmproductname" value="{{$productdetails->ProductName}}">
<input type="hidden" id="gtmcategory" value="{{App\Models\Category::where('id',$productdetails->category_id)->first()->category_name}}">
<input type="hidden" id="gtmproductid" value="{{$productdetails->id}}">
<input type="hidden" id="gtmproductsku" value="{{$productdetails->ProductSku}}">


<script>
$(document).ready( function(){

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
        .on('initialized.owl.carousel', function() {
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
        //if you set loop to false, you have to restore this next line
        //var current = el.item.index;

        //if you disable loop you have to comment this block
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

    sync2.on("click", ".owl-item", function(e) {
        e.preventDefault();
        var number = $(this).index();
        sync1.data('owl.carousel').to(number, 300, true);
    });


    $('#AddToCartForm').submit(function(e) {
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

            success: function(data) {
                updatecart();
                $.ajax({
                    type: 'GET',
                    url: '{{ url('get-cart-content') }}',

                    success: function(response) {
                        $('#cartViewModal .modal-body').empty().append(
                            response);
                    },
                    error: function(error) {
                        console.log('error');
                    }
                });
                $('#processing').modal('hide');
                $('#cartViewModal').modal('show');
            },
            error: function(error) {
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

var gtmprice=$('#gtmprice').val();
var gtmqty=$('#proQuantity').val();
var gtmid=$('#gtmproductid').val();
var gtmsku=$('#gtmproductsku').val();
var gtmproductname=$('#gtmproductname').val();
var gtmcategory=$('#gtmcategory').val();
var gtmdiscount=$('#gtmdiscount').val();

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
            item_id: gtmsku,
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
    $(document).ready(function() {
        document.getElementById('submitaddtocart').addEventListener('submit', function(event) {
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
                        item_id: gtmsku,
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
<script src="{{ asset('public/webview/assets/js/zoomsl.min.js') }}"></script>
    <script type="text/javascript">
        $(".block__pic").imagezoomsl({
            zoomrange: [3, 3]
        });
    </script>
