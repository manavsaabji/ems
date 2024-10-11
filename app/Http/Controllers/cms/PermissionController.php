<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data  = Permission::select('*')->with('module');
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('module', function($data){
                if(empty($data->module)){
                    return 'N/A';
                }
                return $data->module->name;
            })
            ->addColumn('action', function($data){
                $editUrl = route('permission.edit', ['permission' => $data->id]);
                $deleteUrl = route('permission.destroy', ['permission' => $data->id]);

                $btnEdit = '<a href="'. $editUrl .'"><i class="fa fa-edit"></i><a>';
                $btnDelete = '<form action="'. $deleteUrl .'"method="POST">
                              '.csrf_field().'
                              '.method_field("DELETE").'
                              <button type="submit" style="background-color: transparent;border:0px"><i class="fa fa-trash text-red"></i></button>
                              </form>';
                $allBtns = '<div style="display:flex;">' . $btnEdit.$btnDelete . '</div>';
                return $allBtns;
                // return 'N/A';
            })
            ->rawColumns(['action','module'])
            ->make(true);
        }
        // $data['permissions'] = Permission::all();
        return view('cms.permission.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['object'] = new Permission();
        $data['method'] = 'POST';
        $data['url'] = route('permission.store');
        $data['modules'] = Module::pluck('name','id')->toArray();
        return view('cms.permission.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request)
    {
        $duplicate = Permission::where('module_id',$request->module_id)
        ->where('name',strtolower($request->name))->exists();

        if($duplicate){
            Session::flash('error', 'data already exists');
            return back();
        }
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->module_id = $request->module_id;
        $permission->save();
        $data['message']            =       auth()->user()->name." has created $permission->name permission";
        $data['action']             =       'created';
        $data['module']             =       'permission';
        $data['object']             =       $permission;
        saveLogs($data);
        Session::flash('success', 'data saved');
        return redirect(route('permission.index'));
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
        $data['object'] = Permission::find($id);
        if(empty($data['object'])){
            Session::flash('error','data not found');
            return redirect(route('permission.index'));
        }
        $data['method'] = 'PUT';
        $data['url'] = route('permission.update',['permission'=>$id]);
        $data['modules'] = Module::pluck('name','id')->toArray();
        return view('cms.permission.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, string $id)
    {
        $duplicate = Permission::where('id','<>',$request->id)->where('module_id',$request->module_id)
        ->where('name',strtolower($request->name))->exists();
        if($duplicate){
            Session::flash('error', 'data already exists');
            return back();
        }
        $permission = Permission::find($id);
        if(empty($permission)){
            Session::flash('error','data not found');
            return redirect(route('permission.index'));
        }
        $permission->name = $request->name;
        $permission->module_id = $request->module_id;
        $permission->update();
        $data['message']            =       auth()->user()->name." has updated $permission->name permission";
        $data['action']             =       'updated';
        $data['module']             =       'permission';
        $data['object']             =       $permission;
        saveLogs($data);
        Session::flash('success', 'data updated');
        return redirect(route('permission.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::find($id);
        if(empty($permission)){
            Session::flash('error','data not found');
            return redirect(route('permission.index'));
        }

        $data['message']            =       auth()->user()->name." has deleted $permission->name permission";
        $data['action']             =       'deleted';
        $data['module']             =       'permission';
        $data['object']             =       $permission;
        saveLogs($data);

        $permission->delete();
        Session::flash('success', 'data deleted');
        return redirect(route('permission.index'));
    }
}
