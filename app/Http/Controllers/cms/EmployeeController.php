<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $data['object'] = Employee::with('user')->find($id);
        if(empty($data['object'])){
            Session::flash('success','Data not found');
            return redirect(route('user.index'));
        }
        $data['method'] = 'PUT';
        $data['url'] = route('employee.update',['employee'=> $id]);
        $data['department'] = Department::pluck('name', 'id')->toArray();

        return view('cms.employee.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, string $id)
    {
        // dd($request->id);
        $employee = Employee::find($id);
        if(empty($employee)){
            Session::flash('error','data not found');
            return redirect(route('user.index'));
        }
        $employee->address = $request->address;
        $employee->city = $request->city;
        $employee->phone_no = $request->phone_no;
        $employee->salary = $request->salary;
        $employee->department_id = $request->department_id;
        
        if($request->has('addhar_card')){
            if(file_exists('uploads/addhar_card/'. $employee->addhar_card)){
                File::delete('uploads/addhar_card/'. $employee->addhar_card);
            }

            // dd($request->addhar_card);
            $fileName = 'addhar_card_' . Carbon::now()->timestamp .'.'. $request->addhar_card->getClientOriginalExtension();
            // dd($request->addhar_card);
            $request->file('addhar_card')->move(public_path('uploads/addhar_card/'), $fileName);
            $employee->addhar_card = $fileName;
        }
        if($request->has('pan_card')){
            if(file_exists('uploads/pan_card/'. $employee->pan_card)){
                File::delete('uploads/pan_card/'. $employee->pan_card);
            }

            $fileName = 'pan_card_' . Carbon::now()->timestamp .'.'. $request->pan_card->getClientOriginalExtension();
            $request->file('pan_card')->move(public_path('uploads/pan_card/'), $fileName);
            $employee->pan_card = $fileName;
        }
        if($request->has('bank_document')){
            if(file_exists('uploads/bank_document/'. $employee->bank_document)){
                File::delete('uploads/bank_document/'. $employee->bank_document);
            }

            $fileName = 'bank_document_' . Carbon::now()->timestamp .'.'. $request->bank_document->getClientOriginalExtension();
            $request->file('bank_document')->move(public_path('uploads/bank_document/'), $fileName);
            $employee->bank_document = $fileName;
        }

        $employee->update();
        Session::flash('success','details updated');
        return redirect(route('user.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
