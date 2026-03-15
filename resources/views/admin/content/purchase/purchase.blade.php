@extends('backend.master')

@section('maincontent')
    @section('subcss')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endsection

    <div class="container-fluid pt-4 px-4">

        <div class="pagetitle row">
            <div class="col-6">
                <h1><a href="{{url('/admindashboard')}}">Dashboard</a></h1>
                <nav>
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/admindashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Purchases</li>
                    </ol>
                </nav>
            </div>
            <div class="col-6" style="text-align: right">
                <a href="{{url('admin/purchase-create')}}" class="btn btn-primary btn-sm" ><span style="font-weight: bold;">+</span>  Add New Purchese</a>
            </div>
            <div class="col-3"> 
                    <div class="form-group col-md-12 p-2">
                        <label for="inputCity" class="col-form-label">Start Date</label>
                        <input type="text" class="form-control datepicker" id="startDate"
                            value="<?php echo date('Y-m-d'); ?>" placeholder="Select Date">
                    </div> 
            </div>
            <div class="col-3">  
                    <div class="form-group col-md-12 p-2">
                        <label for="inputCity" class="col-form-label">End Date</label>
                        <input type="text" class="form-control datepicker" id="endDate"
                            value="<?php echo date('Y-m-d'); ?>" placeholder="Select Date">
                    </div>
 
            </div>
            <div class="col-3">  
                    <div class="form-group col-md-12 pt-2" style="margin-top: 35px;">
                        <button class="btn btn-info btn-print-accountreport"><i class="fas fa-print"></i>
                            Print</button>
                    </div> 
            </div>
        </div><!-- End Page Title -->

        {{-- //table section for category --}}
        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                <div class="card">
                    <div class="card-body pt-4">
                    @if(\Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-1"></i>
                            {{ \Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <!-- Table with stripped rows -->
                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover mb-0" id="purcheseinfotbl" width="100%">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Invoice</th>
                                <th>Supplier</th>
                                <th>Products</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Quantity</th>
                                <th style="width: 55px">Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <!-- End Table with stripped rows -->

                    </div>
                </div>

                </div>
            </div>
        </section>

        <div class="modal" id="editmainPurchese">
            <div class="modal-dialog" style="width: 92%;max-width: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Purchese</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">



                    </div>

                </div>
            </div>
        </div><!-- End popup Modal-->

    </div>

    <script>
        $(document).ready(function() {
            $(".datepicker").flatpickr();
            
            var purcheseinfotbl = $('#purcheseinfotbl').DataTable({
                order: [ [0, 'desc'] ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{!! route('purchese.info') !!}",
                    data: {
                        startDate: function() {
                            return $('#startDate').val()
                        },
                        endDate: function() {
                            return $('#endDate').val()
                        }
                    }
                },
                columns: [
                    { data: 'id' },
                    { data: 'invoice' },
                    { data: 'supplier' },
                    { data: 'products' },
                    { data: 'totalAmount' },
                    { data: 'paid' },
                    { data: 'due' },
                    { data: 'quantityall' },
                    { data: 'action', name: 'action', orderable: false, searchable: false},

                ],
                lengthChange: false,
                bFilter: false,
                search: false,
                dom: '<"row"<"col-sm-6"Bl><"col-sm-6"f>>' +
                    '<"row"<"col-sm-12"<"table-responsive"tr>>>' +
                    '<"row"<"col-sm-5"i><"col-sm-7"p>>',
                buttons: {
                    buttons: [{
                        extend: 'print',
                        text: 'Print',
                        footer: true,
                        title: function() {
                            var printTitle = 'Account Report';
                            return printTitle;
                        },
                        exportOptions : {
                            stripHtml : false,
                            columns: [ 0, 1, 2, 3, 4,5,6,7,8]
                        },
                        customize: function(win) {
                            $(win.document.body).find('h1').css('text-align', 'center');
                            $(win.document.body).find('h1').after(
                                '<p style="text-align: center">From : ' + $('#startDate').val() + ' - To : ' + $('#endDate').val() + '</p>');
    
                        }
                    }]
                },
                language: {
                    paginate: {
                        previous: "<i class='fas fa-chevron-left'>",
                        next: "<i class='fas fa-chevron-right'>"
                    }
                },
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-sm");
                    $('.dt-buttons').hide();
                },
                footerCallback: function() {
                    var api = this.api();
                    var numRows = api.rows().count();
                    $('.total').empty().append(numRows);
    
                    var intVal = function(i) {
                        return typeof i === "string" ? i.replace(/[\$,]/g, "") * 1 : typeof i ===
                            "number" ? i : 0;
                    };
    
                }
            });
            
            $(document).on('click', '.btn-print-accountreport', function() {
                $(".buttons-print")[0].click();
            });
            $(document).on('change', '#startDate', function() {
                purcheseinfotbl.ajax.reload();
            });
            $(document).on('change', '#endDate', function() {
                purcheseinfotbl.ajax.reload();
            });


            //edit city

            $(document).on('click', '.btn-editpurchese', function(e) {
                e.preventDefault();
                let purcheseId = $(this).data('id');

                $.ajax({
                    type:'GET',
                    url:'purchases/'+purcheseId+'/edit',

                    success: function (response) {
                        $('.modal .modal-body').html('');
                        $('.modal .modal-body').empty().append(response);
                        $('.modal').modal('toggle');
                        $('.modal-footer').hide();

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
                            dropdownParent: $('#paymentTypedr'),
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
                            dropdownParent: $('#paymentIdnumber'),
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

                    },
                    error: function(error){
                        console.log('error');
                    }

                });
            });

            //update city
            $('#EditPurchase').submit(function(e){
                e.preventDefault();
                let purcheseId = $('#idhidden').val();

                $.ajax({
                    type:'POST',
                    url:'purchase/'+purcheseId,
                    processData: false,
                    contentType: false,
                    data:new FormData(this),

                    success: function (data) {
                        $('#editdate').val('');
                        $('#editinvoiceID').val('');
                        $('#editproduct_id').val('');
                        $('#editsupplier_id').val('');
                        $('#editquantity').val('');


                        swal({
                            title: "Purchase update successfully !",
                            icon: "success",
                            showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                        });
                        purcheseinfotbl.ajax.reload();
                    },
                    error: function(error){
                        console.log('error');
                    }
                });
            });

            //deleteuser

            $(document).on('click', '#deletePurchaseBtn', function(){
                let purcheseId = $(this).data('id');
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this !",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type:'DELETE',
                            url:'purchases/'+purcheseId,

                            success: function (data) {
                                swal("Poof! Your purchase has been deleted!", {
                                    icon: "success",
                                });
                                purcheseinfotbl.ajax.reload();
                            },
                            error: function(error){
                                console.log('error');
                            }

                        });


                    } else {
                        swal("Your data is safe!");
                    }
                });

            });


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






        });

    </script>

    @section('subscript')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
            flatpickr("#date", {});
            flatpickr("#editdate", {});
        </script>

    @endsection

@endsection
