<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Prenda';
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
            <?= Html::input('text', 'subcategoria', $subcategoria,['disabled' => true,'size'=>'20']) ?>
        </div>       
    </div>
</div>
<div class="botones">
    <?php $form = ActiveForm::begin([
                        'method' => 'post',
                        'action' => ['ventas/agregarcarrito'],]); ?>
            <div class="prendaContainer">
                <input type="hidden" name="idprenda" value="<?= $model->id ?>">
            </div> 
        <button type="submit" style="margin-top:0px;" class="btn btn-primary botonagregarcomponente">Agregar al Carrito</button>
    <?php ActiveForm::end(); ?>
</div>
<?php if (count($componentes)>0): ?>
<h1 class="titulo">Componentes</h1>

   <table class="table table-bordered">
    <tr>
        <th>Imagen</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Descripcion</th>
    </tr>
    <?php foreach($componentes as $row): ?>
    <tr>
        <?php $urlfinal = Url::base(true).$row->urlImagenMiniatura ?>
        <td><br/><p><img src=<?=$urlfinal ?> alt="Imagen de la Prenda" style="width:100px;height:100px;" ></p></td>
        <td><?= $row->nombre ?> </td>
        <td><?= $row->precio ?></td>
        <td><?= $row->descripcion?></td>
    </tr>
    <?php endforeach ?>
</table>     
<?php endif; ?>
   