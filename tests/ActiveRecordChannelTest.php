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

        $channel = \Yii::createObject([
            'class' => 'tuyakhov\notifications\channels\ActiveRecordChannel',
            'model' => function () use ($message) {
                $notificationModel = $this->createMock('yii\db\BaseActiveRecord');
                $notificationModel->method('load')->willReturn(true);
                $notificationModel->expects($this->once())
                    ->method('load')
                    ->with([
                        'level' => $message->level,
                        'subject' => $message->subject,
                        'body' => $message->body,
                        'notifiable_type' => 'yii\base\DynamicModel',
                        'notifiable_id' => 123,
                    ], '');
                $notificationModel->method('insert')->willReturn(true);
                $notificationModel->expects($this->once())
                    ->method('insert');
                return $notificationModel;
            },
        ]);

        $notification = $this->createMock('tuyakhov\notifications\NotificationInterface');
        $notification->expects($this->once())
            ->method('exportFor')
            ->with('database')
            ->willReturn($message);
        $channel->send($recipient, $notification);

    }
}