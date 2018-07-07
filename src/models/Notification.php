<?php


namespace tuyakhov\notifications\models;


use tuyakhov\notifications\behaviors\ReadableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Database notification model
 * @property $level string
 * @property $subject string
 * @property $notifiable_type string
 * @property $notifiable_id int
 * @property $body string
 * @property $read_at string
 * @property $notifiable
 * @method  void markAsRead()
 * @method  void markAsUnread()
 * @method  bool read()
 * @method  bool unread()
 */
class Notification extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level', 'notifiable_type', 'subject', 'body'], 'string'],
            ['notifiable_id', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
            ReadableBehavior::className()
        ];
    }

    public function getNotifiable()
    {
        return $this->hasOne($this->notifiable_type, ['id' => 'notifiable_id']);
    }
}