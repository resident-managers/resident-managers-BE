<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\Users;

use App\Models\User;

final readonly class Create
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): User
    {
        $user = User::create([
            'name'     => $args['name'],
            'email'    => $args['email'],
            'password' => $args['password'],
        ]);

        $user->assignRole($args['role'] ?? 'user');

        return $user;
    }
}
