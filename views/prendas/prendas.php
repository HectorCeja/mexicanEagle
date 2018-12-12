<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

$this->title = 'Prendas';
$this->params['breadcrumbs'][] = $this->title;
?> 
<h1 class="titulo">Prendas </h1>
<div class="divagregar">
    <?= Html::beginForm(Url::toRoute("prendas/agregar"), "POST") ?>
        <button type="submit" class="btn btn-primary">Agregar Prenda</button>
    <?= Html::endForm() ?>
</div>
<?php if($tipo==1): ?>
    <div class="alert alert-success">
        <h4><?= $msg ?></h4>
    </div>
<?php endif; ?>
<table class="table table-bordered table-striped table-hover text-center">
    <tr>
        <th class="text-center">Imagen</th>
        <th class="text-center">Nombre</th>
        <th class="text-center">Tipo de Prenda</th>
        <th class="text-center">Precio</th>
        <th class="text-center">Descripcion</th>
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
        <td><a href="<?= Url::toRoute(["prendas/mostrardetalle", "id" => $row->id]) ?>" class ="btn btn-primary">Mostrar Detalle</a></td>
    </tr>
    <?php endforeach ?>
</table>