@extends('backend.master')

@section('maincontent')


<?php
use App\Models\Admin;
$admin = Admin::where('email', Auth::guard('admin')->user()->email)->first();
$users = Admin::where('status', 'Active')
    ->inRandomOrder()
    ->get();
?>

<style>
    .card-box {
        background-color: #ffffff !important;
        padding: 1.5rem;
        -webkit-box-shadow: 0 1px 4px 0 rgb(0 0 0 / 10%);
        box-shadow: 0 1px 4px 0 rgb(0 0 0 / 10%);
        margin-bottom: 24px;
        border-radius: 0.25rem;
        }
        
        #orderinfo_filter{
            display:none;
        }
        td{
                color: black !important;
        }
        
        input.form-control.cuID {
            background: #029620 !important;
            border: 1px solid red;
        }
        input.form-control.phone {
            background: #029620 !important;
            border: 1px solid red;
        }
         
        input.form-control.cuID::placeholder {
          color: white;
          opacity: 1; /* Firefox */
        }
        input.form-control.phone::placeholder {
          color: white;
          opacity: 1; /* Firefox */
        }

</style>

<div class="container-fluid pt-4 px-4">

    <div class="pagetitle row">
        <div class="col-6">
            <h1><a href="{{ url('/admindashboard') }}">Dashboard</a></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/admindashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Complains</li>
                </ol>
            </nav>
        </div>
        <div class="col-6" style="text-align: right">
        </div>
    </div><!-- End Page Title -->



    {{-- //popup modal for edit user --}}
    <div class="modal fade" id="editmainComplain" tabindex="-1" data-bs-backdrop="false">
        <div class="modal-dialog" style="width: 92%;max-width: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Complain</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                </div>

            </div>
        </div>
    </div><!-- End popup Modal-->

    {{-- //table section for category --}}
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <section class="section">
        <div class="row mb-2">
            <div class="col-lg-12">
                <div class="row">
                    <div class="form-group col-md-2 p-2">
                        <label for="inputCity" class="col-form-label">Start Date</label>
                        <input type="text" class="form-control datepicker" id="startDate"  value="<?php echo date('Y-m-d')?>" placeholder="Select Date">
                    </div>
                    <div class="form-group col-md-2 p-2">
                        <label for="inputCity" class="col-form-label">End Date</label>
                        <input type="text" class="form-control datepicker" id="endDate" value="<?php echo date('Y-m-d')?>" placeholder="Select Date">
                    </div> 
                    <div class="form-group col-md-1 pt-2">
                        <label for="" class="col-form-label" style="opacity: ">Print</label>
                        <button class="btn btn-info btn-print-courieruserreport"><i class="fas fa-print"></i> Print</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">  
            <div class="col-md-6 col-xl-2">
                <a href="{{ url('complain/complainall') }}">
                    <div class="widget-rounded-circle card-box order pt-1 pb-1">
                        <div class="row">
                            <div class="col-12">
                                <div class="float-left">
                                    <h3 class="text-dark mt-1 mb-0">
                                        <span id="total" data-plugin="counterup">{{App\Models\Complain::get()->count()}}</span>
                                    </h3>
                                    <p class="text-muted mb-1 text-truncate">Total</p>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </a>
            </div> <!-- end col-->
            <div class="col-md-6 col-xl-2"> 
                <a href="{{ url('complain/Pending') }}">
                    <div class="widget-rounded-circle card-box order pt-1 pb-1">
                        <div class="row">
                            <div class="col-12">
                                <div class="float-left">
                                    <h3 class="text-dark mt-1 mb-0">
                                        <span id="pending" data-plugin="counterup">{{App\Models\Complain::where('status','Pending')->get()->count()}}</span>
                                    </h3>
                                    <p class="text-muted mb-1 text-truncate">Pending</p>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </a>
            </div> <!-- end col-->
            <div class="col-md-6 col-xl-2">
                <a href="{{ url('complain/On Progress') }}">
    
                    <div class="widget-rounded-circle card-box order pt-1 pb-1">
                        <div class="row">
                            <div class="col-12">
                                <div class="float-left">
                                    <h3 class="text-dark mt-1 mb-0">
                                        <span id="onprogress" data-plugin="counterup">{{App\Models\Complain::where('status','On Progress')->get()->count()}}</span>
                                    </h3>
                                    <p class="text-muted mb-1 text-truncate">On Progress</p>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </a>
            </div> <!-- end col--> 
             {{-- <div class="col-md-6 col-xl-2">
                        <a href="{{ url('complain/Issue') }}">
                            <div class="widget-rounded-circle card-box order pt-1 pb-1">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="float-left">
                                            <h3 class="text-dark mt-1 mb-0">
                                                <span id="issue" data-plugin="counterup">{{App\Models\Complain::where('status','Issue')->get()->count()}}</span>
                                            </h3>
                                            <p class="text-muted mb-1 text-truncate">Issue</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div> <!-- end widget-rounded-circle-->
                        </a>
                    </div> <!-- end col--> --}}
            <div class="col-md-6 col-xl-2">
                <a href="{{ url('complain/Solved') }}">
                    <div class="widget-rounded-circle card-box order pt-1 pb-1">
                        <div class="row">
                            <div class="col-12">
                                <div class="float-left">
                                    <h3 class="text-dark mt-1 mb-0">
                                        <span id="solved" data-plugin="counterup">{{App\Models\Complain::where('status','Solved')->get()->count()}}</span>
                                    </h3>
                                    <p class="text-muted mb-1 text-truncate">Solved</p>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </a>
            </div> <!-- end col-->
        </div>
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body pt-4" style="text-align: center;">
                        @if (\Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-1"></i>
                                {{ \Session::get('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="buttonsec"> 
                            <a href="{{ url('complain/create/complain') }}" class="btn btn-primary btn-sm"><span
                                    style="font-weight: bold;">+</span> Add New Complain</a>

                            <div class="btn-group dropdown">
                                <!--<a href="javascript: void(0);" style="color: white"-->
                                <!--    class="table-action-btn dropdown-toggle arrow-none btn bg-success btn-sm"-->
                                <!--    data-bs-toggle="dropdown" aria-expanded="false"><i-->
                                <!--        class="fas fa-user-check mr-1"></i> Assign User</a>-->
                                <div class="dropdown-menu dropdown-menu-right">

                                    @foreach ($users as $user)
                                        <a class="dropdown-item assign-usertocomplain" data-id="{{ $user->id }}"
                                            href="#">{{ $user->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- Table with stripped rows -->
                        <div class="table-responsive">
                            <table class="table table-centered table-striped table-hover mb-0" id="complaininfo"
                                width="100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th>Date</th>
                                        <th>INV</th>
                                        <th>Customer</th>
                                        <th>Complain</th>
                                        <th>Complain By</th>
                                        <th>Solved By</th>
                                        <th>Solved Date</th>
                                        <th>Notes</th>
                                        <th>Status</th>
                                        <th>Action</th>
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


    @if ($status)
        <input type="text" class="form-control" name="complain_status" id="complain_status"
            value="{{ $status }}" hidden>
    @else
        <input type="text" class="form-control" name="complain_status" id="complain_status" value="all" hidden>
    @endif
</div>

<input type="hidden" name="_token" value="{{ csrf_token() }}" />

@section('subscript')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.4/js/dataTables.select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-datatables-checkboxes@1.2.13/js/dataTables.checkboxes.min.js"></script>
@endsection
<script>
    $(document).ready(function() {
        //token
        var token = $("input[name='_token']").val();
        //date picker
        $(".datepicker").flatpickr();
        
         
        var statuscomplain = $('#complain_status').val();
        var token = $("input[name='_token']").val();
        
        var complaininfotbl = $('#complaininfo').DataTable({
            order: [
                [0, 'desc']
            ],
            processing: true,
            serverSide: true,
             ajax: {
                url: "{{ url('complain/data/') }}" + '/' + statuscomplain,
                data: {
                    startDate: function() { return $('#startDate').val() },
                    endDate: function() { return $('#endDate').val() }
                }
            },
            columnDefs: [{
                targets: 0,
                checkboxes: {
                    selectRow: false,
                },
            }, ],
            columns: [{
                    data: 'id'
                },
                {
                    data: 'date'
                },
                {
                    data: 'order_invoice_id'
                },
                {
                    data: 'customer_phone'
                },
                {
                    data: 'complain_message'
                },
                
                {
                    data: 'user'
                },
                {
                    data: 'solved_by'
                },
                {
                    data: 'solved'
                },
                {
                    data: 'notes'
                },
                {
                    data: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },

            ],
            dom: '<"row"<"col-sm-6"Bl><"col-sm-6"f>>' +
                '<"row"<"col-sm-12"<"table-responsive"tr>>>' +
                '<"row"<"col-sm-5"i><"col-sm-7"p>>',
            buttons: {
                buttons: [{
                    extend: 'print',
                    text: 'Print',
                    footer: true ,
                    title: function(){
                        return 'Complain Report';
                    },
                    customize: function (win) {
                        $(win.document.body).find('h1').css('text-align','center');
                        $(win.document.body).find('h1').after('<p style="text-align: center">'+$('#startDate').val()+' to '+$('#endDate').val()+'</p>');

                    }
                }]
            },
            language: {
                paginate: {
                    previous: "<i class='fas fa-chevron-left'>",
                    next: "<i class='fas fa-chevron-right'>"
                }
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-sm");
                $('.dt-buttons').hide();
            },
        });

        //assign user
        $(document).on('click', '.assign-usertocomplain', function(e) {
            e.preventDefault();

            var rows_selected = complaininfotbl.column(0).checkboxes.selected();
            var ids = [];
            $.each(rows_selected, function(index, rowId) {
                ids[index] = rowId;
            });
            var admin_id = $(this).attr('data-id');

            jQuery.ajax({
                type: "GET",
                url: "{{ url('assign_user_complain') }}",
                contentType: "application/json",
                data: {
                    action: "assigncomplain",
                    ids: ids,
                    admin_id: admin_id
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data["status"] == "success") {
                        swal(data["message"]);
                        complaininfotbl.ajax.reload();
                    } else {
                        if (data["status"] == "failed") {
                            swal(data["message"]);
                        } else {
                            swal("Something wrong ! Please try again.");
                        }
                    }
                }
            });

        });


        //sync complain

        $(document).on('click', '.btn-synccomplain', function() {

            swal({
                html: true,
                title: 'Auto sync start!',
                text: 'It will close after all Complains sync.',
                buttons: true,
                dangerMode: true,
                buttons: "Please Wait ...",
            });

            $.ajax({
                type: 'GET',
                url: "{{ url('complain/complain/Sync') }}",

                success: function(data) {
                    var datas = JSON.parse(data);

                    if (datas.status == 'success') {
                        swal({
                            title: "Auto sync completed!",
                            text: datas.complainCount + ' complain added by sync',
                            icon: "success",
                            buttons: true,
                            buttons: "Completed",
                        });
                    } else {
                        swal({
                            title: "Auto sync completed!",
                            text: 'O complain added . Nothing to sync',
                            icon: "success",
                            buttons: true,
                            buttons: "Done",
                        });
                    }
                    complaininfotbl.ajax.reload();
                },
                error: function(error) {
                    swal({
                        icon: 'error',
                        title: 'Cant process auto sync !',
                        text: 'Connection Error . Please wait for internet',
                        buttons: true,
                        buttons: "Thanks",
                    });
                }

            });
        });


        $(document).on('click', '.btn-asdad', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax({
                type: "get",
                url: "{{ url('complain/complains') }}/" + id + "/edit",
                success: function(response) {

                    $('#editmainComplain .modal-body').empty().append(response);
                    $('#editmainComplain').modal('toggle');
                    $('#editmainComplain .modal-footer').hide();


                    var ComplainCommentTable = $("#ComplainCommentTable").DataTable({
                        ajax: "{{ url('complain/comment/get') }}?id=" + $(
                            '#ComplainCommentTable').attr('data-id'),
                        ordering: false,
                        lengthChange: false,
                        bFilter: false,
                        search: false,
                        info: false,
                        columns: [{
                                data: "date"
                            },
                            {
                                data: "comment"
                            },
                            {
                                data: "name"
                            }
                        ],
                    });

                    $(document).on("click", "#updateComplainComment", function() {
                        var note = $('#complaincomment');
                        var id = $('#complain_id').val();
                        var token = $("input[name='_token']").val();

                        if (note.val() == '') {
                            note.css('border', '1px solid red');
                            return;
                        } else if (id == '') {
                            toastr.success('Something Wrong , Try again ! ');
                            return;
                        } else {
                            $.ajax({
                                type: "GET",
                                url: "{{ url('complain/comment/update') }}",
                                data: {
                                    'comment': note.val(),
                                    'complain_id': id,
                                    '_token': token
                                },
                                success: function(response) {
                                    var data = JSON.parse(response);
                                    if (data['status'] == 'success') {
                                        toastr.success(data["message"]);
                                        ComplainCommentTable.ajax
                                            .reload();
                                    } else {
                                        if (data['status'] ==
                                            'failed') {
                                            toastr.error(data[
                                                "message"]);
                                        } else {
                                            toastr.error(
                                                'Something wrong ! Please try again.'
                                            );
                                        }
                                    }
                                }
                            });
                            return;
                        }


                    });





                },
                error: function(error) {
                    swal({
                        icon: 'error',
                        title: 'Cant Open Complain !',
                        text: 'Complain Phone number is not valid',
                        buttons: true,
                        buttons: "Thanks",
                    });
                }
            });
        });
        $(document).on('click', '.btn-editcomplain', function(e) {

            e.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax({
                type: "get",
                url: "{{ url('complain/complains') }}/" + id + "/edit",
                success: function(response) {
                    $('#editmainComplain  .modal-body').empty().append(response);
                    $('#editmainComplain ').modal('toggle');
                    $('#editmainComplain  .modal-footer').hide();

                    $(".datepicker").flatpickr();

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


                    $("#courierID").select2({
                        placeholder: "Select a Courier",
                        allowClear: true,
                        dropdownParent: $('#courierdatatbl'),
                        ajax: {
                            url: '{{ url('admin_order/couriers') }}',
                            processResults: function(data) {
                                var data = $.parseJSON(data);
                                return {
                                    results: data
                                };
                            }
                        }
                    }).trigger("change").on("select2:select", function(e) {
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
                            }

                            if (e.params.data.text == 'Pathao') {
                                $("#cityID").empty().append(
                                    '<option value="8">Dhaka</option>');
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
                            }
                        }
                    }

                    $("#cityID").select2({
                        placeholder: "Select a City",
                        dropdownParent: $('#citydatatbl'),
                        allowClear: true,
                        ajax: {
                            data: function(params) {
                                var query = {
                                    q: params.term,
                                    courierID: $("#courierID").val()
                                };
                                return query;
                            },
                            type: 'GET',
                            url: '{{ url('admin_order/cities') }}',
                            processResults: function(data) {
                                var data = $.parseJSON(data);
                                return {
                                    results: data
                                };
                            }
                        }
                    });

                    $("#zoneID").select2({
                        placeholder: "Select a Zone",
                        dropdownParent: $('#xonedatatbl'),
                        allowClear: true,
                        ajax: {
                            data: function(params) {
                                var query = {
                                    q: params.term,
                                    courierID: $("#courierID").val(),
                                    cityID: $("#cityID").val()
                                };
                                return query;
                            },
                            type: 'GET',
                            url: '{{ url('admin_order/zones') }}',
                            processResults: function(data) {
                                var data = $.parseJSON(data);
                                return {
                                    results: data
                                };
                                console.log(data);
                            }
                        }
                    });


                    var orderCommentTable = $("#orderCommentTable").DataTable({
                        ajax: "{{ url('admin_order/getComment') }}?id=" + $(
                            '#orderCommentTable').attr('data-id'),
                        ordering: false,
                        lengthChange: false,
                        bFilter: false,
                        search: false,
                        info: false,
                        columns: [{
                                data: "date"
                            },
                            {
                                data: "comment"
                            },
                            {
                                data: "name"
                            }
                        ],
                    });

                    var oldOrderTable = $("#oldOrderTable").DataTable({
                        ajax: "{{ url('admin_order/previous_orders') }}?id=" + $(
                            '#oldOrderTable').attr('data-id'),
                        ordering: false,
                        lengthChange: false,
                        bFilter: false,
                        search: false,
                        info: false,
                        columns: [{
                                data: "created_at"
                            },
                            {
                                data: "invoiceID"
                            },
                            {
                                data: null,
                                width: "15%",
                                render: function(data) {
                                    return '<i class="fas fa-user mr-2 text-grey-dark"></i>' +
                                        data.customerName +
                                        '<br> <i class="fas fa-phone  mr-2 text-grey-dark"></i>' +
                                        data.customerPhone +
                                        '<br><i class="fas fa-map-marker mr-2 text-grey-dark"></i>' +
                                        data.customerAddress;
                                }
                            },
                            {
                                data: "products"
                            },
                            {
                                data: "subTotal"
                            },
                            {
                                data: "status"
                            },
                            {
                                data: "name"
                            }
                        ]
                    });

                    $(document).on("click", "#updateComment", function() {
                        var note = $('#comment');
                        var id = $('#btn-update').val();
                        if (note.val() == '') {
                            note.css('border', '1px solid red');
                            return;
                        } else if (id == '') {
                            toastr.success('Something Wrong , Try again ! ');
                            return;
                        } else {
                            $.ajax({
                                type: "GET",
                                url: "{{ url('admin_order/updateComment') }}",
                                data: {
                                    'comment': note.val(),
                                    'id': id,
                                    '_token': token
                                },
                                success: function(response) {
                                    var data = JSON.parse(response);
                                    if (data['status'] == 'success') {
                                        toastr.success(data["message"]);
                                        orderCommentTable.ajax.reload();
                                    } else {
                                        if (data['status'] ==
                                            'failed') {
                                            toastr.error(data[
                                                "message"]);
                                        } else {
                                            toastr.error(
                                                'Something wrong ! Please try again.'
                                            );
                                        }
                                    }
                                }
                            });

                        }
                        return;

                    });


                    if ($("#paymentTypeID").text()) {
                        var paymentType = $("#paymentTypeID").val();
                        if (paymentType == "") {
                            $(".paymentID").hide();
                            $(".paymentAgentNumber").hide();
                            $(".paymentAmount").hide();
                        } else {
                            $(".paymentID").show();
                            $(".paymentAgentNumber").show();
                            $(".paymentAmount").show();
                        }
                    }

                    $("#paymentTypeID").select2({
                        placeholder: "Select a payment Type",
                        dropdownParent: $('#paymntidname'),
                        allowClear: true,
                        ajax: {
                            data: function(params) {
                                return {
                                    q: params.term
                                };
                                console.log(params);
                            },
                            url: '{{ url('admin_order/paymenttype') }}',
                            processResults: function(data) {

                                var data = $.parseJSON(data);
                                return {
                                    results: data
                                };
                            }
                        }
                    }).trigger("change").on("select2:select", function(e) {
                        if (e.params.data.text == "") {
                            $(".paymentID").hide();
                            $(".paymentAgentNumber").hide();
                            $(".paymentAmount").hide();
                        } else {
                            $(".paymentID").show();
                            $(".paymentAgentNumber").show();
                            $(".paymentAmount").show();
                        }
                    }).on("select2:unselect", function(e) {
                        $(".paymentID").hide();
                        $(".paymentAgentNumber").hide();
                        $(".paymentAmount").hide();
                        calculation();
                    });

                    $("#paymentID").select2({
                        placeholder: "Select a payment Number",
                        dropdownParent: $('#paymentIDname'),
                        allowClear: true,
                        ajax: {
                            data: function(params) {
                                return {
                                    q: params.term,
                                    paymentTypeID: $("#paymentTypeID").val(),
                                };
                            },
                            type: 'GET',
                            url: '{{ url('admin_order/paymentnumber') }}',

                            processResults: function(data) {
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


                },
                error: function(error) {
                    swal({
                        icon: 'error',
                        title: 'Cant Open Complain !',
                        text: 'Complain Phone number is not valid',
                        buttons: true,
                        buttons: "Thanks",
                    });
                }
                
            });
        });

        //update
        $('#EditCourier').submit(function(e) {
            e.preventDefault();
            let courierId = $('#idhidden').val();

            $.ajax({
                type: 'POST',
                url: 'courier/' + courierId,
                processData: false,
                contentType: false,
                data: new FormData(this),

                success: function(data) {
                    $('#editcourierName').val('');
                    $('#editcourierCharge').val('');

                    swal({
                        title: "Courier update successfully !",
                        icon: "success",
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes",
                        cancelButtonText: "No",
                    });
                    courierinfotbl.ajax.reload();
                },
                error: function(error) {
                    console.log('error');
                }
            });
        });

        //delete complain

        $(document).on('click', '#deleteComplainBtn', function() {
            let complainsId = $(this).data('id');
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
                            type: 'DELETE',
                            url: 'complains/' + complainsId,

                            success: function(data) {
                                swal("Poof! Your complain has been deleted!", {
                                    icon: "success",
                                });
                                complaininfotbl.ajax.reload();
                            },
                            error: function(error) {
                                console.log('error');
                            }

                        });


                    } else {
                        swal("Your data is safe!");
                    }
                });

        });

        //status update

        $(document).on('click', '#complainstatusBtn', function() {
            let complainsId = $(this).data('id');
            let complainStatus = $(this).data('status');

            $.ajax({
                type: 'POST',
                url: 'complainstatus',
                data: {
                    'complain_id': complainsId,
                    'status': complainStatus, 
                    '_token': token
                },

                success: function(data) {
                    swal({
                        title: "Status updated !",
                        icon: "success",
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes",
                        cancelButtonText: "No",
                    });
                    complaininfotbl.ajax.reload();
                },
                error: function(error) {
                    console.log('error');
                }

            });
        });

        $(document).on('click', '.btn-status', function(e) {
            e.preventDefault();
            let complainsId = $(this).data('id');
            let complainStatus = $(this).data('status');

            $.ajax({
                type: 'POST',
                url: 'complainstatus',
                data: {
                    'complain_id': complainsId,
                    'status': complainStatus, 
                    '_token': token
                },

                success: function(data) {
                    swal({
                        title: "Status updated !",
                        icon: "success",
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes",
                        cancelButtonText: "No",
                    });
                    complaininfotbl.ajax.reload();
                },
                error: function(error) {
                    console.log('error');
                }

            });
        });

        $(document).on('click', '.btn-print-courieruserreport', function(){
            $(".buttons-print")[0].click();
        });
        $(document).on('change', '#startDate', function(){
            complaininfotbl.ajax.reload();
        });
        $(document).on('change', '#endDate', function(){
            complaininfotbl.ajax.reload();
        });







    });
     

</script>

@if (Auth::user()->role == 0 || Auth::user()->role == 1)
    <style>
        .btn-delete {
            display: none;
        }
    </style>
@else
@endif
@endsection
