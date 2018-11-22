<?php


namespace tuyakhov\notifications\models;


use tuyakhov\notifications\behaviors\ReadableBehavior;
use tuyakhov\notifications\messages\DatabaseMessage;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Database notification model
 * @property string $level
 * @property string $subject
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property string $body
 * @property string $data
 * @property DatabaseMessage $message
 * @property string $read_at
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
            [['level', 'notifiable_type', 'subject', 'body', 'data'], 'string'],
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
                'value' => new Expression('CURRENT_TIMESTAMP'),
            ],
            ReadableBehavior::className()
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifiable()
    {
        return $this->hasOne($this->notifiable_type, ['id' => 'notifiable_id']);
    }


    /**
     * @param null $key
     * @return mixed
     */
    public function data($key = null)
    {
        $data = Json::decode($this->data);
        if ($key === null) {
            return $data;
        }
        return ArrayHelper::getValue($data, $key);
    }

}