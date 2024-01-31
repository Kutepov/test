<?php

namespace app\entities\Box;

use DateTimeImmutable;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "boxes".
 *
 * @property int $id
 * @property string $reference
 * @property float|null $height
 * @property float|null $length
 * @property float|null $weight
 * @property float|null $width
 * @property float|null $total_price
 * @property string $status
 * @property DateTimeImmutable $created_at
 * @property DateTimeImmutable $updated_at
 *
 * @property Product[] $products
 */
class Box extends ActiveRecord
{
    const SCENARIO_CHANGE_STATUS = 'change-status';
    const SCENARIO_TOTAL_PRICE = 'total-price';

    const STATUS_EXPECTED = 'expected';
    const STATUS_AT_WAREHOUSE = 'at-warehouse';
    const STATUS_PREPARED = 'prepared';
    const STATUS_SHIPPED = 'shipped';

    const STATUSES = [
        self::STATUS_EXPECTED => 'Expected',
        self::STATUS_AT_WAREHOUSE => 'At warehouse',
        self::STATUS_PREPARED => 'Prepared',
        self::STATUS_SHIPPED => 'Shipped',
    ];

    public static function tableName(): string
    {
        return 'boxes';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'weight' => 'Weight, kg',
            'height' => 'Height, cm',
            'length' => 'Length, cm',
            'width' => 'Width, cm',
        ];
    }

    public function rules(): array
    {
        $rules = [
            [['reference', 'status'], 'required'],
            [['weight', 'length', 'height', 'width', 'total_price'], 'number'],
            [
                ['weight', 'length', 'height', 'width'],
                'filter',
                'filter' => function ($value) {
                    return $value ? round($value, 2) : null;
                }
            ],
            [['status'], 'string', 'max' => 12],
            [['status'], 'in', 'range' => array_keys(self::STATUSES)],
            [['reference'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['created_at', 'updated_at'], 'safe'],
        ];

        if ($this->scenario === self::SCENARIO_CHANGE_STATUS) {
            $rules[] = [['status'], 'canChangeStatus'];
        }

        return $rules;
    }

    public function scenarios(): array
    {
        return [
            self::SCENARIO_DEFAULT => ['reference', 'weight', 'length', 'height', 'width', 'status', 'created_at', 'updated_at'],
            self::SCENARIO_CHANGE_STATUS => ['status'],
            self::SCENARIO_TOTAL_PRICE => ['status'],
        ];
    }

    public function isProductsGood(): bool
    {
        if (!count($this->products)) {
            return false;
        }
        foreach ($this->products as $product) {
            if ($product->shipped_qty !== $product->received_qty) {
                return false;
            }
        }

        return true;
    }

    public static function batchChangeStatus(array $ids, string $status): void
    {
        foreach ($ids as $id) {
            if ($box = self::findOne($id)) {
                $box->scenario = self::SCENARIO_CHANGE_STATUS;
                $box->status = $status;
                $box->save();
            }
        }
    }

    public static function saveTotalPrice(int $id)
    {
        if ($box = self::findOne($id)) {
            $totalPrice = 1;
            foreach ($box->products as $product) {
                if ($product->price) {
                    $totalPrice *= $product->price;
                }
            }
            $box->scenario = self::SCENARIO_TOTAL_PRICE;
            $box->total_price = $totalPrice;
            $box->save();
        }
    }

    public function availableStatusesOnViewBox(): array
    {
        if (count($this->products)) {
            $statuses = self::STATUSES;
            unset($statuses[self::STATUS_EXPECTED]);
            unset($statuses[self::STATUS_AT_WAREHOUSE]);
            return $statuses;
        }
        return [];
    }

    public function availableStatusesOnListBox(): array
    {
        $statuses = self::STATUSES;
        unset($statuses[self::STATUS_SHIPPED]);
        if (!$this->weight || !count($this->products)) {
            unset($statuses[self::STATUS_AT_WAREHOUSE]);
            return $statuses;
        }

        foreach ($this->products as $product) {
            if ($product->shipped_qty !== $product->received_qty) {
                unset($statuses[self::STATUS_AT_WAREHOUSE]);
                break;
            }
        }

        return $statuses;
    }

    public function canChangeStatus(): void
    {
        if (($this->status === self::STATUS_PREPARED || $this->status === self::STATUS_SHIPPED) && !count($this->products)) {
            $this->addError('status', 'Error change status.');
        } elseif ($this->status === self::STATUS_AT_WAREHOUSE) {
            if (!$this->weight || !count($this->products)) {
                $this->addError('status', 'Error change status.');
            }
            foreach ($this->products as $product) {
                if ($product->shipped_qty !== $product->received_qty) {
                    $this->addError('status', 'Error change status.');
                    break;
                }
            }
        }
    }

    public function getProducts(): ActiveQuery
    {
        return $this->hasMany(Product::class, ['box_id' => 'id']);
    }

    public function beforeValidate(): bool
    {
        if (!$this->status) {
            $this->status = self::STATUS_EXPECTED;
        }

        return parent::beforeValidate();
    }
}