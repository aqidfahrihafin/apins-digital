<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ActivityLogs;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityLogsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ActivityLogs');
    }

    public function view(AuthUser $authUser, ActivityLogs $activityLogs): bool
    {
        return $authUser->can('View:ActivityLogs');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ActivityLogs');
    }

    public function update(AuthUser $authUser, ActivityLogs $activityLogs): bool
    {
        return $authUser->can('Update:ActivityLogs');
    }

    public function delete(AuthUser $authUser, ActivityLogs $activityLogs): bool
    {
        return $authUser->can('Delete:ActivityLogs');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ActivityLogs');
    }

    public function restore(AuthUser $authUser, ActivityLogs $activityLogs): bool
    {
        return $authUser->can('Restore:ActivityLogs');
    }

    public function forceDelete(AuthUser $authUser, ActivityLogs $activityLogs): bool
    {
        return $authUser->can('ForceDelete:ActivityLogs');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ActivityLogs');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ActivityLogs');
    }

    public function replicate(AuthUser $authUser, ActivityLogs $activityLogs): bool
    {
        return $authUser->can('Replicate:ActivityLogs');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ActivityLogs');
    }

}