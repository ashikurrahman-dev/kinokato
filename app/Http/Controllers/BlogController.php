<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use DataTables;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.content.blog.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.content.blog.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $blog = new Blog();
        $blog->title=$request->title;
        $blog->short_description=$request->short_description;
        $blog->description=$request->description;
        $blog->button_name=$request->button_name;
        $blog->link=$request->link;

        $time = microtime('.') * 10000;
        $productImg = $request->file('image');
        if($productImg){
            $imgname = $time . $productImg->getClientOriginalName();
            $imguploadPath = ('public/images/blog/image/');
            $productImg->move($imguploadPath, $imgname);
            $productImgUrl = $imguploadPath . $imgname;
            $blog->image = $productImgUrl;
        }

        $productImgb = $request->file('banner');
        if($productImgb){
            $imgnameb = $time . $productImgb->getClientOriginalName();
            $imguploadPathb = ('public/images/blog/banner/');
            $productImgb->move($imguploadPathb, $imgnameb);
            $productImgUrlb = $imguploadPathb . $imgnameb;
            $blog->banner = $productImgUrlb;
        }

        $blog->save();
        return redirect()->back()->with('success','Blog Insert Successfully');
    }


    public function blogdata()
    {
        $blogs = Blog::all();
        return Datatables::of($blogs)
            ->addColumn('action', function ($blogs) {
                return '<a href="blogs/' . $blogs->id . '/edit" class="btn btn-primary btn-sm" style="margin-bottom:2px;"><i class="bi bi-pencil-square"></i></a>
                <a href="#" type="button" style="margin-bottom:2px;" id="deleteBlogBtn" data-id="' . $blogs->id . '" class="btn btn-danger btn-sm" ><i class="bi bi-archive" ></i></a>';
            })

            ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blog=Blog::where('id',$id)->first();
        return view('backend.content.blog.edit',['blog'=>$blog]);
    }

    public function statusupdate(Request $request)
    {
        $blog=Blog::where('id',$request->blog_id)->first();
        $blog->status=$request->status;
        $blog->update();
        return response()->json($blog, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);
        $blog->title=$request->title;
        $blog->short_description=$request->short_description;
        $blog->description=$request->description;
        $blog->button_name=$request->button_name;
        $blog->link=$request->link;

        $time = microtime('.') * 10000;
        $productImg = $request->file('image');
        if($productImg){ 
            $imgname = $time . $productImg->getClientOriginalName();
            $imguploadPath = ('public/images/blog/image/');
            $productImg->move($imguploadPath, $imgname);
            $productImgUrl = $imguploadPath . $imgname;
            $blog->image = $productImgUrl;
        }

        $productImgb = $request->file('banner');
        if($productImgb){ 
            $imgnameb = $time . $productImgb->getClientOriginalName();
            $imguploadPathb = ('public/images/blog/banner/');
            $productImgb->move($imguploadPathb, $imgnameb);
            $productImgUrlb = $imguploadPathb . $imgnameb;
            $blog->banner = $productImgUrlb;
        }

        $blog->update();
        return redirect()->route('admin.blogs.index')->with('success','Blog Update Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog=Blog::where('id',$id)->first();
        $blog->delete();
        return response()->json('success', 200);
    }
}
