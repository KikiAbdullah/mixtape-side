<?php

namespace App\Policies;

use App\Models\Gig;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GigPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('gigs_view');
    }

    public function view(User $user, Gig $gig)
    {
        return $user->can('gigs_view');
    }

    public function create(User $user)
    {
        return $user->can('gigs_create');
    }

    public function update(User $user, Gig $gig)
    {
        if ($user->can('gigs_edit')) {
            if ($user->hasRole('ADMIN')) return true;
            if ($user->hasRole('VERIFIED_ENTITY')) {
                // If it's owned by organizer, check organizer owner
                if ($gig->organizer) {
                    return $gig->organizer->owner_id === $user->id;
                }
                return $gig->created_by === $user->id;
            }
        }
        return false;
    }

    public function delete(User $user, Gig $gig)
    {
        return $user->hasRole('ADMIN');
    }

    public function restore(User $user, Gig $gig)
    {
        return $user->hasRole('ADMIN');
    }

    public function forceDelete(User $user, Gig $gig)
    {
        return false;
    }
}
