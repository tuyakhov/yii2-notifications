<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications;


use tuyakhov\notifications\models\Notification;
use yii\db\BaseActiveRecord;
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
        if (isset($this->notificationSettings)) {
            $settings = $this->notificationSettings;
            if (array_key_exists($alias, $settings)) {
                if ($settings[$alias] instanceof \Closure) {
                    return call_user_func($settings[$alias], $notification);
                }
                return (bool) $settings[$alias];
            }
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
            case 'twilio':
                return $this->phone_number;
            case 'database':
                return [get_class($this), $this->id];
        }
    }

    public function getNotifications()
    {
        /** @var $this BaseActiveRecord */
        return $this->hasMany(Notification::className(), ['notifiable_id' => 'id'])
            ->andOnCondition(['notifiable_type' => get_class($this)]);
    }

    public function getUnreadNotifications()
    {
        return $this->getNotifications()->andOnCondition(['read_at' => null]);
    }
}