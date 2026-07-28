<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TeamMembers;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamMembersPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TeamMembers');
    }

    public function view(AuthUser $authUser, TeamMembers $teamMembers): bool
    {
        return $authUser->can('View:TeamMembers');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TeamMembers');
    }

    public function update(AuthUser $authUser, TeamMembers $teamMembers): bool
    {
        return $authUser->can('Update:TeamMembers');
    }

    public function delete(AuthUser $authUser, TeamMembers $teamMembers): bool
    {
        return $authUser->can('Delete:TeamMembers');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:TeamMembers');
    }

    public function restore(AuthUser $authUser, TeamMembers $teamMembers): bool
    {
        return $authUser->can('Restore:TeamMembers');
    }

    public function forceDelete(AuthUser $authUser, TeamMembers $teamMembers): bool
    {
        return $authUser->can('ForceDelete:TeamMembers');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TeamMembers');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TeamMembers');
    }

    public function replicate(AuthUser $authUser, TeamMembers $teamMembers): bool
    {
        return $authUser->can('Replicate:TeamMembers');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TeamMembers');
    }

}