<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="site-agregar">
<h1 class="titulo">Nueva Prenda Basica </h1>
<h4 ><?= $msg ?></h4>
<div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin([
                    'id' => 'prenda',
                    'method' => 'post',
                    'action' => ['site/guardarprenda'],]); ?>
                    <?= $form->field($model, 'nombre')->textInput() ?>
                    <?= $form->field($model,'tipoPrenda')->dropDownList(['BASICA' => 'BASICA', 'DISEÑADOR'=>'DISEÑADOR']); ?>
                    <?= $form->field($model, 'idCategoria')->dropDownList($listCategorias, ['prompt'=>'Selecciona Categoria'] );?>
                    <?= $form->field($model, 'idSubCategoria')->dropDownList($listSubCategorias, ['prompt'=>'Selecciona SubCategoria'] );?>
                    <?= $form->field($model, 'idTemporada')->dropDownList($listTemporadas, ['prompt'=>'Selecciona Temporada'] );?>
                    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
</div>