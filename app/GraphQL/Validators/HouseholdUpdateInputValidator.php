<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

final class HouseholdUpdateInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        $householdId = (string) $this->arg('id');

        return [
            'id' => ['required', Rule::exists('households', 'id')],
            'code' => ['nullable', 'string', 'max:20'],
            'headId' => ['nullable', Rule::exists('residents', 'id')],
            'address' => ['nullable', 'string'],
            'members' => ['nullable', 'array'],
            'members.*.residentId' => [
                'required',
                'distinct',
                Rule::exists('residents', 'id'),
                Rule::unique('household_residents', 'resident_id')
                    ->where(fn ($query) => $query->where('household_id', '!=', $householdId)),
            ],
            'members.*.relationship' => ['required', 'max:50'],
        ];
    }
}
