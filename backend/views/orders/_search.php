<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OrdersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'id' => 'form-order',
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="input-group">
        <!--         <span class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>-->
        <?= $form->field($model, 'globalSearch')->textInput(['placeholder' => 'ค้นหา', 'class' => 'form-control', 'aria-describedby' => 'basic-addon1'])->label(false) ?>
        <?php $model->order_date == null ? date('d/m/Y') : date('d/m/Y', $model->order_date); ?>
        <?= $form->field($model, 'order_date')->widget(\kartik\date\DatePicker::className(), [
            'value' => $model->order_date,
            'options' => [
                'onclick' => '$("form#form-order").submit()'
            ]
        ])->label(false) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
