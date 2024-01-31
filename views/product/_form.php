<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use app\entities\Box\Product;
use app\assets\ProductAsset;

/** @var View $this */
/** @var Product $model */
/** @var ActiveForm $form */

ProductAsset::register($this);

$this->title = ($model->isNewRecord ? 'Create' : 'Update') . ' Product ' . ($model->isNewRecord ? '' : $model->title);
?>
<div class="product">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="product-form">

        <?= app\widgets\Alert::widget() ?>
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'SKU')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <?= $form->field($model, 'received_qty')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-1">
                <?= Html::button('Match', ['class' => 'btn btn-primary product-btn-match', 'onclick' => 'product.matchWithReceived($("#product-received_qty").val())']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'shipped_qty')->textInput(['maxlength' => true]) ?>
            </div>

        </div>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>