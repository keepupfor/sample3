<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users=factory(User::class)->times(50)->make();
        User::insert($users->makeVisible(['password','remember_token'])->toArray());
        $user=User::first();
        $user->name='NeverMore';
        $user->email='458103210@qq.com';
        $user->is_admin=true;
        $user->password=bcrypt('111111');
        $user->save();

    }
}
