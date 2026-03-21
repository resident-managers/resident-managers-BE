<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::query()->where(['name' => 'admin', 'guard_name' => 'admin'])->firstOrFail();

        Admin::query()->firstOrCreate(
            ['email' => 'admin@quan-ly-dan-cu.local'],
            ['name' => 'Admin', 'password' => 'password'],
        )->syncRoles($adminRole);
    }
}
