@extends('backend.master')

@section('maincontent')
    <div class="px-4 pt-4 container-fluid">


        <div class="pagetitle row">
            <div class="col-6">
                <h1><a href="{{url('/admin/dashboard')}}">Dashboard</a></h1>
                <nav>
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/admin/dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">userreport</li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form class="form" method="POST" action="{{url('download-excle')}}">
                    <div class="card-body">
                        <div class="mb-2 row">
                            <div class="col-lg-12">
                                <div class="form-row">

                                        @csrf
                                        <div class="p-2 form-group col-md-2">
                                            <label for="inputCity" class="col-form-label">Start Date</label>
                                            <input type="text" class="form-control datepicker" name="startDate" id="startDate"  value="<?php echo date('Y-m-d')?>" placeholder="Select Date">
                                        </div>
                                        <div class="p-2 form-group col-md-2">
                                            <label for="inputCity" class="col-form-label">End Date</label>
                                            <input type="text" class="form-control datepicker" name="endDate" id="endDate" value="<?php echo date('Y-m-d')?>" placeholder="Select Date">
                                        </div>
                                        <div class="p-2 form-group col-md-2">
                                            <label for="inputState" class="col-form-label">Select Status</label>
                                            <select id="orderStatus" name="orderStatus" class="form-control">
                                                <option value="All">All</option>
                                                <option value="Shipped">Shipped</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Hold">Hold</option>
                                                <option value="Ready to Ship">Ready to Ship</option>
                                                <option value="Packaging">Packaging</option>
                                                <option value="Cancelled">Cancelled</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Del. Failed">Del. Failed</option>
                                            </select>
                                        </div>
                                        <div class="pt-2 form-group col-md-1">
                                            <label for="inputZip" class="col-form-label" style="opacity: 0">Print</label>
                                            <button class="btn btn-info" type="submit">Download</button>
                                        </div>

                                </div>
                            </div>
                        </div>

                         </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


 <script>

        $(document).ready(function() {
            //date picker
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
