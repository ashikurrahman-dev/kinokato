 
@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-{{ $productdetails->ProductName }}
@endsection
    <style>
      .sizetext {
        color: 000;
        background: #fff;
    }
    .colortext {
        color: #000;
        background: #fff;
    }
       .product-img{
           height:900px;
           width:100%;
           object-fit: cover;
           border-radius:6px;
       }
        .animate-charcter
        {
          background-image: linear-gradient(
            -225deg,
            #231557 0%,
            #44107a 29%,
            #ff1361 67%,
            #fff800 100%
          );
          background-size: auto auto;
          background-clip: border-box;
          background-size: 200% auto;
          color: #fff;
          background-clip: text;
          text-fill-color: transparent;
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          animation: textclip 2s linear infinite; 
              font-size: 190px;
        }
        
        @keyframes textclip {
          to {
            background-position: 200% center;
          }
        }
        
        
        
        #mtt{
            margin-top:60px;
        }
        #ptt{
            padding:30px;
        }
        .headtext {
            color: #00560C;
            font-family: "Baloo Da 2", Sans-serif;
            font-size: 38px;
            font-weight: 700;
            line-height: 65px;
        }

        #videodiv {
            height: 508px;
            border-style: groove;
            border-width: 5px 5px 5px 5px;
            border-color: #EC159EF7;
            border-radius: 10px 10px 10px 10px;
            box-shadow: 0px 0px 10px 0px rgb(0 0 0 / 50%);
        }

        #orderbtn {
            padding: 8px;
            font-family: "Hind Siliguri", Sans-serif;
            font-size: 35px;
            font-weight: bold;
            fill: #000;
            color: #000;
            background-color: yellow;
            border-style: groove;
            border-width: 2px 2px 2px 2px;
            border: none;
        }

        #callnowbtn {
            padding: 8px;
            font-family: "Hind Siliguri", Sans-serif;
            font-size: 30px;
            font-weight: bold;
            fill: #FFFFFF;
            color: #FFFFFF;
            background-color: #6E1183;
            border-style: groove;
            border-width: 2px 2px 2px 2px;
            border-color: #FFFFFF;
        }

        #priceText {
            font-size: 40px;
            font-weight: bold;
            padding-bottom:20px;
        }

        .headdingTitle {
            color: #FF6600;
            font-family: "Hind Siliguri", Sans-serif;
            font-size: 28px;
            font-weight: 700;
            line-height: 1.4em;
        }

        .heading-title {
            color: #848484;
            font-family: "Baloo Da 2", Sans-serif;
            font-size: 23px;
            font-weight: 500;
            line-height: 1.4em;
        }

        .elementor-widget-container {
            padding: 50px 0px 50px 0px;
            background-color: #FFFFFF73;
            border-style: solid;
            border-width: 1px 1px 1px 1px;
            border-color: #FF6600;
            border-radius: 10px 10px 10px 10px;
            box-shadow: 0px 0px 10px 0px rgb(118 118 118 / 50%);
        }

        .elementor-icon-box-wrapper {
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start;
        }

        .elementor-icon-box-wrapper {
            display: block;
            text-align: center;
        }

        .elementor-icon-box-icon {
            margin-bottom: var(--icon-box-icon-margin, 15px);
            margin-right: auto;
            margin-left: auto;
        }

        .elementor-icon {
            fill: #FF6600;
            color: #FF6600;
            border-color: #FF6600;
        }

        .elementor-icon-box-content {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            margin-bottom: 20px
        }

        .elementor-icon-box-title a {
            font-family: "Hind Siliguri", Sans-serif;
            font-size: 26px;
            font-weight: 600;
        }

        .elementor-icon-box-title {
            margin-bottom: 0px;
            color: #FF6600;
            font-size: 26px;
            font-weight: 600;
        }

        #mid-bannerd {
            background-image: url('./public/images/bgimg.jpg');
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            width: 100%;
            height: 100%;
        }

        #mid-banner {
            position: absolute;
        }

        #overlay {
            position: relative;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #FF6600;
            z-index: 2;
            cursor: pointer;
            opacity: 0.9;
        }

        #midbH2 {
            color: #FFFD06;
            font-family: "Hind Siliguri", Sans-serif;
            font-size: 50px;
            font-weight: 800;
        }

        .elementor-icon-list-items {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .elementor-icon-list-item a {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            font-size: inherit;
        }

        .elementor-widget .elementor-icon-list-item {
            margin: 0;
            padding: 0;
            position: relative;
        }

        .elementor-icon-list-text {
            color: #FFFFFF;
            align-self: center;
            padding-left: 5px;
            font-family: "Hind Siliguri", Sans-serif;
            font-size: 30px;
            font-weight: 700;
        }

        .elementor-icon-list-icon {
            font-size: 30px;
            color: yellow;
        }

        #formText {
            color: #000;
            font-family: "Hind Siliguri", Sans-serif;
            font-size: 28px;
            font-weight: 700;
            line-height: 1;
            border-radius: 6px;
            margin-bottom: 4px;
            padding-bottom: 12px;
            text-align: center;
        }
        #formTextN {
            color: #fff;
            font-family: "Hind Siliguri", Sans-serif;
            font-size: 28px;
            font-weight: 700;
            line-height: 1;
            border-radius: 6px;
        }
        
        #formContent {
            padding: 30px 30px 30px 30px;
            border-radius: 10px 10px 10px 10px;
            box-shadow: 0px 0px 10px 0px rgb(80 193 5 / 94%);
        }

        .shop_table {
            border: none;
            border-bottom: 0;
            background-color: inherit;
            border-radius: 0;
            font-family: inherit;
            font-weight: inherit;
            font-size: 0.95em;
            margin: 0 0 25px 0;
            border-collapse: collapse;
            text-align: left;
        }

        #payment {
            background-color: inherit;
            border: none;
            border-radius: 0;
        }

        #payment ul.payment_methods {
            border: none;
            margin: 1em 0 0;
            background-color: #f7f7f7;
            padding: 15px;
            text-align: left;
            padding: 1em;
            border-bottom: none;
            margin: 0;
            list-style: none outside;
        }

        #payment ul.payment_methods li {
            line-height: 2;
            text-align: left;
            margin: 0;
            font-weight: 400;
        }

        #payment div.payment_box {
            background-color: #eaeaea;
            font-family: inherit;
            font-weight: inherit;
            margin-bottom: 0.5em;
        }

        #payment div.payment_box {
            position: relative;
            box-sizing: border-box;
            width: 100%;
            padding: 1em;
            margin: 1em 0;
            font-size: .92em;
            border-radius: 2px;
            line-height: 1.5;
            background-color: #dfdcde;
            color: #515151;
        }

        .privacy-policy-text p {
            font-family: inherit;
            font-weight: inherit;
            font-size: 16px;
            color: #777;
            margin-top: 8px;
            text-align: justify;
        }

        #place_order {
            border: 1px solid;
            font-family: inherit;
            font-weight: inherit;
            letter-spacing: 0.5px;
            width: 100%;
            padding: 16px 24px;
            font-size: 18px;
            line-height: 1.5;
            border-radius: 3px;
            color: white;
            background-color: #00CD04;
            border-color: #00CD04;
        }

        #place_order_footer {
            border: 1px solid;
            width: 100%;
            padding: 12px 18px;
            font-size: 18px;
            line-height: 1.5;
            border-radius: 3px;
            color: white;
            text-decoration: none;
            background-color: #00CD04;
            border-color: #00CD04;
        }

        .sticky-product-bar-container {
            width: 100%;
            float: right;
            right: 0;
            position: fixed;
            display: flex;
            flex-wrap: nowrap;
            justify-content: center;
            align-items: center;
            padding: 5px;
            height: 60px;
            margin-left: auto;
            margin-right: auto;
            bottom: 0;
            background: green;
            z-index:999;
        }

        .checkout-page.display-button section.total {
            margin-left: auto;
        }

        .total .total-label {
            color: #fff;
            font-weight: 700;
            font-size: 20px;
        }
        #videoDiv{
            height:550px;
        }
        
        #priceDiv{
            color: #333333; 
        }
        
        .headtext2{
            color:white;
            font-size: 26px;
            padding: 60px;
            padding-top: 0;
            padding-bottom: 0;
        }
        
        #priceDiv2{
            color: #f6f6cb;padding-bottom: 10px;font-size: 35px;
        }
        
         
        #priceDiv3{
            color:yellow;padding-bottom:10px;
        }
        #priceDiv4{
            color:white;padding-bottom:10px;
        }
        #btnid{
            font-size: 25px;
            font-weight: bold;
        }
        @media only screen and (max-width: 600px) {
           .product-img{
               height:350px;
               width:100%;
               object-fit: cover;
           }
            #btnid{
                font-size: 22px;
                font-weight: bold;
            }
            #formTextN {
                color: #fff;
                font-family: "Hind Siliguri", Sans-serif;
                font-size: 20px;
                font-weight: 700;
                line-height: 1;
                border-radius: 6px;
            }
            #formText {
                color: #000;
                font-family: "Hind Siliguri", Sans-serif;
                font-size: 20px;
                font-weight: 700;
                line-height: 1;
                border-radius: 6px;
                margin-bottom: 4px;
                padding-bottom: 12px;
                text-align: center;
            }
            #priceText { 
                font-weight: bold;
                padding-bottom:0px;
                font-size: 26px;
            }
           .elementor-icon-list-item > a {
                font-family: "Poppins", Sans-serif;
                font-size: 16px;
                font-weight: 400;
            }
          .headtext {
               color: #00560C;
                font-family: "Baloo Da 2", Sans-serif;
                font-size: 20px;
                font-weight: 700;
                line-height: 1.1em;
            }
            
            .headtext2{
                color:white;
                font-size: 14px;
                padding:14px;
                padding-top: 10px;
                padding-bottom: 0;
            }
            
            #mtt{
                margin-top:20px;
            }
            #ptt{
                padding:14px;
            }
            #headTextdiv{
                padding-top:0px !important;
            }
            #videoDiv {
                height: 300px;
            }
            #orderbtn {
                padding: 6px;
                font-family: "Hind Siliguri", Sans-serif;
                font-size: 18px;
            }
            #callnowbtn{
                padding: 6px;
                font-family: "Hind Siliguri", Sans-serif;
                font-size: 19px;
            }
            #priceDiv{
                color: #333333;
                font-size: 25px;
            }
            #priceDiv2{
                color: #ff6600;
                font-size: 25px;
            }
            .headdingTitle {
                color: #FF6600;
                font-family: "Hind Siliguri", Sans-serif;
                font-size: 22px;
                font-weight: 700;
                line-height: 1.4em;
            }
            
            
            .heading-title {
                color: #848484;
                font-family: "Baloo Da 2", Sans-serif;
                font-size: 19px;
                font-weight: 500;
                line-height: 1.4em;
            }
            
            #midbH2 {
                color: #FFFD06;
                font-family: "Hind Siliguri", Sans-serif;
                font-size: 40px;
                font-weight: 800;
            }
            
            .elementor-icon-list-text {
                color: #FFFFFF;
                align-self: center;
                padding-left: 5px;
                font-family: "Hind Siliguri", Sans-serif;
                font-size: 25px;
                font-weight: 700;
            }
            #marSM{
                margin:0 !important;
                padding:0 !important;
                margin-top: 15px !important;
            }
            #marSMTest{
                margin:0 !important;
                padding:0 !important;
            }
            #formContent {
                padding: 30px 6px 4px 6px;
                border-radius: 10px 10px 10px 10px;
                box-shadow: 0px 0px 10px 0px rgb(0 0 0 / 50%);
            }
            #pbSM{
                padding-bottom:0 !important;
            }
        }
        
        #info{
            color: red;
            font-weight: bold;
            font-size: 18px;
        }
        
        
        .form-control, .form-control>.btn {
            font-size: .875rem;
            font-weight: 400;
            color: #555;
            background-color: #fff;
            border-width: 1px;
            border-color: #514d4d;
            border-radius: 2px;
            -webkit-box-shadow: none;
            box-shadow: none;
            -webkit-transition: background-color .2s linear;
            -ms-transition: background-color .2s linear;
            transition: background-color .2s linear;
        }
        
        .elementor-widget-container {
            margin: 0 0 22px;
            padding: 0;
            border-style: double;
            border-width: 5px;
            border-color: #362727;
            border-radius: 10px 10px 10px 10px;
            box-shadow: 0 0 10px 0 rgba(0, 0, 0, .5);
        }
