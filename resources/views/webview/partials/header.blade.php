<style>
.cat-nav { background:#fff; border:1px solid #e5e5e5; border-radius:8px; padding:8px 0; }
.cat-item { position: relative; }
.cat-row { display:flex; align-items:center; justify-content:space-between; padding:9px 16px; transition:background .15s; }
.cat-row:hover { background:#f5f5f5; }
.cat-link { color:#222; font-size:14px; text-decoration:none; flex:1; }
.arrow-btn { background:none; border:none; padding:2px 6px; cursor:pointer; color:#888; font-size:12px; transition:transform .25s; }
.arrow-btn.open { transform:rotate(180deg); color:#222; }
.sub-list { list-style:none; padding:0; margin:0; background:#fafafa; border-top:1px solid #eee; display:none; }
.sub-list.show { display:block; }
.sub-list li a { display:block; padding:8px 16px 8px 70px; font-size:13px; color:#555; text-decoration:none; }
.sub-list li a:hover { background:#f0f0f0; color:#111; }
.divider { border-top:1px solid #f0f0f0; margin:2px 0; }
</style>
<header class="header-style-1">

    <!-- ============================================== TOP MENU ============================================== -->
    <div class="top-barhead animate-dropdown" id="">
        <div class="header-top-inner">
            <div style="display:flex; align-items:center;justify-content:center;">
                <p class="m-0" style="color: white;text-align:center;">
                    {{ $basicinfo->marquee_text }}
                    <a href="{{ url('/shop') }}" style="color:white; text-decoration:underline;font-size:17px; margin-left:20px">
                        Shop Now
                    </a>
                </p>
            </div>
        </div>
    </div>
    <div class="main-header" id="myHeader" style="background: #fff;border-bottom: 1px solid #e9e9e9;">
        <div class="container">
            <div class="row" style="" class="pcview_margin">
                <div class="col-6 col-sm-6 col-md-6 col-lg-2 logo-holder ps-0">
                    <!-- ============================================================= LOGO ============================================================= -->
                    <div class="logo" style="display: flex;justify-content:start;align-items:center">
                        <!-- <button type="button" class="d-lg-block" onclick="openNav()" id="menubutton">
                            <img src="{{ asset('public/menu.png') }}" alt="" id="menuiconcss">
                        </button> -->

                        <a href="{{ url('/') }}" id="logoimage">
                            <img src="{{ asset($basicinfo->logo) }}" alt="" id="logosm"
                                style="    width: 100%;">
                        </a>
                    </div>
                    <!-- /.logo -->
                    <!-- ============================================================= LOGO : END ============================================================= -->
                </div>
                <!-- /.logo-holder -->

                <div class="col-md-2 col-lg-4 nav-items" style="display:flex;align-items:center;justify-content:space-around;"
                    id="d-sm-none">
                    <!-- /.contact-row -->
                        <a href="{{ url('/') }}">Home</a>
                        <a href="{{ url('venture/contact_us') }}">Contact</a>
                        <a href="{{ url('venture/about_us') }}">About</a>
                        <a href="{{ url('register') }}">Sing Up</a>

                </div>
                <!-- /.top-search-holder -->

                <div class="p-0 col-6 col-sm-6 col-md-6 col-lg-6 animate-dropdown top-cart-row top-search-holder" id="headcart">

                    <div class="search-area" id="d-sm-none" style="margin-top:-10px">
                        <div class="navbar">

                            <form action="{{ url('search') }}" method="GET" style="margin-top:10px">
                                <div class="control-group" style="display: flex;">
                                    <input class="m-0 search-field" name="search" placeholder="What are you looking for?"
                                        style="width:384px">
                                    <button class="search-button" type="submit"></button>
                                </div>
                            </form>
                        </div>

                    </div>

                    <div class="">
                        <a href="{{ url('/wishlist') }}" class="d-none d-lg-block" id="iconhead">
                           <i class="fa-regular fa-heart" style="font-size:21px;"></i>
                        </a>
                    </div>

                    <div class="dropdown-cart" id="d-sm-none" style="margin-left:-15px;">
                        <a href="#" class="dropdown" onclick="checkcart(this)" data-bs-toggle="dropdown"
                            id="smcarticon">
                            <div class="items-cart-inner">
                                <div class="basket" style="padding: 0;display:flex;">
                                    <span style="color: #212129;font-weight:bold;position:relative">
                                        <span style="    position: absolute;right: -13px;top: -11px;">{{ intval(Cart::count()) }}</span>
                                        <i class="fa-solid fa-cart-shopping"
                                        style="padding-top: 26px;font-size: 21px;color: #212129;"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu">
                            <li id="checkcartview">
                            </li>
                        </ul>
                        <!-- /.dropdown-menu-->
                    </div>

                    <div class="d-none d-xl-inline-block" id="d-sm-none">

                        @if (Auth::id())
                            <a href="#" type="button" onclick="openProfileNav()"
                                style="color: #212129;font-size:20px"><i class="fa-solid fa-user" color="#212129"></i></a>
                        @else
                            <a href="{{ url('login') }}" id="iconhead" style="padding-right: 16px;font-size:20px"><i
                                    class="fa-solid fa-user"></i></a>
                        @endif
                    </div>
                    <!-- /.dropdown-cart -->

                    <a type="button" class="search-button d-lg-none" onclick="showser()"
                        style="float: right;font-size: 30px; color: #b9b9b9;    margin-right: 10px;" href="#"
                        id="smsericon">
                        <!-- <img src="{{ asset('public/search.png') }}" style="width:25px"> -->
                         <i style="color:black;" class="fa-solid fa-magnifying-glass"></i>
                    </a>
                    <!-- ============================================================= SHOPPING CART DROPDOWN : END============================================================= -->
                    <input type="text" id="valcheck" value="0" hidden>

                    <div class="d-lg-none">
                        <a href="{{ url('/wishlist') }}" class="" id="iconhead">
                           <i class="fa-regular fa-heart" style="font-size:30px;"></i>
                        </a>
                    </div>

                    <div class="dropdown-cart" id="d-lg-none" style="padding-right:5px">
                        <div type="button" onclick="checkcartview()">
                            <span style="font-size: 16px;line-height: 50px;position:relative;">
                                <span style="color: #000;position: absolute;top: -32px;right: -9px;">{{ count(Cart::content()) }}</span>

                                <i class="fa-solid fa-cart-shopping"
                                            style="padding-top:8px;font-size: 25px;color: #212129;"></i>
                            </span>
                        </div>
                        <!-- /.dropdown-menu-->
                    </div>
                </div>
                <!-- /.top-cart-row -->

                <div class="mt-1 mb-1 text-left col-lg-6 col-12" id="hideser">
                    <form action="{{ url('search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="Search for products" style="border-radius: 4px 0px 0px 4px;margin:0;">
                            <div class="input-group-append">
                                <button type="submit" class="input-group-text bg-primary text-light"
                                    style="background: #212129 !important;padding: 10.7px;margin-bottom: 2px;margin-left: -10px;margin-top: -1px;border-radius: 0px 4px 4px 0px;">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container -->

    </div>


    <!-- side bar panel start -->
    <div id="mySidepanel" class="sidepanel">
        <div class="side-menu-header ">
            <div class="side-menu-close" onclick="closeNav()">
                <i class="fas fa-close"></i>
            </div>
            <div class="px-3 pb-3 side-login" style="padding-top: 12px;padding-bottom: 15px; padding-left: 10px;">
                <a href=""></a>
                <a style="font-size: 16px" href="#">Categories</a>
            </div>
        </div>
        <ul class="level1-styles collapse show" id="id0">

            <ul class="list-unstyled mb-0">
    @forelse ($categories as $category)
        @php
            $subcategories = App\Models\Subcategory::where('status','Active')
                ->where('category_id', $category->id)->get();
        @endphp

        <li class="cat-item">
            <div class="cat-row">
                <a class="cat-link" href="{{ url('products/category/' . $category->slug) }}">
                    {{ $category->category_name }}
                </a>

                @if($subcategories->isNotEmpty())
                    <button class="arrow-btn m-0" onclick="toggleSub(this)">
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                @endif
            </div>

            @if($subcategories->isNotEmpty())
                <ul class="sub-list">
                    @foreach($subcategories as $value)
                        <li>
                            <a href="{{ url('products/sub/category/' . $value->slug) }}">
                                {{ $value->sub_category_name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
        <li class="divider"></li>

    @empty
    @endforelse
</ul>



        </ul>
    </div>

    <!-- side bar panel start -->
    <div id="myProfileSidepanel" class="sidepanel">
        <div class="side-menu-header ">
            <div class="side-menu-close" onclick="clossProfileNav()">
                <i class="fas fa-close"></i>
            </div>
            <div class="px-3 pb-3 side-login" style="padding-top: 12px;padding-bottom: 15px; padding-left: 10px;">
                @if (Auth::guard('web')->check())
                    @if (Auth::guard('web')->user()->profile)
                        )
                        <img src="{{ asset(Auth::guard('web')->user()->profile) }}" alt=""
                            id="profileImage">
                    @else
                        <img src="{{ asset('public/backend/img/user.jpg') }}" alt="" id="profileImage">
                    @endif
                    <h4 class="m-0 text-left" style="color: white;font-size: 16px;text-transform: uppercase;">
                        {{ Auth::guard('web')->user()->name }}</h4>
                    <h4 class="m-0 text-left" style="color: white;font-size: 16px;">
                        {{ Auth::guard('web')->user()->email }}</h4>
                @else
                @endif


            </div>
        </div>
        <div class="py-0 widget-profile-menu">
            <ul class="categories categories--style-3">
                <li class="p-0">
                    <a href="{{ url('user/dashboard') }}" class="active">
                        <i class="fas fa-dashboard category-icon"></i>
                        <span class="category-name">
                            Dashboard
                        </span>
                    </a>
                </li>

                <li class="p-0">
                    <a href="{{ url('user/wallets') }}" class="">
                        <i class="fas fa-wallet category-icon"></i>
                        <span class="category-name">
                            Wallet </span>
                    </a>
                </li>

                <li class="p-0">
                    <a href="{{ url('user/purchase_history') }}" class="">
                        <i class="fas fa-file-text category-icon"></i>
                        <span class="category-name">
                            Orders </span>
                    </a>
                </li>

                <li class="p-0">
                    <a href="{{ url('track-order') }}" class="">
                        <i class="fas fa-file-text category-icon"></i>
                        <span class="category-name">
                            Track Order
                        </span>
                    </a>
                </li>
                <li class="p-0">
                    <a href="{{ url('user/profile') }}" class="">
                        <i class="fas fa-user category-icon"></i>
                        <span class="category-name">
                            Manage Profile
                        </span>
                    </a>
                </li>
                <li class="p-0">
                    <a href="{{ url('logout') }}" class="">
                        <i class="fas fa-comment category-icon"></i>
                        <span class="category-name">
                            Logout
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- side bar panel end -->
</header>

<!-- Search Popup Modal -->
<div class="modal fade" id="searchPopup" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 0px !important">
            <div class="modal-body" style="padding: 0px;">
                <div class="modalsearch-area">
                    <div class="control-group d-flex justify-content-between">
                        <input class="mb-0 search-field" id="modalsearchinput" onkeyup="searchproduct()"
                            placeholder="Search here...">
                        <a class="search-button" data-bs-dismiss="modal" href="#"></a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="searchproductlist" style="background: white;margin: 10px;height: auto;overflow: scroll;">

    </div>
</div>

<style>
    #profileImage {
        border-radius: 50%;
        padding: 0px;
        padding-bottom: 8px;
        padding-top: 10px;
    }

    .sidebar-widget-title {
        position: relative;
    }

    .sidebar-widget-title:before {
        content: "";
        width: 100%;
        height: 1px;
        background: #eee;
        position: absolute;
        left: 0;
        right: 0;
        top: 50%;
    }

    .py-3 {
        padding-bottom: 1rem !important;
    }

    .sidebar-widget-title span {
        background: #fff;
        text-transform: uppercase;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.2em;
        position: relative;
        padding: 8px;
        color: #dadada;
    }

    ul.categories {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    ul.categories--style-3>li {
        border: 0;
    }

    ul.categories>li {
        border-bottom: 1px solid #f1f1f1;
    }

    .widget-profile-menu a i {
        opacity: 0.6;
        font-size: 13px !important;
        top: 0 !important;
        width: 18px;
        height: 18px;
        text-align: center;
        line-height: 18px;
        display: inline-block;
        margin-right: 0.5rem !important;
    }

    .category-name {
        color: black;
        font-size: 18px;
    }

    .category-icon {
        font-size: 18px;
        color: black;
    }

    .modalsearch-area .search-field {
        border: medium none;
        padding: 10px;
        border-right: none;
        float: left;
    }

    .modalsearch-area .search-button {
        display: inline-block;
        float: left;
        margin-top: -1px;
        padding: 6px 15px 7px;
        text-align: center;
        background-color: #000000;
        border: 1px solid #000000;
    }

    .modalsearch-area .search-button:after {
        color: #fff;
        content: "\f00d";
        font-family: fontawesome;
        font-size: 24px;
        line-height: 9px;
        vertical-align: middle;
    }
    @media only screen and (min-width: 600px) {
        .pcview_margin{
            margin-bottom:-13px
        }
    }
</style>
<script>
    function showser() {
        var s = $('#valcheck').val();
        if (s == '0') {

            $('#valcheck').val('1');
            $('#hideser').css('display', 'inline');

        } else {

            $('#valcheck').val('0');
            $('#hideser').css('display', 'none');

        }
    }
</script>
<script>
    function toggleSub(btn) {
    const subList = btn.closest('.cat-item').querySelector('.sub-list');
    btn.classList.toggle('open');
    subList.classList.toggle('show');
}
</script>