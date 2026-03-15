@extends('backend.master')

@section('maincontent')
    <div class="container-fluid pt-4 px-4">

        <div class="pagetitle row">
            <div class="col-6">
                <h1><a href="{{url('/admindashboard')}}">Dashboard</a></h1>
                <nav>
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/admindashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Stocks</li>
                    </ol>
                </nav>
            </div>
            <div class="col-6" style="text-align: right">
                {{-- <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mainStock"><span style="font-weight: bold;">+</span>  Add New Stock</button> --}}
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
                        <table class="table table-centered table-borderless table-hover mb-0" id="stocksinfotbl" width="100%">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Purchese</th>
                                <th style="width:250px">Product Name</th>
                                <th>Size</th>
                                <th>Total</th>
                                <th>Initial</th>
                                <th>Purchese</th>
                                <th>Stock</th>
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

    </div>



    <script>
        $(document).ready(function() {

           var stockseinfotbl = $('#stocksinfotbl').DataTable({
                order: [ [0, 'desc'] ],
                processing: true,
                serverSide: true,
                ajax: '{!! route('stock.info') !!}',
                columns: [
                    { data: 'id' },
                    { data: 'date' },
                    { data: 'purchese' },
                    { data: 'product_name' },
                    { data: 'size' },
                    { data: 'total_stock' },
                    { data: 'initial_stock' },
                    { data: 'purchase' },
                    { data: 'stock' },

                ]
            });

        });

    </script>

@endsection
