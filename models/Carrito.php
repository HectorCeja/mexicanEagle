<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\entities\EntityCarrito;
use app\models\Prenda;
use yii\helpers\ArrayHelper;

class Carrito extends \yii\db\ActiveRecord{

    public static function tableName()
    {
        return EntityCarrito::tableName();
    }

    public static function obtenerPrendasPorUsuario($idUsuario)
    {
        $prendasCarrito = static::find()->where(['idUsuario' => $idUsuario])->all();
        $listprendasCarrito = ArrayHelper::map($prendasCarrito,'idPrenda','idPrenda');
        $prendas = Prenda::obtenerPrendasPorIds($listprendasCarrito);
        return $prendas;
    }

    public static function obtenerCarritoPorUsuario($idUsuario)
    {
        $prendasCarrito = static::find()->where(['idUsuario' => $idUsuario])->all();
        return ArrayHelper::map($prendasCarrito,'idPrenda','cantidad');
    }

    public static function limpiarCarritoPorCliente()
    {
        return static::findModel($id)
            ->delete();
    }

    public static function totalCarrito($idUsuario){
        $prendas = Carrito::obtenerPrendasPorUsuario($idUsuario);
        $listprendas = ArrayHelper::map($prendas,'id','precio');
        return array_sum($listprendas);
    }

}