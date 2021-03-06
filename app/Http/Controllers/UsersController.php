<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UsersController extends Controller
{
    public function __construct(){
        $this->middleware('auth',[
          'except' => ['show','create','store','index']  
        ]);
        $this->middleware('guest',[
            'only' => ['create'],
        ]);
    }
    public function create(){
        return view('users.create');
    }

    public function show(User $user){
        $statuses = $user->statuses()->orderBy('created_at','desc')->paginate(15);
        return view('users.show',['user'=>$user,'statuses'=>$statuses]);
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|max:50',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>bcrypt($request->password),
        ]);
        Auth::login($user);
        session()->flash('success','欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show',$user);
    }

    public function edit(User $user){
        $this->authorize('update',$user);
        return view('users.edit',['user'=>$user]);
    }

    public function update(User $user,Request $request){
        $this->authorize('update',$user);
        $this->validate($request,[
            'name' => 'required|max:50',
            'password' => 'nullable|min:6|confirmed',
        ]);
        $data=[];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        return redirect()->route('users.show',$user->id);
    }

    public function index(){
        $users = User::paginate(10);
        return view('users.index',['users'=>$users]);
    }

    public function destroy(User $user){
        $this->authorize('destroy',$user);
        $user->delete();
        Session()->flash('success','删除成功~');
        return back();
    }

    public function followings(User $user){
        $users = $user->followings()->paginate(15);
        $title = $user->name.'关注的人';
        return view('users.show_follow',['users'=>$users,'title'=>$title]);
    }

    public function followers(User $user){
        $users = $user->followers()->paginate(15);
        $title = $user->name.'的粉丝';
        return view('users.show_follow',['users'=>$users,'title'=>$title]);
    }

}
