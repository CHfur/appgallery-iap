<?php

namespace CHfur\AppGallery\Tests\Exceptions;

use CHfur\AppGallery\Exceptions\InvalidPublicKeyException;
use PHPUnit\Framework\TestCase;

class InvalidPublicKeyExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function test_create()
    {
        $exception = InvalidPublicKeyException::create('invalid');

        $this->assertEquals('String \'invalid\' cannot be converted to a valid public key', $exception->getMessage());
    }
}
