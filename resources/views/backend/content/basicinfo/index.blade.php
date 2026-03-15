@extends('backend.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}- Basicinfo
@endsection

<div class="px-4 pt-4 container-fluid">
    <div class="row">

        <div class="mb-4 col-sm-12 col-md-12 col-xl-12">
            <div class="p-4 rounded bg-secondary h-100">
                <h2 class="mb-4" style="text-align: center;color:red">Website Basic Information Update</h2>
                <form action="{{ route('admin.basicinfos.update', $webinfo->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3 form-floating">
                                <input type="email" class="form-control" name="email" value="{{ $webinfo->email }}"
                                    id="floatingInput" placeholder="name@example.com">
                                <label for="floatingInput">Email address</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="phone_one"
                                    value="{{ $webinfo->phone_one }}" id="floatingPassword" placeholder="Phone One">
                                <label for="floatingPassword">Phone One</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="phone_two"
                                    value="{{ $webinfo->phone_two }}" id="floatingPassword" placeholder="Phone Two">
                                <label for="floatingPassword">Phone Two</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="wp_1"
                                    value="{{ $webinfo->wp_1 }}" id="floatingPassword" placeholder="Whats App One">
                                <label for="floatingPassword">Whats App One</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="wp_2"
                                    value="{{ $webinfo->wp_2 }}" id="floatingPassword" placeholder="Whats App Two">
                                <label for="floatingPassword">Whats App Two</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="messanger"
                                    value="{{ $webinfo->messanger }}" id="floatingPassword" placeholder="Messanger Link">
                                <label for="floatingPassword">Messanger Link</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="imo"
                                    value="{{ $webinfo->imo }}" id="floatingPassword" placeholder="Imo">
                                <label for="floatingPassword">Imo</label>
                            </div>

                            <div class="mb-3 form-floating">
                                <textarea class="form-control" placeholder="Office Address" name="address" id="floatingTextarea" style="height: 100px;">{{ $webinfo->address }}</textarea>
                                <label for="floatingTextarea">Office Address</label>
                            </div>
                             <div class="mb-3">
                                <input class="form-control form-control-lg bg-dark" name="favicon" id="favicon"
                                    type="file">
                            </div>
                            <div class="m-3 ms-0" style="text-align: center;height: 85px;margin-top:50px !important">
                                <h4 style="width:30%;float: left;text-align: left;">FAV ICON : </h4>
                                <img src="{{ asset($webinfo->favicon) }}" alt="" srcset=""
                                    style="max-height: 100px;">
                            </div>
                             <div class="mb-3">
                                <input class="form-control form-control-lg bg-dark" name="page_image" id="page_image"
                                    type="file">
                            </div>
                            <div class="m-3 ms-0" style="text-align: center;height: 85px;margin-top:50px !important">
                                <h4 style="width:30%;float: left;text-align: left;">Page Image : </h4>
                                <img src="{{ asset($webinfo->page_image) }}" alt="" srcset=""
                                    style="max-height: 100px;">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="bk"
                                    value="{{ $webinfo->bk }}" id="floatingPassword" placeholder="Bkash Account">
                                <label for="floatingPassword">Bkash Account</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="ng"
                                    value="{{ $webinfo->ng }}" id="floatingPassword" placeholder="Nagad Account">
                                <label for="floatingPassword">Nagad Account</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="dbbl"
                                    value="{{ $webinfo->dbbl }}" id="floatingPassword" placeholder="DBBL Account">
                                <label for="floatingPassword">DBBL Account</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="usd_rate"
                                    value="{{ $webinfo->usd_rate }}" id="floatingPassword" placeholder="Phone Two">
                                <label for="floatingPassword">1 USD TO BDT</label>
                            </div>
                            <div class="mb-3">
                                <input class="form-control form-control-lg bg-dark" name="logo" id="formFileLg"
                                    type="file">
                            </div>
                            <div class="m-3 ms-0" style="text-align: center;height: 85px;margin-top:50px !important">
                                <h4 style="width:30%;float: left;text-align: left;">LOGO : </h4>
                                <img src="{{ asset($webinfo->logo) }}" alt="" srcset=""
                                    style="max-height: 100px;">
                            </div>
                            <div class="mb-3">
                                <lable>Vat Status</lable>
                                <select name="vat_status" id="vat_status" required
                                    class="mb-3 form-select form-select-lg" aria-label=".form-select-lg example">
                                    @if ($webinfo->vat_status == 'On')
                                        <option value="On" selected>On</option>
                                        <option value="Off">Off</option>
                                    @else
                                        <option value="On">On</option>
                                        <option value="Off" selected>Off</option>
                                    @endif

                                </select>
                            </div>
                            <div class="mb-3">
                                <lable>Choose Courier</lable>
                                <select name="courier" id="courier" required
                                    class="mb-3 form-select form-select-lg" aria-label=".form-select-lg example">
                                    @if ($webinfo->courier == '1')
                                        <option value="1" selected>{{ env('APP_NAME') }} Courier</option>
                                        {{-- <option value="2">Rashi World Courier</option> --}}
                                    @else
                                        <option value="1">{{ env('APP_NAME') }} Courier</option>
                                        {{-- <option value="2" selected>Rashi World Courier</option> --}}
                                    @endif

                                </select>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="vat"
                                    value="{{ $webinfo->vat }}" id="floatingPassword" placeholder="Vat">
                                <label for="floatingPassword">Vat (%)</label>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary btn-lg w-100">Update</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        <div class="mb-4 col-sm-12 col-md-12 col-xl-12">
            <div class="p-4 rounded bg-secondary h-100">
                <h2 class="mb-4" style="text-align: center;color:red">Shipping Information Update</h2>
                <form action="{{ route('admin.shipping.update', $webinfo->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="inside_dhaka_charge"
                                    value="{{ $webinfo->inside_dhaka_charge }}" id="inside_dhaka_charge"
                                    placeholder="Inside Dhaka Charge">
                                <label for="floatingInput">Inside Dhaka Charge</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="outside_dhaka_charge"
                                    value="{{ $webinfo->outside_dhaka_charge }}" id="outside_dhaka_charge"
                                    placeholder="Outside Dhaka Charge">
                                <label for="floatingPassword">Outside Dhaka Charge</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="sub_dhaka_charge"
                                    value="{{ $webinfo->sub_dhaka_charge }}" id="sub_dhaka_charge"
                                    placeholder="Sub Dhaka Charge">
                                <label for="floatingPassword">Sub Dhaka Charge</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="insie_dhaka"
                                    value="{{ $webinfo->insie_dhaka }}" id="insie_dhaka"
                                    placeholder="Inside Dhaka Delivery Time">
                                <label for="floatingPassword">Inside Dhaka Delivery Time</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="outside_dhaka"
                                    value="{{ $webinfo->outside_dhaka }}" id="outside_dhaka"
                                    placeholder="Outside Dhaka Delivery Time">
                                <label for="floatingPassword">Outside Dhaka Delivery Time</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="contact"
                                    value="{{ $webinfo->contact }}" id="contact" placeholder="Contact Info">
                                <label for="floatingInput">Contact Info</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="refund_rule"
                                    value="{{ $webinfo->refund_rule }}" id="refund_rule" placeholder="Refund Rules">
                                <label for="floatingPassword">Refund Rules</label>
                            </div>
                            <div class="mb-4 ">
                                <select name="cash_on_delivery" id="cash_on_delivery" required
                                    class="mb-3 form-select form-select-lg" aria-label=".form-select-lg example">
                                    @if ($webinfo->cash_on_delivery == 'ON')
                                        <option value="ON" selected>ON</option>
                                        <option value="OFF">OFF</option>
                                    @else
                                        <option value="ON">ON</option>
                                        <option value="OFF" selected>OFF</option>
                                    @endif

                                </select>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary btn-lg w-100">Update</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        <div class="mb-4 col-sm-12 col-md-12 col-xl-12">
            <div class="p-4 rounded bg-secondary h-100">
                <h2 class="mb-4" style="text-align: center;color:red">Pixel & Analytics</h2>
                <form action="{{ url('/admin/pixel/analytics', $webinfo->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3 form-floating">
                                <textarea class="form-control" placeholder="Facebook Pixel" name="facebook_pixel" id="floatingTextarea"
                                    style="height: 150px;">{{ $webinfo->facebook_pixel }}</textarea>
                                <label for="floatingTextarea">Facebook Pixel</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3 form-floating">
                                <textarea class="form-control" placeholder="Google Analytics" name="google_analytics" id="floatingTextarea"
                                    style="height: 150px;">{{ $webinfo->google_analytics }}</textarea>
                                <label for="floatingTextarea">Google Analytics</label>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3 form-floating">
                                <textarea class="form-control" placeholder="Marquee Text" name="marquee_text" id="marquee_text"
                                    style="height: 100px;">{{ $webinfo->marquee_text }}</textarea>
                                <label for="floatingTextarea">Marquee Text</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3 form-floating">
                                <textarea class="form-control" placeholder="Chatbox Script" name="chat_box" id="chat_box"
                                    style="height: 100px;">{{ $webinfo->chat_box }}</textarea>
                                <label for="floatingTextarea">Chatbox Script</label>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="p-4 rounded bg-secondary h-100">
                <h2 class="mb-4" style="text-align: center;color:red">Social Links Update</h2>
                <form action="{{ url('/admin/basicinfo/update', $webinfo->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="facebook"
                                    value="{{ $webinfo->facebook }}" id="floatingInput"
                                    placeholder="https://www.facebook.com/">
                                <label for="floatingInput">Facebook</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="twitter"
                                    value="{{ $webinfo->twitter }}" id="floatingInput"
                                    placeholder="https://www.google.com/maps">
                                <label for="floatingInput">Google Maps</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="pinterest"
                                    value="{{ $webinfo->pinterest }}" id="floatingInput"
                                    placeholder="">
                                <label for="floatingInput">Twitter</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="linkedin"
                                    value="{{ $webinfo->linkedin }}" id="floatingInput"
                                    placeholder="https://www.instagram.com/">
                                <label for="floatingInput">Instagram</label>
                            </div>
                            <div class="mb-3 form-floating">
                                <input type="text" class="form-control" name="youtube"
                                    value="{{ $webinfo->youtube }}" id="floatingInput"
                                    placeholder="https://www.Youtube.com/">
                                <label for="floatingInput">Youtube</label>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100">Update</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
