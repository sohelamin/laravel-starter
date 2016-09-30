<?php

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UsersRolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => bcrypt('123456'),
        ]);

        // Create roles
        Role::create([
            'name' => 'admin',
            'label' => 'Administrator',
        ]);

        Role::create([
            'name' => 'user',
            'label' => 'User',
        ]);

        // Create permissions
        Permission::create([
            'name' => 'all_permissions',
            'label' => 'All Permissions',
        ]);

        $user = User::whereEmail('admin@example.com')->first();
        if ($user) {
            $user->assignRole('admin');
        }

        // Give permission to admin role
        $permission = Permission::whereName('all_permissions')->first();
        $role = Role::whereName('admin')->first();
        if ($role && $permission) {
            $role->givePermissionTo($permission);
        }
    }
}
