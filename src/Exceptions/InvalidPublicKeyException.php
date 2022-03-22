<?php

namespace CHfur\AppGallery\Exceptions;

use DomainException;

/**
 * Class InvalidPublicKeyException
 * @package CHfur\AppGallery\Exceptions
 */
class InvalidPublicKeyException extends DomainException
{
    /**
     * @param  string  $publicKey
     * @return InvalidPublicKeyException
     */
    public static function create(string $publicKey): self
    {
        return new self(sprintf(
            'String \'%s\' cannot be converted to a valid public key',
            $publicKey
        ));
    }
}
