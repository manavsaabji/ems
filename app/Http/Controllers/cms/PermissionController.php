<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['permissions'] = Permission::all();
        return view('cms.permission.index', $data);
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
    public function store(Request $request)
    {
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->module_id = $request->module_id;
        $permission->save();
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
    public function update(Request $request, string $id)
    {
        $permission = Permission::find($id);
        if(empty($permission)){
            Session::flash('error','data not found');
            return redirect(route('permission.index'));
        }
        $permission->name = $request->name;
        $permission->module_id = $request->module_id;
        $permission->update();
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
        $permission->delete();
        Session::flash('success', 'data deleted');
        return redirect(route('permission.index'));
    }
}
