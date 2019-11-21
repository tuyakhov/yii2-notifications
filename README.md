:bell: Notifications for Yii2
======================
This Yii2 extension provides support for sending notifications across a variety of delivery channels, including mail, SMS, Slack, Telegram etc. Notifications may also be stored in a database so they may be displayed in your web interface.

Typically, notifications should be short, informational messages that notify users of something that occurred in your application. For example, if you are writing a billing application, you might send an "Invoice Paid" notification to your users via the email and SMS channels.

[![Latest Stable Version](https://poser.pugx.org/tuyakhov/yii2-notifications/v/stable)](https://packagist.org/packages/tuyakhov/yii2-notifications) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tuyakhov/yii2-notifications/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tuyakhov/yii2-notifications/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/tuyakhov/yii2-notifications/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tuyakhov/yii2-notifications/build-status/master) [![Code Climate](https://codeclimate.com/github/tuyakhov/yii2-notifications/badges/gpa.svg)](https://codeclimate.com/github/tuyakhov/yii2-notifications)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist tuyakhov/yii2-notifications "*"
```

or add

```
"tuyakhov/yii2-notifications": "*"
```

to the require section of your `composer.json` file.


Usage
-----

The following example shows how to create a Notifier instance and send your first notification:

```php
$notifier = new \tuyakhov\notifications\Notifier([
  'channels' => [...],
]);
$notifier->send($recipients, $notifications);
```

Notifier is often used as an application component and configured in the application configuration like the following:

```php
[
   'components' => [
       'notifier' => [
           'class' => '\tuyakhov\notifications\Notifier',
           'channels' => [
               'mail' => [
                   'class' => '\tuyakhov\notifications\channels\MailChannel',
                   'from' => 'no-reply@example.com'
               ],
               'sms' => [
                   'class' => '\tuyakhov\notifications\channels\TwilioChannel',
                   'accountSid' => '...',
                   'authToken' => '...',
                   'from' => '+1234567890'
               ],
               'telegram' => [
                    'class' => '\tuyakhov\notifications\channels\TelegramChannel',
                    'botToken' => '...'
                ],
               'database' => [
                    'class' => '\tuyakhov\notifications\channels\ActiveRecordChannel'
               ]
           ],
       ],
   ],
]
```
Once the component is configured it may be used for sending notifications:
```php
$recipient = User::findOne(1);
$notification = new InvoicePaid($invoice);

Yii::$app->notifier->send($recipient, $notification);
```
Each notification class should implement `NotificationInterface` and contain a `viaChannels` method and a variable number of message building methods (such as `exportForMail`) that convert the notification to a message optimized for that particular channel.
Example of a notification that covers the case when an invoice has been paid:

```php
use tuyakhov\notifications\NotificationInterface;
use tuyakhov\notifications\NotificationTrait;

class InvoicePaid implements NotificationInterface
 {
    use NotificationTrait;
    
    private $invoice;
    
    public function __construct($invoice) 
    {
        $this->invoice = $invoice;
    }

    /**
     * Prepares notification for 'mail' channel
     */
    public function exportForMail() {
        return Yii::createObject([
           'class' => '\tuyakhov\notifications\messages\MailMessage',
           'view' => ['html' => 'invoice-paid'],
           'viewData' => [
               'invoiceNumber' => $this->invoice->id,
               'amount' => $this->invoice->amount
           ]
        ])
    }
    
    /**
     * Prepares notification for 'sms' channel
     */
    public function exportForSms()
    {
        return \Yii::createObject([
            'class' => '\tuyakhov\notifications\messages\SmsMessage',
            'text' => "Your invoice #{$this->invoice->id} has been paid"
        ]);
    }
    
    /**
     * Prepares notification for 'database' channel
     */
    public function exportForDatabase()
    {
        return \Yii::createObject([
            'class' => '\tuyakhov\notifications\messages\DatabaseChannel',
            'subject' => "Invoice has been paid",
            'body' => "Your invoice #{$this->invoice->id} has been paid",
            'data' => [
                'actionUrl' => ['href' => '/invoice/123/view', 'label' => 'View Details']
            ]
        ]);
    }
 }
```

You may use the NotifiableInterface and NotifiableTrait on any of your models:
 
 ```php
 use yii\db\ActiveRecord;
 use tuyakhov\notifications\NotifiableTrait;
 use tuyakhov\notifications\NotifiableInterface;
 
 class User extends ActiveRecord implements NotifiableInterface 
 {
    use NotifiableTrait;
    
    public function routeNotificationForMail() 
    {
         return $this->email;
    }
 }
 ```
 
#### Database notifications

The `database` notification channel stores the notification information in a database table.   
You can query the table to display the notifications in your application's user interface. But, before you can do that, you will need to create a database table to hold your notifications. To do this, you can use the migration that comes with this extension:
```
yii migrate --migrationPath=@vendor/tuyakhov/yii2-notifications/src/migrations
```
or
```php
'controllerMap' => [
    ...
    'migrate' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationNamespaces' => [
            'tuyakhov\notifications\migrations',
        ],
    ],
    ...
],
```

```
php yii migrate/up
```

**Accessing The Notifications**   
Once notifications are stored in the database, you need a convenient way to access them from your notifiable entities. The `NotifiableTrait`, which comes with this extension, includes a notifications relationship that returns the notifications for the entity.
To fetch notifications, you may access this method like any other `ActiveRecord` relationship.
```php
$model = User::findOne(1);
foreach($model->notifications as $notification) {
    echo $notification->subject;
}
```
If you want to retrieve only the "unread" notifications, you may use the `unreadNotifications` relationship.
```php
$model = User::findOne(1);
foreach($model->unreadNotifications as $notification) {
    echo $notification->subject;
}
```
You can access custom JSON data that describes the notification and was added using `DatabaseMessage`:
```php
/** @var $notificatiion tuyakhov\notifications\models\Notificatios */
$actionUrl = $notification->data('actionUrl'); // ['href' => '/invoice/123/pay', 'label' => 'Pay Invoice']
```

**Marking Notifications As Read**   
Typically, you will want to mark a notification as "read" when a user views it. The `ReadableBehavior` in `Notification` model provides a `markAsRead` method, which updates the read_at column on the notification's database record:
```php
$model = User::findOne(1);
foreach($model->unreadNotifications as $notification) {
    $notification->markAsRead();
    
    // the following methods are also available
    $notification->markAsUnread();
    $notification->isUnread();
    $notification->isRead();
}
```