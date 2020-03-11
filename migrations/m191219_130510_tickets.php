<?php

use yii\db\Migration;

/**
 * Class m191219_130510_tickets
 */
class m191219_130510_tickets extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        try {
            $this->dropTable('ticket');
        } catch (\Exception $e) {}

        $this->createTable('ticket', [
            'id'              => $this->string()->notNull()->unique(),
            'user_session_id' => $this->string()->notNull(),
            'status'          => $this->string()->notNull()->defaultValue("wait"),
            'ticket_comment'  => $this->string()->notNull(),
            'rate'            => $this->string()->notNull(),
            'currency_amount' => $this->string()->notNull()->comment("сумма в валюте, которую покупаю"),
            'currency'        => $this->string()->notNull()->comment("какую валюту покупаю"),
            'amount'          => $this->string()->notNull()->comment("сумма в рублях"),
            'phone'           => $this->string()->notNull(),
            'btc_address'     => $this->string()->notNull(),
            'created_at'      => $this->integer()
        ]);

        $this->addForeignKey('ticket_session_fk', 'ticket', 'user_session_id', 'user_session', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191219_130510_tickets cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191219_130510_tickets cannot be reverted.\n";

        return false;
    }
    */
}
