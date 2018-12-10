<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="componentes-agregar">
    <h1 class="titulo">Nuevo Componente</h1>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                    'id' => 'componente',
                    'method' => 'post',
                    'action' => ['prendas/componentes'],]); ?>
                <?= $form->field($model, 'nombre')->textInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>
                <div class="form-group">
                    <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>