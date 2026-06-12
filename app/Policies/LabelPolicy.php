<?php

namespace App\Policies;

use App\Models\Label;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LabelPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('labels_view');
    }

    public function view(User $user, Label $label)
    {
        return $user->can('labels_view');
    }

    public function create(User $user)
    {
        return $user->can('labels_create');
    }

    public function update(User $user, Label $label)
    {
        if ($user->can('labels_edit')) {
            if ($user->hasRole('ADMIN')) return true;
            if ($user->hasRole('VERIFIED_ENTITY')) {
                return $user->id === $label->owner_id;
            }
        }
        return false;
    }

    public function delete(User $user, Label $label)
    {
        return $user->hasRole('ADMIN');
    }

    public function restore(User $user, Label $label)
    {
        return $user->hasRole('ADMIN');
    }

    public function forceDelete(User $user, Label $label)
    {
        return false;
    }
}
