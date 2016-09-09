<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications;


interface NotificationInterface
{
    /**
     * @param mixed $recipient
     * @return mixed
     */
    public function export($recipient = null);

    /**
     * @return array
     */
    public function broadcastOn();
}