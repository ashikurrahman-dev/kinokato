@extends('backend.master')

@section('maincontent')
    <div class="container-fluid pt-4 px-4">

        <div class="pagetitle row">
            <div class="col-6">
                <h1><a href="{{url('/admindashboard')}}">Dashboard</a></h1>
                <nav>
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/admindashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">coupons</li>
                    </ol>
                </nav>
            </div>
            <div class="col-6" style="text-align: right">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mainCoupon"><span style="font-weight: bold;">+</span>  Add New Coupon</button>
            </div>
        </div><!-- End Page Title -->

        {{-- //popup modal for create user --}}
        <div class="modal fade" id="mainCoupon" tabindex="-1" data-bs-backdrop="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Coupon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form name="form" id="AddCoupon" enctype="multipart/form-data">
                            @csrf
                            <div class="successSMS"></div>
                            <div class="form-group mb-3">
                                <label for="menuName" class="control-label mt-2">Coupon Code</label>
                                <div class="">
                                    <input type="text" class="form-control" name="code" id="code" required>
                                </div>
                            </div>

                            <input type="date" class="form-control" value="{{date('Y-m-d')}}" name="date" id="date" hidden>

                            <div class="form-group pb-3">
                                <label for="websiteTitle" class="control-label">Type</label>
                                <div class="webtitle">
                                    <select class="form-control" name="type" id="type" >
                                        <option value="">Select Type</option>
                                        <option value="Percent">Percent</option>
                                        <option value="Amount">Amount</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="menuName" class="control-label mt-2">Amount</label>
                                <div class="">
                                    <input type="text" class="form-control" name="amount" id="amount" required>
                                </div>
                            </div>
                            <div class="form-group pb-3">
                                <label for="websiteTitle" class="control-label">Validity</label>
                                <div class="webtitle">
                                    <input type="date" class="form-control" value="{{date('Y-m-d')}}" name="validity" id="validity" required>
                                </div>
                            </div>


                            <div class="form-group pb-3">
                                <label for="websiteTitle" class="control-label">Status</label>
                                <div class="webtitle">
                                    <select class="form-control" name="status" id="status" >
                                        <option value="">Select Status</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" style="text-align: right">
                                <div class="submitBtnSCourse">
                                    <button type="submit" name="btn" class="btn btn-primary AddCouponBtn btn-block">Save</button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div><!-- End popup Modal-->

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
                        <table class="table table-centered table-borderless table-hover mb-0" id="couponinfo" width="100%">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Coupon</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Validity</th>
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

          {{-- //popup modal for edit user --}}
        <div class="modal fade" id="editmainCoupon" tabindex="-1" data-bs-backdrop="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Coupon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form name="form" id="EditCoupon" enctype="multipart/form-data">
                            @csrf
                            <div class="successSMS"></div>

                            <div class="form-group mb-3">
                                <label for="menuName" class="control-label mt-2">Coupon Code</label>
                                <div class="">
                                    <input type="text" class="form-control" name="code" id="code" required>
                                </div>
                            </div>

                            <div class="form-group pb-3">
                                <label for="websiteTitle" class="control-label">Type</label>
                                <div class="webtitle">
                                    <select class="form-control" name="type" id="type" >
                                        <option value="">Select Type</option>
                                        <option value="Percent">Percent</option>
                                        <option value="Amount">Amount</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="menuName" class="control-label mt-2">Amount</label>
                                <div class="">
                                    <input type="text" class="form-control" name="amount" id="amount" required>
                                </div>
                            </div>
                            <input type="date" class="form-control" value="{{date('Y-m-d')}}" name="date" id="date" hidden>

                            <div class="form-group pb-3">
                                <label for="websiteTitle" class="control-label">Validity</label>
                                <div class="webtitle">
                                    <input type="date" class="form-control" value="{{date('Y-m-d')}}" name="validity" id="validity" required>
                                </div>
                            </div>
                            <input type="hidden" name="idhidden" id="idhidden">
                            <div class="form-group pb-3">
                                <label for="websiteTitle" class="control-label">Status</label>
                                <div class="webtitle">
                                    <select class="form-control" name="status" id="status" >
                                        <option value="">Select Status</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" style="text-align: right">
                                <div class="submitBtnSCourse">
                                    <button type="submit" name="btn" class="btn btn-primary btn-block">Save</button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div><!-- End popup Modal-->

    </div>

<input type="hidden" name="_token" value="{{ csrf_token() }}" />

    <script>
        $(document).ready(function() {
           var token = $("input[name='_token']").val();

           var couponinfotbl = $('#couponinfo').DataTable({
                order: [ [0, 'desc'] ],
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.coupon.data') !!}',
                columns: [
                    { data: 'id' },
                    { data: 'code' },
                    { data: 'type' },
                    { data: 'amount' },
                    { data: 'validity' },
                    {
                        "data": null,
                        render: function (data) {

                            if (data.status === 'Active') {
                                return '<button type="button" class="btn btn-success btn-sm btn-status" data-status="Inactive" id="couponstatusBtn" data-id="'+data.id+'">Active</button>';
                            } else {
                                return '<button type="button" class="btn btn-warning btn-sm btn-status" data-status="Active" id="couponstatusBtn" data-id="'+data.id+'" >Inactive</button>';
                            }


                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false},

                ]
            });


            //add user

            $('#AddCoupon').submit(function(e){
                e.preventDefault();

                $.ajax({
                    type:'POST',
                    uploadUrl:'{{route("admin.coupons.store")}}',
                    processData: false,
                    contentType: false,
                    data:new FormData(this),

                    success: function (data) {
                        $('#couponName').val('');
                        $('#courier_id').val('');

                        swal({
                            title: "Success!",
                            icon: "success",
                            showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                        });
                        couponinfotbl.ajax.reload();
                    },
                    error: function(error){
                        console.log('error');
                    }
                });
            });

            //edit coupon

            $(document).on('click', '#editCouponBtn', function(){
                let couponId = $(this).data('id');
                        $('#editcourier_id').val('');
                        $('#editcouponName').val('');
                        $('#EditCoupon').attr('data-id', '');
                $.ajax({
                    type:'GET',
                    url:'coupons/'+couponId+'/edit',

                    success: function (data) {
                        $('#EditCoupon').find('#code').val(data.code);
                        $('#EditCoupon').find('#date').val(data.date);
                        $('#EditCoupon').find('#validity').val(data.validity);
                        $('#EditCoupon').find('#type').val(data.type);
                        $('#EditCoupon').find('#amount').val(data.amount);
                        $('#EditCoupon').find('#status').val(data.status);
                        $('#EditCoupon').find('#idhidden').val(data.id);
                        $('#EditCoupon').attr('data-id', data.id);
                    },
                    error: function(error){
                        console.log('error');
                    }

                });
            });

            //update coupon
            $('#EditCoupon').submit(function(e){
                e.preventDefault();
                let couponId = $('#idhidden').val();

                $.ajax({
                    type:'POST',
                    url:'coupon/'+couponId,
                    processData: false,
                    contentType: false,
                    data:new FormData(this),

                    success: function (data) {
                        $('#editcourier_id').val('');
                        $('#editcouponName').val('');

                        $("#EditCoupon").trigger("reset");

                        swal({
                            title: "Coupon update successfully !",
                            icon: "success",
                            showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                        });
                        couponinfotbl.ajax.reload();
                    },
                    error: function(error){
                        console.log('error');
                    }
                });
            });

            //deleteuser

            $(document).on('click', '#deleteCouponBtn', function(){
                let couponId = $(this).data('id');
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
                            url:'coupons/'+couponId,

                            success: function (data) {
                                swal("Poof! Your coupon has been deleted!", {
                                    icon: "success",
                                });
                                couponinfotbl.ajax.reload();
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

            //status update

             $(document).on('click', '#couponstatusBtn', function(){
                let couponId = $(this).data('id');
                let couponStatus = $(this).data('status');

                $.ajax({
                    type:'PUT',
                    url:'coupon/status',
                    data:{
                        coupon_id:couponId,
                        status:couponStatus,
                        '_token': token
                    },

                    success: function (data) {
                        swal({
                            title: "Status updated !",
                            icon: "success",
                            showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                        });
                        couponinfotbl.ajax.reload();
                    },
                    error: function(error){
                        console.log('error');
                    }

                });
            });











        });



    </script>


@endsection
