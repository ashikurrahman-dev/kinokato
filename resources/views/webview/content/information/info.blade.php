@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-{{ $title }}
@endsection

<div class="body-content outer-top-xs">
    <div class="breadcrumb pt-2">
        <div class="container">
            <div class="row">
                <div class="breadcrumb-inner p-0">
                    <ul class="list-inline list-unstyled mb-0">
                        <li><a href="#"
                                style="text-transform: capitalize !important;color: #888;padding-right: 12px;font-size: 12px;">Home
                                > {{ $title }}
                            </a></li>
                    </ul>
                </div>
                <!-- /.breadcrumb-inner -->
            </div>
        </div>
        <!-- /.container -->
    </div>
</div>

<div class="container">
    <div class="row mt-4">
        <div class="col-12 p-0">
            <div class="body-content outer-top-xs p-2" style="background: white !important;">
                @if (request()->segment(count(request()->segments())) == 'contact_us')
                    @php
                        $basicinfo = App\Models\Basicinfo::first();
                    @endphp

                    <div class="body-content">
                        <div class="container">
                            <div class="contact-page">
                                <div class="row">
                                    <div class="col-12 contact-map outer-bottom-vs">
                                        <!--<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.697236412805!2d90.37145777326648!3d23.793793378640807!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c142ca33438f%3A0xfd0600bb9d7193ae!2sRashi%20Fashion!5e0!3m2!1sen!2sbd!4v1721228725694!5m2!1sen!2sbd" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>-->
                                    </div>
                                    <div class="col-md-12 contact-info">
                                        <div class="contact-title">
                                            <h4>Information</h4>
                                        </div>

                                        <div class="address clearfix">{{ $basicinfo->address }}
                                        </div>
                                        <br>

                                        <div class="clearfix phone-no">+(88) {{ $basicinfo->phone_one }}<br> +(88)
                                            {{ $basicinfo->phone_two }}</div>

                                        <div class="clearfix email"><a
                                                href="mailto:{{ $basicinfo->email }}">{{ $basicinfo->email }}</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.contact-page -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                @else
                    {!! $value->value !!}
                @endif
            </div>
        </div>
    </div>
</div>
</div>

<style>
    .breadcrumb {
        padding: 5px 0;
        border-bottom: 1px solid #e9e9e9;
        background-color: #fafafa;
    }
</style>
@endsection
