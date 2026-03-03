<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

final class HouseholdCreateInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'code' => ['nullable', 'string', 'max:20', 'unique:households,code'],
            'residentId' => ['required', 'exists:residents,id', 'unique:households,resident_id'],
            'address' => ['required', 'string'],
            'members' => ['nullable', 'array'],
            'members.*.residentId' => [
                'required',
                'distinct',
                Rule::exists('residents', 'id'),
                Rule::unique('household_residents', 'resident_id'),
            ],
            'members.*.relationship' => ['required', 'max:50'],
        ];
    }
}
