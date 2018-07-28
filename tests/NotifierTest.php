<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */
namespace tuyakhov\notifications\tests;

use tuyakhov\notifications\events\NotificationEvent;
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
                'mockChannel' => $this->createMock('tuyakhov\notifications\channels\ChannelInterface')
            ]
        ]);
    }


    public function testSend()
    {
        $notification = $this->createMock('tuyakhov\notifications\NotificationInterface');
        $notification->method('broadcastOn')->willReturn(['mockChannel']);
        
        $recipient = $this->createMock('tuyakhov\notifications\NotifiableInterface');
        $recipient->method('shouldReceiveNotification')->willReturn(true);
        $recipient->method('viaChannels')->willReturn(['mockChannel']);
        
        $this->notifier->channels['mockChannel']
            ->expects($this->once())
            ->method('send')
            ->with($recipient, $notification);

        $eventRaised = null;
        $this->notifier->on(Notifier::EVENT_AFTER_SEND, function(NotificationEvent $event) use (&$eventRaised) {
            $eventRaised = $event;
        });

        $this->notifier->send($recipient, $notification);
        $this->assertNotEmpty($eventRaised);
        $this->assertInstanceOf('tuyakhov\notifications\NotificationInterface', $eventRaised->notification);
        $this->assertInstanceOf('tuyakhov\notifications\NotifiableInterface', $eventRaised->recipient);
        $this->assertEquals('mockChannel', $eventRaised->channel);
    }
}
