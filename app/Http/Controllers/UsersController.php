<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',[
            'except'=>['create','store','show','index']
        ]);
        $this->middleware('guest',[
            'only'=>['create']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users=User::paginate(20);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required'
        ]);
        $user=new User();
            $user->name =$request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
        if ($user->save()){
            Auth::login($user);
            session()->flash('success','欢迎！');
            return redirect()->route('users.show',$user);
        }
        else{
            session()->flash('danger','注册失败');
            return redirect()->back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view( 'users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request,[
            'name'=>'required|max:25',
            'password'=>'nullable|confirmed|min:6'
        ]);
        $this->authorize('update',$user);
        $data=[];
        $data['name']=$request->name;
        if ($request->filled('password')){
           $data['password']=bcrypt($request->password);
        }
        $user->update($data);
//        session()->flash();
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('destroy',$user);
        $user->delete();
        return redirect()->back()->with('success','删除用户成功');
    }
}
