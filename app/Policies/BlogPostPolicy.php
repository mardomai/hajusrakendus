<?php

namespace App\Policies;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlogPostPolicy
{
    use HandlesAuthorization;

    public function update(User $user, BlogPost $post)
    {
        return $user->id === $post->user_id;
    }

    public function delete(User $user, BlogPost $post)
    {
        return $user->id === $post->user_id;
    }
} 