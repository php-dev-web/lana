<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index"> 

    <h1><?= Html::encode($this->title) ?></h1>

    <p>

<?php
    Modal::begin([
     'header' => '<h2>Create User</h2>',
     'toggleButton' => ['class' => 'btn btn-success', 'label' => 'Create User'],
    ]);
    
    echo $this->render('_form', [
        'model' => $model,
        'action' => '/user/create',
    ]);
    
    Modal::end();
?>

<?php
    Modal::begin([
     'header' => '<h2>Replenish balance</h2>',
     'toggleButton' => ['class' => 'btn btn-primary', 'label' => 'Replenish balance'],
    ]);
    
    $form = ActiveForm::begin(['action' => '/user/replenish-balance']);

    echo $form->field($model, 'id')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\User::find()->where(['status' => 10])->all(), 'id', 'username'))->label('Username');

    echo $form->field($model, 'balance')->textInput(['type' => 'number']);

    echo Html::submitButton('Save', ['class' => 'btn btn-primary', 'style' => 'margin-right:5px']);
    echo Html::submitButton('Close', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']);
    ActiveForm::end();
    
    Modal::end();
?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'phone',
            'full_name',
            'balance',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            //'email:email',
            'status' => [
                'label' => 'Status',
                'headerOptions' => ['style' => 'width:150px'],
                'value' => function ($model, $key, $index, $column) {
                        return Html::activeDropDownList($model,'status', [10 => 'Активный', 9 => 'Неактивный'], [
                            'class' => "form-control",
                            'data-id' => $model->id,
                            'id' => "user-status-$model->id",
                            'onchange' => "
                                   $.ajax({
                                     url: \"/user/change-status\",
                                     type: \"post\",
                                     data: { id:  $key, status : $(\"#user-status-$model->id\").val()},
                                     success: function(res) {
                                        console.log(res)
                                     }
                                    });"
                        ]);
                    },
                'format' =>'raw', 
            ],

            //'created_at',
            //'updated_at',
            //'verification_token',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
