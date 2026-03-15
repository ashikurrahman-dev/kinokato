@extends('backend.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}- Account
@endsection


<div class="container-fluid pt-4 px-4">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="h-100 rounded p-4 pb-0">
                <div class="d-flex align-items-center justify-content-center mb-4">
                    <h2 class="mb-0">{{$slug}} Payment List</h2>
                </div>
                <div class="" style="width: 50%;float:left;">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="inputCity" class="col-form-label">Start Date</label>
                            <input type="text" class="form-control datepicker" id="startDate"
                                value="<?php echo date('Y-m-d'); ?>" placeholder="Select Date">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputCity" class="col-form-label">End Date</label>
                            <input type="text" class="form-control datepicker" id="endDate"
                                value="<?php echo date('Y-m-d'); ?>" placeholder="Select Date">
                        </div>
                        <div class="form-group col-md-2 pt-1" style="margin-top: 35px;">
                            <button class="btn btn-info btn-print-accountreport"><i class="fas fa-print"></i>
                                Print</button>
                        </div>
                    </div>
                </div>
                @if($slug!='Total')
                    <div class="" style="width: 50%;float:left;">
                        <a type="button" data-bs-toggle="modal" data-bs-target="#mainAccount" class="btn btn-primary m-2"
                            style="float: right"> + Add New {{$slug}} Payment</a>
                    </div>
                @endif

            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="rounded h-100 p-4">
                <div class="data-tables">
                    <table class="table table-dark" id="accountinfo" width="100%" style="text-align: center;">
                        <thead class="thead-light">
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>File</th>
                                <th>Title</th>
                                <th>From</th>
                                <th>Amount</th>
                                <th>Payment Methood</th>
                                <th>Admin</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- create payment icon --}}
        <div class="modal fade" id="mainAccount" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content rounded h-100">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: red;">Add New {{$slug}} Payment</h5>
                        <button type="button" class="btn-dark btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form name="form" id="AddAccount" enctype="multipart/form-data">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="title" id="title"
                                    placeholder="Title">
                                <label for="floatingInput">Title</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" name="amount" id="amount"
                                    placeholder="Amount">
                                <label for="floatingInput">Amount</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-control" name="form">
                                    <option value="{{$slug}}">{{$slug}}</option>
                                </select>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-control" name="payment_type" requied>
                                    <option value="">Choose</option>
                                    @foreach(App\Models\Paymenttype::where('status','Active')->get() as $pay)
                                    <option value="{{$pay->paymentTypeName}}">{{ $pay->paymentTypeName}}</option>
                                    @endforeach
                                </select>
                                <label for="floatingInput" style="color: red">Choose Payment Type</label>
                            </div>
                            
                            <div class="mt-4 mb-4">
                                <label for="floatingInput">File</label>
                                <input class="form-control form-control-lg" name="file" id="file"
                                    type="file">
                            </div>
                            <br>
                            <div class="form-group mt-2" style="text-align: right">
                                <div class="submitBtnSCourse">
                                    <button type="submit" name="btn" data-bs-dismiss="modal" class="btn btn-dark "
                                        style="float: left">Close</button>
                                    <button type="submit" name="btn" id="submbyn"
                                        class="btn btn-primary AddAccountBtn">Save</button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div><!-- End popup Modal-->

        {{-- edit payment icon --}}
        <div class="modal fade" id="editmainAccount" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content rounded h-100">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: red;">Edit {{$slug}} Payment</h5>
                        <button type="button" class="btn-dark btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form name="form" id="EditAccount" enctype="multipart/form-data">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="title" id="title"
                                    placeholder="Title">
                                <label for="floatingInput">Title</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" name="amount" id="amount"
                                    placeholder="Amount">
                                <label for="floatingInput">Amount</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-control" name="form">
                                    <option value="{{$slug}}">{{$slug}}</option>
                                </select>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-control" name="payment_type" id="editpayment_type" requied>
                                    <option value="">Choose</option>
                                    @foreach(App\Models\Paymenttype::where('status','Active')->get() as $pay)
                                    <option value="{{$pay->paymentTypeName}}">{{ $pay->paymentTypeName}}</option>
                                    @endforeach
                                </select>
                                <label for="floatingInput" style="color: red">Choose Payment Type</label>
                            </div>
                            
                            <div class="mt-4 mb-4">
                                <label for="floatingInput">File</label>
                                <input class="form-control form-control-lg" name="file" id="file"
                                    type="file" required>
                            </div>

                            <input type="text" name="account_id" id="account_id" hidden>

                            <div class="m-3 ms-0 mb-0"
                                style="text-align: center;height: 100px;margin-top:20px !important">
                                <h4 style="width:30%;float: left;text-align: left;">File : </h4>
                                <div id="previmg" style="float: left;"></div>
                            </div>
                            <br>
                            <div class="form-group mt-2" style="text-align: right">
                                <div class="submitBtnSCourse">
                                    <button type="submit" name="btn" data-bs-dismiss="modal"
                                        class="btn btn-dark" style="float: left">Close</button>
                                    <button type="submit" name="btn"
                                        class="btn btn-primary AddAccountBtn">Update</button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div><!-- End popup Modal-->
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="accountslug" id="accountslug" value="{{ $slug }}" />
    </div>
