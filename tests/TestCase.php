<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */
namespace tuyakhov\notifications\tests;

use yii\console\Application;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        parent::setUp();
        \Yii::$app = new Application([
            'id' => 'test-app',
            'basePath' => __DIR__,
            'aliases' => [
                '@tuyakhov/notifications/migrations' => dirname(__DIR__) . '/src/migrations',
            ],
            'controllerNamespace' => 'yii\console\controllers',
            'components' => [
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'sqlite::memory:'
                ]
            ],
            'controllerMap' => [
                'migrate' => [
                    'class' => 'yii\console\controllers\MigrateController',
                    'compact' => true,
                    'interactive' => false,
                    'migrationNamespaces' => [
                        'tuyakhov\notifications\migrations'
                    ],
                ],
            ],
        ]);

        \Yii::$app->runAction('migrate/fresh');
    }

    protected function tearDown()
    {
        parent::tearDown();
        \Yii::$app = null;
    }


}