<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Contacts;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Contacts');
    }

    public function view(AuthUser $authUser, Contacts $contacts): bool
    {
        return $authUser->can('View:Contacts');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Contacts');
    }

    public function update(AuthUser $authUser, Contacts $contacts): bool
    {
        return $authUser->can('Update:Contacts');
    }

    public function delete(AuthUser $authUser, Contacts $contacts): bool
    {
        return $authUser->can('Delete:Contacts');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Contacts');
    }

    public function restore(AuthUser $authUser, Contacts $contacts): bool
    {
        return $authUser->can('Restore:Contacts');
    }

    public function forceDelete(AuthUser $authUser, Contacts $contacts): bool
    {
        return $authUser->can('ForceDelete:Contacts');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Contacts');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Contacts');
    }

    public function replicate(AuthUser $authUser, Contacts $contacts): bool
    {
        return $authUser->can('Replicate:Contacts');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Contacts');
    }

}