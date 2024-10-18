<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data  = Task::select('*')->with('user');
            if(auth()->user()->hasRole('admin')){
                $data;
            }else{
                $data->where('user_id', auth()->user()->id);
            }

            // dd($data);
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('user', function($data){
                return $data->user->name ?? 'N/A';
            })
            ->rawColumns(['user'])
            ->make(true);
        }
        return view('cms.task.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['object'] = new Task();
        $data['method'] = 'POST';
        $data['url'] = route('task.store');
        return view('cms.task.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $task = new Task();
        $task->task_1 = $request->task_1;
        $task->task_2 = $request->task_2;
        $task->task_3 = $request->task_3;
        $task->task_4 = $request->task_4;
        $task->task_5 = $request->task_5;
        $task->task_6 = $request->task_6;
        $task->date = Carbon::now()->toDateString();
        $task->user_id = auth()->user()->id;
        $task->save();

        $data['message']            =       auth()->user()->name." has created $task->name task";
        $data['action']             =       'created';
        $data['module']             =       'task';
        $data['object']             =       $task;
        saveLogs($data);
        Session::flash('success','Task Created');
        return redirect(route('dashboard'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $data['object'] = Task::find($id);
        // if(empty($data['object'])){
        //     Session::flash('error','data not found');
        //     return back();
        // }
        // $data['method'] = 'PUT';
        // $data['url'] = route('task.update',['task'=> $id]);
        // return view('cms.task.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $task = Task::find($id);
        // if(empty($task)){
        //     Session::flash('error','data not found');
        //     return redirect(route('dashboard'));
        // }
        // $task->task_1 = $request->task_1;
        // $task->task_2 = $request->task_2;
        // $task->task_3 = $request->task_3;
        // $task->task_4 = $request->task_4;
        // $task->task_5 = $request->task_5;
        // $task->task_6 = $request->task_6;
        // $task->update();

        // $data['message']            =       auth()->user()->name." has updated $task->name task";
        // $data['action']             =       'updated';
        // $data['module']             =       'task';
        // $data['object']             =       $task;
        // saveLogs($data);

        // Session::flash('success','data updated');
        // return redirect(route('dashboard'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $task = Task::find($id);
        // if(empty($task)){
        //     Session::flash('error','data not found');
        //     return redirect(route('dashboard'));
        // }
        // $data['message']            =       auth()->user()->name." has deleted $task->name task";
        // $data['action']             =       'deleted';
        // $data['module']             =       'task';
        // $data['object']             =       $task;
        // saveLogs($data);

        // $task->delete();
        // Session::flash('success','data deleted');
        // return redirect(route('dashboard'));
    }
}
