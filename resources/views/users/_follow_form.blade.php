@if(Auth::user()->id!==$user->id)
    @if(Auth::user()->isFollowing($user->id))
        <form action="{{ route('followers.destroy', $user->id) }}" method="post" id="follow_form">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button type="submit" class="btn btn-sm">取消关注</button>
        </form>
        @else
        <form action="{{ route('followers.store', $user->id) }}" method="post" id="follow_form">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-sm btn-primary">关注</button>
        </form>
        @endif
    @endif