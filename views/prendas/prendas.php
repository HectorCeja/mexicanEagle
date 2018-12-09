<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?> 
<h1 class="titulo">Prendas </h1>

<div class="divagregar">
<?= Html::beginForm(Url::toRoute("prendas/agregar"), "POST") ?>
    <button type="submit" class="btn btn-primary">Agregar Prenda</button>
<?= Html::endForm() ?>
</div>