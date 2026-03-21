<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Spatie\Permission\Models\Role;

trait CreatesAuthenticatedUser
{
    private string $token;

    protected function setUpAuthenticatedUser(): void
    {
        $clientRepository = new \Laravel\Passport\ClientRepository();
        $clientRepository->createPersonalAccessGrantClient('Test Client');

        $this->seed(PermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole(Role::findByName('user', 'api'));

        $this->token = $user->createToken('Test')->accessToken;
    }

    private function auth(): static
    {
        return $this->withHeaders(['Authorization' => 'Bearer ' . $this->token]);
    }
}
