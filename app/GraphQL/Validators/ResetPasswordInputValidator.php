<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Illuminate\Validation\Rules\Password as PasswordRule;
use Nuwave\Lighthouse\Validation\Validator;

final class ResetPasswordInputValidator extends Validator
{
    public function rules(): array
    {
        return [
            'token'                 => ['required', 'string'],
            'email'                 => ['required', 'email'],
            'password'              => ['required', 'confirmed', PasswordRule::min(8)],
            'password_confirmation' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'                 => 'Email không được để trống.',
            'email.email'                    => 'Địa chỉ email không hợp lệ.',
            'password.required'              => 'Mật khẩu không được để trống.',
            'password.confirmed'             => 'Xác nhận mật khẩu không khớp.',
            'password.min'                   => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password_confirmation.required' => 'Xác nhận mật khẩu không được để trống.',
        ];
    }
}
