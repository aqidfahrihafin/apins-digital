<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // === CREATE PERMISSIONS ===

        // User Management
        $permissions = [
            'view_users', 'create_users', 'edit_users', 'delete_users',
            'view_roles', 'create_roles', 'edit_roles', 'delete_roles',

            // Service Management
            'view_services', 'create_services', 'edit_services', 'delete_services',

            // Portfolio Management
            'view_portfolio', 'create_portfolio', 'edit_portfolio', 'delete_portfolio',
            'view_portfolio_categories', 'create_portfolio_categories', 'edit_portfolio_categories', 'delete_portfolio_categories',

            // Blog Management
            'view_blog', 'create_blog', 'edit_blog', 'delete_blog',
            'view_blog_tags', 'create_blog_tags', 'edit_blog_tags', 'delete_blog_tags',
            'manage_comments',

            // Testimonial Management
            'view_testimonials', 'create_testimonials', 'edit_testimonials', 'delete_testimonials',

            // FAQ Management
            'view_faqs', 'create_faqs', 'edit_faqs', 'delete_faqs',

            // Team Management
            'view_team', 'create_team', 'edit_team', 'delete_team',

            // Contact & Newsletter
            'view_contacts', 'delete_contacts', 'view_newsletters', 'delete_newsletters',

            // Settings
            'view_settings', 'edit_settings',

            // Activity Logs
            'view_activity_logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // === CREATE ROLES ===

        // Super Admin - All permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin - Most permissions except role management
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo([
            'view_users', 'create_users', 'edit_users',
            'view_services', 'create_services', 'edit_services', 'delete_services',
            'view_portfolio', 'create_portfolio', 'edit_portfolio', 'delete_portfolio',
            'view_portfolio_categories', 'create_portfolio_categories', 'edit_portfolio_categories', 'delete_portfolio_categories',
            'view_blog', 'create_blog', 'edit_blog', 'delete_blog',
            'view_blog_tags', 'create_blog_tags', 'edit_blog_tags', 'delete_blog_tags',
            'manage_comments',
            'view_testimonials', 'create_testimonials', 'edit_testimonials', 'delete_testimonials',
            'view_faqs', 'create_faqs', 'edit_faqs', 'delete_faqs',
            'view_team', 'create_team', 'edit_team', 'delete_team',
            'view_contacts', 'delete_contacts', 'view_newsletters', 'delete_newsletters',
            'view_settings', 'edit_settings',
            'view_activity_logs',
        ]);

        // Editor - Content only
        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editor->givePermissionTo([
            'view_portfolio', 'create_portfolio', 'edit_portfolio',
            'view_portfolio_categories',
            'view_blog', 'create_blog', 'edit_blog',
            'view_blog_tags', 'create_blog_tags', 'edit_blog_tags',
            'manage_comments',
            'view_testimonials', 'create_testimonials', 'edit_testimonials',
            'view_faqs', 'create_faqs', 'edit_faqs',
        ]);

        // User - View only (default role for registered users)
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $user->givePermissionTo([
            'view_portfolio', 'view_blog', 'view_testimonials', 'view_faqs',
        ]);

        $this->command->info('Roles and permissions created successfully!');
    }
}
