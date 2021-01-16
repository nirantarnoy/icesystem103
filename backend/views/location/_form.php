<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="location-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/data']]); ?>
    <div class="row">
        <div class="col-lg-1">
        </div>
        <div class="col-lg-10">
            <?= $form->field($model, 'warehouse_id')->Widget(\kartik\select2\Select2::className(),[
                'data'=>\yii\helpers\ArrayHelper::map(\backend\models\Warehouse::find()->all(),'id','name'),
                'options'=>[
                    'placeholder'=>'--เลือกคลังสินค้า--'
                ]
            ]) ?>
        </div>
        <div class="col-lg-1">
        </div>
    </div>
    <div style="height: 10px;"></div>
    <div class="row">
        <div class="col-lg-1">
        </div>
        <div class="col-lg-10">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-1">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-1">
        </div>
        <div class="col-lg-10">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-1">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-1">
        </div>
        <div class="col-lg-10">
            <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-1">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-1">
        </div>
        <div class="col-lg-10">
            <?= $form->field($model, 'photo')->fileInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-1">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-1">
        </div>
        <div class="col-lg-3">
            <label for=""><?= $model->getAttributeLabel('status') ?></label>
            <?php echo $form->field($model, 'status')->widget(\toxor88\switchery\Switchery::className(),['options'=>['label'=>'','class'=>'form-control']])->label(false) ?>
        </div>
        <div class="col-lg-8">
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-2">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>

        </div>
    </div>




    <?php ActiveForm::end(); ?>

</div>