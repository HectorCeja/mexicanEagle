<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

$this->title = 'Dirección';

?> 
<h1 class="titulo">Prendas </h1>
<div class="divagregar">
    <?= Html::beginForm(Url::toRoute("prendas/agregar"), "POST") ?>
        <button type="submit" class="btn btn-primary">Agregar Prenda</button>
    <?= Html::endForm() ?>
</div>
<h3><?= $msg ?> </h3>
<table class="table table-bordered">
    <tr>
        <th>Imagen</th>
        <th>Nombre</th>
        <th>Tipo de Prenda</th>
        <th>Precio</th>
        <th>Descripcion</th>
        <th></th>
    </tr>
    <?php foreach($model as $row): ?>
    <?php $urlfinal = $urlbase.$row->urlImagenMiniatura; ?>
    <tr> 
        <td><br/><p><img src=<?=$urlfinal ?> alt="Imagen de la Prenda" style="width:100px;height:100px;" ></p></td>
        <td><?= $row->nombre  ?></td>
        <td><?= $row->tipoPrenda ?> </td>
        <td><?= $row->precio ?></td>
        <td><?= $row->descripcion ?></td>
        <td><a href="<?= Url::toRoute(["prendas/mostrardetalle", "id" => $row->id]) ?>">Mostrar Detalle</a></td>
    </tr>
    <?php endforeach ?>
</table>