<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // create permissions
        $permissions = [
            'task.create',
            'task.read',
            'task.update',
            'task.delete',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // create roles
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $user = Role::firstOrCreate(['name' => 'User']);

        // assign permissions to roles
        $admin->syncPermissions($permissions);
        $user->syncPermissions(['task.create', 'task.read', 'task.update']);

        // assign admin role to first user (or create one)
        $adminUser = User::first();
        if (! $adminUser) {
            $adminUser = User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
        }
        $adminUser->assignRole('Admin');
    }
}
