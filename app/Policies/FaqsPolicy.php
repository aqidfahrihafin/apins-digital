<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Faqs;
use Illuminate\Auth\Access\HandlesAuthorization;

class FaqsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Faqs');
    }

    public function view(AuthUser $authUser, Faqs $faqs): bool
    {
        return $authUser->can('View:Faqs');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Faqs');
    }

    public function update(AuthUser $authUser, Faqs $faqs): bool
    {
        return $authUser->can('Update:Faqs');
    }

    public function delete(AuthUser $authUser, Faqs $faqs): bool
    {
        return $authUser->can('Delete:Faqs');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Faqs');
    }

    public function restore(AuthUser $authUser, Faqs $faqs): bool
    {
        return $authUser->can('Restore:Faqs');
    }

    public function forceDelete(AuthUser $authUser, Faqs $faqs): bool
    {
        return $authUser->can('ForceDelete:Faqs');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Faqs');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Faqs');
    }

    public function replicate(AuthUser $authUser, Faqs $faqs): bool
    {
        return $authUser->can('Replicate:Faqs');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Faqs');
    }

}