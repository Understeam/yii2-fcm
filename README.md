# Yii2 Firebase Cloud Messaging

This component wraps [paragraph1/php-fcm](https://packagist.org/packages/paragraph1/php-fcm) library.

## Installation

Preferred way to install is through [Composer](https://getcomposer.org): 

```shell
$ composer require understeam/yii2-fcm:~0.1 --prefer-dist 
```

## Configuration

Add component to your application config:

```php
...
'components' => [
    'fcm' => [
        'class' => 'understeam\fcm\Client',
        'apiKey' => 'your API key', // Server API Key (you can get it here: https://firebase.google.com/docs/server/setup#prerequisites) 
    ],
],
...
```

## Usage

You can find more usage examples [here](https://packagist.org/packages/paragraph1/php-fcm). 

```php
$note = Yii::$app->fcm->createNotification("test title", "testing body");
$note->setIcon('notification_icon_resource_name')
    ->setColor('#ffffff')
    ->setBadge(1);

$message = Yii::$app->fcm->createMessage();
$message->addRecipient(new Device('your-device-token'));
$message->setNotification($note)
    ->setData(['someId' => 111]);

$response = Yii::$app->fcm->send($message);
var_dump($response->getStatusCode());
```

