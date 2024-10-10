<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['modules'] = Module::all();
        return view('cms.module.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['object'] = new Module();
        $data['method'] = 'POST';
        $data['url'] = route('module.store');
        return view('cms.module.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
    public function update(Request $request, string $id)
    {
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
