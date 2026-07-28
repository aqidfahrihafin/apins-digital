<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BlogPostsTags;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlogPostsTagsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BlogPostsTags');
    }

    public function view(AuthUser $authUser, BlogPostsTags $blogPostsTags): bool
    {
        return $authUser->can('View:BlogPostsTags');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BlogPostsTags');
    }

    public function update(AuthUser $authUser, BlogPostsTags $blogPostsTags): bool
    {
        return $authUser->can('Update:BlogPostsTags');
    }

    public function delete(AuthUser $authUser, BlogPostsTags $blogPostsTags): bool
    {
        return $authUser->can('Delete:BlogPostsTags');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:BlogPostsTags');
    }

    public function restore(AuthUser $authUser, BlogPostsTags $blogPostsTags): bool
    {
        return $authUser->can('Restore:BlogPostsTags');
    }

    public function forceDelete(AuthUser $authUser, BlogPostsTags $blogPostsTags): bool
    {
        return $authUser->can('ForceDelete:BlogPostsTags');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BlogPostsTags');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BlogPostsTags');
    }

    public function replicate(AuthUser $authUser, BlogPostsTags $blogPostsTags): bool
    {
        return $authUser->can('Replicate:BlogPostsTags');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BlogPostsTags');
    }

}