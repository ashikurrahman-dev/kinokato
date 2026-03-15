@extends('backend.master')

@section('maincontent')

    <div class="container-fluid pt-4 px-4">

        <div class="pagetitle row">
            <div class="col-6">
                <h1><a href="{{url('/admindashboard')}}">Dashboard</a></h1>
                <nav>
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/admindashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Wcustomers</li>
                    </ol>
                </nav>
            </div>
            <div class="col-6" style="text-align: right">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mainWcustomer"><span style="font-weight: bold;">+</span>  Add New Wcustomer</button>
            </div>
        </div>

        {{-- //popup modal for create user --}}
        <div class="modal fade" id="mainWcustomer" data-bs-backdrop="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Wcustomer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form name="form" id="AddWcustomer" enctype="multipart/form-data">
                            @csrf
                            <div class="successSMS"></div>

                            <div class="form-group pb-3">
                                <label for="websiteTitle" class="control-label">Wcustomer Name</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="wcustomerName" id="wcustomerName" required>
                                    <span class="text-danger">{{ $errors->has('wcustomerName')? $errors->first('wcustomerName'):'' }}</span>
                                </div>
                            </div>

                            <div class="form-group pb-4">
                                <label for="websiteTitle" class="control-label">Wcustomer Phone</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="wcustomerPhone" id="wcustomerPhone" required>
                                    <span class="text-danger">{{ $errors->has('wcustomerPhone')? $errors->first('wcustomerPhone'):'' }}</span>
                                </div>
                            </div>

                            <div class="form-group pb-4">
                                <label for="websiteTitle" class="control-label">Wcustomer Email</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="wcustomerEmail" id="wcustomerEmail" required>
                                    <span class="text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group pb-4">
                                <label for="websiteTitle" class="control-label">Wcustomer Address</label>
                                <div class="webtitle">
                                    <textarea class="form-control" name="wcustomerAddress" id="wcustomerAddress"></textarea>
                                    <span class="text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group pb-4">
                                <label for="websiteTitle" class="control-label">Wcustomer Company Name</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="wcustomerCompanyName" id="wcustomerCompanyName" required>
                                    <span class="text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group pb-4">
                                <label for="websiteTitle" class="control-label">Wcustomer Total Amount</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="wcustomerTotalAmount" id="wcustomerTotalAmount"  required>
                                    <span class="text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group pb-4">
                                <label for="websiteTitle" class="control-label">Wcustomer Paid Amount</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="wcustomerPaidAmount" id="wcustomerPaidAmount" required>
                                    <span class="text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group pb-4">
                                <label for="websiteTitle" class="control-label">Wcustomer Due Amount</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="wcustomerDueAmount" id="wcustomerDueAmount" required>
                                    <span class="text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group pb-4">
                                <label for="websiteTitle" class="control-label">Wcustomer Partial Amount</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="wcustomerPartialAmount" id="wcustomerPartialAmount" required>
                                    <span class="text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group" style="text-align: right">
                                <div class="submitBtnSCourse">
                                    <button type="submit" name="btn" class="btn btn-primary AddWcustomerBtn btn-block">Save</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div><!-- End popup Modal-->

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
                        <table class="table table-centered table-borderless table-hover mb-0" id="wcustomerinfo" width="100%">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Wcustomer Name</th>
                                <th>Wcustomer Phone</th>
                                <th>Total Amount</th>
                                <th>Paid Amount</th>
                                <th>Due Amount</th>
                                <th>Partial Amount</th>
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

        <div class="modal fade" id="editmainWcustomer" tabindex="-1" data-bs-backdrop="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Wcustomer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form name="form" id="EditWcustomer" enctype="multipart/form-data">
                            @csrf
                            <div class="successSMS"></div>

                            <div class="form-group pb-3">
                                <label for="websiteTitle" class="control-label">Wcustomer Name</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="wcustomerName" id="editwcustomerName" required>
                                    <span class="text-danger">{{ $errors->has('wcustomerName')? $errors->first('wcustomerName'):'' }}</span>
                                </div>
                            </div>

                            <div class="form-group pb-4">
                                <label for="websiteTitle" class="control-label">Wcustomer Phone</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="wcustomerPhone" id="editwcustomerPhone" required>
                                    <span class="text-danger">{{ $errors->has('wcustomerPhone')? $errors->first('wcustomerPhone'):'' }}</span>
                                </div>
                            </div>

                            <div class="form-group pb-4">
                                <label for="websiteTitle" class="control-label">Wcustomer Email</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="wcustomerEmail" id="wcustomerEmail" required>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="form-group pb-4">
                                <label for="websiteTitle" class="control-label">Wcustomer Address</label>
                                <div class="webtitle">
                                    <textarea class="form-control" name="wcustomerAddress" id="wcustomerAddress"></textarea>
                                    <span class="text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group pb-4">
                                <label for="websiteTitle" class="control-label">Wcustomer Company Name</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="wcustomerCompanyName" id="wcustomerCompanyName" required>
                                    <span class="text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group pb-4">
                                <label for="websiteTitle" class="control-label">Wcustomer Total Amount</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="wcustomerTotalAmount" id="wcustomerTotalAmount" readonly required>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="form-group pb-4">
                                <label for="websiteTitle" class="control-label">Wcustomer Paid Amount</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="wcustomerPaidAmount" id="wcustomerPaidAmount" readonly required>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="form-group pb-4">
                                <label for="websiteTitle" class="control-label">Wcustomer Due Amount</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="wcustomerDueAmount" id="wcustomerDueAmount" readonly required>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="form-group pb-4">
                                <label for="websiteTitle" class="control-label">Wcustomer Partial Amount</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="wcustomerPartialAmount" id="wcustomerPartialAmount" readonly required>
                                    <span class="text-danger"></span>
                                </div>
                            </div>

                            <input type="text" name="id" id="idhidden" hidden>

                            <div class="form-group" style="text-align: right">
                                <div class="submitBtnSCourse">
                                    <button type="submit" name="btn" class="btn btn-primary btn-block">Update</button>
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

            var wcustomerinfotbl = $('#wcustomerinfo').DataTable({
                order: [ [0, 'desc'] ],
                processing: true,
                serverSide: true,
                ajax: '{!! route('wcustomer.info') !!}',
                columns: [
                    { data: 'id' },
                    { data: 'wcustomerName' },
                    { data: 'wcustomerPhone' },
                    { data: 'wcustomerTotalAmount' },
                    { data: 'wcustomerPaidAmount' },
                    { data: 'wcustomerDueAmount' },
                    { data: 'wcustomerPartialAmount' },
                    {
                        "data": null,
                        render: function (data) {

                            if (data.status === 'Active') {
                                return '<button type="button" class="btn btn-success btn-sm btn-status" data-status="Inactive" id="statusBtnWcustomer" data-id="'+data.id+'">Active</button>';
                            } else {
                                return '<button type="button" class="btn btn-warning btn-sm btn-status" data-status="Active" id="statusBtnWcustomer" data-id="'+data.id+'" >Inactive</button>';
                            }

                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false},

                ]
            });

            $('#AddWcustomer').submit(function(e){
                e.preventDefault();

                $.ajax({
                    type:'POST',
                    uploadUrl:'{{route("wcustomers.store")}}',
                    processData: false,
                    contentType: false,
                    data:new FormData(this),

                    success: function (data) {

                        $('#AddWcustomer').trigger('reset');

                        swal({
                            title: "Success!",
                            icon: "success",
                            showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                        });

                        wcustomerinfotbl.ajax.reload();

                    },
                    error: function(error){
                        console.log('error');
                    }
                });
            });

            //edit store
            $(document).on('click', '#editWcustomerBtn', function(){
                let wcustomerId = $(this).data('id');

                $.ajax({
                    type:'GET',
                    url:'wcustomers/'+wcustomerId+'/edit',

                    success: function (data) {
                        $('#EditWcustomer').find('#editwcustomerName').val(data.wcustomerName);
                        $('#EditWcustomer').find('#editwcustomerPhone').val(data.wcustomerPhone);
                        $('#EditWcustomer').find('#wcustomerEmail').val(data.wcustomerEmail);
                        $('#EditWcustomer').find('#wcustomerAddress').val(data.wcustomerAddress);
                        $('#EditWcustomer').find('#wcustomerCompanyName').val(data.wcustomerCompanyName);
                        $('#EditWcustomer').find('#wcustomerTotalAmount').val(data.wcustomerTotalAmount);
                        $('#EditWcustomer').find('#wcustomerPaidAmount').val(data.wcustomerPaidAmount);
                        $('#EditWcustomer').find('#wcustomerDueAmount').val(data.wcustomerDueAmount);
                        $('#EditWcustomer').find('#wcustomerPartialAmount').val(data.wcustomerPartialAmount);
                        $('#EditWcustomer').find('#idhidden').val(data.id);
                        $('#EditWcustomer').attr('data-id', data.id);
                    },
                    error: function(error){
                        console.log('error');
                    }

                });
            });

            //update store
            $('#EditWcustomer').submit(function(e){
                e.preventDefault();
                let wcustomerId = $('#idhidden').val();

                $.ajax({
                    type:'POST',
                    url:'wcustomer/'+wcustomerId,
                    processData: false,
                    contentType: false,
                    data:new FormData(this),

                    success: function (data) {
                        $('#editwcustomerName').val('');
                        $('#editwcustomerPhone').val('');

                        swal({
                            title: "Wcustomer update successfully !",
                            icon: "success",
                            showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                        });
                        wcustomerinfotbl.ajax.reload();
                    },
                    error: function(error){
                        console.log('error');
                    }
                });
            });

            $(document).on('click', '#deleteWcustomerBtn', function(){
                let wcustomerId = $(this).data('id');
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
                            url:'wcustomers/'+wcustomerId,

                            success: function (data) {
                                swal("Poof! Your wcustomer has been deleted!", {
                                    icon: "success",
                                });
                                wcustomerinfotbl.ajax.reload();
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

            //status update store
            $(document).on('click', '#statusBtnWcustomer', function(){
                let wcustomerId = $(this).data('id');
                let wcustomerStatus = $(this).data('status');

                $.ajax({
                    type:'PUT',
                    url:'wcustomer/status',
                    data:{
                        wcustomer_id:wcustomerId,
                        status:wcustomerStatus,
                    },

                    success: function (data){
                        swal({
                            title: "Status updated !",
                            icon: "success",
                            showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                        });
                        wcustomerinfotbl.ajax.reload();
                    },
                    error: function(error){
                        console.log('error');
                    }
                });
            });

        });
    </script>

@endsection
