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

Once the extension is installed, simply use it in your code by  :

Widget

```php
    \tuyakhov\notifications\EmbedWidget::widget([
        'code' => 'vs5ZF9fRDzA',
        'playerParameters' => [
            'controls' => 2
        ],
        'iframeOptions' => [
            'width' => '600',
            'height' => '450'
        ]
    ]);
```

Validator

```php
    public function rules()
    {
        return [
            ['notifications_code', CodeValidator::className()],
        ];
    }
```