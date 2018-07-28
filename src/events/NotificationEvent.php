<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications\events;


use tuyakhov\notifications\NotifiableInterface;
use tuyakhov\notifications\NotificationInterface;
use yii\base\Event;

class NotificationEvent extends Event
{
    /**
     * @var NotificationInterface
     */
    public $notification;

    /**
     * @var NotifiableInterface
     */
    public $recipient;

    /**
     * @var string
     */
    public $channel;

    /**
     * @var mixed
     */
    public $response;
}