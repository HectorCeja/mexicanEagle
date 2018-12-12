<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Agregar Componente';
$this->params['breadcrumbs'][] = $this->title;
?>
<h4 ><?= $msg ?></h4>
<div class="componentes-agregar">
    <h1 class="titulo">Nuevo Componente</h1>
    <div class="row">
        <div class="col-lg-5">
            <?php if ($cargada=="NO"): ?>
                
                <?php $form1 = ActiveForm::begin([
                        'id' => 'componente-2',
                        'method' => 'post',
                        'action' => ['prendas/savecomponente'],
                        'options' => ['enctype' => 'multipart/form-data'],]); ?>
                    <?= $form1->field($model, 'urlImagen')->fileInput(['multiple' => true,
                    'id' => 'imgInpCC', 'name'=>'componenteCompleto']) ?>
                    <img id="precargaComponenteC" src="<?= $im1 ?>" style="width:200px;height:200px;"/> 
                    <div class="form-group">
                        <?= Html::submitButton('Continuar', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>
                <?php ActiveForm::end();?>
            <?php else: ?>
                <?php $form = ActiveForm::begin([
                    'id' => 'componente',
                    'method' => 'post',
                    'action' => ['prendas/guardarcomponente'],]); ?>
                    <?=$form->field($model, 'urlImagen')->hiddenInput(['value'=> $im1])->label(false); ?>
                    <h4>Imagen componente</h4>
                    <img id="precargaComponenteC" src="<?= $im1 ?>" style="width:200px;height:200px;"/>
                    <?= $form->field($model, 'nombre')->textInput(['autofocus' => true]) ?>
                    <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>
                    <?= $form->field($model,'idPrenda')->hiddenInput(['options' => ['idPrenda'=> $id] ])->label(false);?>
                    <div class="form-group">
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
                <?php $form2 = ActiveForm::begin([
                        'id' => 'componente-2',
                        'method' => 'get',
                        'action' => ['prendas/savecomponente'],]); ?>
                        <div class="form-group">
                            <?= Html::submitButton('Regresar', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                        </div>
                <?php ActiveForm::end(); ?>
            <?php endif; ?>
    </div>
</div>