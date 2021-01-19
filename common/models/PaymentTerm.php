<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payment_term".
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property string|null $description
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $updated_at
 */
class PaymentTerm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_term';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code','name'],'required'],
            [['code'],'unique'],
            [['status', 'created_at', 'created_by', 'updated_by', 'updated_at'], 'integer'],
            [['code', 'name', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
