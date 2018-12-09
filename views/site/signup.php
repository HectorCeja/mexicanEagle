<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Registrarse';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Por favor ingrese los datos que se piden a continuación:</p>
    
    <div class="row">
        <div class="col-lg-5">
        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

        <?= $form->field($model, 'nombre')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'apellidoPaterno')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'apellidoMaterno')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'pais')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'ciudad')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'numeroTelefono')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'fechaNacimiento')->textInput(['autofocus' => true]) ?>
    

        <?= $form->field($model, 'rfc')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'email')->input('email') ?>

        <div class="form-group">
            <?= Html::submitButton('Registrarse', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>
