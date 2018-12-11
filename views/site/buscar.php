<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Búsqueda';
?> 
<div class="site-buscar">
    <br>
    <br>
    <?php if($model!=null):?>
        <table class="table table-bordered">
            <tr>
                <th></th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Descripcion</th>
            </tr>
            <?php foreach($model as $row): ?>
            <?php $urlfinal = $urlbase.$row->urlImagenMiniatura; ?>
            <tr> 
                <td><br/><p><img src=<?=$urlfinal ?> alt="Imagen de la Prenda" style="width:100px;height:100px;" ></p></td>
                <td><?= $row->nombre  ?></td>
                <td><?= $row->precio ?></td>
                <td><?= $row->descripcion ?></td>
            </tr>
            <?php endforeach ?>
        </table>
    <?php else: ?>
    <div class="alert alert-danger">
        <h4 ><?= $msg ?></h4>
    </div>
    <?php endif; ?>
</div>