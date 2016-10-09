<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications;

use yii\helpers\Inflector;

class NotificationTrait implements NotificationInterface
{
    public function broadcastOn()
    {
        $channels = [];
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (($channel = stristr($method, 'exportFor', true)) !== false) {
                $channels[] = $channel;
            }
        }
        return $channels;
    }

    public function exportFor($channel)
    {
        if (method_exists($this, $method = 'exportFor'.Inflector::id2camel($channel))) {
            return $this->{$method}();
        }
        throw new \InvalidArgumentException("Cannot find message export for chanel `{$channel}`");
    }

}