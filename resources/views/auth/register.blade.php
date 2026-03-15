@extends('webview.master')

@section('maincontent')
    @section('title')
        {{ env('APP_NAME') }}-Register
    @endsection

    <div class="container">
        <div class="row login-wrapper">

            <div class="col-lg-7 col-12 left-img">
                <img src="{{ asset('public/login-register-page-side-imag.png') }}">
            </div>

            <div class="col-lg-5 col-12 login-area">

                <h2 class="login-title">Create an account</h2>
                <p class="login-sub">Enter your details below</p>

                <form method="POST" action="{{ url('register') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <input type="text" class="form-control" name="name" placeholder="Name" required>
                    </div>
                    <div class="mb-4">
                        <input type="text" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <div class="mb-4">
                        <input type="text" class="form-control" name="phone" placeholder="Phone Number" required>
                    </div>

                    <div class="mb-4">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>


                    <button type="submit" class="p-3 text-white btn login-btn w-100">Create Account</button>

                    <div class="d-flex align-items-center justify-content-center">
                        <span>Already have an account? <a href="{{ url('login') }}"
                                class="text-decoration-underline text-dark">Login</a></span>

                    </div>

                </form>

            </div>

        </div>

    </div><!-- /.row -->
    <style>
        .login-wrapper {
            background: #fff;
            align-items: center;
        }

        .left-img img {
            width: 100%;
        }

        .login-area {
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
        }

        .login-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .login-sub {
            font-size: 14px;
            color: #777;
            margin-bottom: 30px;
        }

        .form-control {
            border: none !important;
            border-bottom: 1px solid #ccc !important;
            border-radius: 0 !important;
            padding-left: 0;
            background: transparent;
            box-shadow: none !important;
            outline: none;
        }

        .form-control:focus {
            border-bottom: 1px solid #000 !important;
            box-shadow: none !important;
            outline: none;
        }

        .login-btn {
            background: #e74c3c;
            border: none;
            padding: 8px 25px;
        }

        .login-btn:hover {
            background: #e74c3c;
            border: none;
            padding: 8px 25px;
        }

        .forgot {
            font-size: 14px;
            color: #000;
            text-decoration: none;
            margin-left: 15px;
            margin-top: -30px;
        }

        @media(max-width:768px) {

            .login-area {
                padding: 40px 25px;
            }

            .left-img {
                padding: 20px;
            }

        }
    </style>

    {{-- <div class="body-content">
        <div class="container">
            <div class="sign-in-page m-b-10">
                <div class="row">
                    <!-- create a new account -->
                    <div class="col-md-3 col-sm-2 create-new-account">

                    </div>
                    <!-- Sign-in -->
                    <div class="mt-4 mb-4 card col-md-6 col-sm-8 sign-in">
                        <h4 class="pt-4 pb-2 m-0 text-center checkout-subtitle"> <b>Create a new account</b> </h4>
                        <form class="register-form outer-top-xs" method="POST" action="{{ url('register') }}" role="form">
                            @csrf
                            <div class="form-group">
                                <label class="info-title" for="exampleInputEmail1">Name <span>*</span></label>
                                <input type="text" name="name" class="form-control unicase-form-control text-input"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="info-title" for="exampleInputEmail2">Email <span>*</span></label>
                                <input type="email" name="email" class="form-control unicase-form-control text-input"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="info-title" for="exampleInputEmail2">Phone <span>*</span></label>
                                <input type="text" name="phone" class="form-control unicase-form-control text-input"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="info-title" for="exampleInputEmail1">Password <span>*</span></label>
                                <input type="password" name="password" class="form-control unicase-form-control text-input"
                                    id="password" required>
                            </div>
                            <div class="mb-4 form-group">
                                <label class="info-title" for="exampleInputEmail1">Confirm Password <span>*</span></label>
                                <input type="password" onkeyup="checkpass()" id="confirm_password"
                                    class="m-0 form-control unicase-form-control text-input" required>
                                <small id="confirm_passwordtextmatch" style="color: deepskyblue;display:none">Password
                                    Matched</small>
                                <small id="confirm_passwordtext" style="color: red;display:none">Password Not
                                    Matched</small>
                            </div>
                            <button type="submit" id="submit-button"
                                style="background:#000339;border:1px #000339;color: white;width:100%"
                                class="btn-block btn-upper btn btn-dark checkout-page-button">Sign
                                Up</button>
                        </form>
                        <h4 class="text-center" style="margin-top: 20px;margin-bottom:20px;">
                            Already have an account? <a href="{{ url('login') }}" style="color:black"> <b>Login Now</b>
                            </a>
                        </h4>
                    </div>
                    <!-- Sign-in -->

                    <!-- create a new account -->
                    <div class="col-md-3 col-sm-2 create-new-account">

                    </div>
                    <!-- create a new account -->
                </div><!-- /.row -->
            </div><!-- /.sigin-in-->
        </div><!-- /.row -->
    </div><!-- /.sigin-in--> --}}


    <script>
        function checkpass() {
            var pass = $('#password').val();
            var con_pass = $('#confirm_password').val();
            if (pass == con_pass) {
                $('#confirm_passwordtext').css('display', 'none');
                $('#confirm_passwordtextmatch').css('display', 'inline');
                $('#submit-button').prop('disabled', false);
            } else {
                $('#confirm_password').focus();
                $('#confirm_passwordtext').css('display', 'inline');
                $('#confirm_passwordtextmatch').css('display', 'none');
                $('#submit-button').prop('disabled', true);
            }
        }

        function checkunick() {
            var username = $('#username').val();
            $.ajax({
                type: "GET",
                url: "{{ url('check/username') }}/" + username,

                success: function (data) {

                    if (data == 'taken') {
                        $('#username').focus();
                        $('#submit-button').prop('disabled', true);
                        $('#avaliableusername').css('display', 'none');
                        $('#unavaliableusername').css('display', 'inline');
                    } else {
                        $('#avaliableusername').css('display', 'inline');
                        $('#unavaliableusername').css('display', 'none');
                        $('#submit-button').prop('disabled', false);
                    }
                }
            });
        }
    </script>
@endsection
