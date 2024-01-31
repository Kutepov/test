<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\entities\Box\Box;
use yii\web\View;
use yii\web\YiiAsset;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\entities\Box\Product;
use yii\grid\ActionColumn;
use yii\helpers\Url;

/** @var View $this */
/** @var Box $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Box Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
\app\assets\BoxAsset::register($this)
?>
<div class="box-record-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'reference',
            'weight',
            'length',
            'height',
            'width',
            'total_price',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    /** @var Box $model */
                    $changeStatuses = $model->availableStatusesOnViewBox();
                    return Html::dropDownList('', $model->status, array_merge($changeStatuses, [$model->status => Box::STATUSES[$model->status]]), ['onchange' => 'box.changeStatus(' . $model->id . ', this.value)', 'class' => 'form-control']);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'created_at',
                'value' => date('Y-m-d H:i:s', $model->created_at)
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s', $model->updated_at)
            ],
        ],
    ]) ?>

    <h3>Products</h3>
    <p>
        <?= Html::a('Create product', ['product/create', 'boxId' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => new ActiveDataProvider([
                'query' => $model->getProducts(),
                'pagination' => [
                    'pageSize' => 20,
                ],
                'sort'=> ['defaultOrder' => ['id' => SORT_DESC]],
            ]
        ),
        'columns' => [
            'id',
            'title',
            'SKU',
            'shipped_qty',
            'received_qty',
            'price',
            [
                'class' => ActionColumn::class,
                'template' => '{update} {delete}',
                'urlCreator' => function ($action, Product $model, $key, $index, $column) {
                    return Url::toRoute(['product/' . $action, 'id' => $model->id, 'boxId' => $model->box_id]);
                }
            ],
        ],
    ]
    ) ?>

</div>
