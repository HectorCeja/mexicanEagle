<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Pago';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pago-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Por favor ingrese sus datos de pago:</p>
    
    <div>
        <div>
            <?php $form = ActiveForm::begin([
                                    'id' => 'form-pago',
                                    'method' => 'post',
                                    'action' => ['ventas/agregarpago'],
                                ]); ?>

                <div class="prendaContainer">
                    <div class="labelPrenda">Subtotal:</div>
                    <?= Html::input('text', 'total', number_format($subtotal,2,".",",") ,['readonly' => true]) ?>
                </div>
                <div class="prendaContainer">
                    <div class="labelPrenda">Iva:</div>
                    <?= Html::input('text', 'total', number_format($iva,2,".",","),['readonly' => true]) ?>
                </div>
                <div class="prendaContainer">
                    <div class="labelPrenda">Total:</div>
                    <?= Html::input('text', 'total', number_format($total,2,".",","),['readonly' => true]) ?>
                </div>

                <?= $form->field($model, 'idPago')->dropDownList(['1' => 'Contado', '2'=>'Credito']); ?>

                <div class="form-group"></div>
                <div class="form-group pago-container">
                    <?= Html::submitButton('Pagar', ['class' => 'btn btn-primary', 'name' => 'pago-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>
