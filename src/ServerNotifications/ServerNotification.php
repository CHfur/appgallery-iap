<?php

namespace CHfur\AppGallery\ServerNotifications;

use CHfur\AppGallery\Exceptions\InvalidPublicKeyException;
use CHfur\AppGallery\Exceptions\InvalidSignatureException;
use CHfur\AppGallery\Validation\SignatureVerifier;

/**
 * AppGallery Server Notifications
 * @see https://developer.huawei.com/consumer/en/doc/development/HMSCore-References/api-notifications-about-subscription-events-0000001050706084
 * @see https://developer.huawei.com/consumer/en/doc/development/HMSCore-References/api-notifications-about-pending-payment-events-0000001230063777
 */
class ServerNotification
{
    /**
     * @var SubscriptionNotification|null
     */
    protected $subscriptionNotification;

    /**
     * @var PendingPurchaseNotification|null
     */
    protected $pendingPurchaseNotification;

    /**
     * @param  array  $data  | Request body from AppGallery notification server
     * @param  string  $publicKey
     * @return ServerNotification|null
     * @throws InvalidSignatureException
     * @throws InvalidPublicKeyException
     */
    public static function parse(array $data, string $publicKey): ?self
    {
        if (isset($data['eventType']) && $data['eventType'] == 'ORDER') {
            return self::parsePendingPurchaseNotification($data);
        }

        if (isset($data['statusUpdateNotification'])) {
            return self::parseSubscriptionNotification($data, $publicKey);
        }

        return null;
    }

    /**
     * @param  array  $data
     * @param  string  $publicKey
     * @return ServerNotification
     * @throws InvalidSignatureException
     * @throws InvalidPublicKeyException
     */
    protected static function parseSubscriptionNotification(array $data, string $publicKey): ServerNotification
    {
        self::validateSignature($data, $publicKey);

        $notification = new self();

        $subscriptionNotification = new SubscriptionNotification(json_decode($data['statusUpdateNotification']));

        $notification->subscriptionNotification = $subscriptionNotification;

        return $notification;
    }

    /**
     * @param  array  $data
     * @return ServerNotification
     */
    protected static function parsePendingPurchaseNotification(array $data): ServerNotification
    {
        $notification = new self();

        $pendingPurchaseNotification = new PendingPurchaseNotification(
            $data['version'],
            $data['eventType'],
            $data['notifyTime'],
            $data['applicationId'],
            $data['orderNotification']
        );

        $notification->pendingPurchaseNotification = $pendingPurchaseNotification;

        return $notification;
    }

    /**
     * @return bool
     */
    public function isSubscriptionNotification(): bool
    {
        return ! is_null($this->subscriptionNotification);
    }

    /**
     * @return bool
     */
    public function isPendingPurchaseNotification(): bool
    {
        return ! is_null($this->pendingPurchaseNotification);
    }

    /**
     * @return SubscriptionNotification|null
     */
    public function getSubscriptionNotification(): ?SubscriptionNotification
    {
        return $this->subscriptionNotification;
    }

    /**
     * @return PendingPurchaseNotification|null
     */
    public function getPendingPurchaseNotification(): ?PendingPurchaseNotification
    {
        return $this->pendingPurchaseNotification;
    }

    /**
     * @param  array  $data
     * @param  string  $publicKey
     * @return void
     * @throws InvalidSignatureException|InvalidPublicKeyException
     */
    private static function validateSignature(array $data, string $publicKey)
    {
        $signatureVerifier = new SignatureVerifier($publicKey);

        if (! $signatureVerifier->verify(
            $data['statusUpdateNotification'],
            $data['notifycationSignature'],
            $data['signatureAlgorithm']
        )) {
            throw new InvalidSignatureException();
        }
    }
}
