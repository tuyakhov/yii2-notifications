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
     * @var Client|array|string
     */
    public $httpClient;

    /**
     * @var string
     */
    public $apiUrl = "https://api.telegram.org/";

    /**
     * @var string
     */
    public $bot_id;

    /**
     * @var string
     */
    public $botToken;

    /**
     * @var string
     */
    public $parseMode = null;

    const PARSE_MODE_HTML = "HTML";

    const PARSE_MODE_MARKDOWN = "Markdown";

    /**
     * @var bool
     * If you need to change silentMode, you can use this code before calling telegram channel
     *
     * \Yii::$container->set('\app\additional\notification\TelegramChannel', [
     *                         'silentMode' => true,
     * ]);
     */
    public $silentMode = false;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if(!isset($this->bot_id) || !isset($this->botToken)){
            throw new InvalidConfigException("Bot id or bot token is undefined");
        }

        if (!isset($this->httpClient)) {
            $this->httpClient = [
                'class' => Client::className(),
                'baseUrl' => $this->apiUrl
            ];
        }
        $this->httpClient = Instance::ensure($this->httpClient, Client::className());
    }


    /**
     * @param NotifiableInterface $recipient
     * @param NotificationInterface $notification
     * @return mixed
     * @throws \Exception
     */
    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        /** @var TelegramMessage $message */
        $message = $notification->exportFor('telegram');
        $text = "*{$message->subject}*\n{$message->body}";
        $chat_id = $recipient->routeNotificationFor('telegram');
        if(!$chat_id){
            throw new \Exception("User doesn't have telegram_id");
        }

        $data = [
            "chat_id" => $chat_id,
            "text" => $text,
            'disable_notification' => $this->silentMode
        ];
        if($this->parseMode  != null){
            $data["parse_mode"] = $this->parseMode;
        }

        return $this->httpClient->createRequest()
            ->setMethod('post')
            ->setUrl($this->createUrl())
            ->setData($data)
            ->send();
    }

    private function createUrl()
    {
        return "bot" . $this->bot_id . ":" . $this->botToken . "/sendmessage";
    }
}