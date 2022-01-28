<?php

namespace CHfur\AppGallery\ServerNotifications;

use CHfur\AppGallery\ValueObjects\Time;

class PendingPurchaseNotification
{
    /**
     * Event type. The value can be:
     * 1: payment success
     * @var int
     */
    protected $notificationType;

    /**
     * Notification version, always v2.
     * @var int
     */
    protected $notificationVersion;

    /**
     * Product ID.
     * @var string
     */
    protected $productId;

    /**
     * App ID.
     * @var string
     */
    protected $applicationId;

    /**
     * Purchase token of the product to be delivered.
     * @var string
     */
    protected $purchaseToken;


    /**
     * @var Time
     */
    protected $notifyTime;

    /**
     * Notification type, always ORDER.
     * @var string
     */
    protected $eventType;

    /**
     * @param  string  $version
     * @param  string  $eventType
     * @param  int  $notifyTime
     * @param  string  $applicationId
     * @param $orderNotification
     */
    public function __construct(
        string $version,
        string $eventType,
        int $notifyTime,
        string $applicationId,
        $orderNotification
    ) {
        $this->notificationVersion = $version;
        $this->eventType = $eventType;
        $this->notifyTime = new Time($notifyTime);
        $this->applicationId = $applicationId;
        $this->notificationType = $orderNotification->notificationType;
        $this->purchaseToken = $orderNotification->purchaseToken;
        $this->productId = $orderNotification->productId;
    }

    /**
     * @return int
     */
    public function getNotificationType(): int
    {
        return $this->notificationType;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getApplicationId(): string
    {
        return $this->applicationId;
    }

    /**
     * @return int
     */
    public function getNotificationVersion()
    {
        return $this->notificationVersion;
    }

    /**
     * @return string
     */
    public function getPurchaseToken(): string
    {
        return $this->purchaseToken;
    }

    /**
     * @return Time
     */
    public function getNotifyTime(): Time
    {
        return $this->notifyTime;
    }

    /**
     * @return string
     */
    public function getEventType(): string
    {
        return $this->eventType;
    }
}
