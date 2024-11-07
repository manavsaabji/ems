<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModuleRequest;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_if(!auth()->user()->hasRole('admin'), 403);
        if($request->ajax())
        {
            $data  = Module::select('*')->with('permissions');
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('permissions', function($data){
                if($data->permissions->isEmpty()){
                    return 'N/A';
                }
                $permissions =  $data->permissions->pluck('name','name')->toArray();
                return implode(', ', $permissions);
            })
            ->addColumn('action', function($data){
                $editUrl = route('module.edit', ['module' => $data->id]);
                $deleteUrl = route('module.destroy', ['module' => $data->id]);

                $btnEdit = '<a href="'. $editUrl .'"><i class="fa fa-edit"></i><a>';
                $btnDelete = '<form action="'. $deleteUrl .'"method="POST">
                              '.csrf_field().'
                              '.method_field("DELETE").'
                              <button type="submit" style="background-color: transparent;border:0px"><i class="fa fa-trash text-red"></i></button>
                              </form>';
                $allBtns = '<div style="display:flex;">' . $btnEdit.$btnDelete . '</div>';
                return $allBtns;
            })
            ->rawColumns(['action','permissions'])
            ->make(true);
        }
        // $data['modules'] = Module::all();
        return view('cms.module.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(!auth()->user()->hasRole('admin'), 403);
        $data['object'] = new Module();
        $data['method'] = 'POST';
        $data['url'] = route('module.store');
        return view('cms.module.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ModuleRequest $request)
    {
        abort_if(!auth()->user()->hasRole('admin'), 403);
        $module = new Module();
        $module->name = $request->name;
        $module->save();
        $data['message']            =       auth()->user()->name." has created $module->name module";
        $data['action']             =       'created';
        $data['module']             =       'module';
        $data['object']             =       $module;
        saveLogs($data);
        return redirect(route('module.index'));
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
        abort_if(!auth()->user()->hasRole('admin'), 403);
        $data['object'] = Module::find($id);
        if(empty($data['object'])){
            Session::flash('error','data not found');
            return redirect(route('module.index'));
        }
        $data['method'] = 'PUT';
        $data['url'] = route('module.show',['module'=>$id]);
        return view('cms.module.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ModuleRequest $request, string $id)
    {
        abort_if(!auth()->user()->hasRole('admin'), 403);
        $module = Module::find($id);
        if(empty($module)){
            Session::flash('error','data not found');
            return redirect(route('module.index'));
        }
        $module->name = $request->name;
        $module->update();
        $data['message']            =       auth()->user()->name." has updated $module->name module";
        $data['action']             =       'updated';
        $data['module']             =       'module';
        $data['object']             =       $module;
        saveLogs($data);
        return redirect(route('module.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_if(!auth()->user()->hasRole('admin'), 403);
        $module = Module::find($id);
        if(empty($module)){
            Session::flash('error','data not found');
            return redirect(route('module.index'));
        }

        $data['message']            =       auth()->user()->name." has deleted $module->name module";
        $data['action']             =       'deleted';
        $data['module']             =       'module';
        $data['object']             =       $module;
        saveLogs($data);

        $module->delete();
        return redirect(route('module.index'));
    }
}
