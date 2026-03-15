@php
$admin = App\Models\Admin::where('id', Auth::guard('admin')->user()->id)->first();
@endphp
<div class="pb-3 sidebar" style="background: #d7dceb !important">
    <nav class="navbar bg-secondary navbar-dark">
        <a href="{{ url('/admin/dashboard') }}" class="mx-4 mb-3 navbar-brand">
            <h3 class="text-primary"><img src="{{asset(\App\Models\Basicinfo::first()->logo)}}" alt="logo" style="width:100%"></h3>
        </a>

        <div class="navbar-nav w-100">
            <a href="{{ url('/admin/dashboard') }}" class="nav-item nav-link active"><i
                    class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
            @if ($admin->hasRole('superadmin') || $admin->hasRole('manager') || $admin->hasRole('admin'))
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Admins</a>
                    <div class="bg-transparent border-0 dropdown-menu">

                        @if ($admin->hasRole('superadmin') || $admin->hasRole('admin'))
                        <a href="{{ route('admin.admins.index') }}" class="dropdown-item">Admins</a>
                        @endif
                        <a href="{{ url('admin/block-user') }}" class="dropdown-item">Block Ip</a>
                    </div>
                </div>

                @if ($admin->hasRole('manager') || $admin->hasRole('superadmin'))
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                            class="fa fa-laptop me-2"></i>Accounts</a>
                    <div class="bg-transparent border-0 dropdown-menu">
                        <a href="{{url('admin/account-deposit/Courier')}}" class="dropdown-item">Courier Payment</a>
                        <a href="{{url('admin/account-deposit/Office Sale')}}" class="dropdown-item">Office Sale Payment</a>
                        <a href="{{url('admin/account-deposit/Wholesale')}}" class="dropdown-item">Wholesale Payment</a>
                        <a href="{{url('admin/account-deposit/Total')}}" class="dropdown-item">Total Payment</a>
                        <a href="{{url('admin/expense-cost/Boost Cost')}}" class="dropdown-item">Boost Cost</a>
                        <a href="{{url('admin/expense-cost/Office Cost')}}" class="dropdown-item">Office Cost</a>
                        <a href="{{url('admin/expense-cost/Bank Deposit')}}" class="dropdown-item">Bank Deposit</a>
                        <a href="{{url('admin/expense-cost/Total Cost')}}" class="dropdown-item">Total Cost</a>
                    </div>
                </div>
                @endif

                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Store</a>
                    <div class="bg-transparent border-0 dropdown-menu">
                        <a href="{{ route('admin.categorys.index') }}" class="dropdown-item">Category</a>
                        <a href="{{ route('admin.subcategorys.index') }}" class="dropdown-item">Sub Category</a>
                        <a href="{{ route('admin.attrvalues.index') }}" class="dropdown-item">Size & Sigment</a>
                        <a href="{{ route('admin.products.index') }}" class="dropdown-item">Single Products</a>
                        <a href="{{ route('mainproducts.index') }}" class="dropdown-item">Varient Products</a>
                        <a href="{{ route('purchases.index') }}" class="dropdown-item">Purchase</a>
                        <a href="{{ route('returns.index') }}" class="dropdown-item">Return</a>
                        <a href="{{ url('admin/stock/overview') }}" class="dropdown-item">Inventory</a>
                        <a href="{{ route('orderchange.bybarcode') }}" class="dropdown-item">Auto Shipment</a>
                        <a href="{{ route('orderchange.manualbarcode') }}" class="dropdown-item">Manual Shipment</a>
                        <a href="{{ route('orderchange.autoreturn') }}" class="dropdown-item">Auto Return</a>
                        <a href="{{ route('orderchange.manualreturn') }}" class="dropdown-item">Manual Return</a>
                        <!--<a href="{{ route('stocks.index') }}" class="dropdown-item">Stock</a>-->
                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>Orders</a>
                    <div class="bg-transparent border-0 dropdown-menu">
                        <a href="{{ url('admin/create/order') }}" class="dropdown-item">Create Order</a>
                        <a href="{{ url('admin_order/Pending') }}" class="dropdown-item">Orders</a>
                        <a href="{{ url('incomplete_order/Incomplete') }}" class="dropdown-item">Incomplete</a>
                        <a href="{{ url('admin/maps') }}" class="dropdown-item">Maps</a>
                        <a href="{{ url('complain/Pending') }}" class="dropdown-item">Complane Box</a>
                    </div>
                </div>


                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                            class="fa fa-laptop me-2"></i>Wholesale</a>
                    <div class="bg-transparent border-0 dropdown-menu">
                        <a href="{{ route('wsales.index') }}" class="dropdown-item">W-sale</a>
                        <a href="{{ route('wcustomers.index') }}" class="dropdown-item">W-customer</a>
                        <a href="{{ route('wsalestocks.index') }}" class="dropdown-item">W-sale Stock</a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                            class="far fa-file-alt me-2"></i>Pages</a>
                    <div class="bg-transparent border-0 dropdown-menu">
                        <a href="{{ route('admin.blogs.index') }}" class="dropdown-item">Blog</a>
                        <a href="{{ route('admin.sliders.index') }}" class="dropdown-item">Sliders</a>
                        <a href="{{ route('admin.addbanners.index') }}" class="dropdown-item">Adds</a>
                        <a href="{{ route('admin.menus.index') }}" class="dropdown-item">Youtube Gallery</a>
                        <a href="{{ url('admin/information/about_us') }}" class="dropdown-item">About Us</a>
                        <a href="{{ url('admin/information/contact_us') }}" class="dropdown-item">Contact Us</a>
                        <a href="{{ url('admin/information/faq') }}" class="dropdown-item">FAQ</a>
                        <a href="{{ url('admin/information/news') }}" class="dropdown-item">Our News</a>
                        <a href="{{ url('admin/information/terms_codition') }}" class="dropdown-item">Terms Conditions</a>
                        <a href="{{ url('admin/information/privacy_policy') }}" class="dropdown-item">Privacy Policy</a>
                        <a href="{{ url('admin/information/refund_return_policy') }}" class="dropdown-item">Refund & Return Policy</a>
                        <a href="{{ url('admin/information/shipping_policy') }}" class="dropdown-item">Shipping Policy</a>
                        <a href="{{ url('admin/information/payment_policy') }}" class="dropdown-item">Payment Policy</a>
                        <a href="{{ url('admin/information/contact_info') }}" class="dropdown-item">Contact Information</a>

                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                            class="fas fa-cog fa-spin me-2"></i>Settings</a>
                    <div class="bg-transparent border-0 dropdown-menu">
                        <a href="{{ route('admin.basicinfos.index') }}" class="dropdown-item">Settings</a>
                        <a href="{{ route('couriers.index') }}" class="dropdown-item">Courier</a>
                        <a href="{{ route('cities.index') }}" class="dropdown-item">City</a>
                        <a href="{{ route('zones.index') }}" class="dropdown-item">Zone</a>
                        {{-- <a href="{{ route('areas.index') }}" class="dropdown-item">Areas</a> --}}
                        <a href="{{ route('payments.index') }}" class="dropdown-item">Payment</a>
                        <a href="{{ route('paymenttypes.index') }}" class="dropdown-item">Payment Method</a>
                        <a href="{{ route('admin.coupons.index') }}" class="dropdown-item">Coupons</a>
                        <a href="{{ route('admin.reviews.index') }}" class="dropdown-item">Reviews</a>
                    </div>
                </div>

                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                            class="far fa-file-alt me-2"></i>Report</a>
                    <div class="bg-transparent border-0 dropdown-menu">
                        <a href="{{ route('courieruserreport') }}" class="dropdown-item">Salse Report</a>
                        <a href="{{ route('courierreport') }}" class="dropdown-item">Courier Report</a>
                        <a href="{{ route('userreport') }}" class="dropdown-item">User Report</a>
                        <a href="{{ route('productreport') }}" class="dropdown-item">Product</a>
                        <a href="{{ url('admin/download/orderinfo') }}" class="dropdown-item">Download Order</a>
                    </div>
                </div>
            @endif

            @if ($admin->hasRole('user'))
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                        class="far fa-file-alt me-2"></i>Orders</a>
                <div class="bg-transparent border-0 dropdown-menu">
                    <a href="{{ url('admin/create/order') }}" class="dropdown-item">Create Order</a>
                    <a href="{{ url('admin_order/Pending') }}" class="dropdown-item">Orders</a>
                    <a href="{{ url('incomplete_order/Incomplete') }}" class="dropdown-item">Incomplete</a>
                    <a href="{{ url('admin/maps') }}" class="dropdown-item">Maps</a>
                    <a href="{{ url('complain/Pending') }}" class="dropdown-item">Complane Box</a>
                </div>
            </div>
            @endif

            @if ($admin->hasRole('accounts'))
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                        class="fa fa-laptop me-2"></i>Accounts</a>
                <div class="bg-transparent border-0 dropdown-menu">
                    <a href="{{url('admin/account-deposit/Courier')}}" class="dropdown-item">Courier Payment</a>
                    <a href="{{url('admin/account-deposit/Office Sale')}}" class="dropdown-item">Office Sale Payment</a>
                    <a href="{{url('admin/account-deposit/Wholesale')}}" class="dropdown-item">Wholesale Payment</a>
                    <a href="{{url('admin/account-deposit/Total')}}" class="dropdown-item">Total Payment</a>
                    <a href="{{url('admin/expense-cost/Boost Cost')}}" class="dropdown-item">Boost Cost</a>
                    <a href="{{url('admin/expense-cost/Office Cost')}}" class="dropdown-item">Office Cost</a>
                    <a href="{{url('admin/expense-cost/Bank Deposit')}}" class="dropdown-item">Bank Deposit</a>
                    <a href="{{url('admin/expense-cost/Total Cost')}}" class="dropdown-item">Total Cost</a>
                </div>
            </div>
             @endif
            @if ($admin->hasRole('accounts') || $admin->hasRole('store'))
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Store</a>
                <div class="bg-transparent border-0 dropdown-menu">
                    <a href="{{ route('admin.categorys.index') }}" class="dropdown-item">Category</a>
                    <a href="{{ route('admin.subcategorys.index') }}" class="dropdown-item">Sub Category</a>
                    <a href="{{ route('admin.attrvalues.index') }}" class="dropdown-item">Size & Sigment</a>
                    <a href="{{ route('admin.products.index') }}" class="dropdown-item">Single Products</a>
                    <a href="{{ route('mainproducts.index') }}" class="dropdown-item">Varient Products</a>
                    <a href="{{ route('purchases.index') }}" class="dropdown-item">Purchase</a>
                    <a href="{{ route('returns.index') }}" class="dropdown-item">Return</a>
                    <a href="{{ url('admin/stock/overview') }}" class="dropdown-item">Inventory</a>
                    <a href="{{ route('orderchange.bybarcode') }}" class="dropdown-item">Auto Shipment</a>
                    <a href="{{ route('orderchange.manualbarcode') }}" class="dropdown-item">Manual Shipment</a>
                    <a href="{{ route('orderchange.autoreturn') }}" class="dropdown-item">Auto Return</a>
                    <a href="{{ route('orderchange.manualreturn') }}" class="dropdown-item">Manual Return</a>
                    <!--<a href="{{ route('stocks.index') }}" class="dropdown-item">Stock</a>-->
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                        class="far fa-file-alt me-2"></i>Orders</a>
                <div class="bg-transparent border-0 dropdown-menu">
                    <a href="{{ url('admin/create/order') }}" class="dropdown-item">Create Order</a>
                    <a href="{{ url('admin_order/Pending') }}" class="dropdown-item">Orders</a>
                    <a href="{{ url('incomplete_order/Incomplete') }}" class="dropdown-item">Incomplete</a>
                    <a href="{{ url('admin/maps') }}" class="dropdown-item">Maps</a>
                    <a href="{{ url('complain/Pending') }}" class="dropdown-item">Complane Box</a>
                    <a href="{{ url('admin/block-user') }}" class="dropdown-item">Block Ip</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                        class="fa fa-laptop me-2"></i>Wholesale</a>
                <div class="bg-transparent border-0 dropdown-menu">
                    <a href="{{ route('wsales.index') }}" class="dropdown-item">W-sale</a>
                    <a href="{{ route('wcustomers.index') }}" class="dropdown-item">W-customer</a>
                    <a href="{{ route('wsalestocks.index') }}" class="dropdown-item">W-sale Stock</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                        class="far fa-file-alt me-2"></i>Report</a>
                <div class="bg-transparent border-0 dropdown-menu">
                    <a href="{{ route('courieruserreport') }}" class="dropdown-item">Salse Report</a>
                    <a href="{{ route('courierreport') }}" class="dropdown-item">Courier Report</a>
                    <a href="{{ route('userreport') }}" class="dropdown-item">User Report</a>
                    <a href="{{ route('productreport') }}" class="dropdown-item">Product</a>
                    <a href="{{ url('admin/download/orderinfo') }}" class="dropdown-item">Download Order</a>
                </div>
            </div>
            @endif

            @if ($admin->hasRole('support'))
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                        class="far fa-file-alt me-2"></i>Orders</a>
                <div class="bg-transparent border-0 dropdown-menu">
                    <a href="{{ url('admin/create/order') }}" class="dropdown-item">Create Order</a>
                    <a href="{{ url('admin_order/Pending') }}" class="dropdown-item">Orders</a>
                    <a href="{{ url('incomplete_order/Incomplete') }}" class="dropdown-item">Incomplete</a>
                    <a href="{{ url('admin/maps') }}" class="dropdown-item">Maps</a>
                    <a href="{{ url('complain/Pending') }}" class="dropdown-item">Complane Box</a>
                    <a href="{{ url('admin/block-user') }}" class="dropdown-item">Block Ip</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                        class="far fa-file-alt me-2"></i>Report</a>
                <div class="bg-transparent border-0 dropdown-menu">
                    <a href="{{ route('courieruserreport') }}" class="dropdown-item">Salse Report</a>
                    <a href="{{ route('courierreport') }}" class="dropdown-item">Courier Report</a>
                    <a href="{{ route('userreport') }}" class="dropdown-item">User Report</a>
                    <a href="{{ route('productreport') }}" class="dropdown-item">Product</a>
                    <a href="{{ url('admin/download/orderinfo') }}" class="dropdown-item">Download Order</a>
                </div>
            </div>
            @endif
            @if ($admin->hasRole('storeassistant'))
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Store</a>
                <div class="bg-transparent border-0 dropdown-menu">
                    <a href="{{ route('orderchange.bybarcode') }}" class="dropdown-item">Auto Shipment</a>
                    <a href="{{ route('orderchange.manualbarcode') }}" class="dropdown-item">Manual Shipment</a>
                    <a href="{{ route('orderchange.autoreturn') }}" class="dropdown-item">Auto Return</a>
                    <a href="{{ route('orderchange.manualreturn') }}" class="dropdown-item">Manual Return</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                        class="far fa-file-alt me-2"></i>Orders</a>
                <div class="bg-transparent border-0 dropdown-menu">
                    <a href="{{ url('admin/create/order') }}" class="dropdown-item">Create Order</a>
                    <a href="{{ url('admin_order/Pending') }}" class="dropdown-item">Orders</a>
                    <a href="{{ url('incomplete_order/Incomplete') }}" class="dropdown-item">Incomplete</a>
                    <a href="{{ url('admin/maps') }}" class="dropdown-item">Maps</a>
                    <a href="{{ url('complain/Pending') }}" class="dropdown-item">Complane Box</a>
                    <a href="{{ url('admin/block-user') }}" class="dropdown-item">Block Ip</a>
                </div>
            </div>

            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                        class="far fa-file-alt me-2"></i>Report</a>
                <div class="bg-transparent border-0 dropdown-menu">
                    <a href="{{ route('courieruserreport') }}" class="dropdown-item">Salse Report</a>
                    <a href="{{ route('courierreport') }}" class="dropdown-item">Courier Report</a>
                    <a href="{{ route('userreport') }}" class="dropdown-item">User Report</a>
                    <a href="{{ route('productreport') }}" class="dropdown-item">Product</a>
                    <a href="{{ url('admin/download/orderinfo') }}" class="dropdown-item">Download Order</a>
                </div>
            </div>
            @endif
        </div>
    </nav>
</div>


