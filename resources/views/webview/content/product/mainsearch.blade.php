@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-Search Products
@endsection

<style>
    #checked {
        color: orange;
    }
    .star{
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


    <div class='container'>
        <div class='row'>
            <!-- /.sidebar -->
            <div class='col-md-12' id="cateoryPro">
                <div class="container" >

                    <div class="row pt-2 pb-2" style="background: white;">

                        @forelse ($searchproducts as $product)

                            @php
                                $firstcatepro=App\Models\Product::where('id',json_decode($product->RelatedProductIds)[0]->productID)->first();
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
                                                                    <img src="{{ asset($product->ProductImage) }}" >
                                                                </a>
                                                            </div> 
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
                                                                     
                                                                        <span class="fas fa-star" id="checked"></span>
                                                                        <span class="fas fa-star" id="checked"></span>
                                                                        <span class="fas fa-star" id="checked"></span>
                                                                        <span class="fas fa-star" id="checked"></span>
                                                                        <span class="fas fa-star" id="checked"></span> 
                                                                </div>
        
                                                            </div>
        
                                                           <div class="price-box">
                                                                <del class="old-product-price strong-400">৳
                                                                    {{ round($firstcatepro->sizes[0]->RegularPrice) }}</del>
                                                                <span
                                                                    class="product-price strong-600">৳ {{ round($firstcatepro->sizes[0]->SalePrice) }}</span>
                                                            </div>
        
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
                            <h2 class="p-4 text-center"><b>No Products found...</b></h2>
                        @endforelse
                    </div>

                </div>
                <!-- /.category-product -->


                <!-- /.tab-content -->
                <div class="clearfix filters-container">
                    <div class="text-right">
                        <div class="pagination-container">

                        </div>
                        <!-- /.pagination-container -->
                    </div>
                    <!-- /.text-right -->

                </div>
                <!-- /.filters-container -->

            </div>
            <!-- /.col -->
        </div>

        <!-- ============================================== BRANDS CAROUSEL : END ============================================== -->
    </div>
    <!-- /.container -->

</div>
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
                    $('#cateoryPro #likereactof' + id).text(data.total);
                    $('#cateoryPro #likereactdone' + id).css('color', 'orange');
                }else if (data.sigment == 'unlike') {
                    $('#cateoryPro #likereactof' + id).text(data.total);
                    $('#cateoryPro #likereactdone' + id).css('color', 'black');
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
                    $('#cateoryPro #lovereactof' + id).text(data.total);
                    $('#cateoryPro #lovereactdone' + id).css('color', 'orange');
                } else {
                    $('#cateoryPro #lovereactof' + id).text(data.total);
                    $('#cateoryPro #lovereactdone' + id).css('color', 'black');
                }
            },
            error: function(error) {
                console.log('error');
            }
        });
    }
</script>
@endsection
