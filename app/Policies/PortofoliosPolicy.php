<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Portofolios;
use Illuminate\Auth\Access\HandlesAuthorization;

class PortofoliosPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Portofolios');
    }

    public function view(AuthUser $authUser, Portofolios $portofolios): bool
    {
        return $authUser->can('View:Portofolios');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Portofolios');
    }

    public function update(AuthUser $authUser, Portofolios $portofolios): bool
    {
        return $authUser->can('Update:Portofolios');
    }

    public function delete(AuthUser $authUser, Portofolios $portofolios): bool
    {
        return $authUser->can('Delete:Portofolios');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Portofolios');
    }

    public function restore(AuthUser $authUser, Portofolios $portofolios): bool
    {
        return $authUser->can('Restore:Portofolios');
    }

    public function forceDelete(AuthUser $authUser, Portofolios $portofolios): bool
    {
        return $authUser->can('ForceDelete:Portofolios');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Portofolios');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Portofolios');
    }

    public function replicate(AuthUser $authUser, Portofolios $portofolios): bool
    {
        return $authUser->can('Replicate:Portofolios');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Portofolios');
    }

}