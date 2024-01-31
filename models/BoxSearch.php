<?php

namespace app\models;

use app\entities\Box\Box;
use yii\data\ActiveDataProvider;
use yii\base\Model;

class BoxSearch extends Box
{
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $search = '';
    public string $statusSearch = '';

    public function rules(): array
    {
        return [
            [['dateFrom', 'dateTo', 'search', 'statusSearch'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search($params): ActiveDataProvider
    {
        $query = Box::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->dateFrom) {
            $dateTimeFrom = strtotime($this->dateFrom);
            $query->andFilterWhere(['>=', 'created_at', $dateTimeFrom]);
        }

        if ($this->dateTo) {
            $dateTimeTo = strtotime($this->dateTo);
            $query->andFilterWhere(['<=', 'created_at', $dateTimeTo]);
        }

        if ($this->search) {
            $query->joinWith('products');
            $query->andFilterWhere([
                'or',
                ['like', Box::tableName() . '.id', $this->search],
                ['like', 'reference', $this->search],
                ['like', 'title', $this->search],
                ['like', 'SKU', $this->search],
            ]);
        }

        $query->andFilterWhere(['status' => $this->statusSearch]);

        return $dataProvider;
    }
}
