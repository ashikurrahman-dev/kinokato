<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Admin;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.content.task.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $task = new Task();
        $task->task_name = $request->task_name;
        $task->type = $request->type;
        $task->date = $request->date;
        $task->message = $request->message;
        
        if(isset($request->admin_id)){
            $task->admin_id = $request->admin_id;
            $task->create_by = Auth::guard('admin')->user()->id;
        }else{
            $task->admin_id = Auth::guard('admin')->user()->id;
            $task->create_by = Auth::guard('admin')->user()->id;
        }
        $result = $task->save();
        return response()->json($task, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
     
     public function datacourier (Request $request)
    {  
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        
         
        $tasks = Task::where('type','courier')->get(); 
        
         if ($request->status=='All') {
            $tasks = $tasks;
        }else{
            $tasks = $tasks->where('status', $request->status);
        }

        if ($startDate != '' && $endDate != '') {
            $tasks = $tasks->whereBetween('date', [$startDate, $endDate]);
        }

        return Datatables::of($tasks)
            ->addColumn('action', function ($tasks) {
                $ad=Admin::where('id',$tasks->create_by)->first();
                if(isset($ad)){
                    if(Auth::user()->id==1){
                         return '<a href="#" type="button" id="editTaskBtn" data-id="' . $tasks->id . '"   class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editmainTask" ><i class="bi bi-pencil-square"></i></a>
                            <a href="#" type="button" id="deleteTaskBtn" data-id="' . $tasks->id . '" class="btn btn-danger btn-sm" ><i class="bi bi-archive" ></i></a>'; 
                    }else{
                        if($tasks->create_by==Auth::guard('admin')->user()->id){
                           return '<a href="#" type="button" id="editTaskBtn" data-id="' . $tasks->id . '"   class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editmainTask" ><i class="bi bi-pencil-square"></i></a>
                            <a href="#" type="button" id="deleteTaskBtn" data-id="' . $tasks->id . '" class="btn btn-danger btn-sm" ><i class="bi bi-archive" ></i></a>'; 
                        }else{
                            return '<a href="#" type="button" id="editTaskBtn" data-id="' . $tasks->id . '"   class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editmainTask" ><i class="bi bi-pencil-square"></i></a> ';
                        }
                    }
                }else{
                    return 'Not Available';
                }
                
            })
            ->addColumn('create_by', function ($tasks) {
                    $ad=Admin::where('id',$tasks->create_by)->first();
                    if(isset($ad)){
                        return '<span style="color:red;font-weight:bold;">'.Admin::where('id',$tasks->create_by)->first()->name.'</span>';
                    }else{
                        return 'Not Available';
                    }
                    
                 
            })
            ->addColumn('assign_for', function ($tasks) {
                $ad=Admin::where('id',$tasks->admin_id)->first();
                if(isset($ad)){
                   return '<span style="color:green;font-weight:bold;">'.Admin::where('id',$tasks->admin_id)->first()->name.'</span>';
                }else{
                    return 'Not Available';
                }
                
            })

            ->escapeColumns([])->make();
    }
    
    public function taskdata(Request $request)
    {  
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        if ($request->assign_for=='All') {
            $tasks = Task::where('type','task')->get();
        } else {
            $tasks = Task::where('type','task')->get();
        }
        
         if ($request->status=='All') {
            $tasks = $tasks;
        }else{
            $tasks = $tasks->where('status', $request->status);
        }

        if ($startDate != '' && $endDate != '') {
            $tasks = $tasks->whereBetween('date', [$startDate, $endDate]);
        }

        return Datatables::of($tasks)
            ->addColumn('action', function ($tasks) {
                $ad=Admin::where('id',$tasks->create_by)->first();
                if(isset($ad)){
                    if(Auth::user()->id==1){
                         return '<a href="#" type="button" id="editTaskBtn" data-id="' . $tasks->id . '"   class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editmainTask" ><i class="bi bi-pencil-square"></i></a>
                            <a href="#" type="button" id="deleteTaskBtn" data-id="' . $tasks->id . '" class="btn btn-danger btn-sm" ><i class="bi bi-archive" ></i></a>'; 
                    }else{
                        if($tasks->create_by==Auth::guard('admin')->user()->id){
                           return '<a href="#" type="button" id="editTaskBtn" data-id="' . $tasks->id . '"   class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editmainTask" ><i class="bi bi-pencil-square"></i></a>
                            <a href="#" type="button" id="deleteTaskBtn" data-id="' . $tasks->id . '" class="btn btn-danger btn-sm" ><i class="bi bi-archive" ></i></a>'; 
                        }else{
                            return '<a href="#" type="button" id="editTaskBtn" data-id="' . $tasks->id . '"   class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editmainTask" ><i class="bi bi-pencil-square"></i></a> ';
                        }
                    }
                    
                }else{
                    return 'Not Available';
                }
                
            })
            ->addColumn('create_by', function ($tasks) {
                    $ad=Admin::where('id',$tasks->create_by)->first();
                    if(isset($ad)){
                        return '<span style="color:red;font-weight:bold;">'.Admin::where('id',$tasks->create_by)->first()->name.'</span>';
                    }else{
                        return 'Not Available';
                    }
                    
                 
            })
            ->addColumn('assign_for', function ($tasks) {
                $ad=Admin::where('id',$tasks->admin_id)->first();
                if(isset($ad)){
                   return '<span style="color:green;font-weight:bold;">'.Admin::where('id',$tasks->admin_id)->first()->name.'</span>';
                }else{
                    return 'Not Available';
                }
                
            })

            ->escapeColumns([])->make();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::findOrfail($id);
        return response()->json($task, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrfail($id);
        $task->task_name = $request->task_name; 
        $task->date = $request->date;
        $task->message = $request->message;
        $task->update();
        return response()->json($task, 200);
    }

    public function updatestatus(Request $request)
    {
        $task = Task::where('id', $request->task_id)->first();
        $task->status = $request->status;
        $task->update();
        return response()->json($task, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrfail($id);
        $task->delete();
        return response()->json('success', 200);
    }
}
