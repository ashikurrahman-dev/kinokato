@extends('backend.master')

@section('maincontent')
    <div class="container-fluid pt-4 px-4">

        <div class="pagetitle row">
            <div class="col-6">
                <h1><a href="{{url('/admindashboard')}}">Dashboard</a></h1>
                <nav>
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/admindashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Orders</li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>Customer Info</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="supplierID">Supplier Name</label>
                                    <select id="supplierID"  class="form-control">
                                        <option value="2" selected >Return Steadfast</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="invoiceID">Invoice Number</label>
                                    <input type="text" class="form-control" id="invoiceID" value="{{ $uniqueId }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label>Payment</label>
                                    <select id="paymentTypeID" class="form-control select2">
                                        <option value="">Select payment Type</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2 paymentID">
                                    <select id="paymentID" class="form-control" style="width: 100%;">
                                        <option value="">Select Number</option>
                                    </select>
                                </div>
                                <div class="form-group trx_id mb-2">
                                    <label for="fname" class="text-right control-label col-form-label">TRX ID</label>
                                    <input type="text" class="form-control" id="trx_id">
                                </div>
                                <div class="form-group">
                                    <label for="customerNote">Payment Notes</label>
                                    <textarea name="comments" class="form-control" placeholder="Payment Notes" id="comments" rows="2"> </textarea>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="form-group row mb-2">
                                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Sub Total</label>
                                    <div class="col-sm-8">
                                        <span class="form-control" id="subtotal" style="cursor: not-allowed;"></span>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Transport</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="0" id="deliveryCharge">
                                    </div>
                                </div>


                                <div class="form-group row paymentAmount mb-2">
                                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Payment Amount</label>
                                    <div class="col-sm-8">
                                        <input type="text" value="0" class="form-control" id="paymentAmount">
                                    </div>
                                </div>

                                <div class="form-group row mb-2">
                                    <label for="fname" class="col-sm-4 text-right control-label col-form-label">Total</label>
                                    <div class="col-sm-8">
                                        <span class="form-control" id="total" style="cursor: not-allowed;"   >100</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="orderDate">Order Date</label>
                                    <input type="text" class="form-control datepicker" value="{{ date('Y-m-d') }}" id="orderDate">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>Product Info</strong>
                    </div>
                    <div class="card-body">
                        <table id="productTable" style="width: 100% !important;" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Size</th>
                                <th>Code</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="6">
                                    <select id="productID" style="width: 100%;">
                                        <option value="">Select Product</option>
                                    </select>
                                </td>
                            </tr>
                            </tfoot>

                        </table>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" id="submit" class="btn btn-primary btn-block" data-style="expand-left" style="width: 25%;font-size: 22px;">Save</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

    <script>
        $(document).ready(function() {

            //change
            var token = $("input[name='_token']").val();

            $(".paymentID").hide();
            $(".paymentAgentNumber").hide();
            $(".paymentAmount").hide();
            $(".trx_id").hide();


            $(document).on("click", "#submit", function () {

                var invoiceID = $("#invoiceID");
                var supplier_id = $("#supplierID");
                var comments = $("#comments");
                var due = +$("#total").text();
                var deliveryCharge = +$("#deliveryCharge").val();
                var trx_id = $("#trx_id").val();
                var paymentTypeID = $("#paymentTypeID").val();
                var paymentID = $("#paymentID").val();
                var paid = +$("#paymentAmount").val();
                var orderDate = $("#orderDate");
                var product = [];
                var productCount = 0 ;
                $("#productTable tbody tr").each(function (index, value) {
                    var currentRow = $(this);
                    var obj = {};
                    obj.sizeID = currentRow.find(".sizeID").val();
                    obj.productID = currentRow.find(".productID").val();
                    obj.productSize = currentRow.find("#ProductSize").val();
                    obj.productCode = currentRow.find(".productCode").text();
                    obj.productName = currentRow.find(".productName").text();
                    obj.productQuantity = currentRow.find(".productQuantity").val();
                    obj.productPrice = currentRow.find("#productPrice").val();
                    product.push(obj);
                    productCount++;
                });

                if(supplier_id.val() == ''){
                    toastr.error('Supplier Should Not Be Empty');
                    supplier_id.closest('.form-group').find('.select2-selection').css('border','1px solid red');
                    return;
                }
                supplier_id.closest('.form-group').find('.select2-selection').css('border','1px solid #ced4da');

                if(invoiceID.val() == ''){
                    toastr.error('Invoice ID Should Not Be Empty');
                    invoiceID.css('border','1px solid red');
                    return;
                }
                invoiceID.css('border','1px solid #ced4da');

                if(orderDate.val() == ''){
                    toastr.error('Order Date Should Not Be Empty');
                    orderDate.css('border','1px solid red');
                    return;
                }
                orderDate.css('border','1px solid #ced4da');

                if(productCount == 0){
                    toastr.error('Product Should Not Be Empty');
                    return;
                }

                var data = {};
                data["invoiceID"] = invoiceID.val();
                data["supplier_id"] = supplier_id.val();
                data["comments"] = comments.val();
                data["due"] = due;
                data["trx_id"] = trx_id;
                data["deliveryCharge"] = deliveryCharge;
                data["paymentTypeID"] = paymentTypeID;
                data["paymentID"] = paymentID;
                data["paid"] = paid;
                data["orderDate"] = orderDate.val();
                data["status"] = 'Active';
                data["products"] = product;
                $.ajax({
                    type: "POST",
                    url: '{{url('returns')}}',
                    data: {
                        'data': data,
                        '_token': token
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data["status"] === "success") {
                            toastr.success(data["message"]);
                            window.location.href = "{{ url('returns') }}";

                        } else {
                            toastr.error(data["message"])
                        }
                    }
                });



            });


            $("#productID").select2({
                placeholder: "Select a Product",
                allowClear: true,
                templateResult: function (state) {
                    if (!state.id) {
                        return state.text;
                    }
                    var $state = $(
                        '<span><img width="60px" src="'+state.image +'" class="img-flag" /> '+state.text+'" (Size: "'+state.size+")</span>"
                    );
                    return $state;
                },
                ajax: {
                    type:'GET',
                    url: '{{url('admin_wholesale/products')}}',
                    processResults: function (data) {
                        return {
                            results: data.data
                        };
                    }
                }
            }).trigger("change").on("select2:select", function (e) {
                $("#productTable tbody").append(
                    "<tr>" +
                    '<td  style="display: none"><input type="text" class="productID" style="width:80px;" value="' + e.params.data.id + '"><input type="text" class="sizeID" style="width:80px;" value="' + e.params.data.size_id + '"></td>' +
                    '<td><input type="text" name="size" id="ProductSize" readonly value="' + e.params.data.size + '" style="    max-width: 40px;"></td>' +
                    '<td><span class="productCode">' + e.params.data.productCode + '</span></td>' +
                    '<td><span class="productName">' + e.params.data.text + '</span></td>' +
                    '<td><input type="number" class="productQuantity form-control" style="width:80px;" value="1"></td>' +
                    '<td><input type="text" name="productPrice" id="productPrice" value="' + e.params.data.productPrice + '" style="    max-width: 80px;"></td>' +
                    '<td><button class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i></button></td>\n' +
                    "</tr>"
                );
                calculation();
                $('#productID').val(null).trigger('change');
            });

            //supplierID

            

            $("#paymentTypeID").select2({
                placeholder: "Select a payment Type",
                allowClear: true,
                ajax: {
                    data: function (params) {
                        return {
                            q: params.term
                        };
                        console.log(params);
                    },
                    url: '{{url('admin_order/paymenttype')}}',
                    processResults: function (data) {

                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            }).trigger("change").on("select2:select", function (e) {
                if (e.params.data.text == "") {
                    $(".paymentID").hide();
                    $(".paymentAmount").hide();
                    $(".trx_id").hide();
                } else {
                    $(".paymentID").show();
                    $(".paymentAmount").show();
                    $(".trx_id").show();
                }
            }).on("select2:unselect", function (e) {
                $(".paymentID").hide();
                $(".paymentAmount").hide();
                $(".trx_id").hide();
                calculation();
            });

            $("#paymentID").select2({
                placeholder: "Select a payment Number",
                allowClear: true,
                ajax: {
                    data: function (params) {
                        return {
                            q: params.term,
                            paymentTypeID: $("#paymentTypeID").val(),
                        };
                    },
                    type:'GET',
                    url: '{{url('admin_order/paymentnumber')}}',

                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            });

            $(document).on("change", ".productQuantity", function () {
                calculation();
            });
            $(document).on("input", "#productPrice", function () {
                calculation();
            });
            $(document).on("input", "#paymentAmount", function () {
                calculation();
            });
            $(document).on("input", "#deliveryCharge", function () {
                calculation();
            });
            calculation();
            //calculation part
            function calculation() {
                var subtotal = 0;
                var deliveryCharge = +$("#deliveryCharge").val();
                var paymentAmount = +$("#paymentAmount").val();
                $("#productTable tbody tr").each(function (index) {
                    subtotal = subtotal + +$(this).find("#productPrice").val() * +$(this).find(".productQuantity").val();
                });
                $("#subtotal").text(subtotal);
                $("#total").text(subtotal + deliveryCharge - paymentAmount);
            }
            //delete select order
            $(document).on("click", ".delete-btn", function () {
                $(this).closest("tr").remove();
                calculation();
            });



            $(".datepicker").flatpickr();




        });
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
