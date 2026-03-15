<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Mainproduct;
use App\Models\Product;
use Illuminate\Http\Request;
use DataTables;
use Session;
use DB;

class MainproductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.content.mainproduct.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('status', 'Active')->select('id', 'category_name', 'status')->get();
        return view('backend.content.mainproduct.create',[ 'categories' => $categories,]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeproduct(Request $request)
    {
        $mainproduct = new Mainproduct();
        $mainproduct->ProductName = $request->ProductName;
        $mainproduct->category_id = $request->category_id;
        $mainproduct->subcategory_id = $request->subcategory_id;
        $mainproduct->position = $request->position;
        $productImg = $request->ProductImage;
        $ProductHoverImage = $request->ProductHoverImage;
        $time = microtime('.') * 10000;
        if ($productImg) {
            $imgname = $time . $productImg->getClientOriginalName();
            $imguploadPath = ('public/images/product/');
            $productImg->move($imguploadPath, $imgname);
            $productImgUrl = $imguploadPath . $imgname;
            $mainproduct->ProductImage = $productImgUrl;
        }
        
        if ($ProductHoverImage) {
            $imgname2 = $time . $ProductHoverImage->getClientOriginalName();
            $imguploadPath2 = ('public/images/product/');
            $ProductHoverImage->move($imguploadPath2, $imgname2);
            $productImg2Url = $imguploadPath2 . $imgname2;
            $mainproduct->ProductHoverImage = $productImg2Url;
        }

        $mainproduct->RelatedProductIds = json_encode($request->product);
        $mainproduct->save();
        return response()->json('success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mainproduct  $mainproduct
     * @return \Illuminate\Http\Response
     */
    public function productdata(Request $request)
    {
        Session::put('category_id',$request->category_id);
        if($request->category_id=='all'){
            if(isset($request->code)){
                $products = Mainproduct::where('ProductName', 'LIKE', '%' . $request->code . '%')->orderByRaw('ISNULL(`position`), `position` ASC');
            }else{
                $products = Mainproduct::orderByRaw('ISNULL(`position`), `position` ASC');
            }
            
        }else{
            if(isset($request->code)){
                $products = Mainproduct::where('category_id',$request->category_id)->where('ProductName', 'LIKE', '%' . $request->code . '%')->orderByRaw('ISNULL(`position`), `position` ASC');
            }else{
                $products = Mainproduct::where('category_id',$request->category_id)->orderByRaw('ISNULL(`position`), `position` ASC');
            }
            
        }
        
        return Datatables::of($products)
            ->addColumn('position', function ($products) { 
                if(isset($products->position)){
                    return '<div class="d-flex"><input type="text" id="pos'.$products->id.'" value="'. $products->position .'" style="width:60px;"><button type="button" class="btn btn-warning btn-sm btn-position'.$products->id.' ms-2" id="updateposition" data-id="'.$products->id.'"><i class="fa fa-check" aria-hidden="true"></i></button></div>';
                }else{
                    return '<div class="d-flex"><input type="text" id="pos'.$products->id.'" value="" style="width:60px;"><button type="button" class="btn btn-warning btn-sm btn-position'.$products->id.' ms-2" id="updateposition" data-id="'.$products->id.'"><i class="fa fa-check" aria-hidden="true"></i></button></div>';
                }
                
            })
            ->addColumn('category', function ($products) {
                $cate=Category::where('id', $products->category_id)->first();
                if(isset($cate)){
                    return Category::where('id', $products->category_id)->first()->category_name;
                }else{
                    return '<span style="color:red">Category Deleted</span>';
                }
                
            })
            ->addColumn('action', function ($products) {
                return '<a href="mainproduct-edit/' . $products->id . '" class="btn btn-primary btn-sm" style="margin-bottom:2px;"><i class="bi bi-pencil-square"></i></a>';
            })

            ->escapeColumns([])->make();
    }

    public function statusupdate(Request $request)
    {

        $product = Mainproduct::where('id', $request->product_id)->first();
        $product->status = $request->status;
        $product->update();
        return response()->json($product, 200);
    }
    
    public function positionupdate(Request $request)
    {

        $product = Mainproduct::where('id', $request->product_id)->first();
        $product->position = $request->position;
        $product->update();
        return response()->json('success', 200);
    }

    public function ratedstatusupdate(Request $request)
    {
        $product = Mainproduct::where('id', $request->product_id)->first();
        $product->top_rated = $request->top_rated;
        $product->update();
        return response()->json($product, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Mainproduct  $mainproduct
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mainproduct = Mainproduct::findOrfail($id);
        $categories = Category::where('status', 'Active')->select('id', 'category_name', 'status')->get();
        return view('backend.content.mainproduct.edit', ['mainproduct' => $mainproduct, 'categories'=>$categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mainproduct  $mainproduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $mainproduct = Mainproduct::where('id', $request->main_proid)->first();
        $mainproduct->ProductName = $request->ProductName;
        $mainproduct->category_id = $request->category_id;
        $mainproduct->subcategory_id = $request->subcategory_id;
        $mainproduct->position = $request->position;
        $productImg = $request->ProductImage;
        $ProductHoverImage = $request->ProductHoverImage;
        $time = microtime('.') * 10000;
        if ($request->hasFile('ProductImage')) {
            $imgname = $time . $productImg->getClientOriginalName();
            $imguploadPath = ('public/images/product/');
            $productImg->move($imguploadPath, $imgname);
            $productImgUrl = $imguploadPath . $imgname;
            $mainproduct->ProductImage = $productImgUrl;
        }
        
        if ($request->hasFile('ProductHoverImage')) {
            $imgname2 = $time . $ProductHoverImage->getClientOriginalName();
            $imguploadPath2 = ('public/images/product/');
            $ProductHoverImage->move($imguploadPath2, $imgname2);
            $productImg2Url = $imguploadPath2 . $imgname2;
            $mainproduct->ProductHoverImage = $productImg2Url;
        }

        $mainproduct->RelatedProductIds = json_encode($request->product);
        $mainproduct->update();
        return response()->json('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mainproduct  $mainproduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mainproduct $mainproduct)
    {
        //
    }
}
