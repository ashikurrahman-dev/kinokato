@extends('backend.master')

@section('maincontent')
    <div class="container-fluid pt-4 px-4">
        <div class="pagetitle row">
            <div class="col-6">
                <h1><a href="{{url('/admin/dashboard')}}">Dashboard</a></h1>
                <nav>
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/admin/dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Main Products</li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>Product Info</strong>
                    </div>
                    <div class="row p-3">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="ProductName">Product Name</label>
                                <input type="text" class="form-control" id="ProductName">
                            </div>
                            <div class="form-group d-none">
                                <label for="Position">Position</label>
                                <input type="text" class="form-control" id="position">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="ProductName">Product Image</label>
                                <input type="file" class="form-control" onchange="loadFile(event)" id="ProductImage">
                            </div>
                            <div class="single-image image-holder-wrapper clearfix">
                                <div class="image-holder placeholder">
                                    <img id="prevImage" style="height:100px;width:100px" />
                                    <i class="mdi mdi-folder-image"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="ProductName">Product Hover Image</label>
                                <input type="file" class="form-control" onchange="loadFile2(event)" id="ProductHoverImage">
                            </div>
                            <div class="single-image image-holder-wrapper clearfix">
                                <div class="image-holder placeholder">
                                    <img id="prevImage2" style="height:100px;width:100px" />
                                    <i class="mdi mdi-folder-image"></i>
                                </div>
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
                        
                    </div>

                    <div class="card-body">
                        <table id="productTable" style="width: 100% !important;" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Imae</th>
                                <th>Code</th>
                                <th>Product Name</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="4">
                                    <select id="productID" style="width: 100%;">
                                        <option value="">Select Product</option>
                                    </select>
                                </td>
                            </tr>
                            </tfoot>

                        </table>
                        <br>
                    </div>
                    <div class="card-footer">
                        <button type="button" id="submit" class="btn btn-primary btn-block" data-style="expand-left">Save</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <script>

        $(document).ready(function() {

            //change order status
            var token = $("input[name='_token']").val();

            $(document).on("click", "#submit", function () {

                var ProductName = $("#ProductName");
                var position = $("#position");
                var category_id = $("#category_id");
                var subcategory_id = $("#subcategory_id");
                var product = [];
                var productCount = 0;
                $("#productTable tbody tr").each(function(index, value) {
                    var currentRow = $(this);
                    var obj = {};
                    obj.productID = currentRow.find(".productID").val();
                    product.push(obj);
                    productCount++;
                });

                if (ProductName.val() == '') {
                    toastr.error('Product Name Should Not Be Empty');
                    ProductName.css('border', '1px solid red');
                    return;
                }
                ProductName.css('border', '1px solid #ced4da');

                if (category_id.val() == '') {
                    toastr.error('Category Should Not Be Empty');
                    category_id.css('border', '1px solid red');
                    return;
                }
                category_id.css('border', '1px solid #ced4da');

                if(productCount == 0){
                    toastr.error('Product Should Not Be Empty');
                    return;
                }
                var formData = new FormData();

                formData.append('_token', token);
                formData.append('ProductName', ProductName.val());
                formData.append('category_id', category_id.val());
                formData.append('subcategory_id', subcategory_id.val());
                formData.append('position', position.val());
                formData.append('ProductImage', $('#ProductImage')[0].files[0]);
                formData.append('ProductHoverImage', $('#ProductHoverImage')[0].files[0]);
                product.forEach((item, index) => {
                    Object.entries(item).forEach(([key, value]) => {
                        formData.append(`product[${index}][${key}]`, value);
                    });
                });

                $.ajax({
                    type: "POST",
                    url: '{{url('admin_order/main-product/store')}}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        toastr.success('Main product added successfully');
                        window.location.href = "{{ url('mainproducts') }}";
                    }
                });

            });


            $("#productID").select2({
                placeholder: "Select a Product",
                dropdownParent: $('#productTable'),
                allowClear: true,
                templateResult: function (state) {
                    if (!state.id) {
                        return state.text;
                    }
                    var $state = $(
                        '<span><img width="60px" src="'+state.image +'" class="img-flag" /> '+state.text+'" (SKU: "'+state.productCode+")</span>"
                    );
                    return $state;
                },
                ajax: {
                    type:'GET',
                    url: '{{url('admin_order/mini-products')}}',
                    processResults: function (data) {
                        return {
                            results: data.data
                        };
                    }
                }
            }).trigger("change").on("select2:select", function (e) {
                $("#productTable tbody").append(
                    "<tr>" +
                    '<td style="display: none"><input type="hidden" id="prd" value="new"><input type="text" class="productID" style="width:80px;" value="' + e.params.data.id + '"></td>' +
                    '<td><img src="' + e.params.data.image + '" style="width:60px;margin-top:6px;"> </td>' +
                    '<td><span class="productCode">' + e.params.data.productCode + '</span></td>' +
                    '<td><span class="productName">' + e.params.data.text + '</span></td>' +
                    '<td><button class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i></button></td>\n' +
                    "</tr>"
                );
                calculation();
                $('#productID').val(null).trigger('change');
            });
v
            $(document).on("click", ".delete-btn", function() {
                $(this).closest("tr").remove();
                calculation();
            });

            $(".datepicker").flatpickr();

        });
        
        var loadFile = function(event) {
            var output = document.getElementById('prevImage');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
        
        var loadFile2 = function(event) {
            var output = document.getElementById('prevImage2');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
        
        function setsubcategory() {
            var sub_id = $('#category_id').val();
            $.ajax({
                type: 'GET',
                url: '../admin/get/subcategory/' + sub_id,

                success: function (data) {
                    $('#subcategory_id').html('');

                    for (var i = 0; i < data.length; i++) {
                        $('#subcategory_id').append(`
                                                <option value="`+ data[i].id + `" >` + data[i].sub_category_name + `</option>
                                            `)
                    }
                },
                error: function (error) {
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
    </script>



<style>
    .card-box {
    background-color: #fff;
    padding: 1.5rem;
    -webkit-box-shadow: 0 1px 4px 0 rgb(0 0 0 / 10%);
    box-shadow: 0 1px 4px 0 rgb(0 0 0 / 10%);
    margin-bottom: 24px;
    border-radius: 0.25rem;
}
a {
    text-decoration: none;
}
</style>


@endsection
