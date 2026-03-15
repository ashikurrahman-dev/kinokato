@extends('backend.master')

@section('maincontent')
    <div class="container-fluid pt-4 px-4">

        <div class="pagetitle row">
            <div class="col-6">
                <h1><a href="{{url('/admindashboard')}}">Dashboard</a></h1>
                <nav>
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/admindashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">Areas</li>
                    </ol>
                </nav>
            </div>
            <div class="col-6" style="text-align: right">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mainArea"><span style="font-weight: bold;">+</span>  Add New Area</button>
            </div>
        </div><!-- End Page Title -->

        {{-- //popup modal for create user --}}
        <div class="modal fade" id="mainArea" tabindex="-1" data-bs-backdrop="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Area</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form name="form" id="AddArea" enctype="multipart/form-data">
                            @csrf
                            <div class="successSMS"></div>

                            <div class="form-group mb-3">
                                <label for="menuName" class="control-label mt-2">Courier Name</label>
                                <div class="">
                                    <select class="form-control" name="courier_id" id="courier_id" onchange="zoneupdatenow()" required >
                                        <option value="">Select Courier</option>
                                        @forelse ($couriers as $courier)
                                            <option value="{{$courier->id}}">{{$courier->courierName}}</option>
                                        @empty

                                        @endforelse
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="menuName" class="control-label mt-2">Zone name</label>
                                <div class="">
                                    <select class="form-control" name="zone_id" id="zone_id" required >

                                    </select>
                                </div>
                            </div>
                            <div class="form-group pb-3">
                                <label for="websiteTitle" class="control-label">Area name</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="areaName" id="areaName" required>
                                    <span class="text-danger">{{ $errors->has('areaName')? $errors->first('areaName'):'' }}</span>
                                </div>
                            </div>

                            <div class="form-group pb-3">
                                <label for="websiteTitle" class="control-label">Area ID</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="areaId" id="areaId" >
                                    <span class="text-danger">{{ $errors->has('areaId')? $errors->first('areaId'):'' }}</span>
                                </div>
                            </div>

                            <div class="form-group" style="text-align: right">
                                <div class="submitBtnSCourse">
                                    <button type="submit" name="btn" class="btn btn-primary AddAreaBtn btn-block">Save</button>
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
                        <table class="table table-centered table-borderless table-hover mb-0" id="areainfo" width="100%">
                            <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Courier Name</th>
                                <th>Zone Name</th>
                                <th>Area Name</th>
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
        <div class="modal fade" id="editmainArea" tabindex="-1" data-bs-backdrop="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Area</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form name="form" id="EditArea" enctype="multipart/form-data">
                            @csrf
                            <div class="successSMS"></div>

                            <div class="form-group mb-3">
                                <label for="menuName" class="control-label mt-2">Courier Name</label>
                                <div class="">
                                    <select class="form-control" name="courier_id" id="editcourier_id" onchange="editzoneupdatenow()" required >
                                        <option value="">Select Courier</option>
                                        @forelse ($couriers as $courier)
                                            <option value="{{$courier->id}}">{{$courier->courierName}}</option>
                                        @empty

                                        @endforelse
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="menuName" class="control-label mt-2">Zone name</label>
                                <div class="">
                                    <select class="form-control" name="zone_id" id="editzone_id" required >
                                        <option value="">Select Zone</option>
                                        @forelse ($zones as $zone)
                                            <option value="{{$zone->id}}">{{$zone->zoneName}}</option>
                                        @empty

                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="form-group pb-3">
                                <label for="websiteTitle" class="control-label">Area name</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="areaName" id="editareaName" required>
                                    <span class="text-danger">{{ $errors->has('areaName')? $errors->first('areaName'):'' }}</span>
                                </div>
                            </div>

                            <div class="form-group pb-3">
                                <label for="websiteTitle" class="control-label">Area ID</label>
                                <div class="webtitle">
                                    <input type="text" class="form-control" name="areaId" id="areaId" >
                                    <span class="text-danger">{{ $errors->has('areaId')? $errors->first('areaId'):'' }}</span>
                                </div>
                            </div>

                            <input type="text" name="id" id="idhidden" hidden>

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



    <script>

        function zoneupdatenow() {
            var select=document.getElementById('courier_id').value;
            $('#zone_id').html('');

            $.ajax({
                type:'GET',
                url:'set-value/zone/'+select,

                success: function (data) {

                    for (var i = 0; i < data.length; i++){
                        const option = document.createElement("option");
                        const node = document.createTextNode(data[i].zoneName);
                        option.setAttribute("value", data[i].id);
                        option.appendChild(node);
                        const element = document.getElementById("zone_id");
                        element.appendChild(option);
                    }


                },
                error: function(error){
                    console.log('error');
                }

            });
        };

        function editzoneupdatenow() {
            var select=document.getElementById('editcourier_id').value;
            $('#editzone_id').html('');

            $.ajax({
                type:'GET',
                url:'set-value/zone/'+select,

                success: function (data) {

                    for (var i = 0; i < data.length; i++){
                        const option = document.createElement("option");
                        const node = document.createTextNode(data[i].zoneName);
                        option.setAttribute("value", data[i].id);
                        option.appendChild(node);
                        const element = document.getElementById("editzone_id");
                        element.appendChild(option);
                    }


                },
                error: function(error){
                    console.log('error');
                }

            });
        };

        $(document).ready(function() {

           var areainfotbl = $('#areainfo').DataTable({
                order: [ [0, 'desc'] ],
                processing: true,
                serverSide: true,
                ajax: '{{ route('area.info') }}',
                columns: [
                    { data: 'id' },
                    { data: 'couriers.courierName' },
                    { data: 'zones.zoneName' },
                    { data: 'areaName' },
                    {
                        "data": null,
                        render: function (data) {

                            if (data.status === 'Active') {
                                return '<button type="button" class="btn btn-success btn-sm btn-statusarea" data-status="Inactive" id="areastatusBtn" data-id="'+data.id+'">Active</button>';
                            } else {
                                return '<button type="button" class="btn btn-warning btn-sm btn-statusarea" data-status="Active" id="areastatusBtn" data-id="'+data.id+'" >Inactive</button>';
                            }


                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false},

                ]
            });


            //add area

            $('#AddArea').submit(function(e){
                e.preventDefault();

                $.ajax({
                    type:'POST',
                    uploadUrl:'{{route("areas.store")}}',
                    processData: false,
                    contentType: false,
                    data:new FormData(this),

                    success: function (data) {
                        $('#areaName').val('');
                        $('#courier_id').val('');
                        $('#zone_id').val('');

                        swal({
                            title: "Success!",
                            icon: "success",
                            showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                        });
                        areainfotbl.ajax.reload();
                    },
                    error: function(error){
                        console.log('error');
                    }
                });
            });

            //edit area

            $(document).on('click', '#editAreaBtn', function(){
                let areaId = $(this).data('id');

                $('#editcourier_id').val('');
                $('#editzone_id').val('');
                $('#editareaName').val('');
                $('#EditArea').attr('data-id', '');

                $.ajax({
                    type:'GET',
                    url:'areas/'+areaId+'/edit',

                    success: function (data) {
                        $('#EditArea').find('#areaId').val(data.areaId);
                        $('#EditArea').find('#editcourier_id').val(data.courier_id);
                        $('#EditArea').find('#editzone_id').val(data.zone_id);
                        $('#EditArea').find('#editareaName').val(data.areaName);

                        $('#EditArea').find('#idhidden').val(data.id);

                        $('#EditArea').attr('data-id', data.id);
                    },
                    error: function(error){
                        console.log('error');
                    }

                });
            });

            //update area
            $('#EditArea').submit(function(e){
                e.preventDefault();
                let areaId = $('#idhidden').val();

                $.ajax({
                    type:'POST',
                    url:'area/'+areaId,
                    processData: false,
                    contentType: false,
                    data:new FormData(this),

                    success: function (data) {
                        $('#editcourier_id').val('');
                        $('#editzone_id').val('');
                        $('#editareaName').val('');

                        $("#EditArea").trigger("reset");

                        swal({
                            title: "Area update successfully !",
                            icon: "success",
                            showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes",
                            cancelButtonText: "No",
                        });
                        areainfotbl.ajax.reload();
                    },
                    error: function(error){
                        console.log('error');
                    }
                });
            });

            //delete area

            $(document).on('click', '#deleteAreaBtn', function(){
                let areaId = $(this).data('id');
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
                            url:'areas/'+areaId,

                            success: function (data) {
                                swal("Poof! Your courier has been deleted!", {
                                    icon: "success",
                                });
                                areainfotbl.ajax.reload();
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

            //status update area

             $(document).on('click', '#areastatusBtn', function(){
                let areaId = $(this).data('id');
                let areaStatus = $(this).data('status');

                $.ajax({
                    type:'PUT',
                    url:'area/status',
                    data:{
                        area_id:areaId,
                        status:areaStatus,
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
                        areainfotbl.ajax.reload();
                    },
                    error: function(error){
                        console.log('error');
                    }

                });
            });











        });



    </script>


@endsection
