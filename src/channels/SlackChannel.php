<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications\channels;


use tuyakhov\notifications\messages\SlackMessage;
use tuyakhov\notifications\NotifiableInterface;
use tuyakhov\notifications\NotificationInterface;
use yii\base\Component;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\httpclient\Response;

class SlackChannel extends Component implements ChannelInterface
{
    /**
     * @var Client|array|string
     */
    public $httpClient;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!isset($this->httpClient)) {
            $this->httpClient = [
                'class' => Client::className(),
            ];
        }
        $this->httpClient = Instance::ensure($this->httpClient, Client::className());
    }

    /**
     * @param NotifiableInterface $recipient
     * @param NotificationInterface $notification
     * @return Response
     */
    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        /** @var SlackMessage $message */
        $message = $notification->exportFor('slack');
        $webhookUrl = $recipient->routeNotificationFor('slack');
        $text = "*{$message->subject}*\n{$message->body}";
        return $this->httpClient->createRequest()
            ->setMethod('post')
            ->setUrl($webhookUrl)
            ->setData(ArrayHelper::merge(['text' => $text], $message->arguments))
            ->send();
    }

}