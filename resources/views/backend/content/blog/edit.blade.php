@extends('backend.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}- Edit Blog
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

<div class="container pt-4 px-4">
    <div class="row">

        <div class="col-12 p-4">
            <div class="car-form p-4" style="background: #dfe5ef !important">
                <h5 class="text-uppercase mt-0 mb-3 p-2" style="text-align: center;font-size: 32px;">Edit Blog</h5>
                <br>
                <form name="form" method="POST" action="{{ url('admin/blog', $blog->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">General</h5>

                            <div class="form-group mb-3">
                                <label for="BlogName">Title <span class="text-danger">*</span></label>
                                <input type="text" value="{{ $blog->title }}" name="title" id="title" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="BlogName">Short Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="short_description" id="short_description" rows="4">{{ $blog->short_description }}</textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="BlogName">Button Name</label>
                                <input type="text" value="{{ $blog->button_name }}" name="button_name" id="button_name" class="form-control" >
                            </div>
                            <div class="form-group mb-3">
                                <label for="BlogName">Button Link</label>
                                <input type="text" value="{{ $blog->link }}" name="link" id="link" class="form-control">
                            </div>

                        </div>
                        <div class="col-lg-6">
                            <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Blog Images</h5>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="ProductDetails">Blog Image <span
                                                class="text-danger">*</span></label>
                                        <button type="button" class="btn btn-danger d-block mb-2"
                                            style="background: red">
                                            <input type="file" name="image" id="image"
                                                onchange="loadFile(event)">
                                        </button>
                                        <div class="single-image image-holder-wrapper clearfix">
                                            <div class="image-holder placeholder">
                                                <img id="prevImage" src="{{ asset($blog->image) }}"
                                                    style="height:100px;width:200px" />
                                                <i class="mdi mdi-folder-image"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="ProductDetails">Banner Image <span
                                                class="text-danger">*</span></label>
                                        <button type="button" class="btn btn-danger d-block mb-2"
                                            style="background: red">
                                            <input type="file" name="banner" id="banner"
                                                onchange="loadFileBanner(event)">
                                        </button>
                                        <div class="single-image image-holder-wrapper clearfix">
                                            <div class="image-holder placeholder">
                                                <img id="prevImageBanner" src="{{ asset($blog->banner) }}"
                                                    style="height:100px;width:200px" />
                                                <i class="mdi mdi-folder-image"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="BlogDetails">Blog Description <span class="text-danger">*</span></label>
                                <textarea class="form-control ckeditor" id="BlogDetails" name="description" rows="5">{{ $blog->description }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-2" style="text-align: right">
                        <div class="submitBtnSCourse">
                            <button type="submit" name="btn" class="w-100 btn btn-primary btn-block"
                                style="font-size: 30px;">Save Blog</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>


<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.ckeditor').ckeditor();
    });
</script>

<script>
    var loadFile = function(event) {
        var output = document.getElementById('prevImage');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };

    var loadFileBanner = function(event) {
        var output = document.getElementById('prevImageBanner');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };

    var galleryloadFile = function(event) {
        // document.getElementById("previmg").style.display = "none";
        var output = document.getElementById('galleryprevImage');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };

    var editloadFile = function(event) {
        $('#previmg').html('');
        var output = document.getElementById('editprevImage');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };
    var editgalleryloadFile = function(event) {
        // document.getElementById("previmg").style.display = "none";
        var output = document.getElementById('editgalleryprevImage');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };
</script>


<script>
    var PostImages = [];

    function prevPost_Img() {
        var PostImage = document.getElementById('PostImage').files;

        for (i = 0; i < PostImage.length; i++) {
            if (check_duplicate(PostImage[i].name)) {
                PostImages.push({
                    "name": PostImage[i].name,
                    "url": URL.createObjectURL(PostImage[i]),
                    "file": PostImage[i],
                });
            } else {
                alert(PostImage[i].name + 'is already added to your list');
            }
        }

        document.getElementById("prevFile").innerHTML = PostImage_show();

    }

    function check_duplicate(name) {
        var PostImage = true;
        if (PostImages.length > 0) {
            for (e = 0; e < PostImages.length; e++) {
                if (PostImages[e].name == name) {
                    PostImage = false;
                    break;
                }
            }
        }
        return PostImage;
    }

    function PostImage_show() {
        var PostImage = "";
        PostImages.forEach((i) => {
            PostImage += `<div class="postImg" style="width:25%;float:left;position:relative;">
                                <img src="` + i.url + `" alt="" id="previewImage" style="border-radius: 10px;width:100%;padding:5px;">
                                <span onclick="removeSelectedPostImage(` + PostImages.indexOf(i) + `)" style="position: absolute;right: 0;cursor: pointer;font-size: 31px;color: red;margin-top: -8px;margin-right: 8px;">&times</span>
                            </div>`;
        })
        return PostImage;
    }

    function removeSelectedPostImage(e) {
        PostImages.splice(e, 1);
        document.getElementById("prevFile").innerHTML = PostImage_show();
    }

    var editPostImages = [];

    function editprevPost_Img() {
        $('#viewprevFile').html('');
        var editPostImage = document.getElementById('editPostImage').files;

        for (i = 0; i < editPostImage.length; i++) {
            if (check_duplicate(editPostImage[i].name)) {
                editPostImages.push({
                    "name": editPostImage[i].name,
                    "url": URL.createObjectURL(editPostImage[i]),
                    "file": editPostImage[i],
                });
            } else {
                alert(editPostImage[i].name + 'is already added to your list');
            }
        }

        document.getElementById("editprevFile").innerHTML = editPostImage_show();

    }

    function check_duplicate(name) {
        var editPostImage = true;
        if (editPostImages.length > 0) {
            for (e = 0; e < editPostImages.length; e++) {
                if (editPostImages[e].name == name) {
                    editPostImage = false;
                    break;
                }
            }
        }
        return editPostImage;
    }

    function editPostImage_show() {
        var editPostImage = "";
        editPostImages.forEach((i) => {
            editPostImage += `<div class="postImg" style="width:25%;float:left;position:relative;">
                                <img src="` + i.url + `" alt="" id="previewImage" style="border-radius: 10px;width:100%;padding:5px;">
                                <span onclick="removeSelectededitPostImage(` + editPostImages.indexOf(i) + `)" style="position: absolute;right: 0;cursor: pointer;font-size: 31px;color: red;margin-top: -8px;margin-right: 8px;">&times</span>
                            </div>`;
        })
        return editPostImage;
    }

    function removeSelectededitPostImage(e) {
        editPostImages.splice(e, 1);
        document.getElementById("editprevFile").innerHTML = editPostImage_show();
    }
</script>
@endsection
