<?php

namespace CHfur\AppGallery\ServerNotifications;

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
     * @param  $data  | Request body from AppGallery notification server
     * @param  string  $publicKey
     * @return ServerNotification|null
     * @throws InvalidSignatureException
     */
    public static function parse($data, string $publicKey): ?self
    {
        if (isset($data->eventType) && $data->eventType == 'ORDER') {
            return self::parsePendingPurchaseNotification($data);
        }

        if (isset($data->statusUpdateNotification)) {
            return self::parseSubscriptionNotification($data, $publicKey);
        }

        return null;
    }

    /**
     * @param $data
     * @param  string  $publicKey
     * @return ServerNotification
     * @throws InvalidSignatureException
     */
    protected static function parseSubscriptionNotification($data, string $publicKey): ServerNotification
    {
        self::validateSignature($data, $publicKey);

        $notification = new self();

        $subscriptionNotification = new SubscriptionNotification(json_decode($data->statusUpdateNotification));

        $notification->subscriptionNotification = $subscriptionNotification;

        return $notification;
    }

    /**
     * @param $data
     * @return ServerNotification
     */
    protected static function parsePendingPurchaseNotification($data): ServerNotification
    {
        $notification = new self();

        $pendingPurchaseNotification = new PendingPurchaseNotification(...$data);

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

    public function getSubscriptionNotification(): SubscriptionNotification
    {
        return $this->subscriptionNotification;
    }

    public function getPendingPurchaseNotification(): PendingPurchaseNotification
    {
        return $this->pendingPurchaseNotification;
    }

    /**
     * @param $data
     * @param  string  $publicKey
     * @return void
     * @throws InvalidSignatureException
     */
    private static function validateSignature($data, string $publicKey)
    {
        $signatureVerifier = new SignatureVerifier($publicKey);

        $params = array_values($data);

        if (! $signatureVerifier->verify(...$params)) {
            throw new InvalidSignatureException();
        }
    }
}
