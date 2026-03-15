@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-Blogs
@endsection
<style>
    #checked {
        color: orange;
    }
    .star{
        font-size: 8px !important;
    }
</style>

<!-- /.breadcrumb -->
<div class="body-content outer-top-xs">
    <div class="pt-2 breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="p-0 breadcrumb-inner">
                        <ul class="mb-0 list-inline list-unstyled">
                            <li><a href="#"
                                    style="text-transform: capitalize !important;color: #888;padding-right: 12px;font-size: 12px;">Home > Multimedia
                                </a></li>
                        </ul>
                    </div>
                </div>
                <!-- /.breadcrumb-inner -->
            </div>
        </div>
        <!-- /.container -->
    </div>
    <div class='container'>
        <div class='row'>
            <!-- /.sidebar -->

            <div class='col-md-12'>
                <div class="container p-0">

                    <div class="pt-2 pb-2 row" style="background: white;">
                        <div class="px-2 pt-0 p-md-3" style="padding-bottom:4px !important;padding-top: 8px !important;">
                            <h4 class="m-0" style="text-align: center;padding-bottom: 12px;font-size: 30px;"><b> Multimedia</b></h4>
                        </div>
                        @forelse ($medias as $media)
                            <div class="mb-4 col-6 col-md-3 col-lg-3">
                                <iframe width="100%"
                                    src="https://www.youtube.com/embed/{{ $media->menu_banner }}">
                                </iframe>
                            </div>
                        @empty
                            <h2 class="p-4 text-center"><b>No media found...</b></h2>
                        @endforelse
                    </div>

                </div>

            </div>
            <!-- /.col -->
        </div>

        <!-- ============================================== BRANDS CAROUSEL : END ============================================== -->
    </div>
    <!-- /.container -->

</div>




<style>
    @media only screen and (max-width: 768px) {
        #cateoryProSidebar {
            padding-right: 0;
        }

        #cateoryPro {
            padding-left: 0;
        }
    }

    #cateoryProSidebar {
        padding-left: 0;
    }

    #cateoryPro {
        padding-right: 0px;
    }

    .sidebar-widget .sidebar-widget-body .accordion .accordion-group .accordion-heading .accordion-toggle.collapsed:after {
        color: #636363;
        content: "\f067";
        font-family: fontawesome;
        font-weight: normal;
    }

    .sidebar .sidebar-module-container .sidebar-widget .sidebar-widget-body .accordion .accordion-group .accordion-heading .accordion-toggle:after {
        content: "\f068";
        float: right;
        font-family: fontawesome;
    }

    .widget-title {
        font-size: 16px;
        text-align: center;
        border-bottom: 1px solid #e9e9e9;
        padding-bottom: 10px;
        margin: 0;
    }

    .list {
        list-style: none;
    }

    #liaside {
        color: #858585;
        font-weight: bold;
    }

    .breadcrumb {
        padding: 5px 0;
        border-bottom: 1px solid #e9e9e9;
        background-color: #fafafa;
    }
</style>

@endsection
