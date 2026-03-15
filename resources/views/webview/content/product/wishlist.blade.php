@extends('webview.master')

@section('maincontent')

    @section('title')
        {{ env('APP_NAME') }}-Wishlist
    @endsection

    <div class="container">
        @if (count($products) > 0)
            <div class="pb-2 bg-white row">
                <div class="col-12">
                    <div class="section-title">
                        <div style="display:flex; align-items:center; justify-content: space-between;margin:20px 0">
                            <h2 class="m-0 main-title">Wishlists</h2>
                            <div>

                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="owl-carousel " id="promotionalofferSlide">
                        @forelse ($products as $promotional)
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

        @else
        @endif
    </div>

    {{-- <a href="{{ route('wishlist.remove', $promotional->id) }}">
        <i class="bi bi-x-lg"></i>
    </a> --}}


@endsection
