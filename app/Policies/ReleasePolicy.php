<?php

namespace App\Policies;

use App\Models\Release;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReleasePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('releases_view');
    }

    public function view(User $user, Release $release)
    {
        return $user->can('releases_view');
    }

    public function create(User $user)
    {
        return $user->can('releases_create');
    }

    public function update(User $user, Release $release)
    {
        if ($user->can('releases_edit')) {
            if ($user->hasRole('ADMIN')) return true;
            if ($user->hasRole('VERIFIED_ENTITY')) {
                return $release->band->owner_id === $user->id; // Hierarchical Ownership
            }
        }
        return false;
    }

    public function delete(User $user, Release $release)
    {
        return $user->hasRole('ADMIN');
    }

    public function restore(User $user, Release $release)
    {
        return $user->hasRole('ADMIN');
    }

    public function forceDelete(User $user, Release $release)
    {
        return false; // Immutable audit trail
    }
}
