@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-Blogs
@endsection
<style>
    #checked {
        color: orange;
    }
    .star{
        font-size: 8px !important;
    }
</style> 

<!-- /.breadcrumb -->
<div class="body-content outer-top-xs">
    <div class="breadcrumb pt-2">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-inner p-0">
                        <ul class="list-inline list-unstyled mb-0">
                            <li><a href="#"
                                    style="text-transform: capitalize !important;color: #888;padding-right: 12px;font-size: 12px;">Home
                                    > Blogs 
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
                <div class="container p-0">
                     
                    <div class="row pt-2 pb-2" style="background: white;">

                        @forelse ($blogs as $blog)
                            <div class="col-12 col-md-4 col-lg-4 mb-4">
                                <div class="product">
                                    <a href="{{$blog->link}}">
                                        <div class="product-micro">
                                            <div class="row product-micro-row">
                                                <div class="col-12">
                                                    <div class="product-image" style="position: relative;">
                                                        <div class="image text-center"> 
                                                                <img src="{{ asset($blog->image) }}"> 
                                                        </div> 
                                                    </div>
                                                    <!-- /.product-image -->
                                                </div>
                                                <!-- /.col -->
                                                <div class="col-12">
                                                    <div class="infofe p-md-3 p-2" style="border: 1px solid #e3e1e1;border-top:none;">
                                                        <div class="product-info">
                                                            <h2 class="name" style="font-size: 18px;font-weight: bold;"> {{ $blog->title }} 
                                                            </h2>
                                                        </div> 
                                                        <p class="m-0" style="height:100px;overflow:hidden;    text-align: justify;">
                                                            {{ $blog->short_description}}
                                                        </p>

                                                    </div>
 
                                                </div>
                                                <!-- /.col -->
                                            </div>
                                            <!-- /.product-micro-row -->
                                        </div>
                                    </a>         
                                </div>
                            </div>
                        @empty
                            <h2 class="p-4 text-center"><b>No blog found...</b></h2>
                        @endforelse
                    </div>

                </div>

            </div>
            <!-- /.col -->
        </div>

        <!-- ============================================== BRANDS CAROUSEL : END ============================================== -->
    </div>
    <!-- /.container -->

</div>



{{-- csrf --}}
<input type="hidden" name="_token" value="{{ csrf_token() }}" />

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
                    $('#cateoryPro #likereactdone' + id).css('color', 'green');
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
                    $('#cateoryPro #lovereactdone' + id).css('color', 'red');
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

<script>
    var token = $("input[name='_token']").val();



    function shownowpro() {
        $('#processing').modal('show');
        var pricerange = $('.price-slider').val();
        var categorynow = $('#categoryslug').val();
        $.ajax({
            type: 'GET',
            url: '{{ url('get/products/by-category') }}',
            data: {
                _token: token,
                category: categorynow,
                price_range: pricerange
            },

            success: function(response) {
                $('#processing').modal('hide');
                $('#viewCategoryProduct').empty().append(response);
            },
            error: function(error) {
                console.log('error');
            }
        });
    }


    function viewcategoryproduct(categoryslug) {
        $('#processing').modal('show');

        $.ajax({
            type: 'GET',
            url: '{{ url('get/products/by-category') }}',
            data: {
                _token: token,
                category: categoryslug,
            },

            success: function(response) {
                $('#processing').modal('hide');
                $('#viewCategoryProduct').empty().append(response);
            },
            error: function(error) {
                console.log('error');
            }
        });
    }

    function shownow() {
        $('#processing').modal('show');
        var pricerange = $('.price-slider').val();
        var categoryslg = $('#categoryslug').val();
        $.ajax({
            type: 'GET',
            url: '{{ url('get/products/by-category') }}',
            data: {
                _token: token,
                category: categoryslg,
                price_range: pricerange
            },

            success: function(response) {
                $('#processing').modal('hide');
                $('#viewCategoryProduct').empty().append(response);
            },
            error: function(error) {
                console.log('error');
            }
        });
    }

    function viewsubcategoryproduct(subcategoryslug) {
        $('#processing').modal('show');
        var pricerange = $('.price-slider').val();

        $.ajax({
            type: 'GET',
            url: '{{ url('get/products/by-subcategory') }}',
            data: {
                _token: token,
                subcategory: subcategoryslug,
                price_range: pricerange
            },

            success: function(response) {
                $('#processing').modal('hide');
                $('#viewCategoryProduct').empty().append(response);
            },
            error: function(error) {
                console.log('error');
            }
        });
    }

    function viewcategoryproduct(categoryslug) {
        $('#processing').modal('show');

        $.ajax({
            type: 'GET',
            url: '{{ url('get/products/by-category') }}',
            data: {
                _token: token,
                category: categoryslug,
            },

            success: function(response) {
                $('#processing').modal('hide');
                $('#viewCategoryProduct').empty().append(response);
            },
            error: function(error) {
                console.log('error');
            }
        });
    }
</script>

<style>
    @media only screen and (max-width: 768px) {
        #cateoryProSidebar {
            padding-right: 0;
        }

        #cateoryPro {
            padding-left: 0;
        }
    }

    #cateoryProSidebar {
        padding-left: 0;
    }

    #cateoryPro {
        padding-right: 0px;
    }

    .sidebar-widget .sidebar-widget-body .accordion .accordion-group .accordion-heading .accordion-toggle.collapsed:after {
        color: #636363;
        content: "\f067";
        font-family: fontawesome;
        font-weight: normal;
    }

    .sidebar .sidebar-module-container .sidebar-widget .sidebar-widget-body .accordion .accordion-group .accordion-heading .accordion-toggle:after {
        content: "\f068";
        float: right;
        font-family: fontawesome;
    }

    .widget-title {
        font-size: 16px;
        text-align: center;
        border-bottom: 1px solid #e9e9e9;
        padding-bottom: 10px;
        margin: 0;
    }

    .list {
        list-style: none;
    }

    #liaside {
        color: #858585;
        font-weight: bold;
    }

    .breadcrumb {
        padding: 5px 0;
        border-bottom: 1px solid #e9e9e9;
        background-color: #fafafa;
    }
</style>

@endsection
