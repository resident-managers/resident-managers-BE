<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

final readonly class Update
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): User
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($args['id']);

            if (isset($args['email']) && $args['email'] !== $user->email) {
                if (User::where('email', $args['email'])->exists()) {
                    throw ValidationException::withMessages([
                        'email' => ['Email đã được sử dụng.'],
                    ]);
                }
            }

            $user->fill(array_filter([
                'name'     => $args['name'] ?? null,
                'email'    => $args['email'] ?? null,
                'password' => isset($args['password']) ? $args['password'] : null,
            ], fn ($v) => $v !== null));

            $user->save();

            if (isset($args['role'])) {
                $user->syncRoles(Role::findByName($args['role'], 'api'));
            }

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
