<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

$this->title = 'Carrito';
?> 

<h1 class="titulo">Carrito de compra</h1>

<?php if(count($model) > 0) { ?>

    <div class="divagregar">
        <?= Html::beginForm(Url::toRoute("ventas/proceder"), "POST") ?>
            <button type="submit" class="btn btn-primary">Proceder Compra</button>
        <?= Html::endForm() ?>
    </div>
    <h3> </h3>
    <table class="table table-bordered">
        <tr>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Tipo de Prenda</th>
            <th>Precio</th>
        </tr>
        <?php 
            $totalCompra = 0.00;
            foreach($model as $row):
                $urlfinal = $urlbase.$row->urlImagen;
                $totalCompra += $row->precio;
        ?>
                <tr> 
                    <td><img src=<?=$urlfinal ?> alt="Imagen de la Prenda" style="width:50px;height:50px;" ></td>
                    <td><?= $row->nombre  ?></td>
                    <td><?= $row->tipoPrenda ?> </td>
                    <td>$<?= $row->precio ?></td>
                </tr>
        <?php 
            endforeach 
        ?>
            <tr> 
                <td></td>
                <td></td>
                <td>Total: </td>
                <td>$<?= number_format($totalCompra,2,".",","); ?> </td>
            </tr>
    </table>

<?php } else { ?>
    <img class="empty-cart" src="/images/empty-cart.jpg" alt="">
    <h3 class="text-center">Por el momento no cuenta con prendas en su carrito.</h3>
    <h4 class="text-center">Navega por nuestra página y agrega las prendas que mas te gusten.</h4>
<?php } ?>