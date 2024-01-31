<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\entities\Box\Box;
use yii\web\View;

/** @var View $this */
/** @var Box $model */
/** @var ActiveForm $form */

$this->title = ($model->isNewRecord ? 'Create' : 'Update') . ' Box ' . ($model->isNewRecord ? '' : $model->id);
?>

<div class="box">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="box-form">

        <?= app\widgets\Alert::widget() ?>
        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'length')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'height')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'reference')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'width')->textInput(['maxlength' => true]) ?>
            </div>
            <?php if (!$model->isNewRecord): ?>
                <div class="col-md-6">
                    <?= $form->field($model, 'status')->dropDownList(Box::STATUSES) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>