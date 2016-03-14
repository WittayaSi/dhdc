<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
Modal::begin([
    'id' => 'modal_login',
    'header' => '<h3><center>Login</center></h3>',
    'size' => 'modal-sm',
    'closeButton' => false
]);
?>

<div class="site-login alert alert-success">
<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <p align="center">Please fill out the following fields to login</p><br>

    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'rememberMe')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
Modal::end();
?>
