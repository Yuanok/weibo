<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
     public function create(){
         return view('sessions.create');
     }

     public function store(Request $request){
         $credentials = $this->validate($request,[
             'email' => 'required|email|max:255',
             'password' => 'required',
         ]);

         if(Auth::attempt($credentials)){
            session()->flash('succcess','欢迎回来！');
            return redirect()->route('users.show',[Auth::user()]);
         }else{
            session()->flash('danger','账号密码不对啦~');
            return redirect()->back()->withInput();
         }
         return;
     }

     public function destory(){
         Auth::logout();
         Session()->flash('success','退出成功！');
         return redirect('login');
     }

}
