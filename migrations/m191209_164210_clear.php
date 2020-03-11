<?php

use yii\db\Migration;

/**
 * Class m191209_164210_clear
 */
class m191209_164210_clear extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191209_164210_clear cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191209_164210_clear cannot be reverted.\n";

        return false;
    }
    */
}
