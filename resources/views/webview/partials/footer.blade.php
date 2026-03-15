<style>
    .line {
        height: 5px;
        width: 30px;
        background-color: white;
        margin-bottom: 15px;

    }

    .info_text {
        color: white;
        font-size: 16px;
    }

    @media(min-width:770px){
        .col-md-25{
            flex: 0 0 auto;
            width: 20%;
        }
    }
    .lineb{
        margin: 10px 0;
    }
</style>
<style>
.subscribe-box {
    color: white;
    padding: 20px;
    width: 300px;
    border-radius: 8px;
    font-family: Arial, sans-serif;
}

.subscribe-box h3 {
    margin: 0 0 10px 0;
    font-size: 24px;
    color: white;
}

.subscribe-box p {
    margin: 0 0 15px 0;
    font-size: 14px;
    color: white;
}

.input-group {
    display: flex;
}

.input-group input {
    flex: 1;
    padding: 10px;
    border: 1px solid #fff;
    border-radius: 5px 0 0 5px;
    background-color: transparent;
    color: white;
    outline: none;
}

.input-group input::placeholder {
    color: #ccc;
}

.input-group button {
    padding: 7px 15px;
    border: 1px solid #fff;
    border-left: none;
    border-radius: 0 5px 5px 0;
    background-color: transparent;
    color: white;
    cursor: pointer;
}
input[type="email"]:focus{
    background:transparent;
}
</style>



