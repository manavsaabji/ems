<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLogs;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogsController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $data  =  ActivityLogs::join("users","users.id","=","activity_logs.action_by")->select('activity_logs.module as Module',
            'activity_logs.action as Action','activity_logs.message as Message','users.name as Responsible','activity_logs.created_at as Time')->get();

            return DataTables::of($data)->addIndexColumn()->make(true);
        }

        return view("cms.activityLogs");
    }

    public function saveLogs($data)
    {
        if(!auth()->user()->hasRole('admin'))
        {
            $activityLogs               =  new ActivityLogs();
            $activityLogs->action       =  $data['action'];
            $activityLogs->module       =  $data['module'];
            $activityLogs->module_id    =  $data['object']->id;
            $activityLogs->message      =  $data['message'];
            $activityLogs->action_by    =  auth()->user()->id;
            $activityLogs->save();
        }
    }
}
