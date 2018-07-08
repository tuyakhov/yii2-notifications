<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications\tests;


use tuyakhov\notifications\messages\DatabaseMessage;

class ActiveRecordChannelTest extends TestCase
{
    public function testSend()
    {
        $recipient = $this->createMock('tuyakhov\notifications\NotifiableInterface');
        $recipient->expects($this->once())
            ->method('routeNotificationFor')
            ->with('database')
            ->willReturn(['yii\base\DynamicModel', 123]);

        $message = \Yii::createObject([
            'class' => DatabaseMessage::className(),
            'level' => 'debug',
            'subject' => 'It',
            'body' => 'Works',
        ]);
        $notificationModel = $this->createMock('yii\db\ActiveRecordInterface');
        $notificationModel->method('insert');
        $notificationModel->expects($this->once())
            ->method('insert')
            ->with(true, [
                'level' => $message->level,
                'subject' => $message->subject,
                'body' => $message->body,
                'notifiable_type' => 'yii\base\DynamicModel',
                'notifiable_id' => 123,
            ])
            ->willReturnSelf();


        $channel = \Yii::createObject([
            'class' => 'tuyakhov\notifications\channels\ActiveRecordChannel',
            'model' => $notificationModel,
        ]);

        $notification = $this->createMock('tuyakhov\notifications\NotificationInterface');
        $notification->expects($this->once())
            ->method('exportFor')
            ->with('database')
            ->willReturn($message);
        $channel->send($recipient, $notification);

    }
}