<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */
namespace tuyakhov\notifications\channels;


use tuyakhov\notifications\messages\MailMessage;
use tuyakhov\notifications\NotifiableInterface;
use tuyakhov\notifications\NotificationInterface;
use yii\base\Component;
use yii\di\Instance;
use yii\mail\MailerInterface;

class MailChannel extends Component implements ChannelInterface
{
    /**
     * @var $mailer MailerInterface|array|string the mailer object or the application component ID of the mailer object.
     */
    public $mailer = 'mailer';

    /**
     * The message sender.
     * @var string
     */
    public $from;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->mailer = Instance::ensure($this->mailer, 'yii\mail\MailerInterface');
    }
    
    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        /**
         * @var $message MailMessage
         */
        $message = $notification->exportFor('mail');
        return $this->mailer->compose($message->view, $message->viewData)
            ->setFrom(isset($message->from) ? $message->from : $this->from)
            ->setTo($recipient->routeNotificationFor('mail'))
            ->setSubject($message->subject)
            ->send();
    }
}
