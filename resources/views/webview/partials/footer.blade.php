<style>
    .line {
        height: 5px;
        width: 30px;
        background-color: white;
        margin-bottom: 15px;

    }

    .info_text {
        color: white;
        font-size: 14px;
    }
</style>



<footer id="footer" class="p-0 footer color-bg">


    <div class="pt-4 footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-3" id="left">
                    <div class="row">
                        <a target="_blank" href="{{ $basicinfo->facebook }}">
                            <img src="{{ asset($basicinfo->page_image) }}" style="width:100%;border-radius:4px;">
                        </a>
                    </div>

                </div>

                <div class="col-12 col-md-3" id="left">
                    <div class="module-heading">
                        <h4 class="module-title">Our Info</h4>
                        <!--<div class="line"></div>-->
                    </div>

                    <div class="module-body">
                        <ul class='list-unstyled' style="font-size: 14px;">
                            <li class="first"><a class="info_text" title="Your Account"
                                    href="{{ url('/') }}">Home</a></li>
                            <div class="lineb"></div>
                            <li class="first"><a class="info_text" title="Your Account"
                                    href="{{ url('venture/about_us') }}">About us</a></li>
                            <div class="lineb"></div>
                            <li><a class="info_text" href="{{ url('venture/contact_us') }}"
                                    title="Suppliers">Contact Us</a></li>
                            <li><a class="info_text" href="{{ url('venture/faq') }}"
                                    title="Suppliers">FAQ</a></li>
                            <li><a class="info_text" href="{{ url('venture/news') }}"
                                    title="Suppliers">Our News</a></li>
                            <div class="lineb"></div>

                        </ul>
                    </div>
                    <!-- /.module-body -->
                </div>

                <div class="col-12 col-md-3" id="left">
                    <div class="module-heading">
                        <h4 class="module-title">OUR POLICIES</h4>
                        <!--<div class="line"></div>-->
                    </div>

                    <div class="module-body">
                        <ul class='list-unstyled' style="font-size: 14px;">
                            <li><a class="info_text" href="{{ url('venture/privacy_policy') }}"
                                    title="Terms & Conditions">Privacy Policy</a></li>
                            <div class="lineb"></div>
                            <li><a class="info_text" href="{{ url('venture/refund_return_policy') }}"
                                    title="Terms & Conditions">Refund Return Policy</a></li>
                            <div class="lineb"></div>
                            <li><a class="info_text" href="{{ url('venture/shipping_policy') }}"
                                    title="shipping policy">Shipping Policy</a></li>
                            <div class="lineb"></div>
                            <li><a class="info_text" href="{{ url('venture/payment_policy') }}"
                                    title="shipping policy">Payment Policy</a></li>
                            <div class="lineb"></div>
                            <li><a class="info_text" href="{{ url('venture/terms_codition') }}"
                                    title="Terms & Conditions">Terms of Service</a></li>
                            <div class="lineb"></div>
                            <li><a class="info_text" href="{{ url('venture/contact_info') }}"
                                    title="Terms & Conditions">Contact Information</a></li>
                            <div class="lineb"></div>

                        </ul>
                    </div>
                    <!-- /.module-body -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-md-3" id="left">
                    <div class="module-heading">
                        <h4 class="module-title">OUR INFORMATION:</h4>
                        <!--<div class="line"></div>-->
                    </div>
                    <!-- /.module-heading -->

                    <div class="module-body">
                        <ul class="toggle-footer" style="font-size: 14px;">
                            <li class="media">
                                <small style="color: white; font-size: 14px;">✉ {{$basicinfo->email}} </small>
                            </li>
                            <li class="media">
                                <small style="color: white; font-size: 14px;">📞 {{$basicinfo->phone_one}} </small>
                            </li>
                            <li class="media">
                                <small style="color: white; font-size: 14px;">⏰ Always Open</small>
                            </li>
                            <li class="media">
                                <small style="color: white; font-size: 14px;">🏠 <span class="fw-bold">Address:</span> {{ $basicinfo->address }}</small>
                            </li>

                            <div class="topbar">
                                <div class="container">
                                    <div class="d-flex justify-content-center justify-content-md-start align-items-center w-100">

                                        <!-- Social Icons: Hidden on mobile, shown on desktop -->
                                        <div class="">
                                            <a href="{{ $basicinfo->facebook }}"  target="_blank"><i class="fab fa-facebook-f" ></i></a>
                                            <a href="{{ $basicinfo->linkedin }}" target="_blank"><i class="fab fa-instagram"></i></a>
                                            <a href="{{ $basicinfo->youtube }}" target="_blank"><i class="fab fa-youtube"></i></a>
                                            <a href="{{ $basicinfo->pinterest }}" target="_blank"><i class="fab fa-twitter"></i></a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="lineb"></div>

                        </ul>
                    </div>
                    <style>
                        .topbar {
                            color: white;
                            padding: 8px 0;
                            font-size: 14px;
                        }

                        .topbar a {
                            color: white;
                            text-decoration: none;
                            margin-left: 10px;
                            transition: color 0.3s;
                        }

                        .topbar a:hover {
                            color: #0d6efd;
                        }

                        .topbar i {
                            margin-right: 5px;
                        }

                        .separator {
                            margin: 0 10px;
                            color: #888;
                        }
                    </style>


                    <!-- /.module-body -->
                </div>
                <!-- /.col -->







            </div>
            <div class="container align-items-center">
                <img src="{{asset('public/payment.webp')}}"/>
            </div>
            <div class="pt-3 pb-2 row">

                <div class="col-12">
                    <hr style="border:1px solid #212129;">
                    <div class="module-heading">
                        <p class="text-center" style="font-size: 12px;color:white">Copyright © 2025 -
                            {{ env('APP_NAME') }} | Developed by <a href="https://danpitetech.com/">Danpite Tech</a>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>


</footer>
