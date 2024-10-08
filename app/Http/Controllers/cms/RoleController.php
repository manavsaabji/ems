<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
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
    public function store(Request $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->save();
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
    public function update(Request $request, string $id)
    {
        $data = Role::find($id);
        if(empty($data)){
            Session::flash('error','data not found');
            return redirect(route('role.index'));
        }
        $data->name = $request->name;
        $data->update();
        Session::flash('success','data update successfully');
        return redirect(route('role.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Role::find($id);
        if(empty($data)){
            Session::flash('error', 'data not found');
            return redirect(route('role.index'));
        }
        $data->delete();
        Session::flash('success', 'data deleted successfully');
        return redirect(route('role.index'));
    }
}
