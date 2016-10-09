<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications;

use tuyakhov\notifications\messages\AbstractMessage;
use yii\helpers\Inflector;

trait NotificationTrait
{
    /**
     * @return array
     */
    public function broadcastOn()
    {
        $channels = [];
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (strpos($method, 'exportFor') === false) {
                continue;
            }
            $channel = str_replace('exportFor', '', $method);
            if (!empty($channel)) {
                $channels[] = Inflector::camel2id($channel);
            }
        }
        return $channels;
    }

    /**
     * Determines on which channels the notification will be delivered.
     * ```php
     * public function exportForMail() {
     *      return Yii::createObject([
     *          'class' => 'tuyakhov\notifications\messages\MailMessage',
     *          'view' => ['html' => 'welcome'],
     *          'viewData' => [...]
     *      ])
     * }
     * ```
     * @param $channel
     * @return AbstractMessage
     * @throws \InvalidArgumentException
     */
    public function exportFor($channel)
    {
        if (method_exists($this, $method = 'exportFor'.Inflector::id2camel($channel))) {
            return $this->{$method}();
        }
        throw new \InvalidArgumentException("Can not find message export for chanel `{$channel}`");
    }

}