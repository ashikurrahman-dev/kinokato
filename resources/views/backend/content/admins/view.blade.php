@extends('backend.master')

@section('maincontent')

    @section('title')
        {{ env('APP_NAME') }}-See Profile of {{ $admin->name }}
    @endsection
 
<div class="container-fluid pt-4 px-4">
    <style>
        tr{ 
            font-size: 20px;
            font-weight: bold; 
            padding:10px;
        }
    </style>
        <div class="bg-secondary rounded h-100 p-4">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <h3 class="mb-4 text-center">Profile Of {{ $admin->name }}</h3>

                    <div class="row"> 
                        <div class="col-md-5">
                            <div class="profile text-center">
                                <img src="{{asset($admin->profile)}}" style="border-radius:50%;width:200px;">
                                <h4 class="mb-0 mt-3">{{$admin->name}}</h4> 
                                <h6 class="mb-0">{{$admin->designation}}</h6>
                            </div>
                            <br>
                            <br>
                            <div class="info">
                                <table class="">
                                    <tr>
                                        <td style="width:160px;padding: 8px;">Name</td>
                                        <td>:&nbsp; {{$admin->name}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width:160px;padding: 8px;">Email</td>
                                        <td>:&nbsp; {{$admin->email}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width:160px;padding: 8px;">Phone</td>
                                        <td>:&nbsp; {{$admin->phone}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width:160px;padding: 8px;">Father Name</td>
                                        <td>:&nbsp; {{$admin->father_name}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width:160px;padding: 8px;">Father Phone</td>
                                        <td>:&nbsp; {{$admin->father_phone}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width:160px;padding: 8px;">Mother Name</td>
                                        <td>:&nbsp; {{$admin->mother_name}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width:160px;padding: 8px;">Mother Phone</td>
                                        <td>:&nbsp; {{$admin->mother_phone}}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td style="width:160px;padding: 8px;">Address</td>
                                        <td>:&nbsp; {{$admin->address}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width:160px;padding: 8px;">Date Of Birth</td>
                                        <td>:&nbsp; {{$admin->dob}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width:160px;padding: 8px;">Join Date</td>
                                        <td>:&nbsp; {{$admin->joindate}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width:160px;padding: 8px;">Religion</td>
                                        <td>:&nbsp; {{$admin->region}}</td>
                                    </tr>
                                    <tr>
                                        <td style="width:160px;padding: 8px;">Gender</td>
                                        <td>:&nbsp; {{$admin->gender}}</td>
                                    </tr>
                                </table>
                                <div class="form-group mb-3">
                                    <label for="floatingInput" style="color: black;padding: 8px;font-weight:bold;font-size:20px;">Nid</label> 
                                    <img src="{{asset($admin->nid)}}" style="width:100%;height:160px;padding: 8px;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2"></div> 
                        <div class="col-md-5">
                            <div class="form-group mb-3">
                                <label for="floatingInput" style="color: black;padding: 8px;font-weight:bold;font-size:20px;">Father Nid</label>
                                <img src="{{asset($admin->father_nid)}}" style="width:100%;height:160px;padding: 8px;">
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="floatingInput" style="color: black;padding: 8px;font-weight:bold;font-size:20px;">Mother Nid</label> 
                                <img src="{{asset($admin->mother_nid)}}" style="width:100%;height:160px;padding: 8px;">
                            </div>
                            <div class="form-group mb-3">
                                <label for="floatingInput" style="color: black;padding: 8px;font-weight:bold;font-size:20px;">Local Guardian Nid</label> 
                                <img src="{{asset($admin->localguardian_nid)}}" style="width:100%;height:160px;padding: 8px;">
                            </div>
                            <div class="form-group mb-3">
                                <label for="floatingInput" style="color: black;padding: 8px;font-weight:bold;font-size:20px;">House Electricity Bill</label> 
                                <img src="{{asset($admin->house_electricity_bill)}}" style="width:100%;height:160px;padding: 8px;">
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="floatingInput" style="color: black;padding: 8px;font-weight:bold;font-size:20px;">CV File</label> 
                                <img src="{{asset($admin->cv)}}" style="width:100%;height:160px;padding: 8px;">
                            </div>
                             
                        </div>
                        <div class="col-md-1"></div>
                    </div>

                </div>
            </div>
        </div>
     
</div>

<script>

    function checkpassword(){
        var pass =$('#floatingPassword').val();
        var confirmpass =$('#floatingConfirmPassword').val();
        if(pass==confirmpass){
            $('#floatingConfirmPassword').css('border','none');
        }else{

            $('#floatingConfirmPassword').css('border','1px solid white');
        }
    }

</script>

@endsection
