<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Nuwave\Lighthouse\Validation\Validator;

final class SocialInsuranceCreateInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'residentId'    => ['required', 'uuid', 'exists:residents,id'],
            'code'          => ['required', 'string', 'max:10', 'unique:social_insurances,code'],
            'employer'      => ['nullable', 'string'],
            'enrolledDate'  => ['nullable', 'date'],
            'insuranceType' => ['nullable'],
            'status'        => ['nullable'],
        ];
    }
}
