<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedMonth = $request->select_month ?? now()->format('Y-m');
        $date = Carbon::createFromFormat('Y-m', $selectedMonth);
        $daysInMonth = $date->daysInMonth;
        $leaves = Leave::where(function($query) use($date){
                                $query->whereYear('start_date', $date->year)->whereMonth('start_date', $date->month)->where('status','approved');
                            })
                            ->orWhere(function($query2) use($date){
                                $query2->whereYear('end_date', $date->year)->whereMonth('end_date', $date->month)->where('status','approved');
                            })
                            ->with('user')
                            ->get()
                            ->groupBy('user_id');

        // dd($leaves);

        $attendances = Attendance::whereYear('date', $date->year)
        ->whereMonth('date', $date->month)
        ->with('user')
        ->get()
        ->groupBy('user_id');
        return view('cms.attendance.index', compact('attendances', 'daysInMonth', 'selectedMonth', 'leaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attendance = new Attendance();
        $isSunday = Carbon::parse(Carbon::now()->toDateString())->isSunday();
        if($isSunday){
            return response()->json(['message' => 'Punch-in is not allowed today as it is Sunday.']);
        }
        $attendance->date = Carbon::now()->toDateString();

        $attendance->arrival_time = Carbon::now()->format('H:i:s');
        $attendance->user_id = auth()->user()->id;
        $attendance->save();

        $data['message']            =       auth()->user()->name." has punch In attendance";
        $data['action']             =       'created';
        $data['module']             =       'attendance';
        $data['object']             =       $attendance;
        saveLogs($data);

        return response()->json(['message' => 'You are punched In successfully.']);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $attendance = Attendance::where('user_id', auth()->user()->id)
        ->whereDate('date', Carbon::now()->toDateString())
        ->orderBy('date', 'asc')
        ->first();

        $isSunday = Carbon::parse(Carbon::now()->toDateString())->isSunday();
        if($isSunday){
            return response()->json(['message' => 'Punch-in is not allowed today as it is Sunday.']);
        }

        $attendance->end_time = Carbon::now()->format('H:i:s');
        $attendance->update();

        $data['message']            =       auth()->user()->name." has punch Out attendance";
        $data['action']             =       'updated';
        $data['module']             =       'attendance';
        $data['object']             =       $attendance;
        saveLogs($data);

        return response()->json(['message' => 'You are punched Out successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getFirstDate()
    {
        $firstDate = Attendance::where('user_id', auth()->user()->id)
        ->whereDate('date', Carbon::now()->toDateString())
        ->orderBy('date', 'asc')
        ->first();
        if(empty($firstDate)){
            return response()->json(['response' => 'no data'], 404);
        }else{
            $today = Carbon::now()->toDateString();
            $res = 'punch in';
            if($today == $firstDate->date){
                $res = 'punch out';
                if($firstDate->end_time){
                    $res = 'attendence done';
                }
            }
        }
        if ($firstDate) {
            return response()->json(['response' => $res], 200);
        } else {
            return response()->json(['response' => 'no data'], 404);
        }
    }

    public function manuallyAttendance(Request $request)
    {
        $this->authorize('mannualAttendance',new Attendance());
        $data['users'] = User::pluck('name', 'id')->toArray();

        return view('cms.attendance.form', $data);
    }
    public function getManuallyAttendance(Request $request)
    {
        // $this->authorize('mannualAttendance',new Attendance());
        // $attendance = Attendance::where('user_id', $request->user)
        //     ->whereDate('date', $request->date)
        //     ->first();
        // dd($attendance->arrival_time);
        // if ($attendance) {
        //     return response()->json([
        //             'response' => [
        //             'arrival_time' => Carbon::createFromFormat('H:i', $attendance->arrival_time),
        //             'end_time' => $attendance->end_time->format('H:i'),
        //         ]
        //     ], 200);
        // } else {
        //     return response()->json(['response' => ''], 200);
        // }
    }

    public function submitManuallyAttendance(AttendanceRequest $request)
    {
        // $attendance = Attendance::where('user_id', $request->user)
        //     ->whereDate('date', $request->date)
        //     ->first();

        // if($attendance){
        //     if($request->punch_in || $request->punch_out){
        //         if($request->punch_in){
        //             $attendance->arrival_time = $request->punch_in;
        //         }
        //         if($request->punch_out){
        //             $attendance->end_time = $request->punch_out;
        //         }
        //         $attendance->arrival_time = $request->punch_in;
        //         $attendance->end_time = $request->punch_out;
        //         $attendance->update();
        //         Session::flash('success', 'sucessfully updated');
        //         return redirect(route('attendance.index'));
        //     }
        //     return back();

        // }else{
        //     $attendance = new Attendance();
        //     $attendance->date = $request->date;
        //     if($request->punch_in || $request->punch_out){
        //         if($request->punch_in){
        //             $attendance->arrival_time = $request->punch_in;
        //         }
        //         if($request->punch_out){
        //             $attendance->end_time = $request->punch_out;
        //         }
        //         $attendance->user_id = $request->user;
        //         $attendance->save();
        //         Session::flash('success', 'sucessfully created');
        //         return redirect(route('attendance.index'));
        //     }
        //     return back();
        // }


        $this->authorize('mannualAttendance',new Attendance());
        
        $isSunday = Carbon::parse($request->date)->isSunday();
        if($isSunday){
            Session::flash('error','Punch-in is not allowed today as it is Sunday.');
            return back();
        }

        $leaveDuration      =       Leave::where('user_id',$request->user)->where(function($query) use($request){
                                                        $query->where('start_date',$request->date)->orWhere('end_date',$request->date)
                                                            ->orWhere(function($query2) use($request){
                                                            $query2->where('start_date','<=',$request->date)->where('end_date','>=',$request->date);
                                                        });
                                                    })->where('status','approved')->count();
        if( $leaveDuration > 0)
        {
            Session::flash('error','User are on leave today');
            return back();
        }

        $attendance         =       Attendance::where('user_id',$request->user)->whereDate('date',$request->date)->first();




        // dd($attendance);
        if(!empty( $attendance))
        {
            $attendance->arrival_time     =     $request->punch_in;
            $attendance->end_time         =     $request->punch_out;
            $attendance->update();
        }else{
            $attendance                   =   new Attendance();
            $attendance->arrival_time     =     $request->punch_in;
            $attendance->end_time         =     $request->punch_out;
            $attendance->date             =     $request->date;
            $attendance->user_id          =     $request->user;
            $attendance->save();
        }

        $data['message']            =       auth()->user()->name." has created manul attendance";
        $data['action']             =       'created';
        $data['module']             =       'manual';
        $data['object']             =       $attendance;
        saveLogs($data);

        Session::flash('success','Data store');
        return redirect(route('attendance.index'));
    }
}

