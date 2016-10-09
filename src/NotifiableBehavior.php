<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications;


use yii\base\Behavior;
use yii\base\Event;
use yii\di\Instance;

class NotifiableBehavior extends Behavior
{
    public $notifications = [];

    /**
     * @var Notifier
     */
    public $notifier = 'notifier';

    public function init()
    {
        parent::init();
        $this->notifier = Instance::of($this->notifier);
    }

    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        $this->owner = $owner;
        foreach ($this->notifications as $event => $notifications) {
            if (!is_array($notifications)) {
                $notifications = [$notifications];
            }
            foreach ($notifications as $notification) {
                $owner->on($event, [$this, 'handle'], ['notification' => $notification]);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function detach()
    {
        if ($this->owner) {
            foreach ($this->notifications as $event => $notifications) {
                if (!is_array($notifications)) {
                    $notifications = [$notifications];
                }
                foreach ($notifications as $notification) {
                    $this->owner->off($event, [$this, 'handle']);
                }
            }
            $this->owner = null;
        }
    }

    /**
     * Handles the event using public properties.
     * @param Event $event
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function handle(Event $event)
    {
        if (!isset($event->data['notification'])) {
            throw new \InvalidArgumentException('Can not find `notification` in event data');
        }
        if (!$this->owner instanceof NotifiableInterface) {
            throw new \RuntimeException('Owner should implement `NotifiableInterface`');
        }
        $notification = $event->data['notification'];
        $config = [];
        foreach (get_object_vars($event) as $param) {
            $config[$param] = $event->$param;
        }
        $config['class'] = $notification;
        /**
         * @var $notification NotificationInterface
         */
        $notification = \Yii::createObject($config);
        $this->notifier->send($this->owner, $notification);
    }


}