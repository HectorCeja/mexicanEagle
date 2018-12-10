<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?> 
<h1 class="titulo">Prenda</h1>

<div class="infoPrenda">
<?php $extension= '.jpg';?>
    <?php $urlfinal = Url::base(true).$model->urlImagen.$extension; ?>
    <br/><p><img src=<?=$urlfinal ?> alt="Imagen de la Prenda" style="width:100px;height:100px;" ></p>
    <div class="prendaContainer">
        <div class="labelPrenda">Nombre de la Prenda:</div>
        <?= Html::input('text', 'nombre', $model->nombre,['disabled' => true]) ?>
    </div>

    <div class="prendaContainer">
        <div class="labelPrenda">Descripcion:</div>
        <?= Html::input('text', 'descripcion', $model->descripcion,['disabled' => true]) ?>
    </div>

    <div class="prendaContainer">
        <div class="labelPrenda">Tipo de Prenda:</div>
        <?= Html::input('text', 'tipoPrenda', $model->tipoPrenda,['disabled' => true]) ?>
    </div>

    <div class="prendaContainer">
        <div class="labelPrenda">Precio:</div>
        <?= Html::input('number', 'precio', $model->precio,['disabled' => true]) ?>
    </div>

    <div class="prendaContainer">
        <div class="labelPrenda">Temporada:</div>
        <?= Html::input('text', 'temporada', $temporada,['disabled' => true]) ?>
    </div>

    <div class="prendaContainer">
        <div class="labelPrenda">Categoria:</div>
        <?= Html::input('text', 'categoria', $categoria,['disabled' => true]) ?>
    </div>

    <div class="prendaContainer">
        <div class="labelPrenda">SubCategoria:</div>
        <?= Html::input('text', 'subcategoria', $subcategoria,['disabled' => true]) ?>
    </div>


    <h1></h1>

</div>

<h1 class="titulo">Componentes</h1>

     <div class="boton">
        <?= Html::beginForm(Url::toRoute("prendas/agregarcomponente"), "POST") ?>
       <button type="submit" class="btn btn-primary">Agregar Componente</button>
     <?= Html::endForm() ?>
</div>
   