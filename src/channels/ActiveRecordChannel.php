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
    public $model = 'tuyakhov\notifications\models\Notification';

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

        $this->model->level = $message->level;
        $this->model->subject = $message->subject;
        $this->model->body = $message->body;
        $this->model->notifiable_type = $notifiableType;
        $this->model->notifiable_id = $notifiableId;

        return $this->model->insert(true);
    }
}