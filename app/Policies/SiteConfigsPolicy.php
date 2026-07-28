<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SiteConfigs;
use Illuminate\Auth\Access\HandlesAuthorization;

class SiteConfigsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SiteConfigs');
    }

    public function view(AuthUser $authUser, SiteConfigs $siteConfigs): bool
    {
        return $authUser->can('View:SiteConfigs');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SiteConfigs');
    }

    public function update(AuthUser $authUser, SiteConfigs $siteConfigs): bool
    {
        return $authUser->can('Update:SiteConfigs');
    }

    public function delete(AuthUser $authUser, SiteConfigs $siteConfigs): bool
    {
        return $authUser->can('Delete:SiteConfigs');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:SiteConfigs');
    }

    public function restore(AuthUser $authUser, SiteConfigs $siteConfigs): bool
    {
        return $authUser->can('Restore:SiteConfigs');
    }

    public function forceDelete(AuthUser $authUser, SiteConfigs $siteConfigs): bool
    {
        return $authUser->can('ForceDelete:SiteConfigs');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SiteConfigs');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SiteConfigs');
    }

    public function replicate(AuthUser $authUser, SiteConfigs $siteConfigs): bool
    {
        return $authUser->can('Replicate:SiteConfigs');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SiteConfigs');
    }

}