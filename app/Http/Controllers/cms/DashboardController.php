<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data['activeUsers']    =  User::where('is_active',1)->count();
        $data['inActiveUsers']    =  User::where('is_active',0)->count();
        return view('cms.dashboard', $data);
    }
}
