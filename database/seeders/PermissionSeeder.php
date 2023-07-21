<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view-groups',
            'assign-user-groups',
            'remove-user-groups',
            'create-groups',
            'update-groups',
            'delete-groups',
            'view-my-groups',
            'view-users',
            'view-vouchers',
            'create-vouchers',
            'delete-vouchers',
            'export-vouchers',
            'export-all-vouchers'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $userRole = Role::findByName(UserRoleEnum::USER->value);

        $userRole->givePermissionTo('view-vouchers');
        $userRole->givePermissionTo('delete-vouchers');
        $userRole->givePermissionTo('create-vouchers');
        $userRole->givePermissionTo('view-my-groups');

        $groupAdminRole = Role::findByName(UserRoleEnum::GROUP_ADMIN->value);

        $groupAdminRole->givePermissionTo('view-users');
        $groupAdminRole->givePermissionTo('view-groups');
        $groupAdminRole->givePermissionTo('export-vouchers');
        $groupAdminRole->givePermissionTo('assign-user-groups');
        $groupAdminRole->givePermissionTo('remove-user-groups');

        $superAdminRole = Role::findByName(UserRoleEnum::SUPER_ADMIN->value);

        $superAdminRole->givePermissionTo('view-users');
        $superAdminRole->givePermissionTo('view-groups');
        $superAdminRole->givePermissionTo('create-groups');
        $superAdminRole->givePermissionTo('update-groups');
        $superAdminRole->givePermissionTo('delete-groups');
        $superAdminRole->givePermissionTo('export-all-vouchers');
        $superAdminRole->givePermissionTo('export-vouchers');
        $superAdminRole->givePermissionTo('assign-user-groups');
        $superAdminRole->givePermissionTo('remove-user-groups');
    }
}
