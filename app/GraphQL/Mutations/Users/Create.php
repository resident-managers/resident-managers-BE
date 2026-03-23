<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\Users;

use App\Mail\UserCreatedMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

final readonly class Create
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): User
    {
        DB::beginTransaction();
        try {
            $password = Str::random(12);

            $user = User::create([
                'name'              => $args['name'],
                'email'             => $args['email'],
                'password'          => $password,
                'email_verified_at' => now(),
            ]);

            $user->assignRole(Role::findByName($args['role'] ?? 'user', 'api'));

            Mail::to($user->email)->send(new UserCreatedMail($user->name, $user->email, $password));

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
