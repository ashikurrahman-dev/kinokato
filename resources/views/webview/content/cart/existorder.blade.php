@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-Complete
@endsection
     <br>
    <div class="container pb-5 mt-4 mb-4 mb-sm-4">
        <div class="pt-5 pb-5" style="margin-bottom:5px">
            <div class="py-3 card mt-sm-3">
                <div class="text-center card-body">
                    <img src="{{asset('public/warning.png')}}" alt="" style="width: 115px;padding-bottom: 20px;">
                    <h2 class="pb-3 h4" style="color:red">দুঃখিত,আপনার পূর্ববর্তী অর্ডার আমরা ইতিমধ্যে রিসিভ করেছি | অনুগ্রহপূর্বক অপেক্ষা করুন আমাদের কাস্টমার কেয়ার প্রতিনিধি আপনাকে কল করে আপনার অর্ডারটি কনফার্ম করবে |</h2>
                    <h4 class="pb-3 m-0">অনুগ্রহপূর্বক অপেক্ষা করুন,ধন্যবাদ</h4>
                </div>
            </div>
        </div>
    </div>
@endsection
