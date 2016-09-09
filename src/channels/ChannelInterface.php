<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications\channels;

use tuyakhov\notifications\NotificationInterface;

interface ChannelInterface
{
    public function send($recipient, NotificationInterface $notification);
}