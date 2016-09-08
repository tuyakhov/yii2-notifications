<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications;


interface NotificationChannelInterface
{
    public function send($recipient, NotificationInterface $notification);
}