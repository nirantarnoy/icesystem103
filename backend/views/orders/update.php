<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Orders */

$this->title = Yii::t('app', 'แก้ไขคำสั่งซื้อ: {name}', [
    'name' => $model->order_no,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'คำสั่งซื้อ'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->order_no, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'แก้ไข');
?>
<div class="orders-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
