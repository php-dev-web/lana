<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
use kartik\date\DatePicker;
use app\models\Enrollment;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EnrollmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Enrollments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="enrollment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Enrollment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'date' => [
             'attribute' => 'date',
             'format' => 'datetime',
             'filter' => DateRangePicker::widget([
                            'model' => $searchModel,
                            'attribute'=>'datetime_range',
                            'startAttribute'=>'datetime_min',
                            'endAttribute'=>'datetime_max',
                            'convertFormat'=>true,
                            'pluginOptions'=>[
                                'timePicker'=>true,
                                'timePickerIncrement'=>30,
                                'locale'=>[
                                    'format'=>'Y-m-d h:i A'
                                ]
                            ]
                        ])
            ],
            'userName',
            [
                'attribute' => 'sum',
                'footer' => 'Total: ' . Enrollment::getTotalSum($dataProvider->models),
            ],
            'action',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{cancel}{view}{update}{delete}',
                'buttons' => [
                    'cancel' => function ($url, $model) {
                        if ($model->action == 'Added') {
                            return Html::a('<span class="glyphicon glyphicon-remove-circle"></span>', $url, [
                                        'title' => Yii::t('app', 'Cancel'),
                                ]);
                        }
                    },
                ],
            ],

        ],
        'showFooter' => true,
    ]); ?>


</div>
