@extends('webview.master')

@section('maincontent')
    @section('title')
        {{ env('APP_NAME') }}-Search Products
    @endsection

    <style>
        #checked {
            color: orange;
        }

        .star {
            font-size: 8px !important;
        }

        #featureimageCt {
            height: 300px;
            width: auto;
            padding: 2px;
            padding-top: 0;
        }

        @media only screen and (max-width: 600px) {
            #featureimageCt {
                height: 220px;
                width: auto;
                padding: 2px;
                padding-top: 0;
            }
        }
    </style>
    <div class="body-content outer-top-xs">
        <div class="breadcrumb pt-2">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb-inner p-0">
                            <ul class="list-inline list-unstyled mb-0">
                                <li><a href="#"
                                        style="text-transform: capitalize !important;color: #888;padding-right: 12px;font-size: 12px;">Home
                                        > Search > <span class="active"></span>Products</span>
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- /.breadcrumb-inner -->
                </div>
            </div>
            <!-- /.container -->
        </div>

        <div class="container">

            <div class="row g-4">
                <!-- <div class="owl-carousel " id="promotionalofferSlide"> -->
                @forelse ($searchproducts as $promotional)
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

                                                        {{-- <form>
                                                            <button class="addtocartbtn">
                                                                Add To Cart
                                                            </button>
                                                        </form> --}}
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
        <!-- /.container -->

    </div>
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
                        $('#cateoryPro #likereactof' + id).text(data.total);
                        $('#cateoryPro #likereactdone' + id).css('color', 'orange');
                    } else if (data.sigment == 'unlike') {
                        $('#cateoryPro #likereactof' + id).text(data.total);
                        $('#cateoryPro #likereactdone' + id).css('color', 'black');
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
                        $('#cateoryPro #lovereactof' + id).text(data.total);
                        $('#cateoryPro #lovereactdone' + id).css('color', 'orange');
                    } else {
                        $('#cateoryPro #lovereactof' + id).text(data.total);
                        $('#cateoryPro #lovereactdone' + id).css('color', 'black');
                    }
                },
                error: function (error) {
                    console.log('error');
                }
            });
        }
    </script>
@endsection