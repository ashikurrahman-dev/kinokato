<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;
use App\Models\Menu;

class InformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        if($slug=='about_us'){
            $title='About US';
        }else if($slug=='contact_us'){
            $title='Contact Us';
        }else if($slug=='privacy_policy'){
            $title='Privacy Policy';
        }else if($slug=='investor_relation'){
            $title='Investor Relation';
        }else if($slug=='company'){
            $title='Company';
        }else if($slug=='customer_service'){
            $title='Customer Service';
        }else if($slug=='help_center'){
            $title='Help Center';
        }else if($slug=='faq'){
            $title='FAQ';
        }else if($slug=='terms_codition'){
            $title='Terms & Conditions';
        }else if($slug=='refund_return_policy'){
            $title='Refund & Return Policy';
        }else if($slug=='shipping_policy'){
            $title='Shipping Policy';
        }else if($slug=='payment_policy'){
            $title='Payment Policy';
        }else if($slug=='contact_info'){
            $title='Contact Info';
        }else if($slug=='news'){
            $title='Our News';
        }else{

        }

        $value=Information::where('key',$slug)->first();
        return view('backend.content.information.info',['title'=>$title,'slug'=>$slug,'value'=>$value]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create($slug)
    {
        $menus =Menu::where('slug',$slug)->first();
        $value=Information::where('key',$slug)->first();
        return view('backend.content.information.menu',['menus'=>$menus,'slug'=>$slug,'value'=>$value]);
    }


    public function createpage(Request $request, $slug)
    {
        $value=Information::where('key',$slug)->first();
        if(isset($value)){
            $value->value=$request->value;
            $value->update();
            return redirect()->back()->with('message','Info Update Successfully.');
        }else{
            $valuenew=new Information();
            $valuenew->key=$request->key;
            $valuenew->value=$request->value;
            $valuenew->sale1_amount = $request->sale1_amount;
            $valuenew->sale1_title = $request->sale1_title;
            $valuenew->sale2_amount = $request->sale2_amount;
            $valuenew->sale2_title = $request->sale2_title;
            $valuenew->sale3_amount = $request->sale3_amount;
            $valuenew->sale3_title = $request->sale3_title;
            $valuenew->sale4_amount = $request->sale4_amount;
            $valuenew->sale4_title = $request->sale4_title;
            $valuenew->member1_name = $request->member1_name;
            $valuenew->member1_designation = $request->member1_designation;
            $valuenew->member1_twitter = $request->member1_twitter;
            $valuenew->member1_instagram = $request->member1_instagram;
            $valuenew->member1_linkedin = $request->member1_linkedin;
            $valuenew->member2_name = $request->member2_name;
            $valuenew->member2_designation = $request->member2_designation;
            $valuenew->member2_twitter = $request->member2_twitter;
            $valuenew->member2_instagram = $request->member2_instagram;
            $valuenew->member2_linkedin = $request->member2_linkedin;
            $valuenew->member3_name = $request->member3_name;
            $valuenew->member3_designation = $request->member3_designation;
            $valuenew->member3_twitter = $request->member3_twitter;
            $valuenew->member3_instagram = $request->member3_instagram;
            $valuenew->member3_linkedin = $request->member3_linkedin;
            $valuenew->save();
            return redirect()->back()->with('message','Info created Successfully.');
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Information  $information
     * @return \Illuminate\Http\Response
     */
    public function show(Information $information)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Information  $information
     * @return \Illuminate\Http\Response
     */
    public function edit(Information $information)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Information  $information
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $value=Information::where('key',$slug)->first();
        $value->value=$request->value;
        $value->sale1_amount = $request->sale1_amount;
        $value->sale1_title = $request->sale1_title;
        $value->sale2_amount = $request->sale2_amount;
        $value->sale2_title = $request->sale2_title;
        $value->sale3_amount = $request->sale3_amount;
        $value->sale3_title = $request->sale3_title;
        $value->sale4_amount = $request->sale4_amount;
        $value->sale4_title = $request->sale4_title;
        $value->member1_name = $request->member1_name;
        $value->member1_designation = $request->member1_designation;
        $value->member1_twitter = $request->member1_twitter;
        $value->member1_instagram = $request->member1_instagram;
        $value->member1_linkedin = $request->member1_linkedin;
        $value->member2_name = $request->member2_name;
        $value->member2_designation = $request->member2_designation;
        $value->member2_twitter = $request->member2_twitter;
        $value->member2_instagram = $request->member2_instagram;
        $value->member2_linkedin = $request->member2_linkedin;
        $value->member3_name = $request->member3_name;
        $value->member3_designation = $request->member3_designation;
        $value->member3_twitter = $request->member3_twitter;
        $value->member3_instagram = $request->member3_instagram;
        $value->member3_linkedin = $request->member3_linkedin;

        if ($request->file('about_img')) {
            $about_img = $request->file('about_img');

            $imageName          = microtime('.') . '.' . $about_img->getClientOriginalExtension();
            $imagePath          = 'public/backend/images/about/';
            $about_img->move($imagePath, $imageName);

            $value->about_img   = $imagePath . $imageName;
        }

        if ($request->file('sale1_img')) {
            $sale1_img = $request->file('sale1_img');

            $imageName          = microtime('.') . '.' . $sale1_img->getClientOriginalExtension();
            $imagePath          = 'public/backend/images/about/';
            $sale1_img->move($imagePath, $imageName);

            $value->sale1_img   = $imagePath . $imageName;
        }

        if ($request->file('sale2_img')) {
            $sale2_img = $request->file('sale2_img');

            $imageName          = microtime('.') . '.' . $sale2_img->getClientOriginalExtension();
            $imagePath          = 'public/backend/images/about/';
            $sale2_img->move($imagePath, $imageName);

            $value->sale2_img   = $imagePath . $imageName;
        }

        if ($request->file('sale3_img')) {
            $sale3_img = $request->file('sale3_img');

            $imageName          = microtime('.') . '.' . $sale3_img->getClientOriginalExtension();
            $imagePath          = 'public/backend/images/about/';
            $sale3_img->move($imagePath, $imageName);

            $value->sale3_img   = $imagePath . $imageName;
        }

        if ($request->file('sale4_img')) {
            $sale4_img = $request->file('sale4_img');

            $imageName          = microtime('.') . '.' . $sale4_img->getClientOriginalExtension();
            $imagePath          = 'public/backend/images/about/';
            $sale4_img->move($imagePath, $imageName);

            $value->sale4_img   = $imagePath . $imageName;
        }

        if ($request->file('member1_img')) {
            $member1_img = $request->file('member1_img');

            $imageName          = microtime('.') . '.' . $member1_img->getClientOriginalExtension();
            $imagePath          = 'public/backend/images/about/';
            $member1_img->move($imagePath, $imageName);

            $value->member1_img   = $imagePath . $imageName;
        }

        if ($request->file('member2_img')) {
            $member2_img = $request->file('member2_img');

            $imageName          = microtime('.') . '.' . $member2_img->getClientOriginalExtension();
            $imagePath          = 'public/backend/images/about/';
            $member2_img->move($imagePath, $imageName);

            $value->member2_img   = $imagePath . $imageName;
        }
        if ($request->file('member3_img')) {
            $member3_img = $request->file('member3_img');

            $imageName          = microtime('.') . '.' . $member2_img->getClientOriginalExtension();
            $imagePath          = 'public/backend/images/about/';
            $member3_img->move($imagePath, $imageName);

            $value->member3_img   = $imagePath . $imageName;
        }

        $value->update();
        return redirect()->back()->with('message','Info Update Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Information  $information
     * @return \Illuminate\Http\Response
     */
    public function destroy(Information $information)
    {
        //
    }
}
