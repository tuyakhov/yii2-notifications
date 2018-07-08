<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications\channels;


use tuyakhov\notifications\messages\DatabaseMessage;
use tuyakhov\notifications\NotifiableInterface;
use tuyakhov\notifications\NotificationInterface;
use yii\base\Component;
use yii\db\ActiveRecordInterface;
use yii\di\Instance;

class ActiveRecordChannel extends Component implements ChannelInterface
{
    /**
     * @var ActiveRecordInterface|string
     */
    public $model = 'tuyakhov\notifications\models\DatabaseNotification';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->model = Instance::ensure($this->model, 'yii\db\ActiveRecordInterface');
    }

    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        /** @var DatabaseMessage $message */
        $message = $notification->exportFor('database');
        list($notifiableType, $notifiableId) = $recipient->routeNotificationFor('database');
        $this->model->insert(true, [
            'level' => $message->level,
            'subject' => $message->subject,
            'body' => $message->body,
            'notifiable_type' => $notifiableType,
            'notifiable_id' => $notifiableId,
        ]);
    }
}