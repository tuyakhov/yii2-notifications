<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications\tests;


use tuyakhov\notifications\messages\DatabaseMessage;
use tuyakhov\notifications\models\Notification;

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
                        'data' => '[]'
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

    public function testWithDB()
    {
        $recipient = $this->createMock('tuyakhov\notifications\NotifiableInterface');
        $recipient->expects($this->atLeastOnce())
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
        ]);

        $notification = $this->createMock('tuyakhov\notifications\NotificationInterface');
        $notification->expects($this->once())
            ->method('exportFor')
            ->with('database')
            ->willReturn($message);
        $channel->send($recipient, $notification);

        $this->assertEquals(1, Notification::find()->count());

        $body = 'Does not work';
        $actionUrl = ['href' => '/invoice/123/pay', 'label' => 'Pay Invoice'];
        $differentMessage = \Yii::createObject([
            'class' => DatabaseMessage::className(),
            'level' => 'error',
            'subject' => 'It',
            'body' => $body,
            'data' => ['actionUrl' => $actionUrl]
        ]);
        $anotherNotification = $this->createMock('tuyakhov\notifications\NotificationInterface');
        $anotherNotification->expects($this->once())
            ->method('exportFor')
            ->with('database')
            ->willReturn($differentMessage);
        $channel->send($recipient, $anotherNotification);
        $this->assertEquals(2, Notification::find()->count());
        /** @var $savedError Notification */
        $this->assertNotEmpty($savedError = Notification::find()->where(['level' => 'error'])->one());
        $this->assertEquals($body, $savedError->body);
        $this->assertEquals($actionUrl, $savedError->data('actionUrl'));
        $this->assertNull($savedError->data('invalid'));
    }
}