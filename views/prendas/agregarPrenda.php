<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Prendas';
?>
<div class="prendas-agregar"> 
<h1 class="titulo">Nueva Prenda Basica </h1>
<h4 ><?= $msg ?></h4>
<div class="row">
            <div class="col-lg-5 text-center">

                <?php if ($cargada=="NO"): ?>
                    
                    <?php $form1 = ActiveForm::begin([
                            'id' => 'prenda-2',
                            'method' => 'post',
                            'action' => ['prendas/saveprenda'],
                            'options' => ['enctype' => 'multipart/form-data'],]); ?>
                        <?= $form1->field($model, 'urlImagen')->fileInput(['multiple' => true,
                        'id' => 'imgInpC', 'name'=>'imagenCompleta']) ?>
                        <img id="precargaPrendaC" src="<?= $im1 ?>"/> 
                        <?= $form1->field($model, 'urlImagenMiniatura')->fileInput(['multiple' => true,
                        'id' => 'imgInpI', 'name'=>'imagenMiniatura']) ?>
                        <img id="precargaPrendaI" src="<?= $im2 ?>"/> 
                        <div class="form-group">
                            <?= Html::submitButton('Continuar', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                        </div>
                    <?php ActiveForm::end();?>
                    
                <?php else: ?>
                    <?php $form = ActiveForm::begin([
                        'id' => 'prenda',
                        'method' => 'post',
                        'action' => ['prendas/guardarprenda'],]); ?>

                        <?=$form->field($model, 'urlImagen')->hiddenInput(['value'=> $im1])->label(false); ?>
                        <img id="precargaPrendaC" src="<?= $im1 ?>"/>
                        <?=$form->field($model, 'urlImagenMiniatura')->hiddenInput(['value'=> $im2])->label(false); ?>
                        <img id="precargaPrendaI" src="<?= $im2 ?>"/>
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
                    <?php $form2 = ActiveForm::begin([
                        'id' => 'prenda-2',
                        'method' => 'get',
                        'action' => ['prendas/saveprenda'],]); ?>
                        <div class="form-group">
                            <?= Html::submitButton('Regresar', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                        </div>
                    <?php ActiveForm::end(); ?>
                <?php endif; ?>
            </div>
        </div>
</div>
