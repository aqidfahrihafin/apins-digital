<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super_admin')) {
                return true;
            }

            if (! str_contains($ability, ':')) {
                return null;
            }

            [$action, $subject] = explode(':', $ability, 2);
            $permissionGroup = match ($subject) {
                'User' => 'users',
                'Role' => 'roles',
                'Service', 'ServiceDetails' => 'services',
                'Portofolios' => 'portfolio',
                'PortofoliosCategory' => 'portfolio_categories',
                'PortofoliosImages' => 'portfolio',
                'BlogPosts' => 'blog',
                'BlogTags', 'BlogPostsTags' => 'blog_tags',
                'Comments' => 'comments',
                'Testimonials' => 'testimonials',
                'Faqs' => 'faqs',
                'TeamMembers' => 'team',
                'Contacts' => 'contacts',
                'NewsLetters' => 'newsletters',
                'Settings', 'SiteConfigs' => 'settings',
                'ActivityLogs' => 'activity_logs',
                default => null,
            };

            if ($permissionGroup === null) {
                return null;
            }

            if ($permissionGroup === 'comments') {
                return $user->can('manage_comments');
            }

            $prefix = match ($action) {
                'View', 'ViewAny' => 'view',
                'Create', 'Replicate' => 'create',
                'Update', 'Reorder' => 'edit',
                'Delete', 'DeleteAny', 'ForceDelete', 'ForceDeleteAny' => 'delete',
                'Restore', 'RestoreAny' => 'edit',
                default => null,
            };

            return $prefix ? $user->can("{$prefix}_{$permissionGroup}") : null;
        });
    }
}
