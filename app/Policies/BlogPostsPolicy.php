<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BlogPosts;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlogPostsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BlogPosts');
    }

    public function view(AuthUser $authUser, BlogPosts $blogPosts): bool
    {
        return $authUser->can('View:BlogPosts');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BlogPosts');
    }

    public function update(AuthUser $authUser, BlogPosts $blogPosts): bool
    {
        return $authUser->can('Update:BlogPosts');
    }

    public function delete(AuthUser $authUser, BlogPosts $blogPosts): bool
    {
        return $authUser->can('Delete:BlogPosts');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:BlogPosts');
    }

    public function restore(AuthUser $authUser, BlogPosts $blogPosts): bool
    {
        return $authUser->can('Restore:BlogPosts');
    }

    public function forceDelete(AuthUser $authUser, BlogPosts $blogPosts): bool
    {
        return $authUser->can('ForceDelete:BlogPosts');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BlogPosts');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BlogPosts');
    }

    public function replicate(AuthUser $authUser, BlogPosts $blogPosts): bool
    {
        return $authUser->can('Replicate:BlogPosts');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BlogPosts');
    }

}