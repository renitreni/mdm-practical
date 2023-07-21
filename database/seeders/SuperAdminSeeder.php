<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'super-admin@site.com',
        ]);

        $roleSuperAdmin = Role::create(['name' => UserRoleEnum::SUPER_ADMIN]);

        $superAdmin->assignRole($roleSuperAdmin);
    }
}
