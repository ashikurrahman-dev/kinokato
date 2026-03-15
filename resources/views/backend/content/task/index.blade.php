@extends('backend.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}- My Task
@endsection
<style>
.card-box {
        background-color: #ffffff !important;
        padding: 1.5rem;
        -webkit-box-shadow: 0 1px 4px 0 rgb(0 0 0 / 10%);
        box-shadow: 0 1px 4px 0 rgb(0 0 0 / 10%);
        margin-bottom: 24px;
        border-radius: 0.25rem;
        }
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

@php
$date = date('Y-m-d');
$dateold = date("Y-m-d", strtotime($date ." -15 day") );

@endphp

<div class="container-fluid pt-4 px-4">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="h-100 bg-secondary rounded p-4 pb-0">
                <div class="row">
                    <div class="col-md-6 col-xl-2">
                   
                    <div class="widget-rounded-circle card-box order pt-1 pb-1">
                        <div class="row">
                            <div class="col-12">
                                <div class="float-left">
                                    <h3 class="text-dark mt-1 mb-0">
                                        <span id="pending" data-plugin="counterup">{{App\Models\Task::where('type','task')->where('status','Pending')->get()->count()}}</span>
                                    </h3>
                                    <p class="text-muted mb-1 text-truncate">Pending</p>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                    
                </div> <!-- end col-->
                <div class="col-md-6 col-xl-2">
                     
                    <div class="widget-rounded-circle card-box order pt-1 pb-1">
                        <div class="row">
                            <div class="col-12">
                                <div class="float-left">
                                    <h3 class="text-dark mt-1 mb-0">
                                        <span id="pending" data-plugin="counterup">{{App\Models\Task::where('type','task')->where('status','Done')->get()->count()}}</span>
                                    </h3>
                                    <p class="text-muted mb-1 text-truncate">Solved</p>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                 
                </div> <!-- end col-->
                </div>
                <div class="d-flex align-items-center justify-content-center mb-4">
                    <h2 class="mb-0">My Daily Task List</h2>
                </div>
                <div class="" style="width: 100%;float:right;">
                    <a type="button" data-bs-toggle="modal" data-bs-target="#mainTask" class="btn btn-primary m-2"
                        style="float: right"> + Create Task</a>
                </div>
                
                <div class="" style="width: 100%;float:left;">
                    <div class="row">
                        
                        <div class="form-group col-md-3">
                            <label for="inputCity" class="col-form-label">Start Date</label>
                            <input type="text" class="form-control datepicker" id="startDate"
                                value="{{$dateold}}" placeholder="Select Date">
                        </div>
                        <input type="hidden" name="type" value="task">
                        <div class="form-group col-md-3">
                            <label for="inputCity" class="col-form-label">End Date</label>
                            <input type="text" class="form-control datepicker" id="endDate"
                                value="<?php echo date('Y-m-d'); ?>" placeholder="Select Date">
                        </div>
                        <div class="form-group col-md-2 mt-3" style="margin-top: 14px !important;">
                            <label for="floatingInput">Choose Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="Pending">Pending</option>
                                <option value="All">All</option> 
                                <option value="Done">Done</option>
                            </select>
                        </div>
                        @if(Auth::user()->id==1)
                        <div class="form-group col-md-2 mt-3" style="margin-top: 14px !important;">
                            <label for="floatingInput">Choose User</label>
                            <select class="form-control" id="user" name="user">
                                <option value="All">All</option> 
                                @forelse(App\Models\Admin::all() as $adm)
                                    <option value="{{$adm->id}}">{{$adm->name}}</option>
                                @empty
                                
                                @endforelse
                            </select>
                        </div>
                        @endif
                        <div class="form-group col-md-2 pt-1" style="margin-top: 35px;">
                            <button class="btn btn-info btn-print-expreport"><i class="fas fa-print"></i>
                                Print</button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="bg-secondary rounded h-100 p-4">
                <div class="data-tables">
                    <table class="table table-dark" id="taskinfo" width="100%" style="text-align: center;">
                        <thead class="thead-light">
                            <tr>
                                <th>SL</th>
                                <th>Date</th> 
                                <th>Task Name</th> 
                                <th>Note</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
         

        {{-- create payment icon --}}
        <div class="modal fade" id="mainTask" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content rounded h-100">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: red;">Create New Task</h5>
                        <button type="button" class="btn-dark btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form name="form" id="AddTask" enctype="multipart/form-data">
                            @csrf
                            @if(Auth::user()->id==1)
                            <div class="form-group mb-3">
                                <label for="floatingInput">Choose User For Assign Task</label>
                                <select class="form-control" id="admin_id" name="admin_id">
                                    <option value="">Choose</option>
                                    @forelse(App\Models\Admin::all() as $adm)
                                        <option value="{{$adm->id}}">{{$adm->name}}</option>
                                    @empty
                                    
                                    @endforelse
                                </select>
                            </div>
                            @endif
                            <input type="hidden" name="type" value="task">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control datepicker" value="{{date('Y-m-d')}}" name="date" min="{{date('Y-m-d')}}" id="date"
                                    placeholder="Date">
                                <label for="floatingInput">Date</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="task_name" id="task_name"
                                    placeholder="Title">
                                <label for="floatingInput">Task Name</label>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="floatingInput">Notes</label>
                                <textarea class="form-control" name="message" id="message" rows="4"></textarea>
                            </div>
                            <br>
                            <div class="form-group mt-2" style="text-align: right">
                                <div class="submitBtnSCourse">
                                    <button type="submit" name="btn" data-bs-dismiss="modal" class="btn btn-dark "
                                        style="float: left">Close</button>
                                    <button type="submit" name="btn" id="submbyn"
                                        class="btn btn-primary AddTaskBtn">Save</button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div><!-- End popup Modal-->

        {{-- edit payment icon --}}
        <div class="modal fade" id="editmainTask" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content rounded h-100">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: red;">Edit Task</h5>
                        <button type="button" class="btn-dark btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form name="form" id="EditTask" enctype="multipart/form-data">
                            @csrf
                            @if(Auth::user()->id==1)
                            <div class="form-group mb-3">
                                <label for="floatingInput">Choose User For Assign Task</label>
                                <select class="form-control" id="admin_id" name="admin_id">
                                    <option value="">Choose</option>
                                    @forelse(App\Models\Admin::all() as $adm)
                                        <option value="{{$adm->id}}">{{$adm->name}}</option>
                                    @empty
                                    
                                    @endforelse
                                </select>
                            </div>
                            @endif
                            <input type="hidden" name="type" value="task">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control datepicker" value="{{date('Y-m-d')}}" name="date" min="{{date('Y-m-d')}}" id="date"
                                    placeholder="Date">
                                <label for="floatingInput">Date</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="task_name" id="task_name"
                                    placeholder="Title">
                                <label for="floatingInput">Task Name</label>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="floatingInput">Notes</label>
                                <textarea class="form-control" name="message" id="message" rows="4"></textarea>
                            </div>
                            <input type="text" name="task_id" id="task_id" hidden>

                            <br>
                            <div class="form-group mt-2" style="text-align: right">
                                <div class="submitBtnSCourse">
                                    <button type="submit" name="btn" data-bs-dismiss="modal"
                                        class="btn btn-dark" style="float: left">Close</button>
                                    <button type="submit" name="btn"
                                        class="btn btn-primary AddTaskBtn">Update</button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div><!-- End popup Modal-->
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".datepicker").flatpickr();
        var token = $("input[name='_token']").val();

        var taskinfo = $('#taskinfo').DataTable({
            order: [
                [0, 'desc']
            ],
            processing: true,
            serverSide: true,
             ajax: {
                url:'{!! route('task.data') !!}',
                data: {
                    startDate: function() {
                        return $('#startDate').val()
                    },
                    endDate: function() {
                        return $('#endDate').val()
                    },
                    status: function() {
                        return $('#status').val()
                    },
                    assign_for: function() {
                        return $('#user').val()
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
                    data: 'task_name'
                },
                {
                    data: 'message'
                },
                {
                    "data": null,
                    render: function(data) {

                        if (data.status === 'Done') {
                            return '<button type="button" class="btn btn-success btn-sm btn-status" data-status="Pending" id="taskstatusBtn" data-id="' +
                                data.id + '">Done</button>';
                        } else {
                            return '<button type="button" class="btn btn-warning btn-sm btn-status" data-status="Done" id="taskstatusBtn" data-id="' +
                                data.id + '" >Pending</button>';
                        }


                    }
                },
                {
                    data: 'create_by'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }

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
                        var printTitle = 'Daily Task Report Of : '+'<?php echo Auth::guard()->user()->name ?>';
                        return printTitle;
                    },
                    exportOptions : {
                        stripHtml : false,
                        columns: [ 0, 1, 2, 3, 4,5,6]
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
            }
        });


        //add task

        $('#AddTask').submit(function(e) {
            e.preventDefault(); 
            $.ajax({
                type: 'POST',
                uploadUrl: '{{ route('tasks.store') }}',
                processData: false,
                contentType: false,
                data: new FormData(this),

                success: function(data) {
                    $('#task_name').val('');
                    $('#message').val('');

                    swal({
                        title: "Success!",
                        icon: "success",
                    });
                    taskinfo.ajax.reload();
                },
                error: function(error) {
                    console.log('error');
                }
            });
        });

        //edit task
        $(document).on('click', '#editTaskBtn', function() {
            let taskId = $(this).data('id');

            $.ajax({
                type: 'GET',
                url: 'tasks/' + taskId + '/edit',

                success: function(data) {
                    $('#EditTask').find('#task_name').val(data.task_name);
                    $('#EditTask').find('#task_id').val(data.id);
                    $('#EditTask').find('#message').val(data.message);
                    $('#EditTask').find('#date').val(data.date);
                    $('#EditTask').find('#admin_id').val(data.admin_id);
                    $('#EditTask').find('#date').val(data.date);
                    $('#EditTask').attr('data-id', data.id);
                },
                error: function(error) {
                    console.log('error');
                }

            });
        });

        //update task
        $('#EditTask').submit(function(e) {
            e.preventDefault();
            let taskId = $('#task_id').val();

            $.ajax({
                type: 'POST',
                url: 'task/' + taskId,
                processData: false,
                contentType: false,
                data: new FormData(this),

                success: function(data) {
                    $('#EditTask').find('#task_name').val('');
                    $('#EditTask').find('#message').val('');
                    $('#EditTask').find('#task_id').val('');

                    swal({
                        title: "Task update successfully !",
                        icon: "success",
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes",
                        cancelButtonText: "No",
                    });
                    taskinfo.ajax.reload();

                },
                error: function(error) {
                    console.log('error');
                }
            });
        });

        // delete task

        $(document).on('click', '#deleteTaskBtn', function() {
            let taskId = $(this).data('id');
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
                            url: 'tasks/' + taskId,
                            data: {
                                '_token': token
                            },
                            success: function(data) {
                                swal("Task has been deleted!", {
                                    icon: "success",
                                });
                                taskinfo.ajax.reload();
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

        $(document).on('click', '#taskstatusBtn', function() {
            let taskId = $(this).data('id');
            let taskStatus = $(this).data('status');

            $.ajax({
                type: 'PUT',
                url: 'task/status',
                data: {
                    task_id: taskId,
                    status: taskStatus,
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
                    taskinfo.ajax.reload();
                },
                error: function(error) {
                    console.log('error');
                }

            });
        });

        // front status update

        $(document).on('click', '#taskfrontstatusBtn', function() {
            let taskId = $(this).data('id');
            let taskFrontStatus = $(this).data('status');

            $.ajax({
                type: 'PUT',
                url: 'task/status',
                data: {
                    task_id: taskId,
                    front_status: taskFrontStatus,
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
                    taskinfo.ajax.reload();
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
            taskinfo.ajax.reload();
        });
        $(document).on('change', '#endDate', function() {
            taskinfo.ajax.reload();
        });
        $(document).on('change', '#status', function() {
            taskinfo.ajax.reload();
        });
        $(document).on('change', '#user', function() {
            taskinfo.ajax.reload();
        });

    });
</script>

@endsection
