<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications;


use yii\base\Behavior;

class NotifiableBehavior extends Behavior
{
    public $notifications = [];

    public function attach($owner)
    {
        $this->owner = $owner;
        foreach ($this->notifications as $event => $notifications) {
            if (!is_array($notifications)) {
                $notifications = [$notifications];
            }
            foreach ($notifications as $notification) {
                $owner->on($event, is_string($notification) ? [$notification, 'handle'] : $notification);
            }
        }
    }

    public function detach()
    {
        if ($this->owner) {
            foreach ($this->notifications as $event => $notifications) {
                if (!is_array($notifications)) {
                    $notifications = [$notifications];
                }
                foreach ($notifications as $notification) {
                    $this->owner->off($event, is_string($notification) ? [$notification, 'handle'] : $notification);
                }
            }
            $this->owner = null;
        }
    }


}