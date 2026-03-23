<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Illuminate\Validation\Rules\Password as PasswordRule;
use Nuwave\Lighthouse\Validation\Validator;

final class UserUpdateInputValidator extends Validator
{
    public function rules(): array
    {
        return [
            'id'                    => ['required', 'exists:users,id'],
            'name'                  => ['sometimes', 'string', 'max:255'],
            'email'                 => ['sometimes', 'email'],
            'password'              => ['sometimes', 'confirmed', PasswordRule::min(8)],
            'password_confirmation' => ['required_with:password', 'string'],
            'role'                  => ['sometimes', 'string', 'in:admin,user'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists'                           => 'Người dùng không tồn tại.',
            'email.email'                         => 'Địa chỉ email không hợp lệ.',
            'password.confirmed'                  => 'Xác nhận mật khẩu không khớp.',
            'password.min'                        => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password_confirmation.required_with' => 'Xác nhận mật khẩu không được để trống.',
            'role.in'                             => 'Role không hợp lệ. Chọn "admin" hoặc "user".',
        ];
    }
}
