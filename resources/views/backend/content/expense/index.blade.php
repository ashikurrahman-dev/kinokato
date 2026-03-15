@extends('backend.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}- {{$slug}}
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
    .form-select {
        display: block;
        width: 100%;
        padding: .375rem 2.25rem .375rem .75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #0b0b0c;
        background-color: #fff;
        background-image: url(data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e);
        background-repeat: no-repeat;
        background-position: right .75rem center;
        background-size: 16px 12px;
        border: 1px solid #000;
        border-radius: 5px;
        appearance: none;
    }
</style>
<div class="container-fluid pt-4 px-4">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="h-100 rounded p-4 pb-0">
                <div class="d-flex align-items-center justify-content-center mb-4">
                    <h2 class="mb-0">{{$slug}} List</h2>
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
                            <button class="btn btn-info btn-print-expreport"><i class="fas fa-print"></i>
                                Print</button>
                        </div>
                    </div>
                </div>
                @if($slug!='Total Cost')
                <div class="" style="width: 50%;float:left;">
                    <a type="button" data-bs-toggle="modal" data-bs-target="#mainExpense" class="btn btn-primary m-2"
                        style="float: right"> + Create {{$slug}}</a>
                </div>
                @endif

            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="rounded h-100 p-4">
                <div class="data-tables">
                    <table class="table " id="expenseinfo" width="100%" style="text-align: center;">
                        <thead class="thead-light">
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Image</th>
                                <th>Type</th>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
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
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- create payment icon --}}
        <div class="modal fade" id="mainExpense" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content rounded h-100">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: red;">Create New {{$slug}}</h5>
                        <button type="button" class="btn-dark btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form name="form" id="AddExpense" enctype="multipart/form-data">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="title" id="title"
                                    placeholder="Title">
                                <label for="floatingInput">Title</label>
                            </div>
                            <div class="form-group mb-3">
                                <select class="form-select" name="type" id="type">
                                    <option value="{{$slug}}">{{$slug}}</option>
                                </select>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" name="amount" id="amount"
                                    placeholder="Amount">
                                <label for="floatingInput">Amount</label>
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
                                <label for="floatingInput">Image</label>
                                <input class="form-control form-control-lg" name="image" id="image"
                                    type="file">
                            </div>
                            <br>
                            <div class="form-group mt-2" style="text-align: right">
                                <div class="submitBtnSCourse">
                                    <button type="submit" name="btn" data-bs-dismiss="modal" class="btn btn-dark "
                                        style="float: left">Close</button>
                                    <button type="submit" name="btn" id="submbyn"
                                        class="btn btn-primary AddExpenseBtn">Save</button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div><!-- End popup Modal-->

        {{-- edit payment icon --}}
        <div class="modal fade" id="editmainExpense" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content rounded h-100">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: red;">Edit {{$slug}}</h5>
                        <button type="button" class="btn-dark btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form name="form" id="EditExpense" enctype="multipart/form-data">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="title" id="title"
                                    placeholder="Title">
                                <label for="floatingInput">Title</label>
                            </div>
                            <div class="form-group mb-3">
                                <select class="form-select" name="type" id="type">
                                  <option value="{{$slug}}">{{$slug}}</option>
                                </select>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" name="amount" id="amount"
                                    placeholder="Amount">
                                <label for="floatingInput">Amount</label>
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-control" name="payment_type" id="payment_type" required>
                                    <option value="">Choose</option>
                                    @foreach(App\Models\Paymenttype::where('status','Active')->get() as $pay)
                                    <option value="{{$pay->paymentTypeName}}">{{ $pay->paymentTypeName}}</option>
                                    @endforeach
                                </select>
                                <label for="floatingInput" style="color: red">Choose Payment Type</label>
                            </div>

                            <div class="mt-4 mb-4">
                                <label for="floatingInput">Image</label>
                                <input class="form-control form-control-lg" name="image" id="image"
                                    type="file" required>
                            </div>

                            <input type="text" name="expense_id" id="expense_id" hidden>

                            <div class="m-3 ms-0 mb-0"
                                style="text-align: center;height: 100px;margin-top:20px !important">
                                <h4 style="width:30%;float: left;text-align: left;">Image : </h4>
                                <div id="previmg" style="float: left;"></div>
                            </div>
                            <br>
                            <div class="form-group mt-2" style="text-align: right">
                                <div class="submitBtnSCourse">
                                    <button type="submit" name="btn" data-bs-dismiss="modal"
                                        class="btn btn-dark" style="float: left">Close</button>
                                    <button type="submit" name="btn"
                                        class="btn btn-primary AddExpenseBtn">Update</button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div><!-- End popup Modal-->
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

        <input type="hidden" name="expenseslug" id="expenseslug" value="{{ $slug }}" />

    </div>
