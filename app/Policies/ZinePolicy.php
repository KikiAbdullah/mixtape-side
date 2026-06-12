<?php

namespace App\Policies;

use App\Models\Zine;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ZinePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('zines_view');
    }

    public function view(User $user, Zine $zine)
    {
        return $user->can('zines_view');
    }

    public function create(User $user)
    {
        return $user->can('zines_create');
    }

    public function update(User $user, Zine $zine)
    {
        if ($user->can('zines_edit')) {
            if ($user->hasRole('ADMIN')) return true;
            return $zine->author_id === $user->id;
        }
        return false;
    }

    public function delete(User $user, Zine $zine)
    {
        if ($user->can('zines_delete')) {
            if ($user->hasRole('ADMIN')) return true;
            return $zine->author_id === $user->id;
        }
        return false;
    }

    public function restore(User $user, Zine $zine)
    {
        return $user->hasRole('ADMIN');
    }

    public function forceDelete(User $user, Zine $zine)
    {
        return false;
    }
}
