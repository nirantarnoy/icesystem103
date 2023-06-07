<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%daily_count_stock}}`.
 */
class m211121_105935_add_posted_column_to_daily_count_stock_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%daily_count_stock}}', 'posted', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%daily_count_stock}}', 'posted');
    }
}
