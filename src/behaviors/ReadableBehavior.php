<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications\behaviors;


use yii\base\Behavior;
use yii\db\ActiveRecordInterface;

class ReadableBehavior extends Behavior
{

    public $readAttribute = 'read_at';

    /**
     * Mark the notification as read.
     * @throws \Exception
     * @throws \Throwable
     */
    public function markAsRead()
    {
        /** @var ActiveRecordInterface $model */
        $model = $this->owner;
        if (is_null($model->{$this->readAttribute})) {
            $model->{$this->readAttribute} = date('Y-m-d H:i:s');
            $model->update(false, [$this->readAttribute]);
        }
    }

    /**
     * Mark the notification as unread.
     * @return void
     * @throws \Exception
     * @throws \Throwable
     */
    public function markAsUnread()
    {
        /** @var ActiveRecordInterface $model */
        $model = $this->owner;
        if (!is_null($model->{$this->readAttribute})) {
            $model->{$this->readAttribute} = null;
            $model->update(false, [$this->readAttribute]);
        }
    }

    /**
     * Determine if a notification has been read.
     *
     * @return bool
     */
    public function isRead()
    {
        return $this->owner->{$this->readAttribute} !== null;
    }
    /**
     * Determine if a notification has not been read.
     *
     * @return bool
     */
    public function isUnread()
    {
        return $this->owner->{$this->readAttribute} === null;
    }
}
