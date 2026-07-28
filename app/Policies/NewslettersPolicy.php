<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\NewsLetters;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsLettersPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:NewsLetters');
    }

    public function view(AuthUser $authUser, NewsLetters $newsLetters): bool
    {
        return $authUser->can('View:NewsLetters');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:NewsLetters');
    }

    public function update(AuthUser $authUser, NewsLetters $newsLetters): bool
    {
        return $authUser->can('Update:NewsLetters');
    }

    public function delete(AuthUser $authUser, NewsLetters $newsLetters): bool
    {
        return $authUser->can('Delete:NewsLetters');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:NewsLetters');
    }

    public function restore(AuthUser $authUser, NewsLetters $newsLetters): bool
    {
        return $authUser->can('Restore:NewsLetters');
    }

    public function forceDelete(AuthUser $authUser, NewsLetters $newsLetters): bool
    {
        return $authUser->can('ForceDelete:NewsLetters');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:NewsLetters');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:NewsLetters');
    }

    public function replicate(AuthUser $authUser, NewsLetters $newsLetters): bool
    {
        return $authUser->can('Replicate:NewsLetters');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:NewsLetters');
    }

}