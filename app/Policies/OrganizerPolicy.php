<?php

namespace App\Policies;

use App\Models\Organizer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('organizers_view');
    }

    public function view(User $user, Organizer $organizer)
    {
        return $user->can('organizers_view');
    }

    public function create(User $user)
    {
        return $user->can('organizers_create');
    }

    public function update(User $user, Organizer $organizer)
    {
        if ($user->can('organizers_edit')) {
            if ($user->hasRole('ADMIN')) return true;
            if ($user->hasRole('VERIFIED_ENTITY')) {
                return $user->id === $organizer->owner_id;
            }
        }
        return false;
    }

    public function delete(User $user, Organizer $organizer)
    {
        return $user->hasRole('ADMIN');
    }

    public function restore(User $user, Organizer $organizer)
    {
        return $user->hasRole('ADMIN');
    }

    public function forceDelete(User $user, Organizer $organizer)
    {
        return false;
    }
}
