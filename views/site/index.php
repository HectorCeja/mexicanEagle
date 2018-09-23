<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Aumento de folio';
?>
<div class="site-index">

    <div class="jumbotron">
        <h3>Ingresa la carrera</h3>
        <?= Html::beginForm(
                Url::toRoute("site\request"),
                "get",
                ['class'=> 'form-inline']
            );
        ?>
        <div class="form-group">
            <?= Html::label("Ingresa la carrera", "carrera") ?>
            <?= Html::textInput("carrera", null, ["class" => "form-control"]) ?>
        </div>
        <?= Html::submitInput("Obtener folio", ["class" => "btn btn-primary"]) ?>

    </div>
</div>
<?= Html::endForm() ?>
