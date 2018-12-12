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
<table class="table table-bordered table-striped table-hover">
    <tr>
        <th class="tableCell baseWidth">Imagen</th>
        <th class="tableCell baseWidth">Nombre</th>
        <th class="tableCell baseWidth">Tipo de Prenda</th>
        <th class="tableCell baseWidth">Precio</th>
        <th class="tableCell description">Descripcion</th>
        <th></th>
    </tr>
    <?php foreach($model as $row): ?>
    <?php $urlfinal = $urlbase.$row->urlImagen; ?>
    <tr> 
        <td class="tableCell"><img src=<?=$urlfinal ?> alt="Imagen de la Prenda" style="width:100px;height:100px;" ></td>
        <td class="tableCell"><?= $row->nombre  ?></td>
        <td class="tableCell"><?= $row->tipoPrenda ?> </td>
        <td class="tableCell">$<?= $row->precio ?></td>
        <td class="tableCell"><?= $row->descripcion ?></td>
        <td class="tableCell"><a href="<?= Url::toRoute(["prendas/mostrardetalle", "id" => $row->id]) ?>" class ="btn btn-primary">Mostrar Detalle</a></td>
    </tr>
    <?php endforeach ?>
</table>