</div>


<script>
    $(document).ready(function() {
        $(".datepicker").flatpickr();

        var token = $("input[name='_token']").val();
        var slug=$('#expenseslug').val();
        var expenseinfo = $('#expenseinfo').DataTable({
            order: [
                [0, 'desc']
            ],
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('admin/expense/data') }}"+'/'+slug,
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
                    data: 'image',
                    name: 'image',
                    render: function(data, type, full, meta) {
                        return "<img src={{url('/')}}/" + data + " height=\"40\" alt='No Image'/>";
                    }
                },
                {
                    data: 'type'
                },
                {
                    data: 'title'
                },
                {
                    data: 'amount'
                },
                {
                    data: 'account_name'
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
                        var printTitle = slug+' Report';
                        return printTitle;
                    },
                    exportOptions : {
                        stripHtml : false,
                        columns: [ 0, 1, 2, 3, 4, 5,6]
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


        //add expense

        $('#AddExpense').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '{{ url('admin/expenses') }}',
                processData: false,
                contentType: false,
                data: new FormData(this),

                success: function(data) {
                    $('#title').val('');
                    $('#amount').val('');

                    swal({
                        title: "Success!",
                        icon: "success",
                    });
                    expenseinfo.ajax.reload();
                },
                error: function(error) {
                    console.log('error');
                }
            });
        });

        //edit expense
        $(document).on('click', '#editExpenseBtn', function() {
            let expenseId = $(this).data('id');

            $.ajax({
                type: 'GET',
                url: '../expenses/' + expenseId + '/edit',

                success: function(data) {
                    $('#EditExpense').find('#title').val(data
                        .title);
                    $('#EditExpense').find('#payment_type').val(data.account_name);
                    $('#EditExpense').find('#expense_id').val(data.id);
                    $('#EditExpense').find('#amount').val(data.amount);
                    $('#previmg').html('');
                    $('#previmg').append(`
                        <img  src="../` + data.image + `" alt = "" style="height: 80px" />
                    `);

                    $('#EditExpense').attr('data-id', data.id);
                },
                error: function(error) {
                    console.log('error');
                }

            });
        });

        //update expense
        $('#EditExpense').submit(function(e) {
            e.preventDefault();
            let expenseId = $('#expense_id').val();

            $.ajax({
                type: 'POST',
                url: '../expense/' + expenseId,
                processData: false,
                contentType: false,
                data: new FormData(this),

                success: function(data) {
                    $('#EditExpense').find('#title').val('');
                    $('#EditExpense').find('#amount').val('');
                    $('#EditExpense').find('#expense_id').val('');
                    $('#previmg').html('');

                    swal({
                        title: "Expense update successfully !",
                        icon: "success",
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes",
                        cancelButtonText: "No",
                    });
                    expenseinfo.ajax.reload();

                },
                error: function(error) {
                    console.log('error');
                }
            });
        });

        // delete expense

        $(document).on('click', '#deleteExpenseBtn', function() {
            let expenseId = $(this).data('id');
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
                            url: '../expenses/' + expenseId,
                            data: {
                                '_token': token
                            },
                            success: function(data) {
                                swal("Expense has been deleted!", {
                                    icon: "success",
                                });
                                expenseinfo.ajax.reload();
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

        $(document).on('click', '#expensestatusBtn', function() {
            let expenseId = $(this).data('id');
            let expenseStatus = $(this).data('status');

            $.ajax({
                type: 'PUT',
                url: '../expense/status',
                data: {
                    expense_id: expenseId,
                    status: expenseStatus,
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
                    expenseinfo.ajax.reload();
                },
                error: function(error) {
                    console.log('error');
                }

            });
        });

        // front status update

        $(document).on('click', '#expensefrontstatusBtn', function() {
            let expenseId = $(this).data('id');
            let expenseFrontStatus = $(this).data('status');

            $.ajax({
                type: 'PUT',
                url: 'expense/status',
                data: {
                    expense_id: expenseId,
                    front_status: expenseFrontStatus,
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
                    expenseinfo.ajax.reload();
                },
                error: function(error) {
                    console.log('error');
                }

            });
        });


        $(document).on('click', '.btn-print-expreport', function() {
            $(".buttons-print")[0].click();
        });
        $(document).on('change', '#startDate', function() {
            expenseinfo.ajax.reload();
        });
        $(document).on('change', '#endDate', function() {
            expenseinfo.ajax.reload();
        });

    });
</script>

@endsection
