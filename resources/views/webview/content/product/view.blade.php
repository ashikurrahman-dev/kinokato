<style>
    #featureimageCt {
        height: 180px;
        width: auto;
        padding: 2px;
        padding-top: 0;
    }
    @media only screen and (max-width: 600px) {
       #featureimageCt {
           height: 180px;
            width: auto;
            padding: 2px;
            padding-top: 0;
        }
    }
</style>
<div class="row pt-2 pb-2" id="cateoryPro" style="background: white;">

    @forelse ($categoryproducts as $categoryproduct)
        <div class="col-6 col-md-4 col-lg-2 mb-4">
            <div class="product">
                    <div class="product-micro">
                        <div class="row product-micro-row">
                            <div class="col-12">
                                <div class="product-image" style="position: relative;">
                                    <div class="image text-center">
                                        <a href="{{ url('product/' . $categoryproduct->ProductSlug) }}">
                                            <img src="{{ asset($categoryproduct->ViewProductImage) }}"
                                                alt="{{ $categoryproduct->ProductName }}" id="featureimage">
                                        </a>
                                    </div>
                                    @if (App\Models\Size::where('product_id',$categoryproduct->id)->first())
                                        <span id="discountpart"> <p id="pdis">SAVE ৳{{ round(App\Models\Size::where('product_id',$categoryproduct->id)->first()->Discount) }}</p></span>
                                    @else
                                        <span id="discountpart"> <p id="pdis">SAVE ৳{{ round(App\Models\Weight::where('product_id',$categoryproduct->id)->first()->Discount) }}</p></span>
                                    @endif

                                </div>
                                <!-- /.product-image -->
                            </div>
                            <!-- /.col -->
                            <div class="col-12">
                                <div class="infofe p-md-3 p-2" style="border: 1px solid #e3e1e1;border-top:none;">
                                    <div class="product-info">
                                        <h2 class="name text-truncate" id="f_name"><a
                                                href="{{ url('product/' . $categoryproduct->ProductSlug) }}"
                                                id="f_pro_name">{{ $categoryproduct->ProductName }}</a>
                                        </h2>
                                    </div>
                                    @php
                                        $review = App\Models\Review::where('product_id', $categoryproduct->id)->avg(
                                            'rating',
                                        );
                                    @endphp
                                    <div class="d-flex" style="justify-content:space-between">
                                        <div class="star" style="padding-top: 5px;">
                                            <span style="font-weight: bold;color:black;font-size:10px">({{ App\Models\Review::where('product_id', $categoryproduct->id)->get()->count() }})</span>
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
                                                        id="likereactof{{ $categoryproduct->id }}">{{ App\Models\React::where('product_id', $categoryproduct->id)->where('sigment','like')->get()->count() }}</span><i
                                                        @if(App\Models\React::where('product_id', $categoryproduct->id)->whereIn('user_id', [\Request::ip(),Auth::id()])->where('sigment','like')->first()) style="color:green !important" @endif class="fas fa-thumbs-up" id="likereactdone{{ $categoryproduct->id }}"
                                                        onclick="givereactlike({{ $categoryproduct->id }})"></i></span>
                                                <span><span class="sts" style="padding-right: 2px;font-size:12px;"
                                                        id="lovereactof{{ $categoryproduct->id }}">{{ App\Models\React::where('product_id', $categoryproduct->id)->where('sigment','love')->get()->count() }}</span><i
                                                        @if(App\Models\React::where('product_id', $categoryproduct->id)->whereIn('user_id', [\Request::ip(),Auth::id()])->where('sigment','love')->first()) style="color:red !important" @endif class="fas fa-heart" id="lovereactdone{{ $categoryproduct->id }}"
                                                        onclick="givereactlove({{ $categoryproduct->id }})"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    @if (App\Models\Size::where('product_id',$categoryproduct->id)->first())
                                    <div class="price-box">
                                        <del class="old-product-price strong-400">৳
                                            {{ round(App\Models\Size::where('product_id',$categoryproduct->id)->first()->RegularPrice) }}</del>
                                        <span
                                            class="product-price strong-600">৳ {{ round(App\Models\Size::where('product_id',$categoryproduct->id)->first()->SalePrice) }}</span>
                                    </div>
                                    @else
                                    <div class="price-box">
                                        <del class="old-product-price strong-400">৳
                                            {{ round(App\Models\Weight::where('product_id',$categoryproduct->id)->first()->RegularPrice) }}</del>
                                        <span
                                            class="product-price strong-600">৳ {{ round(App\Models\Weight::where('product_id',$categoryproduct->id)->first()->SalePrice) }}</span>
                                    </div>
                                    @endif

                                </div>

                                <form name="form" action="{{url('add-to-cart')}}" method="POST" enctype="multipart/form-data"
                                    style="width: 100%;float: left;text-align: center;">
                                    @method('POST')
                                    @csrf
                                    <input type="text" name="color" id="product_colorold" value="" hidden>
                                    <input type="text" name="price" id="product_priceold" value="" hidden>
                                    <input type="text" name="size" id="product_sizeold" value="" hidden>
                                    <input type="text" name="product_id" value=" {{ $categoryproduct->id }}"
                                        hidden>
                                    <input type="text" name="qty" value="1" id="qtyor" hidden>
                                    <button class="btn btn-danger btn-sm mb-0 btn-block"
                                            style="width: 100%;border-radius: 0%;" id="purcheseBtn">অর্ডার করুন</button>
                                </form>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.product-micro-row -->
                    </div>

                    <!-- /.product-micro -->

                </div>
        </div>
    @empty
        <h2 class="p-4 text-center"><b>No Products found...</b></h2>
    @endforelse
</div>
