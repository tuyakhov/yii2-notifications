<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications\migrations;

use yii\db\Migration;

class m180824_090744_create_notification_table extends Migration
{
    public function up()
    {
        $this->createTable('notification', [
            'id' => $this->primaryKey(),
            'level' => $this->string(),
            'notifiable_type' => $this->string(),
            'notifiable_id' => $this->integer()->unsigned(),
            'subject' => $this->string(),
            'body' => $this->text(),
            'read_at' => $this->timestamp()->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->null(),
        ]);
        $this->createIndex('notifiable', 'notification', ['notifiable_type', 'notifiable_id']);
    }

    public function down()
    {
        $this->dropIndex('notifiable', 'notification');
        $this->dropTable('notification');
    }

}