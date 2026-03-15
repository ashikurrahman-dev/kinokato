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
                    <li class="breadcrumb-item active">Wsales</li>
                    </ol>
                </nav>
            </div>
            <div class="col-6" style="text-align: right">
                <a href="{{url('admin/wsale-create')}}" class="btn btn-primary btn-sm" ><span style="font-weight: bold;">+</span>  Add New Wholesale</a>
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
                        <table class="table table-centered table-borderless table-hover mb-0" id="wsaleinfotbl" width="100%">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Invoice</th>
                                <th>Wcustomer</th>
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

            var wsaleinfotbl = $('#wsaleinfotbl').DataTable({
                order: [ [0, 'desc'] ],
                processing: true,
                serverSide: true,
                ajax: '{!! route('wsale.info') !!}',
                columns: [
                    { data: 'id' },
                    { data: 'invoice' },
                    { data: 'wcustomer' },
                    { data: 'products' },
                    { data: 'totalAmount' },
                    { data: 'paid' },
                    { data: 'due' },
                    { data: 'quantityall' },
                    { data: 'action', name: 'action', orderable: false, searchable: false},

                ]
            });


            //edit city

            $(document).on('click', '.btn-editwsale', function(e) {
                e.preventDefault();
                let wsaleId = $(this).data('id');

                $.ajax({
                    type:'GET',
                    url:'wsales/'+wsaleId+'/edit',

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

                        //wcustomerID

                        $("#wcustomerID").select2({
                            placeholder: "Select a Wcustomer",
                            dropdownParent: $('#wcustomer'),
                            ajax: {
                                type:'GET',
                                url: '{{url('admin_get/wcustomers')}}',
                                processResults: function (data) {
                                    var data = $.parseJSON(data);
                                    return {
                                        results: data
                                    };
                                }
                            }
                        });

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
            $('#EditWsale').submit(function(e){
                e.preventDefault();
                let wsaleId = $('#idhidden').val();

                $.ajax({
                    type:'POST',
                    url:'wsale/'+wsaleId,
                    processData: false,
                    contentType: false,
                    data:new FormData(this),

                    success: function (data) {
                        $('#editdate').val('');
                        $('#editinvoiceID').val('');
                        $('#editproduct_id').val('');
                        $('#editwcustomer_id').val('');
                        $('#editquantity').val('');


                        swal({
                            title: "Wsale update successfully !",
                            icon: "success",
                            showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                        });
                        wsaleinfotbl.ajax.reload();
                    },
                    error: function(error){
                        console.log('error');
                    }
                });
            });

            //deleteuser

            $(document).on('click', '#deleteWsaleBtn', function(){
                let wsaleId = $(this).data('id');
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
                            url:'wsales/'+wsaleId,

                            success: function (data) {
                                swal("Poof! Your wsale has been deleted!", {
                                    icon: "success",
                                });
                                wsaleinfotbl.ajax.reload();
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
