<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\Auth;

use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final readonly class AdminLogin
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args): array
    {
        DB::beginTransaction();
        try {
            $admin = Admin::where('email', $args['email'])->first();

            if (!$admin || !Hash::check($args['password'], $admin->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Thông tin đăng nhập không chính xác.'],
                ]);
            }

            $token = $admin->createToken('Admin Access Token')->accessToken;

            DB::commit();

            return [
                'access_token' => $token,
                'user'         => $admin,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
