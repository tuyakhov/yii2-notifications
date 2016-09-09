<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications;


use yii\helpers\Inflector;

trait NotifiableTrait
{
    public function shouldSendNotification(NotificationInterface $notification)
    {
        
    }

    public function routeNotificationFor($channel)
    {
        if (method_exists($this, $method = 'routeNotificationFor'.Inflector::id2camel($channel))) {
            return $this->{$method}();
        }
        switch ($channel) {
            case 'mail':
                return $this->email;
            case 'nexmo':
                return $this->phone_number;
        }
    }
}