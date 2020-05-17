<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php if (isset($action) && !empty($action)) : ?>
        <?php $form = ActiveForm::begin(['action' => [$action]]); ?>
    <?php else : ?>
        <?php $form = ActiveForm::begin(); ?>
    <?php endif; ?>

    <?= $form->field($model, 'username')->textInput() ?>
    <?= $form->field($model, 'phone')->textInput() ?>
    <?= $form->field($model, 'email')->textInput() ?>
    <?= $form->field($model, 'full_name')->textInput() ?>
    <?= $form->field($model, 'status')->textInput() ?>

    <?php if (Yii::$app->controller->route == 'user/update') : ?>
    	<?= $form->field($model, 'old_password')->label('Old password')->passwordInput() ?>
    	<?= $form->field($model, 'new_password')->label('New password')->passwordInput() ?>
	<?php else : ?>
		<?= $form->field($model, 'password_hash')->label('Password')->passwordInput() ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
