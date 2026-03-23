<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Nuwave\Lighthouse\Validation\Validator;

final class UserCreateInputValidator extends Validator
{
    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role'  => ['sometimes', 'string', 'in:admin,user'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email'    => 'Địa chỉ email không hợp lệ.',
            'email.unique'   => 'Email đã tồn tại trong hệ thống.',
            'role.in'        => 'Role không hợp lệ. Chọn admin hoặc user.',
        ];
    }
}
