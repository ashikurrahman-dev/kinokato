<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use DataTables;

class CategoryController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.content.category.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = new Category();
        $category->category_name = $request->category_name;
        $category->position = $request->position;
    
        // Icon upload
        if ($request->hasFile('category_icon')) {
            $category_icon = $request->file('category_icon');
            $iconName = time() . "_" . $category_icon->getClientOriginalName();
            $uploadPath = 'public/images/category/';
            $destination = public_path('images/category/');
            $category_icon->move($destination, $iconName);
    
            // Convert to webp
            $webpPath = $destination . $iconName;
            $im = @imagecreatefromstring(file_get_contents($webpPath));
            if ($im) {
                $new_webp = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $webpPath);
                imagewebp($im, $new_webp, 50);
                imagedestroy($im);
    
                // Save with 'public/' path (as you asked)
                $category->category_icon = $uploadPath . basename($new_webp);
            }
        }
    
        // Banner upload
        if ($request->hasFile('category_banner')) {
            $category_banner = $request->file('category_banner');
            $bannerName = 'category-banner' . time() . "_" . $category_banner->getClientOriginalName();
            $uploadPath = 'public/images/category/';
            $destination = public_path('images/category/');
            $category_banner->move($destination, $bannerName);
    
            // Convert to webp
            $webpPath = $destination . $bannerName;
            $im = @imagecreatefromstring(file_get_contents($webpPath));
            if ($im) {
                $new_webp = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $webpPath);
                imagewebp($im, $new_webp, 50);
                imagedestroy($im);
    
                // Save with 'public/' path
                $category->category_banner = $uploadPath . basename($new_webp);
            }
        }
    
        $category->save();
        return response()->json($category, 200);
    }


    public function categorydata()
    {
        $categorys = Category::all();
        return Datatables::of($categorys)
            ->addColumn('action', function ($categorys) {
                return '<a href="#" type="button" id="editCategoryBtn" data-id="' . $categorys->id . '"   class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editmainCategory" ><i class="bi bi-pencil-square"></i></a>
                <a href="#" type="button" id="deleteCategoryBtn" data-id="' . $categorys->id . '" class="btn btn-danger btn-sm" ><i class="bi bi-archive" ></i></a>';
            })

            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrfail($id);
        return response()->json($category, 200);
    }

    public function getsubcategory($id)
    {
        $subcategory = Subcategory::where('category_id',$id)->where('status','Active')->get();
        return response()->json($subcategory, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
{
    $category = Category::findOrFail($id);

    $category->category_name = $request->category_name;
    $category->position = $request->position;

    // Upload and save category_icon (optional)
    if ($request->hasFile('category_icon')) {
        $category_icon = $request->file('category_icon');
        $name = time() . "_" . $category_icon->getClientOriginalName();
        $uploadPath = 'public/images/category/';
        $fullPath = public_path('images/category/');
        $category_icon->move($fullPath, $name);

        $category->category_icon = $uploadPath . $name; // Full path with public/
    }

    if ($request->hasFile('category_banner')) {
        $category_banner = $request->file('category_banner');
        $name = 'category-banner_' . time() . "_" . $category_banner->getClientOriginalName();
        $uploadPath = 'public/images/category/';
        $fullPath = public_path('images/category/');
        $category_banner->move($fullPath, $name);

        $category->category_banner = $uploadPath . $name; // Full path with public/
    }

    $category->save();

    return response()->json($category, 200);
}



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrfail($id);
        $category->delete();
        return response()->json('success', 200);
    }

    public function statusupdate(Request $request)
    {
        $category = Category::where('id',$request->category_id)->first();
        if(isset($request->status)){
            $category->status=$request->status;
        }
        if(isset($request->front_status)){
            $category->front_status=$request->front_status;
        }
        $category->update();
        return response()->json($category, 200);
    }
}
