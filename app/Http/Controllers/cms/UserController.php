<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Mail\UserCreate;
use App\Mail\UserDelete;
use App\Models\Employee;
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
            $data  = User::select('*')->with(['roles','employee'])->where('id','<>',auth()->user()->id);

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
            ->addColumn('details', function($data){
                $detailsUrl = route('employee.edit', ['employee' => $data->employee->id]);
                $detailsBtn = '<a href="' . $detailsUrl . '"><i class="fa fa-edit"></i><a>';
                return $detailsBtn;
            })
            ->addColumn('action', function($data){
                $editUrl = route('user.edit', ['user' => $data->id]);
                $editBtn = '<a href="' . $editUrl . '"><i class="fa fa-edit"></i><a>';
                return $editBtn;
            })
            ->rawColumns(['profile','roles','assign_role','details','action'])
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

        $employee    = new Employee();
        $employee->user_id   = $user->id;
        $employee->save();

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

    public function editProfile(){
        $id = auth()->user()->id;
        $data['user'] = User::with('employee')->find($id);
        return view('cms.employee.editProfile', $data);
    }

    public function submitProfile(Request $request){
        $id = auth()->user()->id;
        $user = User::with('employee')->find($id);
        $user->employee->address = $request->address;
        $user->employee->city = $request->city;
        $user->employee->phone_no = $request->phone_no;

        if($request->has('addhar_card')){
            if(file_exists('uploads/addhar_card/'. $user->employee->addhar_card)){
                File::delete('uploads/addhar_card/'. $user->employee->addhar_card);
            }

            // dd($request->addhar_card);
            $fileName = 'addhar_card_' . Carbon::now()->timestamp .'.'. $request->addhar_card->getClientOriginalExtension();
            // dd($request->addhar_card);
            $request->file('addhar_card')->move(public_path('uploads/addhar_card/'), $fileName);
            $user->employee->addhar_card = $fileName;
        }
        if($request->has('pan_card')){
            if(file_exists('uploads/pan_card/'. $user->employee->pan_card)){
                File::delete('uploads/pan_card/'. $user->employee->pan_card);
            }

            $fileName = 'pan_card_' . Carbon::now()->timestamp .'.'. $request->pan_card->getClientOriginalExtension();
            $request->file('pan_card')->move(public_path('uploads/pan_card/'), $fileName);
            $user->employee->pan_card = $fileName;
        }
        if($request->has('bank_document')){
            if(file_exists('uploads/bank_document/'. $user->employee->bank_document)){
                File::delete('uploads/bank_document/'. $user->employee->bank_document);
            }

            $fileName = 'bank_document_' . Carbon::now()->timestamp .'.'. $request->bank_document->getClientOriginalExtension();
            $request->file('bank_document')->move(public_path('uploads/bank_document/'), $fileName);
            $user->employee->bank_document = $fileName;
        }

        $user->employee->update();
        Session::flash('success','Profile updated');
        $data['message']            =       auth()->user()->name." profile updated";
        $data['action']             =       'update profile';
        $data['module']             =       'user';
        $data['object']             =       $user;
        saveLogs($data);
        return redirect(route('dashboard'));
    }
}
