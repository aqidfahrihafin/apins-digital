<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Testimonials;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestimonialsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Testimonials');
    }

    public function view(AuthUser $authUser, Testimonials $testimonials): bool
    {
        return $authUser->can('View:Testimonials');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Testimonials');
    }

    public function update(AuthUser $authUser, Testimonials $testimonials): bool
    {
        return $authUser->can('Update:Testimonials');
    }

    public function delete(AuthUser $authUser, Testimonials $testimonials): bool
    {
        return $authUser->can('Delete:Testimonials');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Testimonials');
    }

    public function restore(AuthUser $authUser, Testimonials $testimonials): bool
    {
        return $authUser->can('Restore:Testimonials');
    }

    public function forceDelete(AuthUser $authUser, Testimonials $testimonials): bool
    {
        return $authUser->can('ForceDelete:Testimonials');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Testimonials');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Testimonials');
    }

    public function replicate(AuthUser $authUser, Testimonials $testimonials): bool
    {
        return $authUser->can('Replicate:Testimonials');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Testimonials');
    }

}