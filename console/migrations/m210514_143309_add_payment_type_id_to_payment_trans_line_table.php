<?php

use yii\db\Migration;

/**
 * Class m210514_143309_add_payment_type_id_to_payment_trans_line_table
 */
class m210514_143309_add_payment_type_id_to_payment_trans_line_table extends Migration
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
        echo "m210514_143309_add_payment_type_id_to_payment_trans_line_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210514_143309_add_payment_type_id_to_payment_trans_line_table cannot be reverted.\n";

        return false;
    }
    */
}
