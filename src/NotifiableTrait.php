<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications;


use yii\helpers\Inflector;

trait NotifiableTrait
{
    /**
     * Determines if notifiable entity should receive the notification by checking in notification settings.
     * @param NotificationInterface $notification
     * @return bool
     */
    public function shouldReceiveNotification(NotificationInterface $notification)
    {
        $alias = Inflector::camel2id(get_class($notification));
        $settings = $this->notificationSettings;
        if (array_key_exists($alias, $settings)) {
            return (bool) $settings[$alias];
        }
        return true;
    }

    /**
     * Send notifications via email by default
     * @return array
     */
    public function viaChannels()
    {
        return ['mail'];
    }

    /**
     * Return the notification routing information for the given channel.
     * ```php
     * public function routeNotificationForMail() {
     *      return $this->email;
     * }
     * ```
     * @param $channel string
     * @return mixed
     */
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