<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?> 
<h1 class="titulo">Componentes</h1>

<div class="divagregar">
<?= Html::beginForm(Url::toRoute(['site/agregarcomponente']), "POST") ?>
    <button type="submit" class="btn btn-primary">Agregar Componente</button>
<?= Html::endForm() ?>
</div>