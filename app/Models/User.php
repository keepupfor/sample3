<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name', 'email', 'password', ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [ 'password', 'remember_token', ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->activation_token = str_random(30);
        });
    }

    public function sentResetPasswordNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
    public function statuses()
    {
        return $this->hasMany(Statuses::class);
    }

    public function feed()
    {
        $user_ids=Auth::user()->followings->pluck('id')->toArray();
        array_push($user_ids,Auth::user()->id);
        return $this->statuses()->whereIn('user_id',$user_ids)
            ->with('user')
            ->orderBy('created_at','desc');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class,'followers','user_id','follower_id');
    }
    public function followings()
    {
        return $this->belongsToMany(User::class,'followers','follower_id','user_id');
    }
    public function follow($user_ids){
        if (!is_array($user_ids)){
            $user_ids=compact('user_ids');
        }
        $this->followings()->sync($user_ids);
    }
    public function unfollow($user_ids)
    {
        if (!is_array($user_ids)){
            $user_ids=compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }
    public function isfollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }
    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "https://www.gravatar.com/avatar/$hash?s=$size";
    }
}
