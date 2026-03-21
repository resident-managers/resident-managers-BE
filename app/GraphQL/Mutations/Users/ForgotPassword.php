<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\Users;

use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

final readonly class ForgotPassword
{
    public function __invoke(null $_, array $args): array
    {
        $status = Password::broker()->sendResetLink(
            ['email' => $args['email']],
            function ($user, string $token) use ($args): void {
                Mail::to($args['email'])->send(new ResetPasswordMail($token, $args['email']));
            }
        );

        if ($status === Password::RESET_THROTTLED) {
            throw ValidationException::withMessages([
                'email' => ['Vui lòng chờ trước khi yêu cầu đặt lại mật khẩu lần nữa.'],
            ]);
        }

        return [
            'message' => 'Nếu địa chỉ email tồn tại, chúng tôi đã gửi liên kết đặt lại mật khẩu.',
        ];
    }
}
