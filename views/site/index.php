<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

/* @var $this yii\web\View */

$this->title = 'Inicio';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index mainContainer">
  <?php 
    $images=['<img src="/images/autumn-winter_season.jpg"/>'];
    echo yii\bootstrap\Carousel::widget(['items'=>$images]);
  ?>

  <div class="content-container">
    <div class="sub-header">Ofertas de temporada</div>
    <section class="items-list">
      <?php foreach($prendasTemporada as $row): ?>
        <?php $urlfinal = Url::base(true).$row->urlImagen ?>
        <a href="<?= Url::toRoute(["prendas/mostrarprenda", "id" => $row->id]) ?>" class="item">
          <figure class="item-image">
            <img src=<?=$urlfinal ?> alt="Imagen de la Prenda">
          </figure>
          <span class="item-overlay">
            <div class="item-info">
              <span class="item-name"><?= $row->nombre ?></span>
              <span class="item-price">$<?= $row->precio ?></span>
            </div>
          </span>
        </a>
      <?php endforeach ?>
    </section>
  </div>
  <div class="content-container">
    <div class="sub-header">Todos los productos</div>
    <section class="items-list">
      <?php foreach($prendas as $row): ?>
        <?php $urlfinal = Url::base(true).$row->urlImagen ?>
        <a href="<?= Url::toRoute(["prendas/mostrarprenda", "id" => $row->id]) ?>" class="item">
          <figure class="item-image">
            <img src=<?=$urlfinal ?> alt="Imagen de la Prenda">
          </figure>
          <span class="item-overlay">
            <div class="item-info">
              <span class="item-name"><?= $row->nombre ?></span>
              <span class="item-price">$<?= $row->precio ?></span>
            </div>
          </span>
        </a>
      <?php endforeach ?>
    </section>
  </div>
</div>
