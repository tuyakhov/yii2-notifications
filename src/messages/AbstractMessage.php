<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */
namespace tuyakhov\notifications\messages;


use yii\base\BaseObject;

abstract class AbstractMessage extends BaseObject
{
    /**
     * The "level" of the notification (info, success, error).
     * @var string
     */
    public $level = 'info';

    /**
     * The subject of the notification.
     * @var string
     */
    public $subject;

    /**
     * The notification's message body
     * @var string
     */
    public $body;
}