AppGallery IAP
=======

## About

AppGallery IAP is a PHP library to handle AppGallery purchase verification and Server Notifications. This package
simplifies development by allowing you to work with ready-made data objects. The package also contains SHA256WithRSA
signature verification with and without PSS filling.

## Installation

Use composer

```
composer require chfur/appgallery-iap
```

## Usage

The AppGallery IAP verifying Order and Subscription services can be found in
the [Package for verification](https://github.com/Stafox/huawei-iap). The implementation for the laravel framework can
be found in the [Laravel In-App purchase package](https://github.com/imdhemy/laravel-in-app-purchases).

### Notification About a Key Subscription Event

You can use server notification handling "About a Key Subscription Event" as follow:

```php
use CHfur\AppGallery\ServerNotifications\ServerNotification;
use CHfur\AppGallery\ServerNotifications\SubscriptionNotification;
use Huawei\IAP\Response\SubscriptionResponse;

/** 
 * @var array $data AppGallery ServerNotification request
 * @see https://developer.huawei.com/consumer/en/doc/development/HMSCore-References/api-notifications-about-subscription-events-0000001050706084 
*/
$data = [];

$publicKey = 'Your AppGallery notification public key';

/** @var ServerNotification $serverNotification */
$serverNotification = ServerNotification::parse($data, $publicKey);

if($serverNotification->isSubscriptionNotification()){
    /** @var SubscriptionNotification $subscriptionNotification */
    $subscriptionNotification = $serverNotification->getSubscriptionNotification();
    
    $productId = $subscriptionNotification->getProductId();
    $environment = $subscriptionNotification->getEnvironment();

    /** @var SubscriptionResponse $subscriptionResponse */
    $subscriptionResponse = $subscriptionNotification->getSubscriptionResponse();
    
    $notificationTypeName = $subscriptionNotification->getNotificationTypeName();
    
    switch ($notificationTypeName){
        case 'RENEWAL':
            //implement your logic
            break;
    }
}
```

### Notification About a Key Event of Pending Purchase

And also you can use server notification handling "About a Key Event of Pending Purchase" as follow:

```php
use CHfur\AppGallery\ServerNotifications\PendingPurchaseNotification;
use CHfur\AppGallery\ServerNotifications\ServerNotification;

/** 
 * @var array $data AppGallery ServerNotification request
 * @see https://developer.huawei.com/consumer/en/doc/development/HMSCore-References/api-notifications-about-pending-payment-events-0000001230063777 
*/
$data = [];

$publicKey = 'Your AppGallery notification public key';

/** @var ServerNotification $serverNotification */
$serverNotification = ServerNotification::parse($data, $publicKey);

if($serverNotification->isPendingPurchaseNotification()){
    /** @var PendingPurchaseNotification $pendingPurchaseNotification */
    $pendingPurchaseNotification = $serverNotification->getPendingPurchaseNotification();
    
    $productId = $pendingPurchaseNotification->getProductId();
    $purchaseToken = $pendingPurchaseNotification->getPurchaseToken();
    $isSuccessPayment = $pendingPurchaseNotification->getNotificationType();
    
    //implement your logic
}
```

## License

The AppGallery IAP is an open-sourced software licensed under the [MIT license](LICENSE.md).

## TODO

* Implementing verification without third-party packages