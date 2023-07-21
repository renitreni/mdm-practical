<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\UserRoleEnum;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use App\Models\Voucher;
use Database\Factories\GroupFactory;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roleUser = Role::create(['name' => UserRoleEnum::USER]);
        $roleGroupAdmin = Role::create(['name' => UserRoleEnum::GROUP_ADMIN]);

        $this->call([
            SuperAdminSeeder::class,
            PermissionSeeder::class
        ]);

        if (app()->isLocal()) {
            $adminUsers = User::factory(10)->has(Group::factory())->create();
            foreach ($adminUsers as $user) {
                $user->assignRole($roleGroupAdmin);
            }

            $users = User::factory(10)->has(Voucher::factory(3))->create();
            foreach ($users as $user) {
                $user->assignRole($roleUser);
            }

            $canBeMember = User::isUser()->get();

            foreach ($canBeMember as $user) {
                GroupMember::updateOrCreate(
                    ['user_id' => $user->id],
                    ['user_id' => $user->id, 'group_id' => Group::inRandomOrder()->first()->id]
                );
            }
            
            $users = User::factory(10)->has(Voucher::factory(3))->create();
            foreach ($users as $user) {
                $user->assignRole($roleUser);
            }
        }
    }
}
