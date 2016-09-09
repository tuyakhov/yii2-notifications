<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications;
use tuyakhov\notifications\channels\ChannelInterface;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class Notifier is a component that can send multiple notifications to multiple recipients using available channels
 *
 * The following example shows how to create a Notifier instance and send your first notification:
 *
 * ```php
 * $notifier = new \tuyakhov\notifications\Notifier([
 *     'channels' => [...],
 * ]);
 * $notifier->send($recipients, $notifications);
 * ```
 *
 * Notifier is often used as an application component and configured in the application configuration like the following:
 *
 * ```php
 * [
 *      'components' => [
 *          'notifier' => [
 *              'class' => '\tuyakhov\notifications\Notifier',
 *              'channels' => [
 *                  'mail' => [
 *                      'class' => 'EmailNotificationChannel',
 *                  ]
 *              ],
 *          ],
 *      ],
 * ]
 * ```
 * @package common\notifications
 */
class Notifier extends Component
{
    /**
     * @var array defines available channels
     * The syntax is like the following:
     *
     * ```php
     * [
     *     'mail' => [
     *         'class' => 'EmailNotificationChannel',
     *     ],
     * ]
     * ```
     */
    public $channels = [];

    /**
     * Sends the given notifications through available channels to the given notifiable entities.
     * You may pass an array in order to send multiple notifications to multiple recipients.
     * 
     * @param array|NotifiableInterface $recipients the recipients that can receive given notifications.
     * @param array|NotificationInterface $notifications the notification that should be delivered.
     * @return void
     */
    public function send($recipients, $notifications)
    {
        if (!is_array($recipients)) {
            /**
             * @var $recipients NotifiableInterface[]
             */
            $recipients = [$recipients];
        }
        
        if (!is_array($notifications)){
            /**
             * @var $notifications NotificationInterface[]
             */
            $notifications = [$notifications];
        }
        
        foreach ($recipients as $recipient) {
            $channels = array_intersect($recipient->viaChannels(), array_keys($this->channels));
            foreach ($notifications as $notification) {
                if (!$recipient->shouldReceiveNotification($notification)) {
                    continue;
                }

                $channels = array_intersect($channels, $notification->broadcastOn());
                foreach ($channels as $channel) {
                    $this->getChannelInstance($channel)->send($recipient, $notification);
                }
            }
        }
    }

    /**
     * Returns channel instance
     * @param string $channel the channel name
     * @return ChannelInterface
     * @throws InvalidConfigException
     */
    protected function getChannelInstance($channel)
    {
        if (!isset($this->channels[$channel])) {
            throw new InvalidConfigException("Notification channel `{$channel}` is not available or configuration is missing");
        }
        if (!$this->channels[$channel] instanceof ChannelInterface) {
            $this->channels[$channel] = \Yii::createObject($this->channels[$channel]);
        }
        return $this->channels[$channel];
    }
}