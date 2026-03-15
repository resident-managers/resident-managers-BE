<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Nuwave\Lighthouse\Validation\Validator;

final class TemporaryAbsenceCreateInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'residentId'  => ['required', 'uuid', 'exists:residents,id'],
            'destination' => ['required', 'string'],
            'fromDate'    => ['required', 'date'],
            'toDate'      => ['nullable', 'date', 'after_or_equal:fromDate'],
            'reason'      => ['nullable', 'string'],
        ];
    }
}
