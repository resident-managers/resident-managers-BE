<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\Users;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

final readonly class ResetPassword
{
    public function __invoke(null $_, array $args): array
    {
        $status = Password::broker()->reset(
            [
                'email'                 => $args['email'],
                'password'              => $args['password'],
                'password_confirmation' => $args['password_confirmation'],
                'token'                 => $args['token'],
            ],
            function ($user, string $password): void {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            $message = match ($status) {
                Password::INVALID_TOKEN => 'Token đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.',
                Password::INVALID_USER  => 'Không tìm thấy tài khoản với địa chỉ email này.',
                default                 => 'Đặt lại mật khẩu thất bại. Vui lòng thử lại.',
            };
            throw ValidationException::withMessages(['token' => [$message]]);
        }

        return ['message' => 'Mật khẩu đã được đặt lại thành công.'];
    }
}
