<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest',[
            'only'=>['create']
        ]);
    }

    public function create()
    {
      return view('session.create');
    }
    public function store(Request $request)
    {
          $this->validate($request,[
              'email' => 'required|email|max:255',
              'password' => 'required'
          ]);
        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
        ];
        if (Auth::attempt($credentials,$request->has('remember')))
        {
            if (Auth::user()->activated){
                return redirect()->intended(route('users.show', [Auth::user()]));
            }else{
                Auth::logout();
                return redirect()->back()->with('danger','您的账号尚未激活，请登录邮箱激活');
            }
        } else {
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back();
        }
    }
    public function destroy()
    {
        Auth::logout();
//        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
}
