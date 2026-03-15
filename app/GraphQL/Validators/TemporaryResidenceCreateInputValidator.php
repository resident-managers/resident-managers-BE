<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Nuwave\Lighthouse\Validation\Validator;

final class TemporaryResidenceCreateInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'residentId' => ['required', 'uuid', 'exists:residents,id'],
            'address'    => ['required', 'string'],
            'hostName'   => ['nullable', 'string', 'max:100'],
            'fromDate'   => ['required', 'date'],
            'toDate'     => ['nullable', 'date', 'after_or_equal:fromDate'],
            'reason'     => ['nullable', 'string'],
        ];
    }
}
