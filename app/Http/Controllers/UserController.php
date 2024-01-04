<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\StoreRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Models\Church;
use App\Models\Department;
use App\Models\Member;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cache;

class UserController extends Controller
{
    public function logActivity()
    {
//        activity('USERS')
//            ->log("Accessed Users Audit Trail")->causer(request()->user());
        $logs = Activity::all();
       // dd($logs);
        return view('users.audits')->with([
            'logs' => $logs,
            'cpage' => 'users'
        ]);
    }
    public function userOnlineStatus()
    {
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            if (Cache::has('user-is-online-' . $user->id))
                echo "User " . $user->name . " is online.";
            else
                echo "User " . $user->name . " is offline.";
        }
    }
    public function index()
    {
        activity('USERS')
            ->log("Accessed users listing")->causer(request()->user());
        return view('users.index')->with([
            'users' => User::orderBy('id','desc')->get(),
            'cpage' => 'users'
        ]);
    }

    public function create()
    {
        return view('users.create')->with([
            'cpage' => 'users',
            'churches'=>Church::all(),
            'members'=>Member::all()
        ]);
    }

    public function show(User $user)
    {

        return view('users.show')->with([
            'user' => $user,
            'cpage' => 'users'
        ]);
    }

    public function store(StoreRequest $request)
    {
        if ($request->post('password')) {
            $password = bcrypt($request->post('password'));
        }
        $level = $request->post('level');
        if(empty($level)){
            $level = 0;
        }
        if(empty($request->post('department_id'))){
            $department_id = 0;
        }else{
            $department_id = $request->post('department_id');
        }
        if(empty($request->post('member_id'))){
            $member_id= 0;
        }else{
           $member_id = $request->post('member_id');
        }
        $data = [
            'password' => $password,
            'first_name' =>$request->post('first_name'),
            'last_name' => $request->post('last_name'),
            'designation' => $request->post('designation'),
            'position' => $request->post('position'),
            'member_id' => $member_id,
            'level' => $level,
            'department_id' =>$department_id,
            'phone_number' => $request->post('phone_number'),
            'email' => $request->post('email')
        ];
        User::create($data);
        activity('USERS')
            ->log("Created a User")->causer(request()->user());
        return redirect()->route('users.index')->with(['success-notification' => "User registered successfully!"]);
    }

    public function edit(User $user)
    {

        return view('users.edit')->with([
            'user' => $user,
            'cpage' => 'users',
            'churches'=>Church::all(),
            'members'=>Member::all()
        ]);
    }


    public function update(UpdateRequest $request, User $user)
    {
        $updates = collect($request->post())->except(['password'])->toArray();

        if ($request->post('password')) {
            $updates['password'] = bcrypt($request->post('password'));
        }
        $user->update($updates);
        activity('USERS')
            ->log("Updated a User")->causer(request()->user());
        return redirect()->route('users.show',$user->id)->with([
            'success-notification'=>"User  updated successfully!"
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        activity('USERS')
            ->log("Deleted a User")->causer(request()->user());
        return redirect()->route('users.index')->with(['success-notification' => "User deleted successfully!"]);
    }

    /*-------------------------------------
    | utility actions
    |------------------------------------*/
    public function settingsPage()
    {
       return view('users.settings')->with(
            ['user' => request()->user(),
                'cpage'=>"settings",
                'users'=>User::all(),
            ]);
    }

    public function settingsUpdate(User $user, Request $request)
    {
        $data = $request->post();

        $this->validate($request, ['password' => "required|string"]);
        if($request->post('password')!=$request->post('password_confirmation')){
            return redirect()->back()->with(['error-notification'=>"Passwords does not match"]);
        }
        $password = bcrypt($request->post('password'));

        DB::table('users')
            ->where(['id'=>$request->post('id')])
            ->update(['password' => $password]);
        $user->update($data);
        activity('USERS')
            ->log("Changed the user Password")->causer(request()->user());
        return redirect()->back()->with(['success-notification' => "Account settings successfully updated!"]);
    }
}
