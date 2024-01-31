<?php

namespace app\entities\Box;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $title
 * @property string $SKU
 * @property int|null $shipped_qty
 * @property int|null $received_qty
 * @property float|null $price
 * @property string $box_id
 *
 * @property Box $box
 */
class Product extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'products';
    }

    public function rules(): array
    {
        return [
            [['SKU', 'box_id', 'title'], 'required'],
            [['shipped_qty', 'received_qty'], 'integer'],
            [['price'], 'number'],
            [['box_id'], 'integer'],
            [['SKU'], 'string', 'max' => 50],
            [['SKU'], 'unique'],
            [
                ['box_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Box::class,
                'targetAttribute' => ['box_id' => 'id']
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'SKU' => 'Sku',
            'shipped_qty' => 'Shipped Qty',
            'received_qty' => 'Received Qty',
            'price' => 'Price',
            'box_id' => 'Box ID',
        ];
    }

    public function getBox(): ActiveQuery
    {
        return $this->hasOne(Box::class, ['id' => 'box_id']);
    }

    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);
        if (isset($changedAttributes['price']) || $insert) {
            Box::saveTotalPrice($this->box_id);
        }
    }
}