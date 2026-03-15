@extends('backend.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}- Blogs
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
            <div class="h-100 bg-secondary rounded p-4 pb-0">
                <div class="d-flex align-items-center justify-content-between" style="width: 50%;float:left;">
                    <h6 class="mb-0">Blog List</h6>
                </div>
                <div class="" style="width: 50%;float:left;">
                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary m-2" style="float: right"> + Add
                        New Blogs</a>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="bg-secondary rounded h-100 p-4">
                <div class="data-tables">
                    <table class="table table-dark" id="bloginfo" width="100%" style="text-align: center;">
                        <thead class="thead-light">
                            <tr>
                                <th>SL</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Short Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
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

        var bloginfo = $('#bloginfo').DataTable({
            order: [
                [0, 'desc']
            ],
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.blog.data') !!}',
            columns: [{
                    data: 'id'
                }, {
                    data: 'image',
                    name: 'image',
                    render: function(data, type, full, meta) {
                        return "<img src=../" + data + " height=\"40\" alt='No Image'/>";
                    }
                },
                {
                    data: 'title'
                },
                {
                    data: 'short_description'
                },
                {
                    "data": null,
                    render: function(data) {

                        if (data.status == 'Active') {
                            return '<button type="button" class="btn btn-success btn-sm btn-status" data-status="Inactive" id="blogstatusBtn" data-id="' +
                                data.id + '">Active</button>';
                        } else {
                            return '<button type="button" class="btn btn-warning btn-sm btn-status" data-status="Active" id="blogstatusBtn" data-id="' +
                                data.id + '" >Inactive</button>';
                        }


                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },

            ]
        });

        // delete blog

        $(document).on('click', '#deleteBlogBtn', function() {
            let blogId = $(this).data('id');
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
                            url: 'blogs/' + blogId,
                            data: {
                                '_token': token
                            },
                            success: function(data) {
                                swal("Blog has been deleted!", {
                                    icon: "success",
                                });
                                bloginfo.ajax.reload();
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

        $(document).on('click', '#blogstatusBtn', function() {
            let blogId = $(this).data('id');
            let blogStatus = $(this).data('status');

            $.ajax({
                type: 'PUT',
                url: 'blog/status',
                data: {
                    blog_id: blogId,
                    status: blogStatus,
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
                    bloginfo.ajax.reload();
                },
                error: function(error) {
                    console.log('error');
                }

            });
        });

    });
</script>

@endsection
