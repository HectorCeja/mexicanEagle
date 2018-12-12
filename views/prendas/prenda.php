<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Detalle Prenda';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1 class="titulo">Prenda</h1>

<?php if($tipo==1): ?>
    <div class="alert alert-success">
        <h4><?= $msg ?></h4>
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
<div class="buttonsContainer">
    <?= Html::beginForm(Url::toRoute(['prendas/prendas'])) ?>
       <button type="submit" class="btn btn-primary">Ir a Prendas</button>
    <?= Html::endForm() ?>
    <?= Html::beginForm(Url::toRoute(['prendas/agregarcomponente','id' => $model->id])) ?>
       <button type="submit" class="btn btn-primary">Agregar Componente</button>
    <?= Html::endForm() ?>
</div>
<?php if (count($componentes)>0): ?>
<h1 class="titulo">Componentes</h1>

   <table class="table table-bordered table-striped table-hover">
    <tr>
        <th class="tableCell baseWidth">Imagen</th>
        <th class="tableCell baseWidth">Nombre</th>
        <th class="tableCell baseWidth">Precio</th>
        <th class="tableCell description">Descripcion</th>
    </tr>
    <?php foreach($componentes as $row): ?>
    <tr>
        <?php $urlfinal = Url::base(true).$row->urlImagen ?>
        <td class="tableCell"><img src=<?=$urlfinal ?> alt="Imagen de la Prenda" style="width:100px;height:100px;" /></td>
        <td class="tableCell"><?= $row->nombre ?> </td>
        <td class="tableCell">$<?= $row->precio ?></td>
        <td class="tableCell"><?= $row->descripcion?></td>
    </tr>
    <?php endforeach ?>
</table>
<?php endif; ?>
   