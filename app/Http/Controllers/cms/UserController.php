<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Mail\UserCreate;
use App\Mail\UserDelete;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {

            // select(..)->whereNotIn('book_price', [100,200])->get();
            $data  = User::select('*')->with('roles')->where('id','<>',auth()->user()->id);
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('profile', function($data){
                if(empty($data->profile_pic)){
                    $emptyUrl = asset('assets/images/user.jpg');
                    $image = '<div class="image">';
                    $image .= '<img src="'.$emptyUrl.'" style="width:35px; height:35px" class="img-circle elevation-2" alt="User Image">';
                    $image .= '</div>';
                    return $image;
                }else{
                    $profileUrl = asset('uploads/profile/'.$data->profile_pic);
                    $image = '<div class="image">';
                    $image .= '<img src="'.$profileUrl.'" style="width:35px; height:35px" class="img-circle elevation-2" alt="User Image">';
                    $image .= '</div>';
                    return $image;
                }
            })
            ->addColumn('roles', function($data){
                if($data->roles->isEmpty())
                {
                    return 'N/A';
                }

                $roles = $data->roles->pluck('name','name')->toArray();
                return implode(', ', $roles);
            })
            ->addColumn('assign_role', content: function($data){
                $assignRoleUrl = route('assignRole', ['id' => $data->id]);
                $assignRoleBtn = '<a href="' . $assignRoleUrl . '"><i class="fa fa-edit"></i><a>';
                return $assignRoleBtn;
            })
            ->addColumn('action', function($data){
                $editUrl = route('user.edit', ['user' => $data->id]);
                $editBtn = '<a href="' . $editUrl . '"><i class="fa fa-edit"></i><a>';
                return $editBtn;
            })
            ->rawColumns(['profile','action','assign_role','roles'])
            ->make(true);
        }
        return view('cms.user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['object'] = new User();
        $data['method'] = 'POST';
        $data['url'] = route('user.store');
        return view('cms.user.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $randomPassword = Str::random(12);
        $user->password = Hash::make($randomPassword);
        if($request->has('profile_pic'))
        {
            $imageName = "profile_". Carbon::now()->timestamp . '.' . $request->file('profile_pic')->getClientOriginalExtension();
            $request->file('profile_pic')->move(public_path('uploads/profile/'), $imageName);
            // dd($imageName);
            $user->profile_pic = $imageName;
        }
        $user->is_active = 1;
        $user->save();
        Mail::to($user->email)->send(new UserCreate($user,$randomPassword));

        $data['message']            =       auth()->user()->name." has created $user->name account";
        $data['action']             =       'created';
        $data['module']             =       'user';
        $data['object']             =       $user;
        saveLogs($data);
        Session::flash('success','data saved');
        return redirect(route('user.index'));

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
        $data['object'] = User::find($id);
        if(empty($data['object'])){
            Session::flash('error','data not found');
            return back();
        }
        $data['method'] = 'PUT';
        $data['url'] = route('user.update',['user'=> $id]);
        return view('cms.user.form', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $user = User::find($id);
        if(empty($user)){
            Session::flash('error','data not found');
            return redirect(route('user.index'));
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_active =  isset($request->is_active) ? 1:0;
        if($request->has('profile_pic'))
        {
            // dd("uploads/profile/" . $user->profile_pic);
            if(file_exists(public_path('uploads/profile/'.$user->profile_pic)))
            {
                File::delete("uploads/profile/" . $user->profile_pic);
            }
            $imageName = "profile_". Carbon::now()->timestamp . '.' . $request->file('profile_pic')->getClientOriginalExtension();
            $request->file('profile_pic')->move(public_path('uploads/profile/'), $imageName);
            // dd($imageName);
            $user->profile_pic = $imageName;
        }
        $user->update();

        $data['message']            =       auth()->user()->name." has updated $user->name account";
        $data['action']             =       'updated';
        $data['module']             =       'user';
        $data['object']             =       $user;
        saveLogs($data);

        Session::flash('success','data updated');
        return redirect(route('user.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if(empty($user)){
            Session::flash('error','data deleted');
            return back();
        }
        Mail::to($user->email)->send(new UserDelete($user));
        if(file_exists("uploads/profile/" . $user->profile_pic))
        {
            File::delete("uploads/profile/" . $user->profile_pic);
        }

        $data['message']            =       auth()->user()->name." has deleted $user->name account";
        $data['action']             =       'deleted';
        $data['module']             =       'user';
        $data['object']             =       $user;
        saveLogs($data);

        $user->delete();
        Session::flash('success','data deleted');
        return redirect(route('user.index'));
    }

    public function assignRole($id)
    {
        $data['user'] = User::with('roles')->find($id);
        if(empty($data['user'])){
            Session::flash('error','data not found in assign role');
            return redirect(route('user.index'));
        }
        $data['roles'] = Role::pluck('name','id')->toArray();

        return view('cms.user.assignRole', $data);

    }
    public function submitRole(Request $request)
    {
        // dd($request);
        $user = User::find($request->id);
        if(empty($user)){
            Session::flash('error', 'data not found in submit role');
            return redirect(route('user.index'));
        }
        $user->roles()->sync($request->role_id);

        $data['message']            =       auth()->user()->name." has assign role $user->name";
        $data['action']             =       'assign role';
        $data['module']             =       'user';
        $data['object']             =       $user;
        saveLogs($data);
        Session::flash('success', 'assign role success');
        return back();

    }
}
