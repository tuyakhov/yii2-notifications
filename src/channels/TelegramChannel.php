<?php

namespace tuyakhov\notifications\channels;

use tuyakhov\notifications\NotifiableInterface;
use tuyakhov\notifications\NotificationInterface;
use tuyakhov\notifications\messages\TelegramMessage;
use yii\base\Component;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

class TelegramChannel extends Component implements ChannelInterface
{
    /**
     * @var BaseActiveRecord|string
     */
    public $model = 'app\additional\notification\Notification';

    /**
     * @var Client|array|string
     */
    public $httpClient;

    /**
     * @var string
     */
    public $api_url = "https://api.telegram.org/bot";

    /**
     * @var string
     */
    public $bot_id;

    /**
     * @var string
     */
    public $bot_token;

    /**
     * @var string
     */
    public $parse_mode = null;

    const PARSE_MODE_HTML = "HTML";

    const PARSE_MODE_MARKDOWN = "Markdown";

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if(!isset($this->bot_id) || !isset($this->bot_token)){
            throw new InvalidConfigException("Bot id or bot token is undefined");
        }

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
     * @return mixed
     */
    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        /** @var TelegramMessage $message */
        $message = $notification->exportFor('telegram');
        $text = "*{$message->subject}*\n{$message->body}";
        $chatId = $recipient->routeNotificationFor('telegram');

        $data = [
            "chat_id" => $chatId,
            "text" => $text,
        ];
        if($this->parse_mode != null){
            $data["parse_mode"] = $this->parse_mode;
        }

        $resultUrl = $this->createUrl();
        return $this->httpClient->createRequest()
            ->setMethod('post')
            ->setUrl($resultUrl)
            ->setData($data)
            ->send();
    }

    private function createUrl()
    {
        return $this->api_url . $this->bot_id . ":" . $this->bot_token . "/sendmessage";
    }
}