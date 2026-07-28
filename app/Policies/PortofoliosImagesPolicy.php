<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PortofoliosImages;
use Illuminate\Auth\Access\HandlesAuthorization;

class PortofoliosImagesPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PortofoliosImages');
    }

    public function view(AuthUser $authUser, PortofoliosImages $portofoliosImages): bool
    {
        return $authUser->can('View:PortofoliosImages');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PortofoliosImages');
    }

    public function update(AuthUser $authUser, PortofoliosImages $portofoliosImages): bool
    {
        return $authUser->can('Update:PortofoliosImages');
    }

    public function delete(AuthUser $authUser, PortofoliosImages $portofoliosImages): bool
    {
        return $authUser->can('Delete:PortofoliosImages');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PortofoliosImages');
    }

    public function restore(AuthUser $authUser, PortofoliosImages $portofoliosImages): bool
    {
        return $authUser->can('Restore:PortofoliosImages');
    }

    public function forceDelete(AuthUser $authUser, PortofoliosImages $portofoliosImages): bool
    {
        return $authUser->can('ForceDelete:PortofoliosImages');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PortofoliosImages');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PortofoliosImages');
    }

    public function replicate(AuthUser $authUser, PortofoliosImages $portofoliosImages): bool
    {
        return $authUser->can('Replicate:PortofoliosImages');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PortofoliosImages');
    }

}