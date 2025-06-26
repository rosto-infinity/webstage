<?php

namespace App\Policies;

use App\Models\SocialMedia;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SocialMediaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, SocialMedia $socialMedia)
    {
        return $user->id === $socialMedia->user_id;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, SocialMedia $socialMedia)
    {
        return $user->id === $socialMedia->user_id;
    }

    public function delete(User $user, SocialMedia $socialMedia)
    {
        return $user->id === $socialMedia->user_id;
    }
}
