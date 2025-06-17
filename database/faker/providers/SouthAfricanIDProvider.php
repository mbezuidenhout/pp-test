<?php

namespace Database\Faker\Providers;

use Faker\Provider\Base;

class SouthAfricanIDProvider extends Base
{
    /**
     * @param  \DateTime|null  $dobDate  Date of birth
     * @param  bool  $isMale  Generate male or female specific number
     *
     * @throws \Random\RandomException
     */
    public function southAfricanID(?\DateTime $dobDate = null, ?bool $isMale = null): string
    {
        if ($dobDate === null) {
            $dob = $this->generator->dateTimeBetween('-70 years', '-18 years')->format('ymd');
        } else {
            $dob = $dobDate->format('ymd');
        }
        $sequence = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        if ($isMale === true) {
            $sequence = str_pad((string) random_int(500000, 999999), 6, '0', STR_PAD_LEFT);
        } elseif ($isMale === false) {
            $sequence = str_pad((string) random_int(0, 499999), 6, '0', STR_PAD_LEFT);
        }

        $checksum = \App\Utils\SouthAfricanIDUtils::calculateLuhnDigit("{$dob}{$sequence}");

        return "{$dob}{$sequence}{$checksum}";
    }
}
