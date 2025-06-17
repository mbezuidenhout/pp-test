<?php

namespace Tests\Unit\Rules;

use App\Rules\SouthAfricanID;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class SouthAfricanIDTest extends TestCase
{
    public function test_valid_south_african_id(): void
    {
        $validator = Validator::make(
            ['id' => '8001015009087'],
            ['id' => [new SouthAfricanID]]
        );

        $this->assertTrue($validator->passes());
    }

    public function test_invalid_south_african_id(): void
    {
        $validator = Validator::make(
            ['id' => '1234567890123'],
            ['id' => [new SouthAfricanID]]
        );

        $this->assertFalse($validator->passes());
    }

    public function test_id_with_invalid_length_fails(): void
    {
        $validator = Validator::make(
            ['id' => '123456789'],
            ['id' => [new SouthAfricanID]]
        );

        $this->assertFalse($validator->passes());
    }
}
