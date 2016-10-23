<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications\tests;


use tuyakhov\notifications\channels\MailChannel;
use tuyakhov\notifications\channels\TwilioChannel;
use tuyakhov\notifications\messages\MailMessage;
use tuyakhov\notifications\messages\SmsMessage;
use tuyakhov\notifications\NotifiableInterface;
use tuyakhov\notifications\NotificationInterface;
use yii\httpclient\Client;
use yii\httpclient\Request;
use yii\mail\MailerInterface;
use yii\mail\MessageInterface;

class TwilioChannelTest extends TestCase
{

    public function testSend()
    {
        $recipient = $this->createMock(NotifiableInterface::class);
        $recipient->expects($this->once())
            ->method('routeNotificationFor')
            ->with('sms')
            ->willReturn('+1234567890');
        
        $client = $this->createMock(Client::className());
        $client->expects($this->once())
            ->method('send');
        $client->method('createRequest')
            ->willReturn(\Yii::createObject([
                'class' => Request::className(),
                'client' => $client
            ]));
        
        $channel = \Yii::createObject([
            'class' => TwilioChannel::className(),
            'from' => '+1234567890123',
            'httpClient' => $client
        ]);

        $notification = $this->createMock(NotificationInterface::class);
        $notification->expects($this->once())
            ->method('exportFor')
            ->with('sms')
            ->willReturn(\Yii::createObject([
                'class' => SmsMessage::className(),
                'body' => 'Test message body',
            ]));
        $channel->send($recipient, $notification);

    }
}