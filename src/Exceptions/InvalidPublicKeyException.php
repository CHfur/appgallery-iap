<?php

namespace CHfur\AppGallery\Exceptions;

use Exception;

/**
 * Class InvalidPublicKeyException
 * @package CHfur\AppGallery\Exceptions
 */
class InvalidPublicKeyException extends Exception
{
    /**
     * @param $publicKey
     * @return InvalidPublicKeyException
     */
    public static function create($publicKey): self
    {
        return new self(sprintf(
            'String \'%s\' cannot be converted to a valid public key',
            $publicKey
        ));
    }
}
