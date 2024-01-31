<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use app\entities\Box\Box;
use app\assets\BoxAsset;

/** @var yii\web\View $this */
/** @var app\models\BoxSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Boxes';
BoxAsset::register($this);
?>
<div class="box-record-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($model, $key, $index, $grid) {
            /** @var Box $model */
            if (!$model->isProductsGood()) {
                return ['class' => 'danger'];
            }
            return [];
        },
        'exportConfig' => [
            GridView::CSV => true,
            GridView::EXCEL => true,
        ],
        'export' => [
        ],
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Html::encode(
                    $this->title
                ) . ' </h3>',
            'before' => Html::a('Создать', ['create'], ['class' => 'btn btn-success']),
            'after' => '<div class="form-group field-box-weight col-md-2"> <label class="control-label" for="box-weight">Set group status</label>' . Html::dropDownList(
                    '',
                    '',
                    Box::STATUSES,
                    ['class' => 'form-control', 'prompt' => '', 'onchange' => 'box.setBatchStatus(this.value)']
                ) . '</div>',
            'type' => 'info',
            'showFooter' => false,
        ],
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            'id',
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    /** @var Box $model */
                    return date('Y-m-d H:s:s', $model->created_at);
                }
            ],
            'weight',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    /** @var Box $model */
                    /** @var Box $model */
                    $changeStatuses = $model->availableStatusesOnListBox();
                    return Html::dropDownList(
                            '',
                            $model->status,
                            array_merge($changeStatuses, [$model->status => Box::STATUSES[$model->status]]),
                            [
                                'onchange' => 'box.changeStatus(' . $model->id . ', this.value)',
                                'class' => 'form-control'
                            ]
                        ) .
                        //For export
                        '<span style="display: none;">' . Box::STATUSES[$model->status] . '</span>';
                },
                'format' => 'raw',
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Box $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
