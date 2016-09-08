<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications;


interface NotificationInterface
{
    /**
     * @return array
     */
    public function export();

    /**
     * @return array
     */
    public function broadcastOn();
}