<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Illuminate\Validation\Rules\Password as PasswordRule;
use Nuwave\Lighthouse\Validation\Validator;

final class UserCreateInputValidator extends Validator
{
    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'password'              => ['required', 'confirmed', PasswordRule::min(8)],
            'password_confirmation' => ['required', 'string'],
            'role'                  => ['sometimes', 'string', 'in:admin,user'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                  => 'Tên không được để trống.',
            'email.required'                 => 'Email không được để trống.',
            'email.email'                    => 'Địa chỉ email không hợp lệ.',
            'email.unique'                   => 'Email đã tồn tại trong hệ thống.',
            'password.required'              => 'Mật khẩu không được để trống.',
            'password.confirmed'             => 'Xác nhận mật khẩu không khớp.',
            'password.min'                   => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password_confirmation.required' => 'Xác nhận mật khẩu không được để trống.',
            'role.in'                        => 'Role không hợp lệ. Chọn "admin" hoặc "user".',
        ];
    }
}
