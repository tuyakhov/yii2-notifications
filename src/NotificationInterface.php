<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications;


use tuyakhov\notifications\messages\AbstractMessage;

interface NotificationInterface
{
    /**
     * Export notification as message for given channel.
     * @param string $channel
     * @return AbstractMessage
     */
    public function exportFor($channel);

    /**
     * Determines on which channels the notification will be delivered
     * @return array
     */
    public function broadcastOn();
}