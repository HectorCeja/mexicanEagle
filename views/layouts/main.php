<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
        <head>
            <meta charset="<?= Yii::$app->charset ?>">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <?= Html::csrfMetaTags() ?>
            <title><?= Html::encode($this->title) ?></title>
            <?php $this->head() ?>
        </head>
        <body>
            <?php $this->beginBody() ?>

                <div class="wrap">
                    <?php
                        NavBar::begin([
                            'brandLabel' => '<img class="logo" src="images/logo2.jpeg"></img>',
                            'brandUrl' => Yii::$app->homeUrl,
                            'options' => [
                                'class' => 'navbar-inverse navbar-fixed-top',
                            ],
                        ]);

                        $items = [];
                        
                        if(Yii::$app->User->isGuest){
                            $items = [
                                ['label' => 'Inicio', 'url' => ['/site/index']],
                                ['label' => 'Conócenos', 'url' => ['/site/about']],
                                ['label' => 'Contáctanos', 'url' => ['/site/contact']],
                                ['label' => 'Iniciar Sesión', 'url' => ['/site/login']],
                                ['label' => 'Registrarse', 'url' => ['/prospecto/register']]
                            ];
                        } else {
                            foreach(Yii::$app->session['opciones'] as $key => $value) {
                                $items[] = ['label' => $key, 'url' => [$value]];
                            }
                            $items[] = (
                                '<li>'
                                . Html::beginForm(['/site/logout'], 'post')
                                . Html::submitButton(
                                    'Salir (' . Yii::$app->user->identity->email . ')',
                                    ['class' => 'btn btn-link logout']
                                )
                                . Html::endForm()
                                . '</li>'
                            );
                        }
                        
                        $items[] = (
                            '<li>'
                            . Html::beginForm(['/site/buscar'], 'post')
                            . AutoComplete::widget([
                                'name' => 'Prenda',
                                'id' => 'ddd',
                                'value' => '',
                                'clientOptions' => [
                                    'source' => Yii::$app->session['prenda'],
                                    'autoFill' => true,
                                    'minLength' => '1',
                                    'select' => new JsExpression("function( event, ui ) {
                                    $('#inoculation-id_user').val(ui.item.id);
                                }")],
                                'options' => [
                                    'class' => 'form-control',
                                    'id' => 'buscarGeneral',
                                    'style' => 'margin:7px'
                                ]
                            ])
                            . '</li> '
                            . '<li> '
                            . '<input type="hidden" name="yii" value="">'
                            . Html::submitButton(
                                'Buscar',
                                ['class' => 'btn btn-link',
                                'style' => 'margin:7px']
                            )
                            . Html::endForm()
                            . '</li> '
                        );

                        echo Nav::widget([
                            'options' => ['class' => 'navbar-nav navbar-right'],
                            'items' => $items,
                        ]);
                        
                        NavBar::end();
                    ?>

                    <div class="container">
                        <?= Breadcrumbs::widget([
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]) ?>
                        <?= Alert::widget() ?>
                        <?= $content ?>
                    </div>
                </div>

                <footer class="footer">
                    <div class="container">
                        <p class="pull-left">&copy; Ropalinda <?= date('Y') ?></p>
                    </div>
                </footer>

            <?php $this->endBody() ?>
        </body>
    </html>
<?php $this->endPage() ?>
