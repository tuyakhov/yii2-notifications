<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications\channels;


use tuyakhov\notifications\messages\DatabaseMessage;
use tuyakhov\notifications\NotifiableInterface;
use tuyakhov\notifications\NotificationInterface;
use yii\base\Component;
use yii\base\DynamicModel;
use yii\db\ActiveRecordInterface;
use yii\db\BaseActiveRecord;
use yii\di\Instance;

class ActiveRecordChannel extends Component implements ChannelInterface
{
    /**
     * @var BaseActiveRecord|string
     */
    public $model = 'tuyakhov\notifications\models\Notification';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
    }

    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        $model = Instance::ensure($this->model, 'yii\db\BaseActiveRecord');

        /** @var DatabaseMessage $message */
        $message = $notification->exportFor('database');
        list($notifiableType, $notifiableId) = $recipient->routeNotificationFor('database');

        $data = [
            'level' => $message->level,
            'subject' => $message->subject,
            'body' => $message->body,
            'notifiable_type' => $notifiableType,
            'notifiable_id' => $notifiableId,
        ];

        if ($model->load($data, '')) {
            return $this->model->insert();
        }

        return false;
    }
}