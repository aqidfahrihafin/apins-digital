<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PortofoliosCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class PortofoliosCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PortofoliosCategory');
    }

    public function view(AuthUser $authUser, PortofoliosCategory $portofoliosCategory): bool
    {
        return $authUser->can('View:PortofoliosCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PortofoliosCategory');
    }

    public function update(AuthUser $authUser, PortofoliosCategory $portofoliosCategory): bool
    {
        return $authUser->can('Update:PortofoliosCategory');
    }

    public function delete(AuthUser $authUser, PortofoliosCategory $portofoliosCategory): bool
    {
        return $authUser->can('Delete:PortofoliosCategory');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PortofoliosCategory');
    }

    public function restore(AuthUser $authUser, PortofoliosCategory $portofoliosCategory): bool
    {
        return $authUser->can('Restore:PortofoliosCategory');
    }

    public function forceDelete(AuthUser $authUser, PortofoliosCategory $portofoliosCategory): bool
    {
        return $authUser->can('ForceDelete:PortofoliosCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PortofoliosCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PortofoliosCategory');
    }

    public function replicate(AuthUser $authUser, PortofoliosCategory $portofoliosCategory): bool
    {
        return $authUser->can('Replicate:PortofoliosCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PortofoliosCategory');
    }

}