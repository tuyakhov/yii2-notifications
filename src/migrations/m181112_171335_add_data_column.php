<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\notifications\migrations;

use yii\db\Migration;

class m181112_171335_add_data_column extends Migration
{

    public function up()
    {
        $this->addColumn('notification', 'data', $this->text());
    }

    public function down()
    {
        $this->dropColumn('notification', 'data');
    }

}