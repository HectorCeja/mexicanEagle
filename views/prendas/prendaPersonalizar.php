<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Prendas detalle';
$this->params['breadcrumbs'][] = $this->title;
?> 

<?php if($tipo==1): ?>
    <div class="alert alert-success">
        <h4 ><?= $msg ?></h4>
    </div>
<?php endif; ?>

<?php if($tipo==0 && $msg!=""): ?>
    <div class="alert alert-danger">
        <?= nl2br(Html::encode($msg)) ?>
    </div>
<?php endif; ?>

<div class="infoContainer">
    <div class="imageContainer">
        <?php $urlfinal = Url::base(true).$model->urlImagen ?>
        <img src=<?=$urlfinal ?> alt="Imagen de la Prenda" />
    </div>
    <div class="infoPrenda">
        <div class="prendaContainer">
            <div class="labelPrenda">Nombre de la Prenda:</div>
            <?= Html::input('text', 'nombre', $model->nombre,['readonly' => true, 'class'=>'form-control']) ?>
        </div>

        <div class="prendaContainer">
            <div class="labelPrenda">Descripcion:</div>
            <?= Html::input('text', 'descripcion', $model->descripcion,['readonly' => true, 'class'=>'form-control']) ?>
        </div>

        <div class="prendaContainer">
            <div class="labelPrenda">Tipo de Prenda:</div>
            <?= Html::input('text', 'tipoPrenda', $model->tipoPrenda,['readonly' => true, 'class'=>'form-control']) ?>
        </div>

        <div class="prendaContainer">
            <div class="labelPrenda">Precio:</div>
            <?= Html::input('number', 'precio', $model->precio,['readonly' => true, 'class'=>'form-control']) ?>
        </div>

        <div class="prendaContainer">
            <div class="labelPrenda">Temporada:</div>
            <?= Html::input('text', 'temporada', $temporada,['readonly' => true, 'class'=>'form-control']) ?>
        </div>

        <div class="prendaContainer">
            <div class="labelPrenda">Categoria:</div>
            <?= Html::input('text', 'categoria', $categoria,['readonly' => true, 'class'=>'form-control']) ?>
        </div>

        <div class="prendaContainer">
            <div class="labelPrenda">SubCategoria:</div>
            <?= Html::input('text', 'subcategoria', $subcategoria,['readonly' => true,'size'=>'20','class'=>'form-control']) ?>
        </div>       
    </div>
</div>

<?php $form = ActiveForm::begin(['method' => 'post', 'action' => ['ventas/agregarcarrito'],]); ?>
<div class="">
    <label for="sel1">Talla:</label>
    <select class="form-control" id="sel1" name="talla">
        <option>Chica</option>
        <option>Mediana</option>
        <option>Grande</option>
    </select>
    <label for="sel2">Color:</label>
    <select class="form-control" id="sel2" name="color">
        <option>Blanco</option>
        <option>Negro</option>
        <option>Azul marino</option>
        <option>Gris</option>
    </select>
    <div class="form-group">
        <label for="usr">Cantidad:</label>
        <input type="number" name="cantidad" min="1" class="form-control" id="usr" required="true">
    </div>
</div>

<div class="buttonsContainer buttonEnd">
    <input type="hidden" name="idprenda" value="<?= $model->id ?>">
    <button type="submit" class="btn btn-primary">Agregar al Carrito</button>
</div>


<?php if (count($componentes)>0): ?>
    <h1 class="titulo">Componentes</h1>

    <table class="table table-bordered table-striped table-hover">
        <tr>
            <th class="tableCell baseWidth">Imagen</th>
            <th class="tableCell baseWidth">Nombre</th>
            <th class="tableCell baseWidth">Precio</th>
            <th class="tableCell descripcion">Descripcion</th>
            <th class="tableCell baseWidth">Agregar</th>
        </tr>
        <?php $listaids = array(); ?>
        <?php foreach($componentes as $row): ?>
            <tr>
                <?php $urlfinal = Url::base(true).$row->urlImagenMiniatura ?>
                <td class="tableCell"><img src=<?=$urlfinal ?> alt="Imagen de la Prenda" style="width:100px;height:100px;" /></td>
                <td class="tableCell"><?= $row->nombre ?> </td>
                <td class="tableCell"><?= $row->precio ?></td>
                <td class="tableCell"><?= $row->descripcion?></td>
                <?php array_push($listaids,  $row->id); ?>
                <td class="tableCell"><input type="checkbox" name="idcomponente" value="<?= implode("|",$listaids); ?>" class="checkbox"></td>
            </tr>
        <?php endforeach ?>
    </table>
<?php endif; ?>
<?php ActiveForm::end(); ?>
   