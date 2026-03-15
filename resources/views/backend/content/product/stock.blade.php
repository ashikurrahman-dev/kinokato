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
        @php
            $total=0;
            $avtotal=0;
            @endphp
            @foreach(App\Models\Category::all() as $category)
                @php
                    $productid=App\Models\Product::where('category_id',$category->id)->get()->pluck('id');
                    $totalstock=App\Models\Size::whereIn('product_id',$productid)->get()->sum('total_stock');
                    $stock=App\Models\Size::whereIn('product_id',$productid)->get()->sum('available_stock');

                    $total+=$totalstock;
                    $avtotal+=$stock;
                @endphp
                <div class="mb-2 col-sm-6 col-xl-3">
                    <div class="p-4 rounded bg-white d-flex align-items-center justify-content-between">
                        <div class="ms-3">
                            <p class="mb-2">{{$category->category_name}}</p>
                            <h6 class="mb-0" id="">{{$stock}} pics</h6>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="mb-2 col-sm-6 col-xl-3">
                <div class="p-4 rounded bg-white d-flex align-items-center justify-content-between">
                    <div class="ms-3">
                        <p class="mb-2">Total</p>
                        <h6 class="mb-0" id="">{{$avtotal}} pics</h6>
                    </div>
                </div>
            </div>

    </div>
               

                <div class="data-tables">
                    <div class="form-group pt-2 mb-2 mt-4" style="float:right">
                        <button class="btn btn-info btn-print-report"><i class="fas fa-print"></i> Print</button>
                    </div>
                    <table class="table table-dark" id="stockinfo" width="100%" style="text-align: center;">
                        <thead class="thead-light">
                            <tr>
                                <th>Product SL</th>
                                <th>Image</th>
                                <th>Product</th>
                                <th style="width: fit-content;">Stock</th> 
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
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </div>
</div>
@if (empty($slug))
@else
    <input type="text" id="stage" value="{{ $slug }}" hidden>
@endif

<script>
    $(document).ready(function() {
        var stage = $('#stage').val();
        var token = $("input[name='_token']").val();

        var stockinfo = $('#stockinfo').DataTable({
            order: [
                [0, 'desc']
            ],
            processing: true,
            serverSide: true,
            ajax: '{!! url('admin/stock/get') !!}'+'/'+stage,
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
                    data: 'stocks'
                }, 
                 
                {
                    "data": null,
                    render: function(data) {

                        if (data.status == 'Active') {
                            return '<button type="button" class="btn btn-success btn-sm btn-status" data-status="Inactive" id="productstatusBtn" data-id="' +
                                data.id + '">Active</button>';
                        } else {
                            return '<button type="button" class="btn btn-warning btn-sm btn-status" data-status="Active" id="productstatusBtn" data-id="' +
                                data.id + '" >Inactive</button>';
                        }

                    }
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
                        var printTitle = 'Stock Report By Size';
                        return printTitle;
                    },
                    exportOptions : {
                        stripHtml : false,
                        columns: [ 0, 1, 2, 3, 4]
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
                
            }
           
        });

        $(document).on('click', '.btn-print-report', function(){
            $(".buttons-print")[0].click();
        });

    });
</script>

@endsection
