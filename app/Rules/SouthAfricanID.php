<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SouthAfricanID implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Must be exactly 13 digits
        if (! preg_match('/^\d{13}$/', $value)) {
            $fail('The :attribute must be a valid 13-digit South African ID number.');

            return;
        }

        $checksum = \App\Utils\SouthAfricanIDUtils::calculateLuhnDigit(substr($value, 0, 12));

        if ("{$checksum}" !== $value[12]) {
            $fail('The :attribute is not a valid South African ID number.');
        }
    }
}
