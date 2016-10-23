Notifications for Yii2
======================
This Yii2 extension provides support for sending notifications across a variety of delivery channels, including mail, SMS, Slack etc. Notifications may also be stored in a database so they may be displayed in your web interface.

Typically, notifications should be short, informational messages that notify users of something that occurred in your application. For example, if you are writing a billing application, you might send an "Invoice Paid" notification to your users via the email and SMS channels.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tuyakhov/yii2-notifications/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tuyakhov/yii2-notifications/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/tuyakhov/yii2-notifications/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tuyakhov/yii2-notifications/build-status/master) [![Code Climate](https://codeclimate.com/github/tuyakhov/yii2-notifications/badges/gpa.svg)](https://codeclimate.com/github/tuyakhov/yii2-notifications)

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
                   'class' => '\tuyakhov\notifications\channels\TwilioChannel,
                   'accountSid' => '...',
                   'authToken' => '...',
                   'from' => '+1234567890'
               ]
           ],
       ],
   ],
]
```

Each notification class should implement NotificationInterface and contains a via method and a variable number of message building methods (such as `exportForMail`) that convert the notification to a message optimized for that particular channel.
Example of notification that covers the case when an invoice has been paid:

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
    
    public function exportForSms()
    {
        return \Yii::createObject([
            'class' => '\tuyakhov\notifications\messages\SmsMessage',
            'text' => "Your invoice #{$this->invoice->id} has been paid"
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