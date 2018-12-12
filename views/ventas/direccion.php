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

                <?= $form->field($model, 'calle')->textInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'noExterior')->textInput() ?>
                <?= $form->field($model, 'noInterior')->textInput() ?>
                <?= $form->field($model, 'colonia')->textInput() ?>
                <?= $form->field($model, 'codigoPostal')->textInput() ?>
                <?= $form->field($model, 'ciudad')->textInput() ?>
                <?= $form->field($model, 'estado')->textInput() ?>
                <?= $form->field($model, 'pais')->textInput() ?>
                <div class="form-group"></div>
                <div class="form-group">
                    <?= Html::submitButton('Ingresar Pago', ['class' => 'btn btn-primary', 'name' => 'direccion-button']) ?>
                </div>
        </div>    
    </div>
    <?php if (count($direcciones)>0): ?>
          <h1 class="titulo">Direcciones</h1>

          <table class="table table-bordered table-striped table-hover">
            <tr>
           <th>Calle</th>
           <th>Colonia</th>
           <th>Ciudad</th>
           <th>Pais</th>
           <th>Codigo Postal</th>
           <th>Seleccionar Direccion</th>
          </tr>
        <?php $listaids = array(); ?>
        <?php foreach($direcciones as $row): ?>
            <tr>
              <td><?= $row->calle ?> </td>
              <td><?= $row->colonia ?></td>
              <td><?= $row->ciudad?></td>
              <td><?= $row->pais?></td>
              <td><?= $row->codigoPostal?></td>
        <?php array_push($listaids,  $row->id); ?>
        <td><input type="checkbox" name="idcomponente" value="<?= implode("|",$listaids); ?>"></td>
    </tr>
    <?php endforeach ?>
    <?php ActiveForm::end(); ?>
    </table>
    <?php endif; ?>
</div>
