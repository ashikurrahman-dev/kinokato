<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;
use DataTables;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins =Admin::all();
        return view('backend.content.admins.index',['admins'=>$admins]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $admin=new Admin();
        $admin->password=Hash::make($request->password);
        $admin->name=$request->name;
        $admin->email=$request->email; 
        $admin->phone=$request->phone;
        $admin->address=$request->address;
        $admin->region=$request->region;
        $admin->gender=$request->gender;
        $admin->quaification=$request->quaification;
        $admin->dob=$request->dob;
        $admin->joindate=$request->joindate;
        $admin->designation=$request->designation;
        
        $time = microtime('.') * 10000;
        
        $productImgnid = $request->nid;
        if ($productImgnid) {
            $imgnamenid = $time . $productImgnid->getClientOriginalName();
            $imguploadPathnid = ('public/employee/');
            $productImgnid->move($imguploadPathnid, $imgnamenid);
            $productImgUrlnid = $imguploadPathnid . $imgnamenid;
            $admin->nid = $productImgUrlnid;
        }
        
        $productImgletter = $request->join_letter;
        if ($productImgletter) {
            $imgnameletter = $time . $productImgletter->getClientOriginalName();
            $imguploadPathletter = ('public/employee/');
            $productImgletter->move($imguploadPathletter, $imgnameletter);
            $productImgUrlletter = $imguploadPathletter . $imgnameletter;
            $admin->join_letter = $productImgUrlletter;
        } 
        
        $admin->father_name=$request->father_name;
        $admin->father_phone=$request->father_phone;
        $admin->mother_name=$request->mother_name;
        $admin->mother_phone=$request->mother_phone;
        $admin->local_guardian=$request->local_guardian;
        
        $productImgprof = $request->profile;
        if ($productImgprof) {
            $imgnameprof = $time . $productImgprof->getClientOriginalName();
            $imguploadPathprof = ('public/employee/');
            $productImgprof->move($imguploadPathprof, $imgnameprof);
            $productImgUrlprof = $imguploadPathprof . $imgnameprof;
            $admin->profile = $productImgUrlprof;
        }
        
        $productImgfn = $request->father_nid;
        if ($productImgfn) {
            $imgnamefn = $time . $productImgfn->getClientOriginalName();
            $imguploadPathfn = ('public/employee/');
            $productImgfn->move($imguploadPathfn, $imgnamefn);
            $productImgUrlfn = $imguploadPathfn . $imgnamefn;
            $admin->father_nid = $productImgUrlfn;
        }
         
        $productImgmn = $request->mother_nid;
        if ($productImgmn) {
            $imgnamemn = $time . $productImgmn->getClientOriginalName();
            $imguploadPathmn = ('public/employee/');
            $productImgmn->move($imguploadPathmn, $imgnamemn);
            $productImgUrlmn = $imguploadPathmn . $imgnamemn;
            $admin->mother_nid = $productImgUrlmn;
        }
        
        $productImglg = $request->localguardian_nid;
        if ($productImglg) {
            $imgnamelg = $time . $productImglg->getClientOriginalName();
            $imguploadPathlg = ('public/employee/');
            $productImglg->move($imguploadPathlg, $imgnamelg);
            $productImgUrllg = $imguploadPathlg . $imgnamelg;
            $admin->localguardian_nid = $productImgUrllg;
        }
         
        
        $productImgebill = $request->house_electricity_bill;
        if ($productImgebill) {
            $imgnameebill = $time . $productImgebill->getClientOriginalName();
            $imguploadPathebill = ('public/employee/');
            $productImgebill->move($imguploadPathebill, $imgnameebill);
            $productImgUrlebill = $imguploadPathebill . $imgnameebill;
            $admin->house_electricity_bill = $productImgUrlebill;
        } 
        $productImgecv = $request->cv;
        if ($productImgecv) {
            $imgnameecv = $time . $productImgecv->getClientOriginalName();
            $imguploadPathecv = ('public/employee/');
            $productImgecv->move($imguploadPathecv, $imgnameecv);
            $productImgUrlecv = $imguploadPathecv . $imgnameecv;
            $admin->cv = $productImgUrlecv;
        } 
        
        $admin->save();
        if($request->roles){
            $admin->assignRole($request->roles);
        }
        return redirect()->back()->with('message','Admin created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles =Role::where('guard_name','admin')->get();
        return view('backend.content.admins.create',['roles'=>$roles]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles =Role::where('guard_name','admin')->get();
        $admin =Admin::where('id',$id)->first();
        return view('backend.content.admins.edit',['roles'=>$roles,'admin'=>$admin]);
    }

    public function show($id)
    { 
        $admin =Admin::where('id',$id)->first();
        return view('backend.content.admins.view',['admin'=>$admin]);
    }
    
    public function print ($id)
    { 
        $admin =Admin::where('id',$id)->first();
        return view('backend.content.admins.print',['admin'=>$admin]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $admin=Admin::findOrfail($id); 
        if($request->password){
            $admin->password=Hash::make($request->password);
        } 
        $admin->name=$request->name;
        $admin->email=$request->email; 
        $admin->phone=$request->phone;
        $admin->address=$request->address;
        $admin->region=$request->region;
        $admin->gender=$request->gender;
        $admin->quaification=$request->quaification;
        $admin->dob=$request->dob;
        $admin->joindate=$request->joindate;
        $admin->designation=$request->designation;
        
        $time = microtime('.') * 10000;
        
        $productImgnid = $request->nid;
        if ($productImgnid) {
            $imgnamenid = $time . $productImgnid->getClientOriginalName();
            $imguploadPathnid = ('public/employee/');
            $productImgnid->move($imguploadPathnid, $imgnamenid);
            $productImgUrlnid = $imguploadPathnid . $imgnamenid;
            $admin->nid = $productImgUrlnid;
        }
        
        $productImgprof = $request->profile;
        if ($productImgprof) {
            $imgnameprof = $time . $productImgprof->getClientOriginalName();
            $imguploadPathprof = ('public/employee/');
            $productImgprof->move($imguploadPathprof, $imgnameprof);
            $productImgUrlprof = $imguploadPathprof . $imgnameprof;
            $admin->profile = $productImgUrlprof;
        }
        
        $productImgletter = $request->join_letter;
        if ($productImgletter) {
            $imgnameletter = $time . $productImgletter->getClientOriginalName();
            $imguploadPathletter = ('public/employee/');
            $productImgletter->move($imguploadPathletter, $imgnameletter);
            $productImgUrlletter = $imguploadPathletter . $imgnameletter;
            $admin->join_letter = $productImgUrlletter;
        } 
        
        $admin->father_name=$request->father_name;
        $admin->father_phone=$request->father_phone;
        $admin->mother_name=$request->mother_name;
        $admin->mother_phone=$request->mother_phone;
        $admin->local_guardian=$request->local_guardian;
         
        $productImgfn = $request->father_nid;
        if ($productImgfn) {
            $imgnamefn = $time . $productImgfn->getClientOriginalName();
            $imguploadPathfn = ('public/employee/');
            $productImgfn->move($imguploadPathfn, $imgnamefn);
            $productImgUrlfn = $imguploadPathfn . $imgnamefn;
            $admin->father_nid = $productImgUrlfn;
        }
         
        $productImgmn = $request->mother_nid;
        if ($productImgmn) {
            $imgnamemn = $time . $productImgmn->getClientOriginalName();
            $imguploadPathmn = ('public/employee/');
            $productImgmn->move($imguploadPathmn, $imgnamemn);
            $productImgUrlmn = $imguploadPathmn . $imgnamemn;
            $admin->mother_nid = $productImgUrlmn;
        }
        
        $productImglg = $request->localguardian_nid;
        if ($productImglg) {
            $imgnamelg = $time . $productImglg->getClientOriginalName();
            $imguploadPathlg = ('public/employee/');
            $productImglg->move($imguploadPathlg, $imgnamelg);
            $productImgUrllg = $imguploadPathlg . $imgnamelg;
            $admin->localguardian_nid = $productImgUrllg;
        }
         
        
        $productImgebill = $request->house_electricity_bill;
        if ($productImgebill) {
            $imgnameebill = $time . $productImgebill->getClientOriginalName();
            $imguploadPathebill = ('public/employee/');
            $productImgebill->move($imguploadPathebill, $imgnameebill);
            $productImgUrlebill = $imguploadPathebill . $imgnameebill;
            $admin->house_electricity_bill = $productImgUrlebill;
        } 
        $productImgecv = $request->cv;
        if ($productImgecv) {
            $imgnameecv = $time . $productImgecv->getClientOriginalName();
            $imguploadPathecv = ('public/employee/');
            $productImgecv->move($imguploadPathecv, $imgnameecv);
            $productImgUrlecv = $imguploadPathecv . $imgnameecv;
            $admin->cv = $productImgUrlecv;
        } 
        
        $admin->update();
        $admin->roles()->detach();
        if($request->roles){
            $admin->assignRole($request->roles);
        }

        return redirect()->back()->with('message','Admin updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admin = Admin::where('id',$id)->first();
        if(is_null($admin)){
            return redirect()->back()->with('error','Something went wrong');
        }else{
            $admin->delete();
            return redirect()->back()->with('message','ADmin Deleted Successfully');
        }
    }

}
