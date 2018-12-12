<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

$this->title = 'Carrito';
$this->params['breadcrumbs'][] = $this->title;
?> 

<h1 class="titulo">Carrito de compra</h1>

<?php if(count($prendas) > 0) { ?>

    <div class="divagregar">
        <?= Html::beginForm(Url::toRoute("ventas/proceder"), "POST") ?>
            <button type="submit" class="btn btn-primary">Proceder Compra</button>
        <?= Html::endForm() ?>
    </div>

    <h3> Fecha de entrega aproximada: <?=$fechaEntrega ?></h3>
    <table class="table table-bordered table-striped table-hover">
        <thead>
        <tr>
            <th class="tableCell baseWidth">Imagen</th>
            <th class="tableCell baseWidth">Nombre</th>
            <th class="tableCell baseWidth">Tipo de Prenda</th>
            <th class="tableCell baseWidth">Cantidad</th>
            <th class="tableCell baseWidth">Precio</th>
            <th class="tableCell baseWidth"></th>
        </tr>
        </thead>
        <tbody>
        <?php 
            foreach($prendas as $prenda):
                $urlfinal = $urlbase.$prenda->urlImagen;
        ?>
                <tr> 
                    <td class="tableCell"><img src=<?=$urlfinal ?> alt="Imagen de la Prenda" style="width:75px;height:75px;" ></td>
                    <td class="tableCell"><?= $prenda->nombre  ?></td>
                    <td class="tableCell"><?= $prenda->tipoPrenda ?> </td>
                    <td class="tableCell"><?= $carrito[$prenda->id] ?> </td>
                    <td class="tableCell">$<?= $prenda->precio * $carrito[$prenda->id] ?></td>
                    <td class="tableCell">
                    <a href="#" data-toggle="modal" data-target="#id_username_<?= $prenda->id ?>" class="btn btn-danger">Eliminar</a>
                    <div class="modal fade" role="dialog" aria-hidden="true" id="id_username_<?= $prenda->id ?>">
                            <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            <h4 class="modal-title">Eliminar Prenda</h4>
                                    </div>
                                    <div class="modal-body">
                                            <p>¿Realmente deseas eliminar del carrito la prenda <?= $prenda->nombre ?>?</p>
                                    </div>
                                    <div class="modal-footer">
                                    <?= Html::beginForm(Url::toRoute("ventas/borrarcarrito"), "POST") ?>
                                    <input type="hidden" name="id" value="<?= $prenda->id ?>">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Eliminar</button>
                                    <?= Html::endForm() ?>
                                    </div>
                                    </div>
                            </div>
                    </div>
                </td>
                </tr>
        <?php 
            endforeach 
        ?>
            <tr> 
                <td></td>
                <td></td>
                <td></td>
                <td>Total: </td>
                <td>$<?= number_format($total,2,".",",") ?> </td>
            </tr>
        </tbody>
    </table>

<?php } else { ?>
    <img class="empty-cart" src="/images/empty-cart.jpg" alt="">
    <h3 class="text-center">Por el momento no cuenta con prendas en su carrito.</h3>
    <h4 class="text-center">Navega por nuestra página y agrega las prendas que mas te gusten.</h4>
<?php } ?>
