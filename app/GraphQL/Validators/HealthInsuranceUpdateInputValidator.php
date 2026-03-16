<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

final class HealthInsuranceUpdateInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'id'                 => ['required', 'uuid', 'exists:health_insurances,id'],
            'code'               => ['nullable', 'string', 'max:20', Rule::unique('health_insurances', 'code')->ignore($this->arg('id'))],
            'healthcareFacility' => ['nullable', 'string'],
            'issuedDate'         => ['nullable', 'date'],
            'expiryDate'         => ['nullable', 'date', 'after_or_equal:issuedDate'],
        ];
    }
}
