<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%payment_receive_line}}`.
 */
class m210623_014638_add_payment_type_id_column_to_payment_receive_line_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%payment_receive_line}}', 'payment_type_id', $this->integer());
        $this->addColumn('{{%payment_receive_line}}', 'payment_term_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%payment_receive_line}}', 'payment_type_id');
        $this->dropColumn('{{%payment_receive_line}}', 'payment_term_id');
    }
}
