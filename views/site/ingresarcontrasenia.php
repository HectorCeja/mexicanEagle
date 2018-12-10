<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Contraseña';
?> 
<div class="site-cambiarcontrasenia">
    <h2>Estás a un paso de ser nuestro cliente!</h2>
    <h3>Por favor ingresa el correo registrado y una contraseña.</h3>
    <?php if($tipo==1): ?>
    <div class="alert alert-danger">
        <?= nl2br(Html::encode($msg)) ?>
    </div>
    <?php endif; ?>
    <?= Html::beginForm(Url::toRoute("site/cambiar"), "POST") ?>
        <div class="form-group">
        Correo: <input type="text" name="email" required size="30" maxlength="30">
        Contraseña: <input type="password" name="password" maxlength="30" size="30" required>

        <?= Html::submitButton('Aceptar', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

        </div>
    <?= Html::endForm() ?>
</div>
