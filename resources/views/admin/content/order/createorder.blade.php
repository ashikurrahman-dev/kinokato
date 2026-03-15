@extends('backend.master')

@section('maincontent')
    <div class="px-4 pt-4 container-fluid">

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
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <strong>Customer Info</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="storeID">Store Name</label>
                                    <select id="storeID"  class="form-control">
                                        <option value="1" selected >{{ env('APP_NAME') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="invoiceID">Invoice Number</label>
                                    <input type="text" readonly class="form-control" style="cursor: not-allowed;" id="invoiceID" value="{{ $uniqueId }}">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="web_id">Order From</label>
                                    <select id="web_id"  class="form-control">
                                        <option value="">Choose</option>
                                        <option value="Whatsapp">Whatsapp</option>
                                        <option value="Exchange">Exchange</option>
                                        <option value="Page">Page</option>
                                        <option value="Shop Sale">Shop Sale</option>
                                        <option value="Phone Call">Phone Call</option>
                                        <option value="Imo">Imo</option>
                                        <option value="Wholesale">Wholesale</option>
                                        <option value="Gift">Gift</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="customerName">Customer Name</label>
                                    <input type="text" class="form-control" id="customerName">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="customerPhone">Customer Phone</label>
                                    <input type="text" class="form-control" id="customerPhone">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="customerAddress">Customer Address</label>
                                    <textarea name="" class="form-control" placeholder="Customer Address" id="customerAddress" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="mb-1 col-lg-12">
                                <div class="form-group">
                                    <label for="customerNote">Customer Notes</label>
                                    <textarea name="" class="form-control" placeholder="Customer Notes" id="customerNote" rows="2"></textarea>
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
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <strong>Product Info</strong>
                    </div>
                    <div class="card-body">
                        <table id="productTable" style="width: 100% !important;" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Code</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="7">
                                    <select id="productID" style="width: 100%;">
                                        <option value="">Select Product</option>
                                    </select>
                                </td>
                            </tr>
                            </tfoot>

                        </table>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputState" class="col-form-label">Choose Status</label>
                                    <select id="orderStatus" class="form-control">
                                        <option value="Pending">Pending</option>
                                        <option value="Hold">Hold</option>
                                        <option value="Ready to Ship">Ready to Ship</option>
                                        <option value="Packaging">Packaging</option>
                                        <option value="Shipped">Shipped</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Del. Failed">Del. Failed</option>
                                    </select>
                                </div>
                                <div class="mb-2 form-group">
                                    <label>Payment</label>
                                    <select id="paymentTypeID" class="form-control select2">
                                        <option value="">Select payment Type</option>
                                    </select>
                                </div>
                                <div class="mb-2 form-group paymentID">
                                    <select id="paymentID" class="form-control" style="width: 100%;">
                                        <option value="">Select Number</option>
                                    </select>
                                </div>
                                <div class="mb-2 form-group paymentAgentNumber">
                                    <input type="text" class="form-control" id="paymentAgentNumber" placeholder="Enter Bkash Agent Number">
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="mb-2 form-group row">
                                    <label for="fname" class="text-right col-sm-4 control-label col-form-label">Sub Total</label>
                                    <div class="col-sm-8">
                                        <span class="form-control" id="subtotal" style="cursor: not-allowed;"></span>
                                    </div>
                                </div>
                                <div class="mb-2 form-group row">
                                    <label for="fname" class="text-right col-sm-4 control-label col-form-label">Delivery</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="100" id="deliveryCharge">
                                    </div>
                                </div>
                                <div class="mb-2 form-group row">
                                    <label for="fname" class="text-right col-sm-4 control-label col-form-label">Discount</label>
                                    <div class="col-sm-8">
                                        <input type="text" value="0" class="form-control" id="discountCharge">
                                    </div>
                                </div>

                                <div class="mb-2 form-group row paymentAmount">
                                    <label for="fname" class="text-right col-sm-4 control-label col-form-label">Payment</label>
                                    <div class="col-sm-8">
                                        <input type="text" value="0" class="form-control" id="paymentAmount">
                                    </div>
                                </div>
                                <div class="mb-2 form-group row">
                                    <label for="fname" class="text-right col-sm-4 control-label col-form-label">Total</label>
                                    <div class="col-sm-8">
                                        <span class="form-control" id="total" style="cursor: not-allowed;"   >100</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" id="submit" class="btn btn-primary btn-block from-prevent-multiple-submits" data-style="expand-left">Save</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <script>
        (function(){
        $('.from-prevent-multiple-submits').on('submit', function(){
            $('.from-prevent-multiple-submits').attr('disabled','true');
            $('.spinner').css('display','inline');
        })
        })();

        $(document).ready(function() {

            //change order status
            var token = $("input[name='_token']").val();

            $(".paymentID").hide();
            $(".paymentAgentNumber").hide();
            $(".paymentAmount").hide();


            $(document).on("click", "#submit", function () {

                var invoiceID = $("#invoiceID");
                var web_id = $("#web_id");
                var customerName = $("#customerName");
                var orderStatus = $("#orderStatus");
                var customerPhone = $("#customerPhone");
                var customerAddress = $("#customerAddress");
                var courier_tracking_link = $("#courier_tracking_link");
                var customerNote = $("#customerNote");
                var storeID = $("#storeID");
                var total = +$("#total").text();
                var deliveryCharge = +$("#deliveryCharge").val();
                var discountCharge = +$("#discountCharge").val();
                var paymentTypeID = $("#paymentTypeID").val();
                var paymentID = $("#paymentID").val();
                var paymentAmount = +$("#paymentAmount").val();
                var paymentAgentNumber = $("#paymentAgentNumber").val();
                var orderDate = $("#orderDate");
                var courierID = $("#courierID");
                var cityID = +$("#cityID").val();
                var zoneID = +$("#zoneID").val();
                var product = [];
                var productCount = 0;

                $("#productTable tbody tr").each(function(index, value) {
                    var currentRow = $(this);
                    var obj = {};
                    obj.productColor = currentRow.find("#ProductColor").val();
                    obj.sigment = currentRow.find("#sigment").val();
                    obj.productSize = currentRow.find("#ProductSize").val();
                    obj.productID = currentRow.find(".productID").val();
                    obj.productCode = currentRow.find(".productCode").text();
                    if(currentRow.find("#ProductSize").val()=='' && currentRow.find("#prd").val()=='old'){
                        toastr.error('please select any size');
                        return false;
                    }
                    obj.productName = currentRow.find(".productName").text();
                    obj.productQuantity = currentRow.find(".productQuantity").val();
                    obj.productPrice = currentRow.find(".productPrice").val();
                    product.push(obj);
                    productCount++;
                });

                if (storeID.val() == '') {
                    toastr.error('Store Should Not Be Empty');
                    storeID.closest('.form-group').find('.select2-selection').css('border',
                        '1px solid red');
                    return;
                }
                storeID.closest('.form-group').find('.select2-selection').css('border',
                    '1px solid #ced4da');

                if (invoiceID.val() == '') {
                    toastr.error('Invoice ID Should Not Be Empty');
                    invoiceID.css('border', '1px solid red');
                    return;
                }
                invoiceID.css('border', '1px solid #ced4da');
                if (web_id.val() == '') {
                    toastr.error('Order From Should Not Be Empty');
                    web_id.css('border', '1px solid red');
                    return;
                }
                web_id.css('border', '1px solid #ced4da');

                if (customerName.val() == '') {
                    toastr.error('Customer Name Should Not Be Empty');
                    customerName.css('border', '1px solid red');
                    return;
                }
                customerName.css('border', '1px solid #ced4da');

                if (customerPhone.val() == '') {
                    toastr.error('Customer Phone Should Not Be Empty');
                    customerPhone.css('border', '1px solid red');
                    return;
                }
                customerPhone.css('border', '1px solid #ced4da');

                if (customerAddress.val() == '') {
                    toastr.error('Customer Address Should Not Be Empty');
                    customerAddress.css('border', '1px solid red');
                    return;
                }
                customerAddress.css('border', '1px solid #ced4da');

                if (orderDate.val() == '') {
                    toastr.error('Order Date Should Not Be Empty');
                    orderDate.css('border', '1px solid red');
                    return;
                }
                orderDate.css('border', '1px solid #ced4da');

                if (courierID.val() == '') {
                    toastr.error('Courier Should Not Be Empty');
                    courierID.closest('.form-group').find('.select2-selection').css('border',
                        '1px solid red');
                    return;
                }


                var data = {};
                data["invoiceID"] = invoiceID.val();
                data["web_id"] = web_id.val();
                data["storeID"] = storeID.val();
                data["customerName"] = customerName.val();
                data["status"] = orderStatus.val();
                data["customerPhone"] = customerPhone.val();
                data["customerAddress"] = customerAddress.val();
                data["customerNote"] = customerNote.val();
                data["courier_tracking_link"] = courier_tracking_link.val();
                data["total"] = total;
                data["deliveryCharge"] = deliveryCharge;
                data["discountCharge"] = discountCharge;
                data["paymentTypeID"] = paymentTypeID;
                data["paymentID"] = paymentID;
                data["paymentAmount"] = paymentAmount;
                data["paymentAgentNumber"] = paymentAgentNumber;
                data["orderDate"] = orderDate.val();
                data["courierID"] = +courierID.val();
                data["cityID"] = cityID;
                data["zoneID"] = zoneID;
                data["userID"] = $('#user_id').val();
                data["products"] = product;
                $.ajax({
                    type: "POST",
                    url: '{{url('admin/order/store')}}',
                    data: {
                        'data': data,
                        '_token': token
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data["status"] === "success") {
                            toastr.success(data["message"]);
                            window.location.href = "{{ url('admin_order/Pending') }}";

                        } else {
                            toastr.error(data["message"])
                        }
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
                        '<span><img width="60px" src="'+state.image +'" class="img-flag" /> '+state.text+'" (Size: "'+state.size+")</span>"
                    );
                    return $state;
                },
                ajax: {
                    type:'GET',
                    url: '{{url('admin_order/products')}}',
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
                    '<td><input type="text" name="color" id="ProductColor" value="" style="    max-width: 60px;"><br><img src="' + e.params.data.image + '" style="width:60px;margin-top:6px;"> </td>' +
                    '<td><input type="text" name="size" id="ProductSize" value="' + e.params.data.size + '" style="    max-width: 40px;"></td>' +
                    '<td><span class="productCode">' + e.params.data.productCode + '</span></td>' +
                    '<td><span class="productName">' + e.params.data.text + '</span><br><input type="text" name="sigment" id="sigment" class="form-control" value="" style="max-width: 250px;"></td>' +
                    '<td><input type="number" class="productQuantity form-control" style="width:80px;" value="1"></td>' +
                    '<td><input type="text" name="productPrice" class="productPrice" value="' + e.params.data.productPrice + '" style="max-width: 60px;"></td>' +
                    '<td><button class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i></button></td>\n' +
                    "</tr>"
                );
                calculation();
                $('#productID').val(null).trigger('change');
            });

            //courier
            $("#courierID").select2({
                placeholder: "Select a Courier",
                ajax: {
                    url: '{{url('admin_order/couriers')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            }).trigger("change").on("select2:select", function (e) {
                    $("#zoneID").empty();
                    for (var i = 0; i < couriers.length; i++) {
                    if (couriers[i]['courierName'] == e.params.data.text) {
                            if (couriers[i]['hasCity'] == 'on') {
                            jQuery(".hasCity").show();
                        } else {
                            jQuery(".hasCity").hide();
                        }
                        if (couriers[i]["hasZone"] == 'on') {
                            jQuery(".hasZone").show();
                        } else {
                            jQuery(".hasZone").hide();
                            $("#zoneID").empty();
                        }
                        if (couriers[i]["hasArea"] == 'on') {
                            jQuery(".hasArea").show();
                        } else {
                            jQuery(".hasArea").hide();
                            $("#areaID").empty();
                        }
                    }

                    if (e.params.data.text == 'Pathao') {
                        $("#cityID").empty().append('<option value="8">Dhaka</option>');
                    } else {
                        $("#cityID").empty();
                    }
                }

            });

            if ($("#courierID").text()) {
                var courier = $("#courierID").text().trim();
                    for (var i = 0; i < couriers.length; i++) {
                    if (couriers[i]['courierName'] == courier) {
                            if (couriers[i]['hasCity'] == 'on') {
                            jQuery(".hasCity").show();
                        } else {
                            jQuery(".hasCity").hide();
                        }

                        if (couriers[i]["hasZone"] == 'on') {
                            jQuery(".hasZone").show();
                        } else {
                            jQuery(".hasZone").hide();
                            $("#zoneID").empty();
                        }
                        if (couriers[i]["hasArea"] == 'on') {
                            jQuery(".hasArea").show();
                        } else {
                            jQuery(".hasArea").hide();
                            $("#areaID").empty();
                        }
                    }
                }
            }

            $("#cityID").select2({
                placeholder: "Select a City",
                ajax: {
                    data: function (params) {
                        var query = {
                            q: params.term,
                            courierID: $("#courierID").val()
                        };
                        return query;
                    },
                    type:'GET',
                    url: '{{url('admin_order/cities')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            });

            $("#zoneID").select2({
                placeholder: "Select a Zone",
                ajax: {
                    data: function (params) {
                        var query = {
                            q: params.term,
                            courierID: $("#courierID").val(),
                            cityID: $("#cityID").val()
                        };
                        return query;
                    },
                    type:'GET',
                    url: '{{url('admin_order/zones')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                        console.log(data);
                    }
                }
            });

            $("#areaID").select2({
                placeholder: "Select a Area",
                ajax: {
                    data: function (params) {
                        var query = {
                            q: params.term,
                            courierID: $("#courierID").val(),
                            zoneID: $("#zoneID").val()
                        };
                        return query;
                    },
                    type:'GET',
                    url: '{{url('admin_order/areas')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                        console.log(data);
                    }
                }
            });

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
                    $(".paymentAgentNumber").hide();
                    $(".paymentAmount").hide();
                } else {
                    $(".paymentID").show();
                    $(".paymentAgentNumber").show();
                    $(".paymentAmount").show();
                }
            }).on("select2:unselect", function (e) {
                $(".paymentID").hide();
                $(".paymentAgentNumber").hide();
                $(".paymentAmount").hide();
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

            $(document).on("change", ".productQuantity", function() {
                calculation();
            });
            $(document).on("input", ".productPrice", function() {
                calculation();
            });
            $(document).on("input", "#paymentAmount", function() {
                calculation();
            });
            $(document).on("input", "#deliveryCharge", function() {
                calculation();
            });
            $(document).on("input", "#discountCharge", function() {
                calculation();
            });
            calculation();

            function calculation() {
                var subtotal = 0;
                var deliveryCharge = +$("#deliveryCharge").val();
                var discountCharge = +$("#discountCharge").val();
                var paymentAmount = +$("#paymentAmount").val();
                $("#productTable tbody tr").each(function(index) {
                    subtotal = subtotal + +$(this).find(".productPrice")
                        .val() * +$(this).find(".productQuantity").val();
                });
                $("#subtotal").text(subtotal);
                $("#total").text(subtotal + deliveryCharge - paymentAmount -
                    discountCharge);
            }

            $(document).on("click", ".delete-btn", function() {
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
