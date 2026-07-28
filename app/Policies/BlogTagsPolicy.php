<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BlogTags;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlogTagsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BlogTags');
    }

    public function view(AuthUser $authUser, BlogTags $blogTags): bool
    {
        return $authUser->can('View:BlogTags');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BlogTags');
    }

    public function update(AuthUser $authUser, BlogTags $blogTags): bool
    {
        return $authUser->can('Update:BlogTags');
    }

    public function delete(AuthUser $authUser, BlogTags $blogTags): bool
    {
        return $authUser->can('Delete:BlogTags');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:BlogTags');
    }

    public function restore(AuthUser $authUser, BlogTags $blogTags): bool
    {
        return $authUser->can('Restore:BlogTags');
    }

    public function forceDelete(AuthUser $authUser, BlogTags $blogTags): bool
    {
        return $authUser->can('ForceDelete:BlogTags');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BlogTags');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BlogTags');
    }

    public function replicate(AuthUser $authUser, BlogTags $blogTags): bool
    {
        return $authUser->can('Replicate:BlogTags');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BlogTags');
    }

}