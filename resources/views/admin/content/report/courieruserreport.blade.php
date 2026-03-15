@extends('backend.master')

@section('maincontent')
    <div class="px-4 pt-4 container-fluid">

        <div class="pagetitle row">
            <div class="col-6">
                <h1><a href="{{url('/admin/dashboard')}}">Dashboard</a></h1>
                <nav>
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/admin/dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Salse Report</li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-2 row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <div class="p-2 form-group col-md-2">
                                        <label for="inputCity" class="col-form-label">Start Date</label>
                                        <input type="text" class="form-control datepicker" id="startDate"  value="<?php echo date('Y-m-d')?>" placeholder="Select Date">
                                    </div>
                                    <div class="p-2 form-group col-md-2">
                                        <label for="inputCity" class="col-form-label">End Date</label>
                                        <input type="text" class="form-control datepicker" id="endDate" value="<?php echo date('Y-m-d')?>" placeholder="Select Date">
                                    </div>
                                    <div class="p-2 form-group col-md-3">
                                        <label for="inputState" class="col-form-label">Select Courier</label>
                                        <select id="courierID" class="form-control"></select>
                                    </div>
                                    <div class="p-2 form-group col-md-2">
                                        <label for="inputState" class="col-form-label">Select User</label>
                                        <select id="userID" class="form-control"></select>
                                    </div>
                                    <div class="p-2 form-group col-md-2">
                                        <label for="inputState" class="col-form-label">Select Status</label>
                                        <select id="orderStatus" class="form-control">
                                            <option value="All">All</option>
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
                                    <div class="pt-2 form-group col-md-1">
                                        <label for="" class="col-form-label" style="opacity: ">Print</label>
                                        <button class="btn btn-info btn-print-courieruserreport"><i class="fas fa-print"></i> Print</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="webreportTable" class="table mb-0 table-centered table-nowrap" style="width: 100%">
                                <thead class="thead-light" >
                                <tr>
                                    <th>Date</th>
                                    <th>Invoice ID</th>
                                    <th>Name</th>
                                    <th>Products</th>
                                    <th>Total</th>
                                    <th>Delivery Charge</th>
                                    <th>Discount</th>
                                    <th>Courier</th>
                                    <th>User</th>
                                    <th>Status</th>
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
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function() {
            //token
            var token = $("input[name='_token']").val();
            //date picker
            $(".datepicker").flatpickr();

            var table = $("#webreportTable").DataTable({
                type: "GET",
                ajax: {
                    url: "{{url('admin/courier/user/report/data')}}",
                    data: {
                        startDate: function() { return $('#startDate').val() },
                        endDate: function() { return $('#endDate').val() },
                        courierID: function() { return $('#courierID').val() },
                        userID: function() { return $('#userID').val() },
                        orderStatus: function() { return $('#orderStatus').val() }
                    }
                },
                ordering: false,
                paging: false,
                columns: [
                    {data: "orderDate"},
                    {data: "invoiceID"},
                    {
                        data: null,
                        width: "15%",
                        render: function (data) {
                            return '<i class="mr-2 fas fa-user text-grey-dark"></i>'+data.customerName +'<br> <i class="mr-2 fas fa-phone text-grey-dark"></i>'+data.customerPhone+'<br><i class="mr-2 fas fa-map-marker text-grey-dark"></i>' + data.customerAddress;
                        }
                    },
                    {
                        data: "products",
                        width: "15%"
                    },
                    {data: "subTotal"},
                    {data: "deliveryCharge"},
                    {data: "discountCharge"},
                    {data: "courierName"},
                    {data: "name"},
                    {data: "status"}
                ],
                lengthChange: false,
                bFilter:false,
                search:false,
                dom: '<"row"<"col-sm-6"Bl><"col-sm-6"f>>' +
                    '<"row"<"col-sm-12"<"table-responsive"tr>>>' +
                    '<"row"<"col-sm-5"i><"col-sm-7"p>>',
                buttons: {
                    buttons: [{
                        extend: 'print',
                        text: 'Print',
                        footer: true ,
                        title: function(){
                            var printTitle = 'Sales Report';
                            return printTitle;
                        },
                        customize: function (win) {
                            $(win.document.body).find('h1').css('text-align','center');
                            $(win.document.body).find('h1').after('<p style="text-align: center">From : '+$('#startDate').val()+' - To : '+$('#endDate').val()+'</p>');

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
                footerCallback: function ( ) {
                    var api = this.api();
                    var numRows = api.rows().count();
                    $('.total').empty().append(numRows);

                    var intVal = function (i) {
                        return typeof i === "string" ? i.replace(/[\$,]/g, "") * 1 : typeof i === "number" ? i : 0;
                    };
                    pageTotal = api.column(4, { page: "current" }).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    $(api.column(4).footer()).html(pageTotal + " Tk");

                    deliveryTotal = api.column(5, { page: "current" }).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    $(api.column(5).footer()).html(deliveryTotal + " Tk");

                    discountTotal = api.column(6, { page: "current" }).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    $(api.column(6).footer()).html(discountTotal + " Tk");
                }

            });

            $("#courierID").select2({
                placeholder: "Select a Courier",
                ajax: {
                    url: '{{url('admin_order/courier')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            }).trigger("change").on("select2:select", function (e) {
                table.ajax.reload();
            });
            $("#userID").select2({
                placeholder: "Select a User",
                allowClear:true,
                ajax: {
                    url:'{{url('admin_order/users')}}',
                    processResults: function (data) {
                        var data = $.parseJSON(data);
                        return {
                            results: data
                        };
                    }
                }
            }).trigger("change").on("select2:select", function (e) {
                table.ajax.reload();
            });

            $(document).on('click', '.btn-print-courieruserreport', function(){
                $(".buttons-print")[0].click();
            });
            $(document).on('change', '#startDate', function(){
                table.ajax.reload();
            });
            $(document).on('change', '#endDate', function(){
                table.ajax.reload();
            });
            $(document).on('change', '#orderStatus', function(){
                table.ajax.reload();
            });


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


.form-row {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-right: -5px;
    margin-left: -5px;
}
.select2-container--default .select2-selection--single {
    display: block;
    width: 100%;
    height: calc(1.5em + 0.9rem + 2px);
    padding: 0.3rem 0.9rem;
    font-size: .875rem;
    font-weight: 400;
    line-height: 1.5;
    color: #383b3d;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.2rem;
}
span.select2-selection__arrow {
    margin-top: 5px;
}
</style>



@endsection
