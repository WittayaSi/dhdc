<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
Modal::begin([
    'id' => 'modal_signup',
    'header' => '<h3><center>Signup</center></h3>',
    'size' => '',
    'closeButton' => false
]);
?>

<div class="site-signup alert alert-success">
<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <p align="center">Please fill out the following fields to signup</p><br>

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
    <div class="form-group">
    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary btn-block', 'name' => 'signup-button']) ?>
    </div>
<?php ActiveForm::end(); ?>
</div>

<?php
Modal::end();
?>
