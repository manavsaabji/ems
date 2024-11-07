<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('management',new User());
        if($request->ajax())
        {
            $data  = Role::select('*');
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('assign_permission', function($data){

                if($data->name == 'admin')
                {
                    return 'All Access';
                }else{
                $assignUrl = route('assignPermission', ['id' => $data->id]);
                $btnAssign = '<a href="'. $assignUrl .'"><i class="fa fa-edit"></i><a>';
                return $btnAssign;
                }
            })
            ->addColumn('action', function($data){
                $editUrl = route('role.edit', ['role' => $data->id]);
                $deleteUrl = route('role.destroy', ['role' => $data->id]);

                $btnEdit = '<a href="'. $editUrl .'"><i class="fa fa-edit"></i><a>';
                $btnDelete = '<form action="'. $deleteUrl .'"method="POST">
                              '.csrf_field().'
                              '.method_field("DELETE").'
                              <button type="submit" style="background-color: transparent;border:0px"><i class="fa fa-trash text-red"></i></button>
                              </form>';
                $allBtns = '<div style="display:flex;">' . $btnEdit.$btnDelete . '</div>';
                return $allBtns;
            })
            ->rawColumns(['action','assign_permission'])
            ->make(true);
        }
        return view('cms.role.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('management',new User());
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
        $this->authorize('management',new User());
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
        $this->authorize('management',new User());
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
        $this->authorize('management',new User());
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
        $this->authorize('management',new User());
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
    public function assignPermission($id)
    {
        $this->authorize('management',new User());
        $data['role'] = Role::with('permissions')->find($id);
        if(empty($data['role'])){
            Session::flash('error', 'data not found');
            return back();
        }
        $data['permissions'] = Permission::with('module')->get()->groupBy('module.name');
        // dd($data['permissions']);
        return view('cms.role.assignPermission', $data);
    }

    public function submitPermission(Request $request)
    {
        $this->authorize('management',new User());
        $role = Role::find($request->id);
        if(empty($role)){
            Session::flash('error', 'data not found');
            return redirect(route('role.index'));
        }
        $role->permissions()->sync($request->permission_id);

        $data['message']            =       auth()->user()->name." has assign permission $role->name";
        $data['action']             =       'assign permission';
        $data['module']             =       'role';
        $data['object']             =       $role;
        saveLogs($data);
        Session::flash('success', 'assign permission success');
        return back();
    }

}
