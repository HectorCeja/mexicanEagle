<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="site-agregar">
<h1 class="titulo">Nueva Prenda Basica </h1>

<div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'prenda']); ?>

                    <?= $form->field($model, 'nombre')->textInput(['autofocus' => true]) ?>
<<<<<<< HEAD
                    <?= $form->field($model, 'tipoPrenda')->dropDownList(['BASICA' => 'BASICA', 'DISEÑADOR'=>'DISEÑADOR']); ?>
                    
=======
                    <?= $form->field($model,'tipoPrenda')->dropDownList(['BASICA' => 'BASICA', 'DISEÑADOR'=>'DISEÑADOR']); ?>
                    <?= $form->field($model, 'idCategoria')->dropDownList($categorias, ['prompt'=>'Select...'] );?>
                    <?= $form->field($model, 'idSubCategoria')->dropDownList($subCategorias, ['prompt'=>'Select...'] );?>
>>>>>>> 928c770bc1a98041bd824d3a90f138927dde9fc6
                    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
</div>