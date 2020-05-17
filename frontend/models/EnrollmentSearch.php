<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Enrollment;
use kartik\daterange\DateRangeBehavior;

/**
 * EnrollmentSearch represents the model behind the search form of `app\models\Enrollment`.
 */
class EnrollmentSearch extends Enrollment
{
    public $datetime_range;
    public $datetime_min;
    public $datetime_max;
    public $userName;

    public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::className(),
                'attribute' => 'datetime_range',
                'dateStartAttribute' => 'datetime_min',
                'dateEndAttribute' => 'datetime_max',
            ]
        ];
    }

    public function rules()
    {
        return [
            [['datetime_max', 'datetime_min', 'datetime_range', 'userName'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Enrollment::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'userName' => [
                    'asc' => ['user.username' => SORT_ASC],
                    'desc' => ['user.username' => SORT_DESC],
                    'label' => 'User Name'
                ]
            ]
        ]);

        $query->joinWith(['user']);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
            
            return $dataProvider;
        }

        $query->andFilterWhere(['>=', 'date', $this->datetime_min])
              ->andFilterWhere(['or', 
                ['=', 'user.id', $this->userName],
                ['like', 'user.phone', $this->userName],
            ])->andFilterWhere(['<', 'date', $this->datetime_max]);

        return $dataProvider;
    }
}