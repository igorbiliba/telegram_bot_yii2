<?php

use yii\db\Migration;

/**
 * Class m191209_191024_addlog
 */
class m191209_191024_addlog extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log_list', [
            'id'   => $this->string()->notNull()->unique(),
            'data' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191209_191024_addlog cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191209_191024_addlog cannot be reverted.\n";

        return false;
    }
    */
}
