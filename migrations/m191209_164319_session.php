<?php

use yii\db\Migration;

/**
 * Class m191209_164319_session
 */
class m191209_164319_session extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_session', [
            'id'          => $this->string()->notNull()->unique(),
            'step'        => $this->string()->notNull()->defaultValue('first'),
            'amount'      => $this->string(),
            'btc_addr'    => $this->string(),
            'llc_account' => $this->string(),
            'currency'    => $this->string(),
            'phone'       => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191209_164319_session cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191209_164319_session cannot be reverted.\n";

        return false;
    }
    */
}
