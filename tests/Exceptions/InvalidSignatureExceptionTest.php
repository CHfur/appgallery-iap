<?php

namespace CHfur\AppGallery\Tests\Exceptions;

use CHfur\AppGallery\Exceptions\InvalidSignatureException;
use PHPUnit\Framework\TestCase;

class InvalidSignatureExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function test_create()
    {
        $exception = InvalidSignatureException::create();

        $this->assertEquals("Signature verification failed", $exception->getMessage());
    }
}
