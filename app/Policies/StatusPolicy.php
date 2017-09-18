<?php

namespace App\Policies;

use App\Models\Statuses;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusPolicy
{
    use HandlesAuthorization;

    public function destroy(User $user , Statuses $statuses)
    {
        return $user->id === $statuses->user_id;
    }
}