@media (min-width: 250px) and (max-width: 700px) {
    .container, .container-md, .container-sm {
        max-width: 100% !important;
    }
  
}
    </style>
    
 <style>

div#productPricetitle {
    font-size: 30px;
    margin-top: -10px;
}

.name{
    margin-bottom: 7px;
    line-height: 42px;
    text-align: center;
    font-size: 46px;
    padding-bottom: 30px;
    padding-top: 18px;
}
 #vid{
     height:500px
 }
  #thumbvid{
     height:500px;
     border: 10px solid lightgray;
    border-radius: 4px; 
 }
 .breaf{
     text-align:center;
     font-size:26px;
 }
 
 .video { position: relative; padding-bottom: 49.25%; /* 16:9 */ height: 0; }
.video img { position: absolute; display: block; top: 0; left: 0; width: 100%; height: 100%; z-index: 20; cursor: pointer; }
.video:after { content: "";
    position: absolute;
    display: block; 
    top: 45%;
    left: 45%;
    width: 80px;
    height: 80px;
    z-index: 30;
    cursor: pointer;
    background-size: cover; } 
.video iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
 
/* image poster clicked, player class added using js */
.video.player img { display: none; }
.video.player:after { display: none; }

#ext{
    padding-left: 14px;
    text-align: center;
    padding-top: 12px;
}

    @media only screen and (max-width: 600px) { 
        #ext{
            padding-left: 14px;
            text-align: center;
            padding-top: 12px;
        }
        
        .name {
            margin-bottom: 5px;
            line-height: 30px;
            text-align: center;
            font-size: 25px;
            padding-bottom: 15px;
            margin-top: 16px;
           
        }
        
        #videodiv {
            height: 269px; 
        }
        
        div#productPricetitle {
            font-size: 30px;
            margin-top: -10px;
        }

        #vid{
             height:260px
         }
         #thumbvid{
             height:260px
         }
             #buttonmobilesss{
                position: fixed;
                border-radius: 28px 0px !important;
                z-index: 101;
                bottom: 0; 
                left: 0;
             }
             .detail-block{
                 padding-top:0px;
             }
             
            .breaf{
                text-align:center; 
                font-size: 13px;
                color: black;
                padding: 4px;
             }
             #smbid{
                 margin-bottom: 8px; margin-top: 75px;
             }
              .video { position: relative; padding-bottom: 56.25% !important; /* 16:9 */ height: 0; }
        }
        
        
        /* DEMO-SPECIFIC STYLES */
