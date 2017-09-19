<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Auth;
class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [ 'except' => [ 'create', 'store', 'show', 'index' ,'confirmEmail'] ]);
        $this->middleware('guest', [ 'only' => [ 'create' ] ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(20);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [ 'name' => 'required|max:50', 'email' => 'required|email|unique:users|max:255', 'password' => 'required' ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        if ($user->save()) {
            $this->sendConfirmEmailTo($user);
            session()->flash('warning', '账号激活信息已发送到您的邮箱，请登录邮箱激活');
            return redirect('/');
        } else {
            session()->flash('danger', '注册失败');
            return redirect()->back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $statuses=$user->statuses()->orderBy('created_at','desc')->paginate(30);
        return view('users.show', compact('user','statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [ 'name' => 'required|max:25', 'password' => 'nullable|confirmed|min:6' ]);
        $this->authorize('update', $user);
        $data = [];
        $data['name'] = $request->name;
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);
//        session()->flash();
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        return redirect()->back()->with('success', '删除用户成功');
    }

    public function sendConfirmEmailTo($user)
    {
        $view='emails.confirm';
        $data=compact('user');
        $from='458103210@qq.com';
        $to=$user->email;
        $name='NeverMore';
        $subject='感谢您注册应用，请确认您的邮箱';
        Mail::send($view,$data,function ($mail) use ($from,$name,$to,$subject){
           $mail->from($from,$name)->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();
        Auth::login($user);
        return redirect()->route('users.show', [$user])->with('success', '邮箱激活成功');
    }

    public function followers(User $user)
    {
        $title='粉丝列表';
        $users=$user->followers()->paginate(30);
        return view('users.show_follow',compact('users','title'));
    }
    public function followings(User $user)
    {
        $title='关注列表';
        $users=$user->followings()->paginate(30);
        return view('users.show_follow',compact('users','title'));
    }
}
