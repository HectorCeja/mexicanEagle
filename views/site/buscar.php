<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Búsqueda';
$this->params['breadcrumbs'][] = $this->title;
?> 
<div class="site-buscar">
    <br>
    <br>
    <?php if($model!=null):?>
        <table class="table table-bordered text-center">
            <tr>
                <th></th>
                <th style="text-align:center">Nombre</th>
                <th style="text-align:center">Precio</th>
                <th style="text-align:center">Descripcion</th>
                <th></th>
            </tr>
            <?php foreach($model as $row): ?>
            <?php $urlfinal = $urlbase.$row->urlImagen; ?>
            <tr> 
                <td style="text-align:center"><br/><p><img src=<?=$urlfinal ?> alt="Imagen de la Prenda" style="width:100px;height:100px;" ></p></td>
                <td><?= $row->nombre  ?></td>
                <td><?= $row->precio ?></td>
                <td><?= $row->descripcion ?></td>
                <td style="text-align:center"><a href="<?= Url::toRoute(["prendas/mostrarprenda", "id" => $row->id]) ?>" class="btn btn-info">Mostrar</a></td>
            </tr>
            <?php endforeach ?>
        </table>
    <?php else: ?>
    <div class="alert alert-danger">
        <h4 ><?= $msg ?></h4>
    </div>
    <?php endif; ?>
</div>