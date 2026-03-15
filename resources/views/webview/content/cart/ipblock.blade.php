@extends('webview.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}-Complete
@endsection
     
    <div class="container pb-5 mb-sm-4 mt-4 mb-4">
        <div class="pt-5 pb-5" style="margin-bottom:5px">
            <div class="card py-3 mt-sm-3">
                <div class="card-body text-center">
                    <h2 class="h4 pb-3" style="color:red">আপনার অতিরিক্ত অর্ডার বাতিলের কারণে আপনার আইপিটি ব্লক করা হয়েছে | পুনরায় অর্ডার করতে সরাসরি কথা বলুন আমাদের হটলাইন নাম্বারে  এবং আন-ব্লক করে নিন আপনার আইপি |</h2>
                    <a class="btn btn-primary mt-3" href="{{url('/')}}">প্রোডাক্ট বাছাই করুন</a>
                    <a class="btn btn-danger mt-3" href="tel:{{App\Models\Basicinfo::first()->phone_one}}"><i class="fas fa-phone"></i>কল করতে ক্লিক করুন</a>
                </div>
            </div>
        </div>
    </div>
@endsection
