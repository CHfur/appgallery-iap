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

```php
use CHfur\AppGallery\ServerNotifications\ServerNotification;

$data = [/* JSON decoded AppGallery ServerNotification request */];
$publicKey = 'Your AppGallery notification public key';

/** @var ServerNotification */
$serverNotification = ServerNotification::parse($data, $publicKey);

$productId = $serverNotification->getProductId();
$environment = $serverNotification->getEnvironment();

$notificationTypeName = $serverNotification->getNotificationTypeName();

switch ($notificationTypeName){
    case 'RENEWAL':
        //implement your logic
        break;
}
```

## License

The App Store IAP is an open-sourced software licensed under the [MIT license](LICENSE.md).

## TODO
* Testing
* Implementing verification without third-party packages