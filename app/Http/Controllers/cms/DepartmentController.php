<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('management',new Department());
        if($request->ajax()){
            $data  = Department::select('*');
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($data){
                $editUrl = route('department.edit', ['department' => $data->id]);
                $deleteUrl = route('department.destroy', ['department' => $data->id]);

                $btnEdit = '<a href="'. $editUrl .'"><i class="fa fa-edit"></i><a>';
                $btnDelete = '<form action="'. $deleteUrl .'"method="POST">
                              '.csrf_field().'
                              '.method_field("DELETE").'
                              <button type="submit" style="background-color: transparent;border:0px"><i class="fa fa-trash text-red"></i></button>
                              </form>';
                $allBtns = '<div style="display:flex;">' . $btnEdit.$btnDelete . '</div>';
                return $allBtns;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('cms.department.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('management',new Department());
        $data['object'] = new Department();
        $data['method'] = 'POST';
        $data['url'] = route('department.store');
        return view('cms.department.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentRequest $request)
    {
        $this->authorize('management',new Department());
        $department = new Department();
        $department->name = $request->name;
        $department->save();

        $data['message']            =       auth()->user()->name." has created $department->name department";
        $data['action']             =       'created';
        $data['module']             =       'department';
        $data['object']             =       $department;
        saveLogs($data);
        Session::flash('success','data saved');
        return redirect(route('department.index'));
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
        $this->authorize('management',new Department());
        $data['object'] = Department::find($id);
        if(empty($data['object'])){
            Session::flash('error','data not found');
            return back();
        }
        $data['method'] = 'PUT';
        $data['url'] = route('department.update',['department'=> $id]);
        return view('cms.department.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentRequest $request, string $id)
    {
        $this->authorize('management',new Department());
        $department = Department::find($id);
        if(empty($department)){
            Session::flash('error','data not found');
            return redirect(route('department.index'));
        }
        $department->name = $request->name;
        $department->update();

        $data['message']            =       auth()->user()->name." has updated $department->name department";
        $data['action']             =       'updated';
        $data['module']             =       'department';
        $data['object']             =       $department;
        saveLogs($data);

        Session::flash('success','data updated');
        return redirect(route('department.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('management',new Department());
        $department = Department::find($id);
        if(empty($department)){
            Session::flash('error','data deleted');
            return back();
        }
        $data['message']            =       auth()->user()->name." has deleted $department->name department";
        $data['action']             =       'deleted';
        $data['module']             =       'department';
        $data['object']             =       $department;
        saveLogs($data);

        $department->delete();
        Session::flash('success','data deleted');
        return redirect(route('department.index'));
    }
}
