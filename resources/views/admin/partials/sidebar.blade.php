<div class="sidebar pb-3" style="background: #d7dceb !important">
    <nav class="navbar bg-secondary navbar-dark">
        <a href="{{ url('/admin/dashboard') }}" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary"><img src="{{asset(\App\Models\Basicinfo::first()->logo)}}" alt="logo" style="width:100%"></h3>
        </a>

        <div class="navbar-nav w-100">
            <a href="{{ url('/admin/dashboard') }}" class="nav-item nav-link active"><i
                    class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
 
            <a href="{{ url('admin_order/Pending') }}" class="nav-item nav-link"><i
            class="fa fa-keyboard me-2"></i>Orders</a>

            

            <a href="{{ url('complain/Pending') }}" class="nav-item nav-link"><i
            class="fa fa-keyboard me-2"></i>Complain Box</a>
             

            
        </div>
    </nav>
</div>


