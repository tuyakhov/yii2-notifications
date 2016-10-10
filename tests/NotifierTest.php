<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */
namespace tuyakhov\notifications\tests;

use tuyakhov\notifications\channels\ChannelInterface;
use tuyakhov\notifications\NotifiableInterface;
use tuyakhov\notifications\NotificationChannelInterface;
use tuyakhov\notifications\NotificationInterface;
use tuyakhov\notifications\Notifier;

class NotifierTest extends TestCase
{
    /**
     * @var $notifier Notifier
     */
    protected $notifier;

    protected function setUp()
    {
        parent::setUp();
        $this->notifier = \Yii::createObject([
            'class' => Notifier::className(),
            'channels' => [
                'mockChannel' => $this->createMock(ChannelInterface::class)
            ]
        ]);
    }


    public function testSend()
    {
        $notification = $this->createMock(NotificationInterface::class);
        $notification->method('broadcastOn')->willReturn(['mockChannel']);
        
        $recipient = $this->createMock(NotifiableInterface::class);
        $recipient->method('shouldReceiveNotification')->willReturn(true);
        $recipient->method('viaChannels')->willReturn(['mockChannel']);
        
        $this->notifier->channels['mockChannel']
            ->expects($this->once())
            ->method('send')
            ->with($recipient, $notification);
        
        $this->notifier->send($recipient, $notification);
    }
}
