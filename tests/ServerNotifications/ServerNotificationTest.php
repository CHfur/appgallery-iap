<?php

namespace CHfur\AppGallery\Tests\ServerNotifications;

use CHfur\AppGallery\Exceptions\InvalidPublicKeyException;
use CHfur\AppGallery\Exceptions\InvalidSignatureException;
use CHfur\AppGallery\ServerNotifications\PendingPurchaseNotification;
use CHfur\AppGallery\ServerNotifications\ServerNotification;
use CHfur\AppGallery\ServerNotifications\SubscriptionNotification;
use PHPUnit\Framework\TestCase;

class ServerNotificationTest extends TestCase
{
    /**
     * @test
     */
    public function test_it_can_parse_subscription_notification()
    {
        $path = realpath(__DIR__.'/../fixtures/appgallery-server-notification-subscription.json');
        $serverNotificationBody = json_decode(file_get_contents($path), true);
        $publicKey = file_get_contents(__DIR__.'/../fixtures/appgallery_public_key');

        $serverNotification = ServerNotification::parse($serverNotificationBody, $publicKey);
        $this->assertInstanceOf(ServerNotification::class, $serverNotification);
        $this->assertTrue($serverNotification->isSubscriptionNotification());
        $this->assertFalse($serverNotification->isPendingPurchaseNotification());
        $this->assertInstanceOf(SubscriptionNotification::class, $serverNotification->getSubscriptionNotification());
        $this->assertNull($serverNotification->getPendingPurchaseNotification());
        //TODO: check getters
    }

    /**
     * @test
     */
    public function test_it_throw_signature_exception_on_subscription_notification()
    {
        $path = realpath(__DIR__.'/../fixtures/appgallery-server-notification-subscription.json');
        $serverNotificationBody = json_decode(file_get_contents($path), true);
        $publicKey = file_get_contents(__DIR__.'/../fixtures/wrong_appgallery_public_key');

        try {
            ServerNotification::parse($serverNotificationBody, $publicKey);
            $this->fail('InvalidSignatureException was not thrown');
        } catch (InvalidSignatureException $exception) {
            $this->assertTrue(true);
        }
    }

    /**
     * @test
     */
    public function test_it_throw_public_key_exception_on_subscription_notification()
    {
        $path = realpath(__DIR__.'/../fixtures/appgallery-server-notification-subscription.json');
        $serverNotificationBody = json_decode(file_get_contents($path), true);

        try {
            ServerNotification::parse($serverNotificationBody, 'invalid');
            $this->fail('InvalidPublicKeyExceptionTest was not thrown');
        } catch (InvalidPublicKeyException $exception) {
            $this->assertTrue(true);
        }
    }

    /**
     * @test
     */
    public function test_it_can_parse_pending_purchase_notification()
    {
        $path = realpath(__DIR__.'/../fixtures/appgallery-server-notification-pending-purchase.json');
        $serverNotificationBody = json_decode(file_get_contents($path), true);
        $publicKey = file_get_contents(__DIR__.'/../fixtures/appgallery_public_key');

        $serverNotification = ServerNotification::parse($serverNotificationBody, $publicKey);
        $this->assertInstanceOf(ServerNotification::class, $serverNotification);
        $this->assertTrue($serverNotification->isPendingPurchaseNotification());
        $this->assertFalse($serverNotification->isSubscriptionNotification());
        $this->assertInstanceOf(
            PendingPurchaseNotification::class,
            $serverNotification->getPendingPurchaseNotification()
        );
        $this->assertNull($serverNotification->getSubscriptionNotification());
        //TODO: check getters
    }

    /**
     * @test
     */
    public function test_it_returns_null_when_receive_invalid_data()
    {
        $publicKey = file_get_contents(__DIR__.'/../fixtures/appgallery_public_key');

        $serverNotification = ServerNotification::parse([], $publicKey);
        $this->assertNull($serverNotification);
    }
}
