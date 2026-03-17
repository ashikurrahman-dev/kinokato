@extends('webview.master')

@section('maincontent')
    @section('title')
        {{ env('APP_NAME') }}- About Us
    @endsection

    <div class="container section-padding">
        <div class="row align-items-center">
            <div class="col-lg-6 col-12">
                <h2 class="story-title">Our Story</h2>
                <p class="story-text">{!! $about->value !!}</p>
            </div>
            <div class="col-lg-6 col-12 story-img">
                <img src="{{ asset($about->about_img) }}" alt="">
            </div>
        </div>

        <!-- Stats -->
        <div class="mt-5 row g-3">
            <div class="col-lg-3 col-6">
                <div class="stat-box">
                    <img src="{{ asset($about->sale1_img) }}" alt="" width="70">
                    <div class="stat-number">{{ $about->sale1_amount }}</div>
                    <div class="stat-label">{{ $about->sale1_title }}</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-box">
                    <img src="{{ asset($about->sale2_img) }}" alt="" width="70">
                    <div class="stat-number">{{ $about->sale2_amount }}</div>
                    <div class="stat-label">{{ $about->sale2_title }}</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-box">
                    <img src="{{ asset($about->sale3_img) }}" alt="" width="70">
                    <div class="stat-number">{{ $about->sale3_amount }}</div>
                    <div class="stat-label">{{ $about->sale3_title }}</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="stat-box">
                    <img src="{{ asset($about->sale4_img) }}" alt="" width="70">
                    <div class="stat-number">{{ $about->sale4_amount }}</div>
                    <div class="stat-label">{{ $about->sale4_title }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- team -->
    <div class="container team-section">
        <div class="position-relative">

            <div class="row">
                <div class="col-lg-4 col-12 team-card">
                    <img src="{{ asset($about->member1_img) }}">
                    <div class="team-name">{{ $about->member1_name }}</div>
                    <div class="team-role">{{ $about->member1_designation }}</div>
                    <div class="social-icons">
                        <a href="{{ $about->member1_twitter }}"><i class="text-dark me-2 fa-brands fa-twitter"></i></a>
                        <a href="{{ $about->member1_instagram }}"><i class="text-dark me-2 fa-brands fa-instagram"></i></a>
                        <a href="{{ $about->member1_linkedin }}"><i class="text-dark me-2 fa-brands fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-12 team-card">
                    <img src="{{ asset($about->member2_img) }}">
                    <div class="team-name">{{ $about->member2_name }}</div>
                    <div class="team-role">{{ $about->member2_designation }}</div>
                    <div class="social-icons">
                        <a href="{{ $about->member2_twitter }}"><i class="text-dark me-2 fa-brands fa-twitter"></i></a>
                        <a href="{{ $about->member2_instagram }}"><i class="text-dark me-2 fa-brands fa-instagram"></i></a>
                        <a href="{{ $about->member2_linkedin }}"><i class="text-dark me-2 fa-brands fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-12 team-card">
                    <img src="{{ asset($about->member3_img) }}">
                    <div class="team-name">{{ $about->member3_name }}</div>
                    <div class="team-role">{{ $about->member3_designation }}</div>
                    <div class="social-icons">
                        <a href="{{ $about->member3_twitter }}"><i class="text-dark me-2 fa-brands fa-twitter"></i></a>
                        <a href="{{ $about->member3_instagram }}"><i class="text-dark me-2 fa-brands fa-instagram"></i></a>
                        <a href="{{ $about->member3_linkedin }}"><i class="text-dark me-2 fa-brands fa-linkedin"></i></a>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <section class="features-section">
        <div class="container">
            <div class="text-center row g-4">

                <div class="col-12 col-md-4 feature-item">
                    <div class="feature-icon-wrap">
                        <i style="color:white;font-size:22px;" class="fa-solid fa-truck-fast"></i>
                    </div>
                    <h6 class="m-0 feature-title">Free and Fast Delivery</h6>
                    <p class="feature-desc">Free delivery for all orders over $140</p>
                </div>

                <div class="col-12 col-md-4 feature-item">
                    <div class="feature-icon-wrap">
                        <i style="color:white;font-size:22px;" class="fa-solid fa-headset"></i>
                    </div>
                    <h6 class="m-0 feature-title">24/7 Customer Service</h6>
                    <p class="feature-desc">Friendly 24/7 customer support</p>
                </div>

                <div class="col-12 col-md-4 feature-item">
                    <div class="feature-icon-wrap">
                        <i style="color:white;font-size:22px;" class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h6 class="m-0 feature-title">Money Back Guarantee</h6>
                    <p class="feature-desc">We return money within 30 days</p>
                </div>

            </div>
        </div>
    </section>




    <style>
        .section-padding {
            padding: 60px 0;
        }

        .story-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .story-text {
            color: #555;
            line-height: 1.6;
        }

        .story-img img {
            width: 100%;
            border-radius: 10px;
        }

        /* Stats */
        .stat-box {
            background: #fff;
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            transition: .3s;
            border: 1px solid #ddd;
        }

        .stat-box:hover {
            background: #db4444;
            color: #fff;
        }

        .stat-box i {
            font-size: 22px;
            margin-bottom: 10px;
            display: block;
        }

        .stat-number {
            font-size: 20px;
            font-weight: 700;
        }

        .stat-label {
            font-size: 14px;
            color: #777;
        }

        .stat-box:hover .stat-label {
            color: #fff;
        }

        /* Carousel */
        .team-section {
            background: #fff;
        }

        .team-card {
            text-align: center;
            padding-bottom: 15px;
        }

        .team-card img {
            width: 100%;
            border-radius: 8px;
        }

        .team-name {
            font-weight: 700;
            margin-top: 10px;
        }

        .team-role {
            font-size: 14px;
            color: #777;
        }

        .social-icons i {
            margin: 0 5px;
            color: #555;
            cursor: pointer;
        }

        .carousel-btn {
            position: absolute;
            top: 40%;
            background: #000;
            color: #fff;
            border: none;
            padding: 8px 12px;
        }

        .prev-btn {
            left: -40px;
        }

        .next-btn {
            right: -40px;
        }

        @media(max-width:768px) {
            .carousel-btn {
                display: none;
            }
        }
    </style>
    <style>
        .features-section {
            background: #fff;
            padding: 50px 20px;
        }

        .feature-icon-wrap {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 2px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            background: #111;
            transition: background .3s;
        }

        .feature-icon-wrap i {
            font-size: 26px;
            color: #fff;
        }

        .feature-item:hover .feature-icon-wrap {
            background: #333;
        }

        .feature-title {
            font-size: 14px;
            font-weight: 800;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: #111;
            margin-bottom: 8px;
        }

        .feature-desc {
            font-size: 13px;
            color: #888;
            margin: 0;
        }
    </style>
@endsection
