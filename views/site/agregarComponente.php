<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="site-agregar">
    <h1 class="titulo">Nuevo Componente</h1>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'componente']); ?>
                <?= $form->field($model, 'nombre')->textInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>
                <?= $form->field($model, 'idPrenda')->dropDownList($model->CategoryDropdown, ['multiple'=>'multiple', 'class'=>'chosen-select input-md required',])->label("Add Categories");?>
                <div class="form-group">
                    <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>