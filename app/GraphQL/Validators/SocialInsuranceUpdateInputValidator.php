<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

final class SocialInsuranceUpdateInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'id'            => ['required', 'uuid', 'exists:social_insurances,id'],
            'code'          => ['nullable', 'string', 'max:10', Rule::unique('social_insurances', 'code')->ignore($this->arg('id'))],
            'employer'      => ['nullable', 'string'],
            'enrolledDate'  => ['nullable', 'date'],
            'insuranceType' => ['nullable'],
            'status'        => ['nullable'],
        ];
    }
}
