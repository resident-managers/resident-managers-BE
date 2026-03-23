<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Laravel\Passport\ClientRepository;
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

        $clientRepository = new ClientRepository();
        $hasAdminClient = \Laravel\Passport\Client::query()
            ->where('provider', 'admins')
            ->where('revoked', false)
            ->whereJsonContains('grant_types', 'personal_access')
            ->exists();

        if (!$hasAdminClient) {
            $clientRepository->createPersonalAccessGrantClient('Admin Personal Access Client', 'admins');
        }
    }
}
