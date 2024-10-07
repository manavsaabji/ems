<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Mail\UserCreate;
use App\Mail\UserDelete;
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
        // $data['users'] = User::all();
        // return view('cms.user.index', $data);

        if($request->ajax())
        {
            $data  = User::select('*');
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($data){
                // <a href="{{ route('user.edit', ['user' => $user->id]) }}"><i class="fa fa-edit"></i><a>
                //                             <form action="{{ route('user.destroy', ['user' => $user->id]) }}"
                //                                 method="POST">
                //                                 @csrf
                //                                 @method('delete')
                //                                 <button type="submit" style="background-color: transparent;border:0px"><i
                //                                         class="fa fa-trash text-red"></i></button>
                //                             </form>
                $editUrl = route('user.edit', ['user' => $data->id]);
                $editBtn = '<a href="' . $editUrl . '"><i class="fa fa-edit"></i><a>';
                return $editBtn;
            })
            ->rawColumns(['action'])
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
        $user->delete();
        Session::flash('success','data deleted');
        return redirect(route('user.index'));
    }
}
