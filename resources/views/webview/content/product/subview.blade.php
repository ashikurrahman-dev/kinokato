<style>
    #featureimageCt {
        height: 180px;
        width: auto;
        padding: 2px;
        padding-top: 0;
    }

    .star {
        font-size: 8px !important;
    }

    #checked {
        color: orange;
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

<div class="row pt-2 pb-2" id="cateoryPro" style="background:#efefef !important;">

    <div class="pb-2 bg-white">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <div class="title-top">
                        <span class="bar"></span>
                        <span class="category-text">{{ $subcategory->sub_category_name }} Products</span>
                    </div>
                    <div style="display:flex; align-items:center; justify-content: space-between;margin:20px 0">
                        <h2 class="m-0 main-title">adf Products</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <!-- <div class="owl-carousel " id="promotionalofferSlide"> -->
            @forelse ($subcategoryproducts as $promotional)
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
                                                                class="p-0 m-0 wishlist-form">
                                                                @csrf
                                                                <input type="hidden" name="product_id"
                                                                    value="{{ $promotional->id }}">

                                                                <button type="submit" class="wishlist-btn">
                                                                    @php
                                                                        $wishlist = session()->get('wishlist', []);
                                                                        $inWishlist = in_array($promotional->id, $wishlist);
                                                                    @endphp

                                                                    @if($inWishlist)
                                                                        <i class="fa-solid fa-heart fs-5"
                                                                            style="font-size:18px;color:black;"></i>
                                                                    @else
                                                                        <i class="fa-regular fa-heart fs-5"
                                                                            style="font-size:18px;color:black;"></i>
                                                                    @endif
                                                                </button>
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
            <!-- </div> -->
            @if ($subcategoryproducts->hasPages())
                <div class="pagination-wrapper">
                    <ul class="pagination">

                        {{-- Previous --}}
                        @if ($subcategoryproducts->onFirstPage())
                            <li class="disabled"><span>«</span></li>
                        @else
                            <li>
                                <a href="{{ $subcategoryproducts->previousPageUrl() }}">«</a>
                            </li>
                        @endif

                        {{-- Page Numbers --}}
                        @for ($i = 1; $i <= $subcategoryproducts->lastPage(); $i++)
                            @if ($i == $subcategoryproducts->currentPage())
                                <li class="active"><span>{{ $i }}</span></li>
                            @else
                                <li>
                                    <a href="{{ $subcategoryproducts->url($i) }}">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor

                        {{-- Next --}}
                        @if ($subcategoryproducts->hasMorePages())
                            <li>
                                <a href="{{ $subcategoryproducts->nextPageUrl() }}">»</a>
                            </li>
                        @else
                            <li class="disabled"><span>»</span></li>
                        @endif

                    </ul>
                </div>
            @endif
        </div>

    </div>
    <style>
        .pagination-wrapper {
            text-align: center;
            margin-top: 30px;
        }

        .pagination {
            list-style: none;
            display: inline-flex;
            gap: 6px;
            padding: 0;
        }

        .pagination li a,
        .pagination li span {
            display: block;
            padding: 8px 14px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
            border-radius: 6px;
        }

        .pagination li.active span {
            background: #ff6a00;
            color: #fff;
            border-color: #ff6a00;
        }

        .pagination li a:hover {
            background: #f5f5f5;
        }

        .pagination li.disabled span {
            color: #aaa;
        }
    </style>

</div>
<script>
    $(document).ready(function () {
        $('.quick-shop-btn').on('click', function () {
            var productId = $(this).data('product-id');

            $('#quickShopModalBody').html('<p>Loading...</p>');

            $('#quickShopModal').modal('show');

            $.ajax({
                url: '{{url("quick-shop")}}/' + productId, // your route
                type: 'GET',
                success: function (response) {
                    $('#quickShopModalBody').html(response);
                },
                error: function () {
                    $('#quickShopModalBody').html('<p>Something went wrong!</p>');
                }
            });
        });
    });

</script>

<script>
    document.querySelectorAll('.wishlist-form').forEach(function (form) {
        form.addEventListener('submit', function () {
            setTimeout(function () {
                window.location.reload();
            }, 300);
        });
    });
</script>