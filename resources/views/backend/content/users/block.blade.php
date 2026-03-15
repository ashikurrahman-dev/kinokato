@extends('backend.master')

@section('maincontent')
    @section('title')
        {{ env('APP_NAME') }}- Users
    @endsection
<style>
    div#roleinfo_length {
        color: red;
    }
    div#roleinfo_filter {
        color: red;
    }
    div#roleinfo_info {
        color: red;
    }
</style>

<div class="container-fluid pt-4 px-4">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="h-100 bg-secondary rounded p-4 pb-0">
                <div class="d-flex"  style="width: 100%;float:left;">
                    <h6 class="mb-0">Block Users List</h6>
                </div> 
                
                <form action="{{url('admin/block-now')}}" method="POST">
                    @csrf
                    <div class="d-flex"  style="width: 50%;float:right;">
                        <input type="text" name="number" id="number" class="form-control" style="width:200px">
                        <button class="btn btn-warning">Submit </button>
                    </div> 
                </form>
                
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="bg-secondary rounded h-100 p-4">
                <div class="data-tables">
                    <table class="table table-dark" id="roleinfo" width="100%"  style="text-align: center;">
                        <thead class="thead-light">
                            <tr>
                                <th>SL</th>
                                <th>User</th>
                                <th>Phone</th>  
                                <th>Status</th>  
                            </tr>
                        </thead>
                        <tbody>
                            @forelse (App\Models\User::where('status','Block')->get() as $user)
                                <tr class="">
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td> 
                                    <td>{{ $user->status }} <a class="btn btn-danger btn-sm" href="{{url('admin/unblock',$user->id)}}">Unblock</a></td>  
                                </tr>
                            @empty

                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
</div>

 

@endsection
