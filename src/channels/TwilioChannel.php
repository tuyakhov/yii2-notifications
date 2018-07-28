<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */
namespace tuyakhov\notifications\channels;

use tuyakhov\notifications\messages\SmsMessage;
use tuyakhov\notifications\NotifiableInterface;
use tuyakhov\notifications\NotificationInterface;
use yii\base\Component;
use yii\di\Instance;
use yii\httpclient\Client;

/**
 * Sending an SMS or MMS using Twilio REST API.
 * 
 * ```php
 * [
 *      'components' => [
 *          'notifier' => [
 *              'class' => '\tuyakhov\notifications\Notifier',
 *              'channels' => [
 *                  'sms' => [
 *                      'class' => '\tuyakhov\notifications\channels\TwilioChannel,
 *                      'accountSid' => '...',
 *                      'authToken' => '...',
 *                      'from' => '+1234567890'
 *                  ]
 *              ],
 *          ],
 *      ],
 * ]
 * ```
 */
class TwilioChannel extends Component implements ChannelInterface
{
    /**
     * @var string
     */
    public $baseUrl = 'https://api.twilio.com/2010-04-01';

    /**
     * A Twilio account SID
     * @var string
     */
    public $accountSid;

    /**
     * A Twilio account auth token
     * @var string
     */
    public $authToken;

    /**
     * A Twilio phone number (in E.164 format) or alphanumeric sender ID enabled for the type of message you wish to send.
     * @var string
     */
    public $from;

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
                'baseUrl' => $this->baseUrl
            ];
        }
        $this->httpClient = Instance::ensure($this->httpClient, Client::className());
    }

    /**
     * The Messages list resource URI
     * @return string
     */
    public function getUri()
    {
        return sprintf('%s/%s/%s', 'Accounts', $this->accountSid, 'Messages.json');
    }

    /**
     * @inheritdoc
     */
    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        /** @var SmsMessage $message */
        $message = $notification->exportFor('sms');
        $data = [
            'From' => isset($message->from) ? $message->from : $this->from,
            'To' => $recipient->routeNotificationFor('sms'),
            'Body' => $message->body
        ];
        if (isset($message->mediaUrl)) {
            $data['MedialUrl'] = $message->mediaUrl;
        }
        return $this->httpClient
            ->createRequest()
            ->setMethod('post')
            ->setUrl($this->getUri())
            ->addHeaders(['Authorization' => 'Basic ' . base64_encode("{$this->accountSid}:{$this->authToken}")])
            ->setData($data)
            ->send();
    }

}