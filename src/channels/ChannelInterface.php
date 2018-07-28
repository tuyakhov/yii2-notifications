<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications\channels;

use tuyakhov\notifications\NotifiableInterface;
use tuyakhov\notifications\NotificationInterface;

interface ChannelInterface
{
    /**
     * @param NotifiableInterface $recipient
     * @param NotificationInterface $notification
     * @return mixed channel response
     * @throws \Exception
     */
    public function send(NotifiableInterface $recipient, NotificationInterface $notification);
}