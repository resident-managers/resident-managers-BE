<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Nuwave\Lighthouse\Validation\Validator;

final class ResidentCreateInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'fullName' => ['required', 'string', 'max:100'],
            'gender' => ['required'],
            'dateOfBirth' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:15'],
            'nationalId' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'occupation' => ['nullable', 'string'],
            'ethnicity' => ['nullable', 'string'],
            'religion' => ['nullable', 'string'],
            'educationLevel' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],
        ];
    }
}
