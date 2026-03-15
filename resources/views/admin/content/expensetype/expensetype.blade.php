@extends('backend.master')

@section('maincontent')
    <div class="container-fluid pt-4 px-4">

        <div class="pagetitle row">
            <div class="col-6">
                <h1><a href="{{url('/admindashboard')}}">Dashboard</a></h1>
                <nav>
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/admindashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Expensetypes</li>
                    </ol>
                </nav>
            </div>
            <div class="col-6" style="text-align: right">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mainExpensetype"><span style="font-weight: bold;">+</span>  Add New Expensetype</button>
            </div>
        </div><!-- End Page Title -->

        {{-- //popup modal for create user --}}
        <div class="modal fade" id="mainExpensetype" tabindex="-1" data-bs-backdrop="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Expensetype</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form name="form" id="AddExpensetype" enctype="multipart/form-data">
                            @csrf
                            <div class="successSMS"></div>

                            <div class="form-group pb-3">
                                <label for="websiteTitle" class="control-label">Expensetype Name</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="expenseTypeName" id="expenseTypeName" required>
                                    <span class="text-danger">{{ $errors->has('expenseTypeName')? $errors->first('expenseTypeName'):'' }}</span>
                                </div>
                            </div>

                            <div class="form-group" style="text-align: right">
                                <div class="submitBtnSCourse">
                                    <button type="submit" name="btn" class="btn btn-primary AddExpensetypeBtn btn-block">Save</button>
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
                        <table class="table table-centered table-borderless table-hover mb-0" id="paymenttpinfo" width="100%">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Expense Type Name</th>
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
        <div class="modal fade" id="editmainExpensetype" tabindex="-1" data-bs-backdrop="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Expensetype</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form name="form" id="EditExpensetype" enctype="multipart/form-data">
                            @csrf
                            <div class="successSMS"></div>

                            <div class="form-group pb-3">
                                <label for="websiteTitle" class="control-label">Expensetype Name</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="expenseTypeName" id="editexpenseTypeName" required>
                                    <span class="text-danger">{{ $errors->has('expenseTypeName')? $errors->first('expenseTypeName'):'' }}</span>
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
        </div><!-- End popup Modal-->

    </div>

<input type="hidden" name="_token" value="{{ csrf_token() }}" />

    <script>
        $(document).ready(function() {

        var token = $("input[name='_token']").val();
           var expensetypeinfotbl = $('#paymenttpinfo').DataTable({
                order: [ [0, 'desc'] ],
                processing: true,
                serverSide: true,
                ajax: '{!! route('expensetype.info') !!}',
                columns: [
                    { data: 'id' },
                    { data: 'expenseTypeName' },
                    {
                        "data": null,
                        render: function (data) {

                            if (data.status === 'Active') {
                                return '<button type="button" class="btn btn-success btn-sm btn-status" data-status="Inactive" id="statusBtnExpensetype" data-id="'+data.id+'">Active</button>';
                            } else {
                                return '<button type="button" class="btn btn-warning btn-sm btn-status" data-status="Active" id="statusBtnExpensetype" data-id="'+data.id+'" >Inactive</button>';
                            }


                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false},

                ]
            });


            //add store

            $('#AddExpensetype').submit(function(e){
                e.preventDefault();

                $.ajax({
                    type:'POST',
                    uploadUrl:'{{route("expensetypes.store")}}',
                    processData: false,
                    contentType: false,
                    data:new FormData(this),

                    success: function (data) {
                        $('#expenseTypeName').val('');
                        swal({
                            title: "Success!",
                            icon: "success",
                            showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                        });
                        expensetypeinfotbl.ajax.reload();
                    },
                    error: function(error){
                        console.log('error');
                    }
                });
            });

            //edit store

            $(document).on('click', '#editExpensetypeBtn', function(){
                let expensetypeId = $(this).data('id');

                $.ajax({
                    type:'GET',
                    url:'expensetypes/'+expensetypeId+'/edit',

                    success: function (data) {
                        $('#EditExpensetype').find('#editexpenseTypeName').val(data.expenseTypeName);
                        $('#EditExpensetype').find('#idhidden').val(data.id);
                        $('#EditExpensetype').attr('data-id', data.id);
                    },
                    error: function(error){
                        console.log('error');
                    }

                });
            });

            //update store
            $('#EditExpensetype').submit(function(e){
                e.preventDefault();
                let expensetypeId = $('#idhidden').val();

                $.ajax({
                    type:'POST',
                    url:'expensetype/'+expensetypeId,
                    processData: false,
                    contentType: false,
                    data:new FormData(this),

                    success: function (data) {
                        $('#editexpenseTypeName').val('');

                        swal({
                            title: "Expensetype update successfully !",
                            icon: "success",
                            showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                        });
                        expensetypeinfotbl.ajax.reload();
                    },
                    error: function(error){
                        console.log('error');
                    }
                });
            });



            //status update store

             $(document).on('click', '#statusBtnExpensetype', function(){
                let expensetypeId = $(this).data('id');
                let expensetypeStatus = $(this).data('status');

                $.ajax({
                    type:'PUT',
                    url:'expensetype/status',
                    data:{
                        expensetype_id:expensetypeId,
                        status:expensetypeStatus,
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
                        expensetypeinfotbl.ajax.reload();
                    },
                    error: function(error){
                        console.log('error');
                    }

                });
            });











        });



    </script>



@endsection
