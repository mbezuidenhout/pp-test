<?php

namespace App\Utils;

class SouthAfricanIDUtils
{
    /**
     * @param  string  $idWithoutCheckDigit  String containing the first 12 digits of ID number.
     * @return int The check digit or 13th digit of the ID number.
     *
     * @throws \InvalidArgumentException
     */
    public static function calculateLuhnDigit(string $idWithoutCheckDigit): int
    {
        if (! preg_match('/^\d{12}$/', $idWithoutCheckDigit)) {
            throw new \InvalidArgumentException('Input must be a 12-digit numeric string');
        }

        // Step 1: Sum all digits in the odd positions (1st, 3rd, 5th, ...)
        $sumOdd = 0;
        for ($i = 0; $i < 12; $i += 2) {
            $sumOdd += (int) $idWithoutCheckDigit[$i];
        }

        // Step 2: Concatenate all digits in the even positions and multiply by 2
        $evenDigits = '';
        for ($i = 1; $i < 12; $i += 2) {
            $evenDigits .= $idWithoutCheckDigit[$i];
        }
        $evenDigitsMultiplied = (string) ((int) $evenDigits * 2);

        // Step 3: Sum all digits in the result of step 2
        $sumEven = array_sum(str_split($evenDigitsMultiplied));

        // Step 4: Add the results of step 1 and 3
        $total = $sumOdd + $sumEven;

        // Step 5: Subtract last digit of total from 10 to get check digit
        return (10 - ($total % 10)) % 10;
    }
}
