<?php

use yii\db\Migration;

/**
 * Class m191209_192307_a
 */
class m191209_192307_a extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('log_list', 'id');
        $this->addColumn('log_list', 'id', $this->primaryKey());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191209_192307_a cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191209_192307_a cannot be reverted.\n";

        return false;
    }
    */
}
