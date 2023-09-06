<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    const NON_EXISTING_ID_INT = -1;

    public function getRandomUuid(): string
    {
        return Str::uuid();
    }
}
