<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications;


use tuyakhov\notifications\messages\AbstractMessage;

interface NotificationInterface
{
    /**
     * @param string $channel
     * @return AbstractMessage
     */
    public function exportFor($channel);

    /**
     * @return array
     */
    public function broadcastOn();
}