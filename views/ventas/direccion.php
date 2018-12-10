<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Dirección';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prospecto-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Por favor ingrese los datos que se piden a continuación:</p>
    
    <div>
        <div>
        <?php $form = ActiveForm::begin(['id' => 'form-direccion']); ?>

        <?= $form->field($model, 'pais')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'estado')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'ciudad')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'codigoPostal')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'calle')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'noExterior')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'noInterior')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'colionia')->textInput(['autofocus' => true]) ?>

        <div class="form-group"></div>
        <div class="form-group">
            <?= Html::submitButton('Ingresar Pago', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>
