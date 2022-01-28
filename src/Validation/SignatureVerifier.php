<?php

namespace CHfur\AppGallery\Validation;

use CHfur\AppGallery\Exceptions\InvalidPublicKeyException;
use phpseclib3\Crypt\RSA;

class SignatureVerifier
{
    /**
     * @var string
     */
    private $publicKey;

    private const ALGORITHMS = [
        'SHA256WithRSA' => 'SHA256WithRSA',
        'SHA256WithRSA/PSS' => 'SHA256WithRSA/PSS',
    ];

    public function __construct(string $publicKey)
    {
        $this->publicKey = $publicKey;
    }

    /**
     * @return string
     * @throws InvalidPublicKeyException
     */
    private function getPublicKey(): string
    {
        $begin_public = "-----BEGIN PUBLIC KEY-----\n";
        $end_public = "-----END PUBLIC KEY-----\n";
        $publicKey = $begin_public.chunk_split($this->publicKey, 64).$end_public;
        if (openssl_get_publickey($publicKey)) {
            return $publicKey;
        }
        throw InvalidPublicKeyException::create($this->publicKey);
    }

    /**
     * @param  string  $content
     * @param  string  $sign
     * @return bool
     * @throws InvalidPublicKeyException
     */
    private function verifySHA256WithRSA(string $content, string $sign): bool
    {
        $publicKey = openssl_get_publickey(self::getPublicKey());
        return (bool)openssl_verify($content, base64_decode($sign), $publicKey, OPENSSL_ALGO_SHA256);
    }

    /**
     * @param  string  $content
     * @param  string  $sign
     * @return bool
     * @throws InvalidPublicKeyException
     */
    private function verifySHA256WithRSAPSS(string $content, string $sign): bool
    {
        return RSA::loadPublicKey(self::getPublicKey())->verify($content, base64_decode($sign));
    }

    /**
     * @param  string  $content
     * @param  string  $sign
     * @param  string  $algorithm
     * @return bool
     * @throws InvalidPublicKeyException
     */
    public function verify(string $content, string $sign, string $algorithm): bool
    {
        switch (self::ALGORITHMS[$algorithm]) {
            case 'SHA256WithRSA':
                return self::verifySHA256WithRSA($content, $sign);
            default:
                return self::verifySHA256WithRSAPSS($content, $sign);
        }
    }
}
