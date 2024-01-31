<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\BoxSearch;
use yii\web\View;
use kartik\date\DatePicker;
use app\entities\Box\Box;

/** @var View $this */
/** @var BoxSearch $model */
/** @var ActiveForm $form */
?>

<div class="product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'dateFrom')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                    ]
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'dateTo')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                ]
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'search') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'statusSearch')->dropDownList(Box::STATUSES, ['prompt' => ''])->label('Status') ?>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary box-btn-form-search']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>