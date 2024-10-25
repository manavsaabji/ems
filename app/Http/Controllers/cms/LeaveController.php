<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data  = Leave::select('*')->with('user');
            if(auth()->user()->hasRole('admin')){
                $data->where('status','pending');
            }else{
                $data->where('user_id', auth()->user()->id);
            }

            // dd($data);
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('user', function($data){
                if(auth()->user()->hasRole('admin')){
                    return $data->user->name ?? 'N/A';
                }
            })
            ->addColumn('action', function($data){
                $buttons = '';
                if(auth()->user()->hasRole('admin') && auth()->user()->id != $data->user->id){
                    $editUrl = route('leave.edit', ['leave' => $data->id]);
                    $editBtn = '<a href="' . $editUrl . '"><i class="fa fa-edit"></i><a>';
                    $buttons .= $editBtn;
                }
                if(auth()->user()->id == $data->user->id){
                    if($data->status == 'pending'){
                        $cancleUrl = route('leaveCancel', ['id' => $data->id]);
                        $btnCancel = '<form action="'. $cancleUrl .'"method="POST">
                              '.csrf_field().'
                              <button type="submit" style="background-color: transparent;border:0px"><a>CANCEL<a></button>
                              </form>';
                        $buttons .= $btnCancel;
                    }
                }
                $allBtns = '<div style="display:flex;">' . $buttons . '</div>';
                return $allBtns;

            })
            ->rawColumns(['user', 'action'])
            ->make(true);
        }
        return view('cms.leave.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['object'] = new Leave();
        $data['method'] = 'POST';
        $data['url'] = route('leave.store');

        $data['leaveType'] = [
            'casual' => 'Casual',
            'medical' => 'Medical',
            'emergency' => 'Emergency',
            'others' => 'Others',
        ];
        $data['leaveDuration'] = [
            'full day' => 'Full Day',
            'first half' => 'First Half',
            'second half' => 'Second Half',
        ];

        return view('cms.leave.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeaveRequest $request)
    {
        $object = Leave::where('user_id', auth()->user()->id)
                        ->whereIn('status', ['pending', 'approved'])
                        ->where(function ($query) use ($request) {
                            $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                                  ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
                        })
                        ->get();

        if($request->leave_duration == 'first half' || $request->leave_duration == 'second half'){
            $object = $object->whereIn('leave_duration',['full day', $request->leave_duration])->isEmpty();
        }else{
            $object = $object->isEmpty();
        }

        if (!$object) {
            return back()->withErrors(['dates' => 'You are filling in the same date again. please check the start date and end date.']);
        }

        $leave = new Leave();
        $today = Carbon::now()->toDateString();

        if (Carbon::parse($request->start_date)->lt($today)) {
            return back()->withErrors(['start_date' => 'The start date must be today or later.']);
        }
        if (Carbon::parse($request->end_date)->lt($request->start_date)) {
            return back()->withErrors(['end_date' => 'The end date must be start date or later.']);
        }
        $leave->start_date = $request->start_date;
        $leave->end_date = $request->end_date;
        $leave->reason = $request->reason;
        $leave->leave_type = $request->leave_type;
        $leave->leave_duration = $request->leave_duration;
        $leave->status = 'pending';
        $leave->user_id = auth()->user()->id;
        $leave->save();
        Session::flash('success', 'data saved successfully');
        return redirect(route('leave.index'));

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
        $data['object'] = Leave::with('user')->find($id);
        if(empty($data['object'])){
            Session::flash('error', 'data not found');
        }
        $data['method'] = 'PUT';
        $data['url'] = route('leave.update',['leave'=> $id]);

        $data['status'] = [
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ];

        return view('cms.leave.updateForm', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $leave = Leave::find($id);
        if(empty($leave)){
            Session::flash('error', 'data not found');
        }
        $leave->status = $request->status;
        $leave->update();
        Session::flash('success', 'data updated successfully');
        return redirect(route('leave.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function leaveCancel($id)
    {
        $leave = Leave::find($id);
        if(empty($leave)){
            Session::flash('error', 'data not found');
        }
        $leave->status = 'cancel';
        $leave->update();
        Session::flash('success', 'leave Cancel successfully');
        return redirect(route('leave.index'));
    }

    public function leaveIndexAll(Request $request)
    {
        if(auth()->user()->hasRole('admin')){
            if($request->ajax()){
                $data  = Leave::select('*')->with('user')->orderBy('start_date','DESC');
                return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user', function($data){
                    return $data->user->name ?? 'N/A';
                })
                ->rawColumns(['user'])
                ->make(true);
            }
            return view('cms.leave.indexAll');
        }
    }


}
