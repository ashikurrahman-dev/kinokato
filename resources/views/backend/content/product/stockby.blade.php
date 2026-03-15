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
</style>

<div class="container-fluid pt-4 px-4">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="h-100 bg-secondary rounded p-4 pb-0 text-center">
                <h2 class="mb-0">Products Stock Overview</h2>
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="bg-secondary rounded h-100 p-4">
                <div class="row">
                    <div class="col-md-6 col-xl-2">
                        <a href="{{ url('admin/stock/overview') }}">
                            <div class="widget-rounded-circle card-box order pt-1 pb-1 bg-white" style="border-radius: 6px">
                                <div class="row">
                                    <div class="col-12" style="padding: 0px 30px;">
                                        <div class="float-left">
                                            <h3 class="text-dark mt-1 mb-0">
                                                <span id="stocktotal" data-plugin="counterup">{{App\Models\Product::get()->count()}}</span>
                                            </h3>
                                            <p class="text-muted mb-1 text-truncate">Total</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div> <!-- end widget-rounded-circle-->
                        </a>
                    </div> <!-- end col-->
 
                    <div class="col-md-6 col-xl-2">
                        <a href="{{ url('admin/stock-by/product') }}">

                            <div class="widget-rounded-circle card-box order pt-1 pb-1 bg-white" style="border-radius: 6px">
                                <div class="row">
                                    <div class="col-12" style="padding: 0px 30px;">
                                        <div class="float-left">
                                            <h3 class="text-dark mt-1 mb-0">
                                                <span id="stock_out" data-plugin="counterup">{{App\Models\Product::get()->count()}}</span>
                                            </h3>
                                            <p class="text-muted mb-1 text-truncate">Stock By Product</p>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div> <!-- end widget-rounded-circle-->
                        </a>
                    </div> <!-- end col-->
                </div>

                <div class="data-tables">
                    <div class="form-group pt-2 mb-2 mt-4" style="float:right">
                        <button class="btn btn-info btn-print-report"><i class="fas fa-print"></i> Print</button>
                    </div>
                    <table class="table table-dark" id="stockinfo" width="100%" style="text-align: center;">
                        <thead class="thead-light">
                            <tr>
                                <th>SL</th>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Total</th>
                                <th>Sold</th>
                                <th>Available</th>
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
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </div>
</div>

<script>
    $(document).ready(function() {
        var token = $("input[name='_token']").val();

        var stockinfo = $('#stockinfo').DataTable({
            order: [
                [0, 'desc']
            ],
            processing: true,
            serverSide: true,
            ajax: '{!! url('admin/stock-by/product/data') !!}',
            deferRender: true,
            pageLength: 50,
            columns: [{
                    data: 'serial'
                },
                {
                    data: 'image'
                },
                {
                    data: 'products'
                },
                {
                    data: 'total'
                },
                {
                    data: 'sold'
                },
                {
                    data: 'available'
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
                        var printTitle = 'Stock Report By Product';
                        return printTitle;
                    },
                    exportOptions : {
                        stripHtml : false,
                        columns: [ 0, 1, 2, 3, 4, 5 ]
                    },
                    customize: function (win) {
                        $(win.document.body).find('h1').css('text-align','center');

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
                for(var i = 3; i<=5;i++){
                    OrderTotal = api.column(i, { page: "current" }).data().reduce(function (a, b) {
                        return a + b;
                    }, 0);
                    $(api.column(i).footer()).html(OrderTotal);
                }
            }
        });

        $(document).on('click', '.btn-print-report', function(){
            $(".buttons-print")[0].click();
        });

    });
</script>

@endsection
