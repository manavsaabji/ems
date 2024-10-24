<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TestController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $data = User::with(['roles','employee.department'])->get();
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('department', function($data){
                if(!empty($data->employee) )
                {
                    if(!empty($data->employee->department))
                    {

                        return $data->employee->department->name;
                    }
                }
                return 'N/a';
            })
            ->addColumn('role', function($data){
                if($data->roles->isEmpty()){
                    return 'N/A';
                }
                $roles = $data->roles->pluck('name','name')->toArray();
                return implode(',',$roles);
            })
            ->addColumn('phone_no', function($data){
                if(empty($data->employee->phone_no)){
                    return 'N/A';
                }
                return $data->employee->phone_no;
            })
            ->rawColumns(['department', 'role','employee','phone_no'])
            ->make();
        }
        return view('cms.test.index');
    }
}
