@extends('backend.master')

@section('maincontent')
    <div class="px-4 pt-4 container-fluid">

        <div class="pagetitle row">
            <div class="col-6">
                <h1><a href="{{ url('/admindashboard') }}">Dashboard</a></h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/admindashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">reviews</li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->


        {{-- //table section for category --}}

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="pt-4 card-body">
                            @if (\Session::has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle me-1"></i>
                                    {{ \Session::get('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <!-- Table with stripped rows -->
                            <div class="table-responsive">
                                <table class="table mb-0 table-centered table-borderless table-hover" id="reviewinfo"
                                    width="100%">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Review</th>
                                            <th>Rating</th>
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


    </div>

<input type="hidden" name="_token" value="{{ csrf_token() }}" />

    <script>
        $(document).ready(function() {
            var token = $("input[name='_token']").val();
            var reviewinfotbl = $('#reviewinfo').DataTable({
                order: [
                    [0, 'desc']
                ],
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.review.data') !!}',
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'user'
                    },
                    {
                        data: 'messages'
                    },
                    {
                        data: 'rating'
                    },
                    {
                        "data": null,
                        render: function(data) {

                            if (data.status === 'Active') {
                                return '<button type="button" class="btn btn-success btn-sm btn-status" data-status="Inactive" id="reviewstatusBtn" data-id="' +
                                    data.id + '">Active</button>';
                            } else {
                                return '<button type="button" class="btn btn-warning btn-sm btn-status" data-status="Active" id="reviewstatusBtn" data-id="' +
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


            $(document).on('click', '#deleteReviewBtn', function() {
                let reviewId = $(this).data('id');
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
                                url: 'reviews/' + reviewId,

                                success: function(data) {
                                    swal("Poof! Your review has been deleted!", {
                                        icon: "success",
                                    });
                                    reviewinfotbl.ajax.reload();
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

            //status update

            $(document).on('click', '#reviewstatusBtn', function() {
                let reviewId = $(this).data('id');
                let reviewStatus = $(this).data('status');

                $.ajax({
                    type: 'PUT',
                    url: 'review/status',
                    data: {
                        review_id: reviewId,
                        status: reviewStatus,
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
                        reviewinfotbl.ajax.reload();
                    },
                    error: function(error) {
                        console.log('error');
                    }

                });
            });




        });
    </script>
@endsection
