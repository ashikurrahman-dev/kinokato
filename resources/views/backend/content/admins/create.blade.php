@extends('backend.master')

@section('maincontent')

    @section('title')
        {{ env('APP_NAME') }}-Create New Admin
    @endsection

<div class="container-fluid pt-4 px-4">
    <form name="form" id="CreateRole" method="POST" action="{{ route("admin.admins.store") }}" enctype="multipart/form-data">
        @csrf
        <div class="bg-secondary rounded h-100 p-4">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <h6 class="mb-4">Create New Admin</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="name" id="floatingInput" placeholder="Your name here" required>
                                <label for="floatingInput" style="color: red">Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" name="email" id="floatingInput" placeholder="name@ayebazar.com" required>
                                <label for="floatingInput" style="color: red">Email address</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="phone" id="floatingInput" placeholder="Type Phone" required>
                                <label for="floatingInput" style="color: red">Phone</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="address" id="floatingInput" placeholder="Address" required>
                                <label for="floatingInput" style="color: red">Address</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="dob" id="floatingInput" placeholder="Birthday" required>
                                <label for="floatingInput" style="color: red">Date Of Birth</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="joindate" id="floatingInput" placeholder="Join Date" required>
                                <label for="floatingInput" style="color: red">Join Date</label>
                            </div>
                            <div class="form-group mb-3">
                                 <lable>Choode Region</lable>
                                 <select name="region" class="form-control" required>
                                     <option value="Islam">Islam</option>
                                     <option value="Hinduism">Hinduism</option>
                                     <option value="Christianity">Christianity</option>
                                     <option value="Buddhism">Buddhism</option>
                                 </select>
                            </div>
                            <div class="form-group mb-3">
                                 <lable>Choode Gender</lable>
                                 <select name="gender" class="form-control" required>
                                     <option value="Male">Male</option>
                                     <option value="Female">Female</option> 
                                 </select>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="quaification" id="floatingInput" placeholder="Qualification" required>
                                <label for="floatingInput" style="color: red">Qualification</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="designation" id="floatingInput" placeholder="Designation" required>
                                <label for="floatingInput" style="color: red">Designation</label>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="floatingInput" style="color: red">Profile</label>
                                <input type="file" class="form-control" name="profile" id="floatingInput"  required> 
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="floatingInput" style="color: red">Nid</label>
                                <input type="file" class="form-control" name="nid" id="floatingInput"  required> 
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="floatingInput" style="color: red">Join Letter</label>
                                <input type="file" class="form-control" name="join_letter" id="floatingInput"  required> 
                            </div>
                            
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password" required>
                                <label for="floatingPassword" style="color: red">Password</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" onchange="checkpassword()" name="confirmpassword" id="floatingConfirmPassword" placeholder="Confirm Password" required>
                                <label for="floatingPassword" style="color: red">Confirm Password</label>
                                <label for="floatingPassword" id="checkText" style="color: red;display:none">Password does not match !</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="father_name" id="floatingInput" placeholder="Your father name here" required>
                                <label for="floatingInput" style="color: red">Father Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="father_phone" id="floatingInput" placeholder="Father phone here" required>
                                <label for="floatingInput" style="color: red">Father Phone</label>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="floatingInput" style="color: red">Father Nid</label>
                                <input type="file" class="form-control" name="father_nid" id="floatingInput"  required> 
                            </div>
                            
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="mother_name" id="floatingInput" placeholder="Your mother name here" required>
                                <label for="floatingInput" style="color: red">Mother Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="mother_phone" id="floatingInput" placeholder="Mother phone here" required>
                                <label for="floatingInput" style="color: red">Mother Phone</label>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="floatingInput" style="color: red">Mother Nid</label>
                                <input type="file" class="form-control" name="mother_nid" id="floatingInput"  required> 
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="local_guardian" id="floatingInput" placeholder="Local Guardian" required>
                                <label for="floatingInput" style="color: red">Local Guardian Name</label>
                            </div> 
                            
                            <div class="form-group mb-3">
                                <label for="floatingInput" style="color: red">Local Guardian Nid</label>
                                <input type="file" class="form-control" name="localguardian_nid" id="floatingInput"  required> 
                            </div>
                            <div class="form-group mb-3">
                                <label for="floatingInput" style="color: red">House Electricity Bill</label>
                                <input type="file" class="form-control" name="house_electricity_bill" id="floatingInput"  required> 
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="floatingInput" style="color: red">CV File</label>
                                <input type="file" class="form-control" name="cv" id="floatingInput"  required> 
                            </div>
                             
                            <select class="form-select mb-4" name="roles[]" id="role" style="font-size: 1rem;" aria-label=".form-select-lg example" multiple>
                                <option value="" style="color: red">Select Roles</option>
                                @forelse ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @empty

                                @endforelse
                            </select>

                            <div class="form-floating mb-3 mt-4 pt-4">
                                <button type="submit" class="btn btn-primary w-100 mt-3">Create Admin</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>

<script>

    function checkpassword(){
        var pass =$('#floatingPassword').val();
        var confirmpass =$('#floatingConfirmPassword').val();
        if(pass==confirmpass){

        }else{

            $('#floatingConfirmPassword').css('border','1px solid white');
        }
    }

</script>

@endsection
