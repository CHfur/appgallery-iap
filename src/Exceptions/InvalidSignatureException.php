<?php

namespace CHfur\AppGallery\Exceptions;

use Exception;

/**
 * Class InvalidSignatureException
 * @package CHfur\AppGallery\Exceptions
 */
class InvalidSignatureException extends Exception
{
    /**
     * @return InvalidSignatureException
     */
    public static function create(): self
    {
        return new self("Signature verification failed");
    }
}
