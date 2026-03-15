@php
$admin=App\Models\Admin::where('id',Auth::guard('admin')->user()->id)->first();
@endphp
<nav class="px-4 py-0 navbar navbar-expand bg-secondary navbar-dark sticky-top">
    <a href="{{ url('/admin/dashboard') }}" class="navbar-brand d-flex d-lg-none me-4">
        <h2 class="mb-0 text-primary">RFBD</h2>
    </a>
    <a href="#" class="flex-shrink-0 sidebar-toggler">
        <i class="fa fa-bars"></i>
    </a>
    <div class="d-md-flex ms-4 d-none d-lg-block">
        <a target="_blank" href="{{url('/')}}" class="btn btn-info">View Website</a>
        <a target="_blank" href="{{url('admin/user/report')}}" class="btn btn-warning ms-3">User Order</a>
        <a target="_blank" href="{{url('/admin/stock/overview')}}" class="btn btn-success ms-3">Inventory</a>
        <a target="_blank" href="{{url('/complain/Pending')}}" class="btn btn-danger ms-3">Complain</a>
        <a target="_blank" href="{{url('admin/tasks')}}" class="btn btn-warning ms-3">My Task</a>
        <button type="button" class="btn btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
          Send Message
        </button>
        <a style="font-size: 26px;float: left;margin-right: 25px;text-transform: uppercase;font-weight: bold;color: #0c0c70;">{{ env('APP_NAME') }}</a>
    </div>
    <div class="navbar-nav align-items-center ms-auto">

        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                @if(isset($admin->profile))
                <img class="rounded-circle" src="{{ asset(Auth::guard('admin')->user()->profile) }}" alt=""
                    style="width: 40px; height: 40px;">
                @else
                <img class="rounded-circle" src="{{ asset('public/user.jpg') }}" alt=""
                    style="width: 40px; height: 40px;">
                @endif
                <span class="d-none d-lg-inline-flex">{{ Auth::user()->name }}</span>
            </a>
            <div class="m-0 border-0 dropdown-menu dropdown-menu-end bg-secondary rounded-0 rounded-bottom">
                <a href="{{url('admin/my/profile')}}" class="dropdown-item">My Profile</a>
                <a href="{{url('admin/account/settings')}}" class="dropdown-item">Settings</a>
                <a href="{{ route('admin.logout') }}" class="dropdown-item">Log Out</a>
            </div>
        </div>
    </div>
</nav>
<div class="mt-3 d-md-flex d-block d-lg-none" style="    text-align: center;">
    <a target="_blank" href="{{url('/')}}" class="mb-2 btn btn-info">View Website</a>
    <a target="_blank" href="{{url('admin/user/report')}}" class="mb-2 btn btn-warning ms-3">User Order</a>
    <a target="_blank" href="{{url('/admin/stock/overview')}}" class="mb-2 btn btn-success ms-3">Inventory</a>
    <a target="_blank" href="{{url('/complain/Pending')}}" class="mb-2 btn btn-danger ms-3">Complain</a>
    <a target="_blank" href="{{url('admin/tasks')}}" class="mb-2 btn btn-warning ms-3">My Task</a>
    <button type="button" class="mb-2 btn btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
      Send Message
    </button>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Write Here</h5>
        <button type="button" class="close" style="border:none" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="" method="POST" id="sendMessage">
            @csrf
            <div class="form-group">
                <lable>Phone</lable>
                <input type="text" name="phone" id="smsphone" class="form-control">
            </div>
            <div class="mb-3 form-group">
                <lable>Messages</lable>
                <textarea class="form-control" name="textmessage" id="textmessage" rows="3"></textarea>
            </div>
            <button type="button" id="sendMessagebtn" class="btn btn-primary" style="float:right">Send</button>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
    $(document).on('click', '#sendMessagebtn', function(e) {
        e.preventDefault();

        swal({
                title: "আপনি কি মেসেজ টি পাঠাতে চাচ্ছেন ?",
                text: "যদি Ok ক্লিক করেন তাহলে মেসেজটি চলে যাবে | সেটা ক্যানসেল করা যাবে না !",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "GET",
                        url: "{{ url('admin/sendsms') }}",
                        data: {
                            phone: $('#smsphone').val(),
                            message: $('#textmessage').val(),
                        },
                        success: function(response) {
                            var data = JSON.parse(response);
                            if (data["status"] == "success") {
                                $('#smsphone').val(''),
                                $('#textmessage').val(''),

                                swal(data["message"]);
                            } else {
                                if (data["status"] == "failed") {
                                    swal(data["message"]);
                                } else {
                                    swal("Something wrong ! Please try again.");
                                }
                            }
                        }
                    });


                } else {
                    swal("Your data is safe!");
                }
            });

    });
</script>
