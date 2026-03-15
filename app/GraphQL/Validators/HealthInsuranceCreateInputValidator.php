<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Nuwave\Lighthouse\Validation\Validator;

final class HealthInsuranceCreateInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'residentId'         => ['required', 'uuid', 'exists:residents,id'],
            'code'               => ['required', 'string', 'max:20', 'unique:health_insurances,code'],
            'healthcareFacility' => ['nullable', 'string'],
            'issuedDate'         => ['nullable', 'date'],
            'expiryDate'         => ['nullable', 'date', 'after_or_equal:issuedDate'],
        ];
    }
}
