<?php

namespace App\Http\Controllers;

use App\Models\Statuses;
use Illuminate\Http\Request;
use Auth;
class StatusesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->validate($request, [ 'content' => 'required' ]);
        Auth::user()->statuses()->create([ 'content' => $request->content ]);
        return back()->with('success', '添加动态成功');
    }

    public function destroy(Statuses $status)
    {
        $this->authorize('destroy', $status);
        $status->delete();
        return back()->with('success', '删除动态成功');
    }
}
