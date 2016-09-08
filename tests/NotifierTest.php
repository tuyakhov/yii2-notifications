<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */
namespace tuyakhov\notifications\tests;

use tuyakhov\notifications\NotificationChannelInterface;
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
                'mockChannel' => ''
            ]
        ]);
    }


    public function testSend()
    {
        
    }
}
