<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Contraseña';
?> 
<div class="site-cambiarcontrasenia">
    <h1>Ingresar datos:</h1>
    <h4 ><?= $msg ?></h4>
    <?= Html::beginForm(Url::toRoute("site/cambiar"), "POST") ?>
        <div class="form-group">
        Correo: <input type="text" name="email" required>
        Contraseña: <input type="password" name="password" required>

        <?= Html::submitButton('Aceptar', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

        </div>
    <?= Html::endForm() ?>
</div>
