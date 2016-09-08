<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */
namespace tuyakhov\notifications\channels;


use tuyakhov\notifications\NotificationChannelInterface;
use tuyakhov\notifications\NotificationInterface;
use yii\base\Component;
use yii\di\Instance;
use yii\mail\MailerInterface;

class MailNotificationChannel extends Component implements NotificationChannelInterface
{
    /**
     * @var $mailer MailerInterface|array|string the mailer object or the application component ID of the mailer object.
     */
    public $mailer = 'mailer';

    public function init()
    {
        parent::init();
        $this->mailer = Instance::ensure($this->mailer, 'yii\mail\MailerInterface');
    }
    
    public function send($recipient, NotificationInterface $notification)
    {
        
    }
}