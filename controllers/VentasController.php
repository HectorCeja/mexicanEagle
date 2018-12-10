<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use app\models\Prenda;
use app\models\Carrito;
use app\models\Direccion;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


class VentasController extends Controller
{
    public function actionCarrito(){
        $prendasCarrito = Carrito::obtenerPrendasPorCliente(Yii::$app->session['idUsuario']);
        $listprendasCarrito = ArrayHelper::map($prendasCarrito,'idPrenda','idPrenda');
        $prendas = Prenda::obtenerPrendasPorIds($listprendasCarrito);
        $urlbase = Url::base(true);
        return $this->render('carrito',[
            'model' => $prendas,
            'urlbase' => $urlbase
        ]);
    }

    public function actionProceder(){
        $direccion = new Direccion();
        return $this->render('direccion',[
            'model' => $direccion
        ]);
    }

}