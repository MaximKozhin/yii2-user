<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model maximkozhin\user\models\UserRole */
/* @var $form yii\widgets\ActiveForm */

$users = \maximkozhin\user\models\User::find()->select('username')->indexBy('id')->column();
$roles = \maximkozhin\user\models\Role::find()->select('name')->indexBy('alias')->column();
?>

<div class="user-role-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->dropDownList($users) ?>

    <?= $form->field($model, 'alias')->dropDownList($roles) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
