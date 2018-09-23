<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;
    $this->title = 'Aumento de folio';
?>

<h1>Obteniendo folio</h1>

<?= Html::beginForm(
        Url::toRoute("site/request"),
        "get",
        ['class'=> 'form-inline']
    );
?>
<div class="form-group">
    <?= Html::label("Ingresa la carrera", "carrera") ?>
    <?= Html::textInput("carrera", null, ["class" => "form-control",
                                          "required" => "true"]) ?>
</div>
<?= Html::submitInput("Obtener folio", ["class" => "btn btn-primary"]) ?>
<h3><?= $carrera ?></h3>

<?= Html::endForm() ?>