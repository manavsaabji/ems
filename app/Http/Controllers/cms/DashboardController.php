<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data = [];
        if(auth()->user()->hasAnyRole(['admin','hr'])){
            $data['activeUsers']    =  User::where('is_active',1)->count();
            $data['inActiveUsers']    =  User::where('is_active',0)->count();

            $today = Carbon::now()->toDateString();
            $data['leaveApproved']    =  Leave::where('status','approved')->where(function($query) use($today){
                $query->where('start_date','<=',$today)->where('end_date','>=',$today);
            })->count();

            $data['leavePending']    =  Leave::where('status','pending')->count();
            // dd($data['leaveApproved'], $data['leavePending']);
        }

        return view('cms.dashboard', $data);
    }
}
