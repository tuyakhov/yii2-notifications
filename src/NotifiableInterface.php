<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications;


interface NotifiableInterface
{
    /**
     * Determines if the notification can be sent to the notifiable entity.
     * @param NotificationInterface $notification
     * @return bool
     */
    public function shouldReceiveNotification(NotificationInterface $notification);

    /**
     * Get the channels the notifiable entity should listen on.
     * @return array
     */
    public function viaChannels();

    /**
     * Get the notification routing information for the given channel.
     * @param string $channel
     * @return mixed
     */
    public function routeNotificationFor($channel);
}