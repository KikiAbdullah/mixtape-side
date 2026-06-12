<?php

namespace App\Policies;

use App\Models\Band;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BandPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('bands_view');
    }

    public function view(User $user, Band $band)
    {
        return $user->can('bands_view');
    }

    public function create(User $user)
    {
        return $user->can('bands_create');
    }

    public function update(User $user, Band $band)
    {
        if ($user->can('bands_edit')) {
            if ($user->hasRole('ADMIN')) return true;
            if ($user->hasRole('VERIFIED_ENTITY')) {
                return $user->id === $band->owner_id;
            }
        }
        return false;
    }

    public function delete(User $user, Band $band)
    {
        return $user->hasRole('ADMIN');
    }

    public function restore(User $user, Band $band)
    {
        return $user->hasRole('ADMIN');
    }

    public function forceDelete(User $user, Band $band)
    {
        return false; // Immutable audit trail
    }
}
