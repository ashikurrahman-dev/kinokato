@extends('backend.master')

@section('maincontent')
    @section('title')
        {{ env('APP_NAME') }}- Information {{ $title }}
    @endsection

    @if($slug == 'about_us')
        <div class="px-4 pt-4 container-fluid">
            <div class="row">
                <div class="mb-4 col-sm-12 col-md-12 col-xl-12">
                    <div class="p-4 rounded bg-secondary h-100">

                        <h2 class="mb-4" style="text-align: center;color:red">{{ $title }} Page Info</h2>
                        <form action="{{ url('/admin/information/update', $slug) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="key" value="{{ $slug }}" hidden>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="" class="form-label">
                                        <h6>Story Text</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <textarea class="form-control ckeditor" name="value" id="value"
                                            style="height: 150px;">{{ $value->value }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <label for="" class="form-label">
                                        <h6>About Image</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="file" class="form-control" name="about_img">
                                    </div>
                                    <div class="mt-2">
                                        <img src="{{ asset($value->about_img) }}" alt="" width="70">
                                    </div>
                                </div>

                                <div class="p-2 m-2 text-center text-white col-sm-12 bg-success">
                                    <span class="text-center">Sale Info </span>
                                </div>

                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Sale One Image</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="file" class="form-control" name="sale1_img">
                                    </div>
                                    <div class="mt-2">
                                        <img src="{{ asset($value->sale1_img) }}" alt="" width="70">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Sale One Amount</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->sale1_amount }}"
                                            name="sale1_amount">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Sale One Title</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->sale1_title }}"
                                            name="sale1_title">
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Sale Two Image</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="file" class="form-control" name="sale2_img">
                                    </div>
                                    <div class="mt-2">
                                        <img src="{{ asset($value->sale2_img) }}" alt="" width="70">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Sale Two Amount</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->sale2_amount }}"
                                            name="sale2_amount">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Sale Two Title</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->sale2_title }}"
                                            name="sale2_title">
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Sale Three Image</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="file" class="form-control" name="sale3_img">
                                    </div>
                                    <div class="mt-2">
                                        <img src="{{ asset($value->sale3_img) }}" alt="" width="70">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Sale Three Amount</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->sale3_amount }}"
                                            name="sale3_amount">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Sale Three Title</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->sale3_title }}"
                                            name="sale3_title">
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Sale Four Image</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="file" class="form-control" name="sale4_img">
                                    </div>
                                    <div class="mt-2">
                                        <img src="{{ asset($value->sale4_img) }}" alt="" width="70">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Sale Four Amount</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->sale4_amount }}"
                                            name="sale4_amount">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Sale Four Title</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->sale4_title }}"
                                            name="sale4_title">
                                    </div>
                                </div>

                                <div class="p-2 m-2 text-center text-white col-sm-12 bg-success">
                                    <span class="text-center">Member One Info </span>
                                </div>

                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Image</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="file" class="form-control" name="member1_img">
                                    </div>
                                    <div class="mt-2">
                                        <img src="{{ asset($value->member1_img) }}" alt="" width="70">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Name</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->member1_name }}"
                                            name="member1_name">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Designation</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->member1_designation }}"
                                            name="member1_designation">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Twitter</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->member1_twitter }}"
                                            name="member1_twitter">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Instagram</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->member1_instagram }}"
                                            name="member1_instagram">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Linkedin</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->member1_linkedin }}"
                                            name="member1_linkedin">
                                    </div>
                                </div>

                                <div class="p-2 m-2 text-center text-white col-sm-12 bg-success">
                                    <span class="text-center">Member Two Info </span>
                                </div>

                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Image</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="file" class="form-control" name="member2_img">
                                    </div>
                                    <div class="mt-2">
                                        <img src="{{ asset($value->member2_img) }}" alt="" width="70">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Name</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->member2_name }}"
                                            name="member2_name">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Designation</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->member2_designation }}"
                                            name="member2_designation">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Twitter</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->member2_twitter }}"
                                            name="member2_twitter">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Instagram</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->member2_instagram }}"
                                            name="member2_instagram">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Linkedin</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->member2_linkedin }}"
                                            name="member2_linkedin">
                                    </div>
                                </div>

                                <div class="p-2 m-2 text-center text-white col-sm-12 bg-success">
                                    <span class="text-center">Member Three Info </span>
                                </div>

                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Image</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="file" class="form-control" name="member3_img">
                                    </div>
                                    <div class="mt-2">
                                        <img src="{{ asset($value->member3_img) }}" alt="" width="70">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Name</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->member3_name }}"
                                            name="member3_name">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Designation</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->member3_designation }}"
                                            name="member3_designation">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Twitter</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->member3_twitter }}"
                                            name="member3_twitter">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Instagram</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->member3_instagram }}"
                                            name="member3_instagram">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="" class="form-label">
                                        <h6>Member Linkedin</h6>
                                    </label>
                                    <div class="mb-3 form-floating">
                                        <input type="text" class="form-control" value="{{ $value->member3_linkedin }}"
                                            name="member3_linkedin">
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    @else
        <div class="px-4 pt-4 container-fluid">
            <div class="row">

                <div class="mb-4 col-sm-12 col-md-12 col-xl-12">
                    <div class="p-4 rounded bg-secondary h-100">

                        <h2 class="mb-4" style="text-align: center;color:red">{{ $title }} Page Info</h2>
                        <form action="{{ url('/admin/information/update', $slug) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="key" value="{{ $slug }}" hidden>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="mb-3 form-floating">
                                        <textarea class="form-control ckeditor" name="value" id="value"
                                            style="height: 150px;">{{ $value->value }}</textarea>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    @endif

    <script type="text/javascript">
        initSample();
        $(document).ready(function () {
            $('.ckeditor').ckeditor();
        });
    </script>

@endsection
