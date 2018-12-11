<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Dirección';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="direccion-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Por favor ingrese los datos que se piden a continuación:</p>
    
    <div>
        <div>
            <?php $form = ActiveForm::begin([
                                    'id' => 'form-direccion',
                                    'method' => 'post',
                                    'action' => ['ventas/agregardireccion'],
                                ]); ?>

                <?= $form->field($model, 'pais')->textInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'estado')->textInput() ?>
                <?= $form->field($model, 'ciudad')->textInput() ?>
                <?= $form->field($model, 'codigoPostal')->textInput() ?>
                <?= $form->field($model, 'calle')->textInput() ?>
                <?= $form->field($model, 'noExterior')->textInput() ?>
                <?= $form->field($model, 'noInterior')->textInput() ?>
                <?= $form->field($model, 'colonia')->textInput() ?>

                <div class="form-group"></div>
                <div class="form-group">
                    <?= Html::submitButton('Ingresar Pago', ['class' => 'btn btn-primary', 'name' => 'direccion-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>
