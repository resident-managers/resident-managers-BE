<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Nuwave\Lighthouse\Validation\Validator;

final class TemporaryResidenceUpdateInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'id'       => ['required', 'uuid', 'exists:temporary_residences,id'],
            'address'  => ['nullable', 'string'],
            'hostName' => ['nullable', 'string', 'max:100'],
            'fromDate' => ['nullable', 'date'],
            'toDate'   => ['nullable', 'date', 'after_or_equal:fromDate'],
            'reason'   => ['nullable', 'string'],
        ];
    }
}
