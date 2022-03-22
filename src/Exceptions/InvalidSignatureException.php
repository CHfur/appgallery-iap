<?php

namespace CHfur\AppGallery\Exceptions;

use LogicException;

/**
 * Class InvalidSignatureException
 * @package CHfur\AppGallery\Exceptions
 */
class InvalidSignatureException extends LogicException
{
    /**
     * @return InvalidSignatureException
     */
    public static function create(): self
    {
        return new self("Signature verification failed");
    }
}