.typewriter h1 {
  color: black;
  font-family: monospace;
  overflow: hidden; /* Ensures the content is not revealed until the animation */
  border-right: .15em solid orange; /* The typwriter cursor */
  white-space: nowrap; /* Keeps the content on a single line */
  margin: 0 auto; /* Gives that scrolling effect as the typing happens */
  letter-spacing: .15em; /* Adjust as needed */
  animation: 
    typing 3.5s steps(30, end),
    blink-caret .5s step-end infinite;
}

/* The typing effect */
@keyframes typing {
  from { width: 0 }
  to { width: 100% }
}

/* The typewriter cursor effect */
@keyframes blink-caret {
  from, to { border-color: transparent }
  50% { border-color: orange }
}

</style>
    <!-- Body --> 

        <div class="container" style="background: white;">
            
            <div class="row  wow fadeInUp">

                <div class="col-xs-12 col-sm-12 col-md-10 m-auto  gallery-holder">
                    <h1 class="name animate-charcter mt-0" >  {{ $productdetails->ProductName }}</h1>
                      <p class="breaf" style="border: 2px solid blueviolet;border-radius: 6px;box-shadow: 1px 2px 6px crimson;font-family: "Anek Bangla", Sans-serif;">{!! $productdetails->ProductBreaf !!}</p>
                        <div class="product-item-holder size-big single-product-gallery small-gallery"> 
                        <div class="single-product-gallery-item" id="slide1">
                            <a data-lightbox="image-1" data-title="Gallery">
                                <img class="img-responsive product-img" alt="{{ $productdetails->ProductName }}"   src="{{ url($productdetails->ProductImage) }}" />
                            </a>
                        </div>
                    </div>
                    <!-- /.single-product-gallery -->
                </div> 
            </div>
            
                    
            <div class="row pt-4 text-center">
                <div class="col-12">
                    <p id="priceText" style="margin:0px;"> মূল্যঃ<span id="priceDiv2" style="color:#868686;"> 
                            <del>{{ round(App\Models\Size::where('product_id',$productdetails->id)->first()->RegularPrice) }} </del></span> <span id="priceDiv3" style="color:green" > 
                           {{ App\Models\Size::where('product_id', $productdetails->id)->first()->SalePrice }} টাকা।</span></p>
                 </div>
                 <div class="col-12 m-auto">
                     <div class="p-3" style="border: 2px solid blueviolet;border-radius: 6px;box-shadow: 1px 2px 6px crimson;">
                         <h4 class="mt-1 mb-1">{{$productdetails->text_two}}</h4>
                     </div> 
                 </div>
                <div class="col-12 pb-4 pt-4"> 
                    <a href="#marSMTest" class="btn" id="orderbtn" >অর্ডার করতে ক্লিক করুন &nbsp; <i aria-hidden="true" class="fas fa-hand-point-down"></i> </a>
                </div>
                 
            </div>
        </div>
        
        <section>
            <div class="container">
                <div class="product-item-holder size-big single-product-gallery small-gallery"> 
                <div class="row" id="videodiv">
                    <div class="video"> 
                         <iframe width="100%" id="vid" style="padding:0"
                            src="https://www.youtube.com/embed/{{ $productdetails->youtube_embade }}">
                        </iframe>
                     </div>
                    
                </div>
                </div>
            </div>
        </section>
 
   @if(isset($productdetails->PostImage))
   <div class="container" >
       <div class="title text-center ">
           <h3> আরো কিছু ছবি</h3>
       </div>
       <div class="row">
              <div class="col-12 gallery-holder">
                <div class="product-item-holder size-big single-product-gallery small-gallery">
                    <div class="owl-carousel owl-theme" id="owl-single-product">
                        @if(isset($productdetails->PostImage))
                            @forelse (json_decode($productdetails->PostImage) as $key =>$slider)
                                <div class="single-product-gallery-item" id="slide{{ $key + 1 }}">
                                    <a data-lightbox="image-1" data-title="Gallery">
                                        <img class="img-responsive product-img" alt="{{ $productdetails->ProductName }}"  src="{{ url('public/images/product/slider/' . $slider) }}"  />
                                    </a>
                                </div>
                            @empty
                                <div class="single-product-gallery-item" id="slide1">
                                    <a data-lightbox="image-1" data-title="Gallery">
                                        <img class="img-responsive product-img" alt="{{ $productdetails->ProductName }}" src="{{ url($productdetails->ProductImage) }}"  />
                                </a>
                            </div>
                        @endforelse
                    @else
                         <div class="single-product-gallery-item" id="slide1">
                            <a data-lightbox="image-1" data-title="Gallery">
                                <img class="img-responsive product-img" alt="{{ $productdetails->ProductName }}"  src="{{ url($productdetails->ProductImage) }}"   />
                            </a>
                        </div>
                    @endif

                </div>

            </div>
        </div>
   
         </div>
        <div class="text-center py2 " >
            @if($productdetails->text_three)
             <h4>{{$productdetails->text_three}}</h4>
             @else
            @endif
           <a href="#marSMTest" class="btn my-3" id="orderbtn" >অর্ডার করতে ক্লিক করুন &nbsp; <i aria-hidden="true" class="fas fa-hand-point-down"></i> </a>
        </div>
   </div>
      @else
  @endif
  
  
     <div class="container"  id='marSM' style="background: white;">
            <div class="row pt-4 text-center" id="marSM">
                <div class="col-lg-12 col-12 mt-4">
                    <p id="formTextN" style="color:red !important"> সরাসরি অর্ডার করতে নিচের নাম্বারগুলোতে কল করুন।
                    </p> 
                </div>
                <div class="col-lg-12 col-12 mb-4">
                     <a class="btn btn-danger text-center m-auto px-4" id="formText" href="tel:{{ \App\Models\Basicinfo::first()->phone_one}} " style="background: #ff6a00;border: 1px solid #ff6a00;color:#fff;">
                        <div class="d-flex text-center" >
                            <i aria-hidden="true" class="fas fa-phone" style="font-size: 36px;"></i> 
                            <div class="mx-3">
                                কল করুন <br>{{ \App\Models\Basicinfo::first()->phone_one}} 
                            </div>
                        </div>
                    </a>    
                </div>
            </div> 
            
            <div class="row pt-4 pb-4"  id="marSM">
                <div class="col-12">
                    <div class="container">
                        
                        <div class="row" id="formContent">
                            <p id="formText"> পণ্য সম্পর্কে বিস্তারিত:        </p> 
                            <div class="col-lg-6 col-12 mb-3"> 
                                 <img src="{{asset( $productdetails->ProductImage)}}" style="width: 100%;border-radius: 6px;">
                            </div>
                            
                            <div class="col-lg-6 col-12">
                                <h4 style="margin: 0;padding-bottom: 12px;text-align: center;font-weight: bold;color: #ff6a00;">{{$productdetails->ProductName}}</h4> 
                                {!! $productdetails->ProductDetails !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            
        </div>
               
        <div class="container"  id='marSMTest' style="background: white;">
            <div class="row pt-4 mt-4 pb-4"  id="marSM">
                <div class="col-12">
                    <div class="container">
                        <div class="row" id="formContent" style="background: #0554011A;">
                            
                            <div class="col-md-6 col-12">
                                <form method="POST" action="{{ url('landing/order') }}" class="from-prevent-multiple-submits">
                                   
                                <ul id="shipping_method" class="shipping-methods"
                                            style="list-style: none;margin:0;">
                                    @php
                                        $prod=App\Models\Product::where('id',$productdetails->id)->first(); 
                                    @endphp
                                        <li class="mb-3 d-flex" style="border: 2px solid #fff;padding: 6px;border-radius: 6px;box-shadow: 1px 1px 4px orangered;">
                                            <input type="radio" name="productview_id" style="margin: 0;width: 26px;display: none;"  onchange='addtocartview({{$prod->id}})' value="{{$prod}}" id="product_id{{$prod->id}}">
                                            <input type="hidden" id="pro{{$prod->id}}" value="{{$prod->ProductSalePrice}}">
                                            <label class="" for="product_id{{$prod->id}}"> <img src="{{asset($prod->ProductImage)}}" style="width:100%;border-radius:6px">
                                             <span class="Price-amount amount"><div id="ext"><span style="font-size:22px">{{$prod->ProductName}}<br> x1</span><span style="font-size:22px;font-weight:bold;" class="Price-currencySymbol">&nbsp;&nbsp;&nbsp;৳  {{ App\Models\Size::where('product_id', $productdetails->id)->first()->SalePrice }}&nbsp;</span></div></span></label>
                                        </li> 
                            
                                    </ul>
                            </div>
                            <div class="col-md-6 col-12">
                               
                                <div class="row mt-2">
                                        @if (empty($productdetails->color))
                                    @else
                                        <div class="col-12 col-md-12 colorpart mb-2">
                                            <div class="d-flex">
                                                <h4 id="resellerprice" class="m-0"><b style="font-size:17px">Color:&nbsp;&nbsp;&nbsp;</b></h4>
                                                <div class="colorinfo">
                                                    @forelse (json_decode($productdetails->color) as $color)
                                                        <input type="radio" class="m-0" id="color{{ $color }}" hidden name="color" value="{{$color}}" onclick="getcolor('{{ $color }}')">
                                                        <label class="colortext ms-0"   id="colortext{{ $color }}" for="color{{ $color }}" style="border: 2px solid grey;font-size:16px;font-weight:bold;padding:0px 15px;" onclick="getcolor('{{ $color }}')">{{ $color}}</label>
                                                    @empty
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (empty($productdetails->size))
                                    @else
                                        <div class="col-12 col-md-12 colorpart mt-2">
                                            <div class="d-flex">
                                                <h4 id="resellerprice" class="m-0"><b style="font-size:17px">Size: &nbsp;&nbsp;&nbsp;</b></h4>
                                                <div class="sizeinfo ms-2">
                                                    @forelse (json_decode($productdetails->size) as $size)
                                                        <input type="radio" class="m-0" hidden id="size{{ $size }}" name="size" value="{{$size}}" onclick="getsize('{{ $size }}')" >
                                                        <label class="sizetext ms-0 my-1"  id="sizetext{{ $size }}" for="size{{ $size }}" style="border: 2px solid grey;font-size:16px;font-weight:bold;padding:0px 15px;" onclick="getsize('{{ $size }}')">{{ $size }}</label>
                                                    @empty
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                  
                       
                                <h4 class="pb-4 mb-1 mt-0" id='marSM'>Billing Details</h4>
                                
                                    @csrf
                                    <div class="row py-4">
                                        <div class="form-group col-sm-12 pb-2" id='pbSM'>
                                            <label id="info">আপনার নাম লিখুন *</label>
                                            <input type="text" id="customerName" name="customerName" required
                                                class="form-control p-2">
                                        </div>
                                        <div class="form-group col-sm-12 pb-2" id='pbSM'>
                                            <label id="info">আপনার মোবাইল নাম্বারটি লিখুন * </label>
                                            <input type="text" id="customerPhone" name="customerPhone" required
                                                class="form-control p-2">
                                        </div>
                                        <input type="text" name="subTotal" value="{{ App\Models\Size::where('product_id', $productdetails->id)->first()->SalePrice }}" hidden id="subTotal">
                                        <input type="text" name="productID" value="{{ $productdetails->id }}" hidden
                                            id="productID">
 
                                        <div class="form-group col-sm-12 pb-2" id='pbSM'>
                                            <label id="info">আপনার ঠিকানা লিখুন * </label>
                                            <input type="text" name="customerAddress" id="customerAddress"
                                                placeholder="বাসা নং, রোড নং, উপজেলা , জেলা" class="form-control p-2"
                                                required autocomplete="address-line1">
                                        </div>
                                        <div class="form"> 
                                            <ul id="shipping_method" class="shipping-methods "
                                                    style="list-style: none;">
                                                <li>
                                                    <input type="radio" name="deliveryCharge"
                                                        onchange='setdelivery()'
                                                        id="shipping_method_flat_rate" value="{{App\Models\Basicinfo::first()->outside_dhaka_charge}}"
                                                        class="shipping_method" checked="checked"><label
                                                        for="shipping_method_flat_rate">ঢাকার বাহিরে হোম
                                                        ডেলিভারি: <span
                                                            class="Price-amount amount"><bdi><span
                                                                    class="Price-currencySymbol">৳&nbsp;</span>{{App\Models\Basicinfo::first()->outside_dhaka_charge}}</bdi></span></label>
                                                </li> 
                                                <li>
                                                    <input type="radio" name="deliveryCharge"
                                                        onchange='setdelivery()'
                                                        id="shipping_method_0_flat_rate" value="{{App\Models\Basicinfo::first()->inside_dhaka_charge}}"
                                                        class="shipping_method"><label
                                                        for="shipping_method_0_flat_rate">ঢাকার ভিতরে হোম
                                                        ডেলিভারি: <span class="Price-amount amount"><bdi>
                                                                <span class="Price-currencySymbol">৳&nbsp;</span>{{App\Models\Basicinfo::first()->inside_dhaka_charge}}</bdi></span></label>
                                                </li>
                                            </ul>

                                        </div>
                                         <input type="text" name="color" id="product_color" hidden>
                                          <input type="text" name="size" id="product_size" hidden>
                                       
                                    </div>
                                    
                                    
                                    <div class="pb-3">
                                    <h4 class="pb-0 mb-0" id='marSM'>Your Order</h4>
                                   <div class="row py-4"  id='marSM'>
                                    <div class="col-12"  id='marSM'>
                                        <table class="table table-responsive"> 
                                            <tfoot>
        
                                                <tr class="cart-subtotal">
                                                    <th>Subtotal</th>
                                                    <td><span class="Price-amount amount"><bdi>
                                                        <span  class="Price-currencySymbol">৳&nbsp;</span><span id="subtID"> {{ App\Models\Size::where('product_id', $productdetails->id)->first()->SalePrice }}</span></bdi>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr class="shipping-totals shipping">
                                                    <th>Shipping</th>
                                                    <td data-title="Shipping"> 
                                                        <span class="Price-currencySymbol">৳&nbsp;</span>
                                                        <span id="ShippingID">{{App\Models\Basicinfo::first()->outside_dhaka_charge}}</span>
                                                    </td>
                                                </tr>
                                                <input type="text" name="ProductPrice" id="ProductPrice"  value="{{ App\Models\Size::where('product_id', $productdetails->id)->first()->SalePrice }}" hidden>
                                                <tr class="order-total">
                                                    <th>Total</th>
                                                    <td><strong><span class="Price-amount amount">
                                                        <bdi><span class="Price-currencySymbol">৳&nbsp;</span>
                                                              <span id="TotalPrice"> {{ App\Models\Size::where('product_id', $productdetails->id)->first()->SalePrice }}</span>
                                                                </bdi></span></strong>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <div id="payment" class="checkout-payment my-2"
                                            style="position: static; zoom: 1;">
                                            <ul class="wc_payment_methods payment_methods methods">
                                                <li class="wc_payment_method payment_method_cod">
                                                    <input id="payment_method_cod" type="radio" class="input-radio"
                                                        name="payment_method" value="cod" checked="checked"
                                                        data-order_button_text="" style="display: none;">

                                                    <label for="payment_method_cod">Cash on delivery </label>
                                                    <div class="payment_box payment_method_cod">
                                                        <p style="font-size: 13px;margin: 0;">পণ্য হাতে পেয়ে ডেলিভারি ম্যানকে পেমেন্ট করুন</p>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                         <button type="submit" style="background-color:#ff0000"  class="button alt from-prevent-multiple-submits" name="checkout_place_order" id="place_order" value="Place Order">
                                                  <i class="fas fa-lock"></i> অর্ডার করুন</button>

                                    </div>
                                 </div>
                                </div>

                            </div>
                             </form>
                    </div>
                   
                </div>
            </div>
        </div>
            
     
       



    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"
        integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"
        integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous">
    </script>

    <!-- font awsam -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/js/fontawesome.min.js"
        integrity="sha512-TXHaOs+47HgWwY4hUqqeD865VIBRoyQMjI27RmbQVeKb1pH1YTq0sbuHkiUzhVa5z0rRxG8UfzwDjIBYdPDM3Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/js/all.min.js"
        integrity="sha512-8pHNiqTlsrRjVD4A/3va++W1sMbUHwWxxRPWNyVlql3T+Hgfd81Qc6FC5WMXDC+tSauxxzp1tgiAvSKFu1qIlA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        function addtocartview(product_id) { 
            $.ajax({
                type: 'POST',
                url: '{{ url('add-to-view') }}',
                data: {
                    _token: token,
                    product_id: product_id,
                    qty: '1',
                },

                success: function(data) { 
                    $.ajax({
                        type: 'GET',
                        url: '{{ url('get-cart-content') }}',

                        success: function(response) {
                           var p= $('#pro'+product_id).val();
                            $('#ProductPrice').val(p);
                            $('#productID').val(product_id);
                            $('#subTotal').val(p);
                            $('#subtID').text(p);
                            setdeliveryss();
                            console.log(p);
                        },
                        error: function(error) {
                            console.log('error');
                        }
                    }); 
                },
                error: function(error) {
                    console.log('error');
                }
            });
        }

        function setdeliveryss() {
            var value = document.querySelector('input[name="deliveryCharge"]:checked').value;
            $('#selectCourier').val('');
            $('#ShippingID').text(value);
            $('#selectCourier').val(value);
            var price = $('#ProductPrice').val();
            console.log(price);
            var totalprice = parseInt(price) + parseInt(value);
            console.log(totalprice);
            $('#TotalPrice').html('');
            $('#TotalPrice').html(totalprice);
        }
        
        function setdelivery() {
            var value = document.querySelector('input[name="deliveryCharge"]:checked').value;
            $('#selectCourier').val('');
            $('#selectCourier').val(value);
            $('#ShippingID').text(value);
            var price = $('#ProductPrice').val();

            var totalprice = parseInt(price) + parseInt(value);
            console.log(totalprice);
            $('#TotalPrice').html('');
            $('#TotalPrice').html(totalprice);
        }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            
            $('#owl-single-product').owlCarousel({
                items: 3,
                itemsTablet: [768, 1],
                itemsDesktop: [1199, 1],
                autoplay: true,
                loop:true,
                margin: 7,
                autoplayTimeout: 1500,
                autoplayHoverPause: true,
                responsiveClass: true,
                dots: true,
                 responsive: {
                        0: {
                            items: 1,
                        },
                        600: {
                            items: 2,
                        },
                        1000: {
                            items: 3,
                        }
                    }
    
            });

            //order now
            $('#OrderFormModel').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '{{ url('press-order') }}',
                    processData: false,
                    contentType: false,
                    data: new FormData(this),

                    success: function(data) {
                        swal({
                            title: "Order placed successfully",
                            text: 'Thanks',
                            icon: "success",
                            buttons: true,
                            buttons: "Completed",
                        });
                         window.location.href="https://jannatlifestyle.com/order-received";
                    },
                    error: function(error) {
                        console.log('error');
                    }
                });
            });

            $('#OrderNowForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '{{ url('press-order') }}',
                    processData: false,
                    contentType: false,
                    data: new FormData(this),

                    success: function(data) {
                        swal({
                            title: "Order placed successfully",
                            text: 'Thanks',
                            icon: "success",
                            buttons: true,
                            buttons: "Completed",
                        });
                        window.location.href="https://jannatlifestyle.com/order-received";
                    },
                    error: function(error) {
                        console.log('error');
                    }
                });
            });


        });
    </script>
<script>
   function getcolor(color) {
        $('#product_color').val(color);
        $('.colortext').css('color','#000');
        $('.colortext').css('background','#fff');
        $('#colortext'+color).css('color','#fff');
        $('#colortext'+color).css('background','#613EEA');
    }

    function getsize(size) {
        $('#product_size').val(size);
        $('.sizetext').css('color','#000');
        $('.sizetext').css('background','#fff');
        $('#sizetext'+size).css('color','#fff');
        $('#sizetext'+size).css('background','#613EEA');
    }

</script>
    <script type="text/javascript">
        (function() {
            $('.from-prevent-multiple-submits').on('submit', function() {
                $('.from-prevent-multiple-submits').attr('disabled', 'true');
            })
        })();
    </script>

    {{-- //sweetalert --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@endsection