</div>


<script>
    $(document).ready(function() {
        $(".datepicker").flatpickr();

        var token = $("input[name='_token']").val();
        var slug=$('#accountslug').val();
        var accountinfo = $('#accountinfo').DataTable({
            order: [
                [0, 'desc']
            ],
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('admin/account/data') }}"+'/'+slug,
                data: {
                    startDate: function() {
                        return $('#startDate').val()
                    },
                    endDate: function() {
                        return $('#endDate').val()
                    }
                }
            },
            columns: [{
                    data: 'id'
                },
                {
                    data: 'date'
                },
                {
                    data: 'file',
                    name: 'file',
                    render: function(data, type, full, meta) {
                        return "<img src={{url('/')}}/" + data + " height=\"40\" alt='No Image'/>";
                    }
                },
                {
                    data: 'titleinfo'
                },
                {
                    data: 'form'
                },
                {
                    data: 'amount'
                },
                {
                    data: 'account_name'
                },
                {
                    data: 'admin'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },

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
                        var printTitle = slug+' payment Report';
                        return printTitle;
                    },
                    exportOptions : {
                        stripHtml : false,
                        columns: [ 0, 1, 2, 3, 4,5,6]
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

                discountTotal = api.column(5, {
                    page: "current"
                }).data().reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
                $(api.column(5).footer()).html(discountTotal + " Tk");
            }
        });


        //add account

        $('#AddAccount').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '{{ url('admin/accounts') }}',
                processData: false,
                contentType: false,
                data: new FormData(this),

                success: function(data) {
                    $('#title').val('');
                    $('#amount').val('');
                    $('#file').val('');

                    swal({
                        title: "Success!",
                        icon: "success",
                    });
                    accountinfo.ajax.reload();
                },
                error: function(error) {
                    console.log('error');
                }
            });
        });

        //edit account
        $(document).on('click', '#editAccountBtn', function() {
            let accountId = $(this).data('id');

            $.ajax({
                type: 'GET',
                url: '../accounts/' + accountId + '/edit',

                success: function(data) {
                    $('#EditAccount').find('#title').val(data.title);
                    $('#EditAccount').find('#editpayment_type').val(data.account_name);
                    $('#EditAccount').find('#amount').val(data.amount);
                    $('#EditAccount').find('#account_id').val(data.id);
                    $('#previmg').html('');
                    $('#previmg').append(`
                        <img  src="../../` + data.file + `" alt = "" style="height: 80px" />
                    `);

                    $('#EditAccount').attr('data-id', data.id);
                },
                error: function(error) {
                    console.log('error');
                }

            });
        });

        //update account
        $('#EditAccount').submit(function(e) {
            e.preventDefault();
            let accountId = $('#account_id').val();

            $.ajax({
                type: 'POST',
                url: '../account/' + accountId,
                processData: false,
                contentType: false,
                data: new FormData(this),

                success: function(data) {
                    $('#EditAccount').find('#title').val('');
                    $('#EditAccount').find('#amount').val('');
                    $('#EditAccount').find('#file').val('');
                    $('#EditAccount').find('#account_id').val('');
                    $('#previmg').html('');

                    swal({
                        title: "Account update successfully !",
                        icon: "success",
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes",
                        cancelButtonText: "No",
                    });
                    accountinfo.ajax.reload();

                },
                error: function(error) {
                    console.log('error');
                }
            });
        });

        // delete account

        $(document).on('click', '#deleteAccountBtn', function() {
            let accountId = $(this).data('id');
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
                            url: '../accounts/' + accountId,
                            data: {
                                '_token': token
                            },
                            success: function(data) {
                                swal("Account has been deleted!", {
                                    icon: "success",
                                });
                                accountinfo.ajax.reload();
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

        // status update

        $(document).on('click', '#accountstatusBtn', function() {
            let accountId = $(this).data('id');
            let accountStatus = $(this).data('status');

            $.ajax({
                type: 'PUT',
                url: 'account/status',
                data: {
                    account_id: accountId,
                    status: accountStatus,
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
                    accountinfo.ajax.reload();
                },
                error: function(error) {
                    console.log('error');
                }

            });
        });

        // front status update

        $(document).on('click', '#accountfrontstatusBtn', function() {
            let accountId = $(this).data('id');
            let accountFrontStatus = $(this).data('status');

            $.ajax({
                type: 'PUT',
                url: 'account/status',
                data: {
                    account_id: accountId,
                    front_status: accountFrontStatus,
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
                    accountinfo.ajax.reload();
                },
                error: function(error) {
                    console.log('error');
                }

            });
        });

        $(document).on('click', '.btn-print-accountreport', function() {
            $(".buttons-print")[0].click();
        });
        $(document).on('change', '#startDate', function() {
            accountinfo.ajax.reload();
        });
        $(document).on('change', '#endDate', function() {
            accountinfo.ajax.reload();
        });

    });
</script>

@endsection
