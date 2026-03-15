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
                    <h2 class="pb-3 h4" style="color:green">আপনার কার্টে কোন প্রোডাক্ট যুক্ত নেই | অনুগ্রহপূর্বক পুনরায় প্রোডাক্ট সিলেক্ট করুন এবং নতুন করে অর্ডারটি প্রসেস করুন |</h2>
                    <a class="mt-3 btn btn-primary" href="{{url('/')}}">প্রোডাক্ট বাছাই করুন</a>
                </div>
            </div>
        </div>
    </div>
@endsection
