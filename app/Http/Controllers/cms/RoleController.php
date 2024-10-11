<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['roles'] = Role::all();
        return view('cms.role.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['object'] = new Role();
        $data['method'] = 'POST';
        $data['url'] = route('role.store');
        return view('cms.role.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->save();
        $data['message']            =       auth()->user()->name." has created $role->name role";
        $data['action']             =       'created';
        $data['module']             =       'role';
        $data['object']             =       $role;
        saveLogs($data);
        Session::flash('success','data saved');
        return redirect(route('role.index'));
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
        $data['object'] = Role::find($id);
        if(empty($data['object'])){
            Session::flash('error','data not found');
            return redirect(route('role.index'));
        }
        $data['method'] = 'PUT';
        $data['url'] = route('role.update',['role'=>$id]);
        return view('cms.role.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id)
    {
        $role = Role::find($id);
        if(empty($role)){
            Session::flash('error','data not found');
            return redirect(route('role.index'));
        }
        $role->name = $request->name;
        $role->update();
        $data['message']            =       auth()->user()->name." has updated $role->name role";
        $data['action']             =       'updated';
        $data['module']             =       'role';
        $data['object']             =       $role;
        saveLogs($data);
        Session::flash('success','data update successfully');
        return redirect(route('role.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);
        if(empty($role)){
            Session::flash('error', 'data not found');
            return redirect(route('role.index'));
        }
        $data['message']            =       auth()->user()->name." has deleted $role->name role";
        $data['action']             =       'updated';
        $data['module']             =       'role';
        $data['object']             =       $role;
        saveLogs($data);
        $role->delete();
        Session::flash('success', 'data deleted successfully');
        return redirect(route('role.index'));
    }
}
