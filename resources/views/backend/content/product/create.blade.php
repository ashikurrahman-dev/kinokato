@extends('backend.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}- Products
@endsection

<style>
    div#roleinfo_length {
        color: red;
    }

    div#roleinfo_filter {
        color: red;
    }

    div#roleinfo_info {
        color: red;
    }
    #collupshead{
        width: 100%;
        display: flex;
        justify-content: space-between;
    }
    #taka{
        font-size: 25px;
        padding-left: 14px;
        color: black;
    }
</style>
{{-- summernote --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

<div class="container-fluid pt-4 px-4">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="bg-secondary rounded h-100 p-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-center">
                            <h3 class="mb-0">Create New Product</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form name="form" id="AddProducts" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <h5 class="text-uppercase bg-light p-2 mt-0 mb-3" style="color: white !important">General</h5>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group mb-3">
                                                <label for="ProductName">Product Name <span class="text-danger">*</span></label>
                                                <input type="text" name="ProductName" id="ProductName" class="form-control"
                                                    required>
                                            </div>
                                        </div>
                                        
                                            <div class="form-group mb-3 d-none">
                                                <label for="ProductName">Position <span class="text-danger">*</span></label>
                                                <input type="text" name="position" id="position" class="form-control"
                                                    required>
                                            </div>
                                        
                                        <div class="col-6 d-none">
                                            <div class="form-group mb-3">
                                                <label for="ProductCategory" style="width: 100%;">Brand Name </label>
                                                <select class="form-control" id="brand_id" style="background: black;" name="brand_id">
                                                    <option>Select Brands</option>
                                                    @forelse ($brands as $brand)
                                                        <option value="{{ $brand->id }}">
                                                            {{ $brand->brand_name }}
                                                        </option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group mb-3">
                                                <label for="ProductCategory" style="width: 100%;">Categories <span class="text-danger">*</span></label>
                                                <select class="form-control" id="category_id" style="background: black;"
                                                    name="category_id" onchange="setsubcategory()" required>
                                                    <option>Select Category</option>
                                                    @forelse ($categories as $category)
                                                        <option value="{{ $category->id }}">
                                                            {{ $category->category_name }}
                                                        </option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group mb-3">
                                                <label for="ProductCategory" style="width: 100%">Sub Category </label>
                                                <select class="form-control" id="subcategory_id" style="background: black;" name="subcategory_id" >
                                                    <option>Select Sub-Category</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-6 d-none">
                                            <div class="form-group mb-3">
                                                <label for="available_bonus">Bonus Coin</label>
                                                <input type="text" name="bonus_coin" id="bonus_coin" class="form-control" >
                                            </div>
                                        </div>
                                        <!--<div class="col-6">-->
                                        <!--    <div class="form-group mb-3">-->
                                        <!--        <label for="ProductName">Product Code <span class="text-danger">*</span></label>-->
                                        <!--        <input type="text" name="ProductSku" id="ProductSku" class="form-control"-->
                                        <!--            required>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="ProductRegularPrice">Product Short Description <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" name="ProductBreaf" id="ProductBreaf" rows="2"></textarea>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="ProductDetailsss">Product Description <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" id="ProductDetails" name="ProductDetails" rows="5"></textarea>
                                    </div>
                                    <script type="text/javascript">
                                        $(document).ready(function() {
                                            $('#ProductBreaf').summernote();
                                            $('#ProductDetails').summernote();
                                        });
                                    </script>

                                </div>

                                <div class="col-lg-12 mb-4">
                                    <div class="card">
                                        <div class="card-header p-0" id="headingOne">
                                          <h5 class="mb-0">
                                            <button type="button" id="collupshead" class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#collapseMedia" aria-expanded="true" aria-controls="collapseOne">
                                                <h5 class="text-uppercase m-0">Product Media<span class="text-danger">*</span></h5>
                                                <h5 class="text-uppercase m-0">+</h5>
                                            </button>
                                          </h5>
                                        </div>

                                        <div id="collapseMedia" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group mb-3">
                                                            <label for="ProductSalePrice">Youtube Embade Code</label>
                                                            <input type="text" id="youtube_embade" name="youtube_embade"
                                                                class="form-control">
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label for="ProductDetails">Main Image <span
                                                                    class="text-danger">*</span></label>
                                                            <button type="button" class="btn btn-danger d-block mb-2"
                                                                style="background: red">
                                                                <input type="file" name="ProductImage" id="ProductImage"
                                                                    onchange="loadFile(event)">
                                                            </button>
                                                            <div class="single-image image-holder-wrapper clearfix">
                                                                <div class="image-holder placeholder">
                                                                    <img id="prevImage" style="height:100px;width:100px" />
                                                                    <i class="mdi mdi-folder-image"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group"
                                                            style="padding: 10px;padding-top: 3px;margin:0;padding-bottom:3px;width:96%;margin-left: 8px;border-radius: 8px;padding-left: 0;margin-left: -0;">
                                                            <label class="fileContainer">
                                                                <span style="font-size: 20px;">Slider
                                                                    image</span>
                                                            </label>
                                                            <br>
                                                            <button type="button" class="btn btn-danger d-block mb-2"
                                                                style="background: red">
                                                                <input type="file" onchange="prevPost_Img()"
                                                                    name="PostImage[]" id="PostImage" multiple>
                                                            </button>
                                                        </div>
                                                        <div class="file">
                                                            <div id="prevFile"
                                                                style="width: 100%;float:left;background: lightgray;">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-4">
                                    <div class="card">
                                        <div class="card-header p-0" id="headingOne">
                                          <h5 class="mb-0">
                                            <button type="button" id="collupshead" class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#collapseVariant" aria-expanded="true" aria-controls="collapseOne">
                                                <h5 class="text-uppercase m-0">Colour<span class="text-danger">*</span></h5>
                                                <h5 class="text-uppercase m-0">+</h5>
                                            </button>
                                          </h5>
                                        </div>

                                        <div id="collapseVariant" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                          <div class="card-body">
                                            <table id="mediaTable" style="width: 100% !important;" class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Color</th>
                                                    <th>Image</th>
                                                    <th>Choose File</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <td colspan="5">
                                                        <select id="mediavariantID" style="width: 100%;">
                                                            <option value="">Select Product Variant</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                </tfoot>

                                            </table>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-4">
                                    <div class="card">
                                        <div class="card-header p-0" id="headingOne">
                                          <h5 class="mb-0">
                                            <button type="button" id="collupshead" class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#collapseSize" aria-expanded="true" aria-controls="collapseOne">
                                                <h5 class="text-uppercase m-0">Size<span class="text-danger">*</span></h5>
                                                <h5 class="text-uppercase m-0">+</h5>
                                            </button>
                                          </h5>
                                        </div>

                                        <div id="collapseSize" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                          <div class="card-body">
                                            <table id="sizeTable" style="width: 100% !important;" class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Size</th>
                                                    <th>Regular Price</th>
                                                    <th>Sale Price</th>
                                                    <th>Stock</th>
                                                    <th>Trash</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <td colspan="6">
                                                        <select id="sizevariantID" style="width: 100%;">
                                                            <option value="">Select Product Size</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                </tfoot>

                                            </table>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-4">
                                    <div class="card">
                                        <div class="card-header p-0" id="headingOne">
                                          <h5 class="mb-0">
                                            <button type="button" id="collupshead" class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#collapseWeight" aria-expanded="true" aria-controls="collapseOne">
                                                <h5 class="text-uppercase m-0">Product Sigment </h5>
                                                <h5 class="text-uppercase m-0">+</h5>
                                            </button>
                                          </h5>
                                        </div>

                                        <div id="collapseWeight" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                          <div class="card-body">
                                            <table id="weightTable" style="width: 100% !important;" class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Sigment</th>
                                                    <th>Regular Price</th>
                                                    <th>Sale Price</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <td colspan="5">
                                                        <select id="weightvariantID" style="width: 100%;">
                                                            <option value="">Select Product Sigment</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                </tfoot>

                                            </table>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-4">
                                    <div class="card">
                                        <div class="card-header p-0" id="headingOne">
                                          <h5 class="mb-0">
                                            <button type="button" id="collupshead" class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#collapseMeta" aria-expanded="true" aria-controls="collapseOne">
                                                <h5 class="text-uppercase m-0">Product Meta </h5>
                                                <h5 class="text-uppercase m-0">+</h5>
                                            </button>
                                          </h5>
                                        </div>

                                        <div id="collapseMeta" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                          <div class="card-body">
                                                <div class="form-group mb-3">
                                                    <label for="MetaTitle">Meta Title</label>
                                                    <input type="text" name="MetaTitle" id="MetaTitle" class="form-control" >
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="MetaKey">Meta Keyword </label>
                                                    <textarea class="form-control" name="MetaKey" id="MetaKey" rows="2"></textarea>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="MetaDescription">Meta Description</label>
                                                    <textarea class="form-control" id="MetaDescription" name="MetaDescription" rows="5"></textarea>
                                                </div>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <br>
                            <div class="form-group mt-2" style="text-align: right">
                                <div class="submitBtnSCourse">
                                    <button type="button" name="btn" data-bs-dismiss="modal"
                                        class="btn btn-dark btn-block" style="float: left">Close</button>
                                    <button type="button" name="btn" id="submit"
                                        class="btn btn-primary AddCourierBtn btn-block">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var token = $("input[name='_token']").val();

        $(document).on("click", "#submit", function () {
            var brand_id = $("#brand_id");
            var category_id = $("#category_id");
            var bonus_coin = $("#bonus_coin");
            var ProductSku = $("#ProductSku");
            var subcategory_id = $("#subcategory_id");
            var ProductName = $("#ProductName");
            var ProductBreaf = $("#ProductBreaf");
            var ProductDetails = $("#ProductDetails");
            var MetaTitle = $("#MetaTitle");
            var MetaKey = $("#MetaKey");
            var MetaDescription = $("#MetaDescription");
            var youtube_embade = $("#youtube_embade");
            var position = $("#position");

            var variant = [];
            var variantCount = 0 ;
            $("#mediaTable tbody tr").each(function (index, value) {
                var currentRow = $(this);
                var obj = {};
                obj.mediaID = currentRow.find("#mediaID").val();
                obj.color = currentRow.find("#color").val();
                obj.image = currentRow.find("#image")[0].files[0];
                variant.push(obj);
                variantCount++;
            });

            var size = [];
            var sizeCount = 0 ;
            $("#sizeTable tbody tr").each(function (index, value) {
                var currentRow = $(this);
                var obj = {};
                obj.sizeID = currentRow.find("#sizeID").val();
                obj.size = currentRow.find("#size").val();
                obj.RegularPrice = currentRow.find("#RegularPrice").val();
                obj.Discount = currentRow.find("#Discount").val();
                size.push(obj);
                sizeCount++;
            });

            var weight = [];
            var weightCount = 0 ;
            $("#weightTable tbody tr").each(function (index, value) {
                var currentRow = $(this);
                var obj = {};
                obj.weightID = currentRow.find("#weightID").val();
                obj.weight = currentRow.find("#weight").val();
                obj.RegularPrice = currentRow.find("#RegularPrice").val();
                obj.Discount = currentRow.find("#Discount").val();
                weight.push(obj);
                weightCount++;
            });

            if(category_id.val() == ''){
                toastr.error('Category Should Not Be Empty');
                category_id.closest('.form-group').css('border','1px solid red');
                return;
            }
            category_id.closest('.form-group').css('border','1px solid #ced4da');

            if(ProductName.val() == ''){
                toastr.error('Product Name Should Not Be Empty');
                ProductName.css('border','1px solid red');
                return;
            }
            ProductName.css('border','1px solid #ced4da');

            if(variantCount == 0){
                toastr.error('Product Variant Should Not Be Empty');
                return;
            }

            var formData = new FormData();

            formData.append('brand_id', brand_id.val());
            formData.append('category_id', category_id.val());
            formData.append('bonus_coin', bonus_coin.val());
            formData.append('ProductSku', ProductSku.val());
            formData.append('subcategory_id', subcategory_id.val());
            formData.append('ProductName', ProductName.val());
            formData.append('ProductBreaf', ProductBreaf.val());
            formData.append('ProductDetails', ProductDetails.val());
            formData.append('MetaTitle', MetaTitle.val());
            formData.append('MetaKey', MetaKey.val());
            formData.append('MetaDescription', MetaDescription.val());
            formData.append('youtube_embade', youtube_embade.val());
            formData.append('position', position.val());
            formData.append('ProductImage', $('#ProductImage')[0].files[0]);
            var fileList = $('#PostImage').get(0).files;

            if (fileList.length > 0) {
                for (let i = 0; i < fileList.length; i += 1) {
                    formData.append('PostImage[]', fileList[i]);
                }
            }

            variant.forEach((item, index) => {
                Object.entries(item).forEach(([key, value]) => {
                    formData.append(`variant[${index}][${key}]`, value);
                });
            });
            size.forEach((item, index) => {
                Object.entries(item).forEach(([key, value]) => {
                    formData.append(`size[${index}][${key}]`, value);
                });
            });
            weight.forEach((item, index) => {
                Object.entries(item).forEach(([key, value]) => {
                    formData.append(`weight[${index}][${key}]`, value);
                });
            });
            $.ajax({
                type: "POST",
                url: '{{url('admin/products')}}',
                data: formData,
                contentType: false,
                processData: false,

                success: function (response) {
                    var data = JSON.parse(response);
                    if (data["status"] === "success") {
                        toastr.success(data["message"]);
                        window.location.href = "{{ url('admin/products') }}";

                    } else {
                        toastr.error(data["message"])
                    }
                }
            });



        });


        $("#mediavariantID").select2({
            placeholder: "Select a Product Variant",
            templateResult: function (state) {
                if (!state.id) {
                    return state.text;
                }
                var $state = $(
                    '<span>' +
                    state.text +
                    "</span>"
                );
                return $state;
            },
            ajax: {
                type:'GET',
                url: '{{url('admin/product/color')}}',
                processResults: function (data) {
                    var data = $.parseJSON(data);
                    return {
                        results: data.data
                    };
                }
            }
        }).trigger("change").on("select2:select", function (e) {
            $("#mediaTable tbody").append(
                "<tr>" +
                '<td><input type="text" id="mediaID" style="width:80px;border: none;color: black;" value="' + e.params.data.id + '" disabled></td>' +
                '<td><input type="text" name="color" id="color" style="width:80px;border: none;color: black;" value="' + e.params.data.text + '" disabled> </td>' +
                '<td><img src="" style="width:50px"></td>' +
                '<td><input type="file" id="image" class="form-control"></td>' +
                '<td><button type="button" class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i></button></td>\n' +
                "</tr>"
            );
        });

        $("#sizevariantID").select2({
            placeholder: "Select a Product Size",
            templateResult: function (state) {
                if (!state.id) {
                    return state.text;
                }
                var $state = $(
                    '<span>' +
                    state.text +
                    "</span>"
                );
                return $state;
            },
            ajax: {
                type:'GET',
                url: '{{url('admin/product/size')}}',
                processResults: function (data) {
                    var data = $.parseJSON(data);
                    return {
                        results: data.data
                    };
                }
            }
        }).trigger("change").on("select2:select", function (e) {
            $("#sizeTable tbody").append(
                "<tr>" +
                '<td><input type="text" id="sizeID" style="width:80px;border: none;color: black;" value="' + e.params.data.id + '" disabled></td>' +
                '<td><input type="text" name="size" id="size" style="width:50px;border: none;color: black;" value="' + e.params.data.text + '" disabled> </td>' +
                '<td><input type="text" name="RegularPrice" id="RegularPrice" class="form-control" style="width:80px;float:left;">  <span id="taka">TK</span></td>' +
                '<td><input type="text" name="Discount" id="Discount" class="form-control" style="width:70px;float:left;"> <span id="taka">TK</span></td>' +
                '<td><span id="total">Total: 0 pics<br>Avalable: 0 pics<br>Sold: 0 pics<br></span></td>' +
                '<td><button type="button" class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i></button></td>\n' +
                "</tr>"
            );
        });

        $("#weightvariantID").select2({
            placeholder: "Select a Product Sigment",
            templateResult: function (state) {
                if (!state.id) {
                    return state.text;
                }
                var $state = $(
                    '<span>' +
                    state.text +
                    "</span>"
                );
                return $state;
            },
            ajax: {
                type:'GET',
                url: '{{url('admin/product/weight')}}',
                processResults: function (data) {
                    var data = $.parseJSON(data);
                    return {
                        results: data.data
                    };
                }
            }
        }).trigger("change").on("select2:select", function (e) {
            $("#weightTable tbody").append(
                "<tr>" +
                '<td><input type="text" id="weightID" style="width:80px;border: none;color: black;" value="' + e.params.data.id + '" disabled></td>' +
                '<td><input type="text" name="weight" id="weight" style="width:80px;border: none;color: black;" value="' + e.params.data.text + '" disabled> </td>' +
                '<td><input type="text" name="RegularPrice" id="RegularPrice" class="form-control" style="width:100px;float:left;">  <span id="taka">TK</span></td>' +
                '<td><input type="text" name="Discount" id="Discount" class="form-control" style="width:100px;float:left;"> <span id="taka">TK</span></td>' +
                '<td><button type="button" class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i></button></td>\n' +
                "</tr>"
            );
        });

        $(document).on("click", ".delete-btn", function () {
            $(this).closest("tr").remove();
        });

    });

    function setsubcategory() {
        var sub_id = $('#category_id').val();
        $.ajax({
            type: 'GET',
            url: '../get/subcategory/' + sub_id,

            success: function(data) {
                $('#subcategory_id').html('');

                for (var i = 0; i < data.length; i++) {
                    $('#subcategory_id').append(`
                            <option value="` + data[i].id + `" >` + data[i].sub_category_name + `</option>
                        `)
                }
            },
            error: function(error) {
                console.log('error');
            }
        });
    }

    function editsetsubcategory() {
        var sub_id = $('#editcategory_id').val();
        $.ajax({
            type: 'GET',
            url: '../../get/subcategory/' + sub_id,

            success: function(data) {
                $('#editsub_category_id').html('');

                for (var i = 0; i < data.length; i++) {
                    $('#editsub_category_id').append(`
                            <option value="` + data[i].id + `" >` + data[i].sub_category_name + `</option>
                        `)
                }
            },
            error: function(error) {
                console.log('error');
            }
        });
    }
    var loadFile = function(event) {
        var output = document.getElementById('prevImage');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };

     var PostImages = [];

    function prevPost_Img() {
        var PostImage = document.getElementById('PostImage').files;

        for (i = 0; i < PostImage.length; i++) {
            if (check_duplicate(PostImage[i].name)) {
                PostImages.push({
                    "name": PostImage[i].name,
                    "url": URL.createObjectURL(PostImage[i]),
                    "file": PostImage[i],
                });
            } else {
                alert(PostImage[i].name + 'is already added to your list');
            }
        }

        document.getElementById("prevFile").innerHTML = PostImage_show();

    }

    function check_duplicate(name) {
        var PostImage = true;
        if (PostImages.length > 0) {
            for (e = 0; e < PostImages.length; e++) {
                if (PostImages[e].name == name) {
                    PostImage = false;
                    break;
                }
            }
        }
        return PostImage;
    }

    function PostImage_show() {
        var PostImage = "";
        PostImages.forEach((i) => {
            PostImage += `<div class="postImg" style="width:25%;float:left;position:relative;">
                                <img src="` + i.url + `" alt="" id="previewImage" style="border-radius: 10px;width:100%;padding:5px;">
                                <span onclick="removeSelectedPostImage(` + PostImages.indexOf(i) + `)" style="position: absolute;right: 0;cursor: pointer;font-size: 31px;color: red;margin-top: -8px;margin-right: 8px;">&times</span>
                            </div>`;
        })
        return PostImage;
    }

    function removeSelectedPostImage(e) {
        PostImages.splice(e, 1);
        document.getElementById("prevFile").innerHTML = PostImage_show();
    }

    var editPostImages = [];

    function editprevPost_Img() {
        $('#viewprevFile').html('');
        var editPostImage = document.getElementById('editPostImage').files;

        for (i = 0; i < editPostImage.length; i++) {
            if (check_duplicate(editPostImage[i].name)) {
                editPostImages.push({
                    "name": editPostImage[i].name,
                    "url": URL.createObjectURL(editPostImage[i]),
                    "file": editPostImage[i],
                });
            } else {
                alert(editPostImage[i].name + 'is already added to your list');
            }
        }

        document.getElementById("editprevFile").innerHTML = editPostImage_show();

    }

    function check_duplicate(name) {
        var editPostImage = true;
        if (editPostImages.length > 0) {
            for (e = 0; e < editPostImages.length; e++) {
                if (editPostImages[e].name == name) {
                    editPostImage = false;
                    break;
                }
            }
        }
        return editPostImage;
    }

    function editPostImage_show() {
        var editPostImage = "";
        editPostImages.forEach((i) => {
            editPostImage += `<div class="postImg" style="width:25%;float:left;position:relative;">
                                <img src="` + i.url + `" alt="" id="previewImage" style="border-radius: 10px;width:100%;padding:5px;">
                                <span onclick="removeSelectededitPostImage(` + editPostImages.indexOf(i) + `)" style="position: absolute;right: 0;cursor: pointer;font-size: 31px;color: red;margin-top: -8px;margin-right: 8px;">&times</span>
                            </div>`;
        })
        return editPostImage;
    }

    function removeSelectededitPostImage(e) {
        editPostImages.splice(e, 1);
        document.getElementById("editprevFile").innerHTML = editPostImage_show();
    }
</script>


<!-- summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

@endsection