<footer id="footer" class="p-0 footer color-bg">


    <div class="pt-4 footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-25" id="left">
                    <div class="row">
                        <a target="_blank" href="{{ $basicinfo->facebook }}">
                            <img src="{{ asset($basicinfo->page_image) }}" style="width:100%;border-radius:4px;">
                        </a>
                       <div class="subscribe-box">
                            <h3>Subscribe</h3>
                            <p>Get 10% off your first order</p>
                            <div class="input-group">
                                <input type="email" placeholder="Enter your email">
                                <button type="submit">&#10148;</button> <!-- Right arrow -->
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-12 col-md-25" id="left">
                    <div class="module-heading">
                        <h4 class="module-title">Support</h4>
                        <!--<div class="line"></div>-->
                    </div>

                    <div class="module-body">
                        <ul class='list-unstyled' style="font-size: 14px;">
                            <li class="first"><a class="info_text" title="Your Account"
                                    >{{ $basicinfo->address }}</a></li>
                            <div class="lineb"></div>
                            <li class="first"><a class="info_text" title="Your Account"
                                    >{{$basicinfo->email}}</a></li>
                            <div class="lineb"></div>
                            <li><a class="info_text"
                                    >{{$basicinfo->phone_one}}</a></li>
                            <div class="lineb"></div>

                        </ul>
                    </div>
                    <!-- /.module-body -->
                </div>

                <!-- /.col -->
                <div class="col-12 col-md-25" id="left">
                    <div class="module-heading">
                        <h4 class="module-title">Account</h4>
                        <!--<div class="line"></div>-->
                    </div>
                    <!-- /.module-heading -->

                    <div class="module-body">
                        <ul class='list-unstyled' style="font-size: 14px;">
                            <li><a class="info_text" href="{{ url('/register') }}"
                                    title="Terms & Conditions">My Account</a></li>
                            <div class="lineb"></div>
                            <li><a class="info_text" href="{{ url('/login') }}"
                                    title="Terms & Conditions">Login / Register</a></li>
                            <div class="lineb"></div>
                            <li><a class="info_text" href="{{ url('/cart') }}"
                                    title="shipping policy">Cart</a></li>
                            <div class="lineb"></div>
                            <li><a class="info_text" href="{{ url('/wishlist') }}"
                                    title="shipping policy">Wishlist</a></li>
                            <div class="lineb"></div>
                            <li><a class="info_text" href="{{ url('/shop') }}"
                                    title="Terms & Conditions">shop</a></li>
                            <div class="lineb"></div>

                        </ul>
                    </div>


                    <!-- /.module-body -->
                </div>
                <!-- /.col -->

                <div class="col-12 col-md-25" id="left">
                    <div class="module-heading">
                        <h4 class="module-title">Quick Link</h4>
                        <!--<div class="line"></div>-->
                    </div>

                    <div class="module-body">
                        <ul class='list-unstyled' style="font-size: 14px;">
                            <li><a class="info_text" href="{{ url('venture/privacy_policy') }}"
                                    title="Terms & Conditions">Privacy Policy</a></li>
                            <div class="lineb"></div>
                            <!-- <li><a class="info_text" href="{{ url('venture/refund_return_policy') }}"
                                    title="Terms & Conditions">Refund Return Policy</a></li>
                            <div class="lineb"></div> -->
                            <!-- <li><a class="info_text" href="{{ url('venture/shipping_policy') }}"
                                    title="shipping policy">Shipping Policy</a></li>
                            <div class="lineb"></div>
                            <li><a class="info_text" href="{{ url('venture/payment_policy') }}"
                                    title="shipping policy">Payment Policy</a></li>
                            <div class="lineb"></div> -->
                            <li><a class="info_text" href="{{ url('venture/terms_codition') }}"
                                    title="Terms & Conditions">Terms of Use</a></li>
                            <div class="lineb"></div>
                            <li><a class="info_text" href="{{ url('venture/contact_us') }}"
                                    title="Terms & Conditions">Contact</a></li>
                            <div class="lineb"></div>
                            <li><a class="info_text" href="{{ url('venture/faq') }}"
                                    title="Terms & Conditions">FAQ</a></li>
                            <div class="lineb"></div>

                        </ul>
                    </div>
                    <!-- /.module-body -->
                </div>
                <div class="col-12 col-md-25" id="left">
                    <div class="module-heading">
                        <h4 class="module-title text-white fw-bold">Download App</h4>
                    </div>
                    <p class="text-white-50 small">Save $3 with App New User Only</p>

                    <div class="row g-2 align-items-start">
                        <!-- QR Code -->
                        <div class="col-auto">
                        <div class="bg-white p-2 rounded">
                            <img src="{{ asset('public/qr.png') }}" alt="QR Code" width="100" height="100">
                        </div>
                        </div>

                        <!-- Store Buttons -->
                        <div class="col">
                        <!-- Google Play -->
                        <a href="#" class="d-flex align-items-center gap-2 text-white text-decoration-none border border-secondary rounded p-2 mb-2" style="background:#222;">
                            <img src="{{ asset('public/play_store.png') }}" alt="Google Play" width="26">
                            <div>
                            <div class="text-white-50" style="font-size:10px;">GET IT ON</div>
                            <div class="fw-semibold" style="font-size:14px;">Google Play</div>
                            </div>
                        </a>
                        <!-- App Store -->
                        <a href="#" class="d-flex align-items-center gap-2 text-white text-decoration-none border border-secondary rounded p-2" style="background:#222;">
                            <img src="{{ asset('public/apple_store.png') }}" alt="App Store" width="26">
                            <div>
                            <div class="text-white-50" style="font-size:10px;">Download on the</div>
                            <div class="fw-semibold" style="font-size:14px;">App Store</div>
                            </div>
                        </a>
                        </div>
                    </div>

                    <!-- Social Icons -->
                    <div class="d-flex gap-3 mt-3 pt-3 ">
                        <a href="{{ $basicinfo->facebook }}" class="text-white-50 fs-5"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="{{ $basicinfo->twitter }}" class="text-white-50 fs-5"><i class="fa-brands fa-twitter"></i></a>
                        <a href="{{ $basicinfo->rss }}" class="text-white-50 fs-5"><i class="fa-brands fa-instagram"></i></a>
                        <a href="{{ $basicinfo->linkedin }}" class="text-white-50 fs-5"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>
                







            </div>
            <!-- <div class="container align-items-center">
                <img src="{{asset('public/payment.webp')}}"/>
            </div> -->
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
