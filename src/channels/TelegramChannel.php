<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications\channels;

use tuyakhov\notifications\NotifiableInterface;
use tuyakhov\notifications\NotificationInterface;
use tuyakhov\notifications\messages\TelegramMessage;
use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\Json;
use yii\httpclient\Client;

/**
 * See an example flow of sending notifications in Telegram
 * @see https://core.telegram.org/bots#deep-linking-example
 */
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
     * Each bot is given a unique authentication token when it is created.
     * The token looks something like 123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11
     * @var string
     */
    public $botToken;

    /**
     * @var string
     */
    public $parseMode = self::PARSE_MODE_MARKDOWN;

    const PARSE_MODE_HTML = "HTML";

    const PARSE_MODE_MARKDOWN = "Markdown";

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if(!isset($this->botToken)){
            throw new InvalidConfigException('Bot token is undefined');
        }

        if (!isset($this->httpClient)) {
            $this->httpClient = [
                'class' => Client::className(),
                'baseUrl' => $this->apiUrl,
            ];
        }
        $this->httpClient = Instance::ensure($this->httpClient, Client::className());
    }


    /**
     * @inheritDoc
     */
    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        /** @var TelegramMessage $message */
        $message = $notification->exportFor('telegram');
        $text = $message->body;
        if (!empty($message->subject)) {
            $text = "*{$message->subject}*\n{$message->body}";
        }
        $chatId = $recipient->routeNotificationFor('telegram');
        if(!$chatId){
            throw new InvalidArgumentException( 'No chat ID provided');
        }

        $data = [
            'chat_id' => $chatId,
            'text' => $text,
            'disable_notification' => $message->silentMode,
            'disable_web_page_preview' => $message->withoutPagePreview,
        ];

        if ($message->replyToMessageId) {
            $data['reply_to_message_id'] = $message->replyToMessageId;
        }

        if ($message->replyMarkup) {
            $data['reply_markup'] = Json::encode($message->replyMarkup);
        }

        if(isset($this->parseMode)){
            $data['parse_mode'] = $this->parseMode;
        }

        return $this->httpClient->createRequest()
            ->setUrl($this->createUrl())
            ->setData($data)
            ->send();
    }

    private function createUrl()
    {
        return "bot{$this->botToken}/sendMessage";
    }
}