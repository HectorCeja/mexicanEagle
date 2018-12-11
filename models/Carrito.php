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

    public static function limpiarCarritoPorIdUsuario($idUsuario)
    {
        return static::deleteAll(['idUsuario' => $idUsuario]);;
    }
    public static function obtenerUsuarioPrenda($idUsuario, $idPrenda){
        return static::find()
            ->where(['idUsuario'=>$idUsuario])->andWhere(['idPrenda'=>$idPrenda])
            ->one();
    }

    public static function totalCarrito($idUsuario){
        $total = 0.00;
        $carrito = Carrito::obtenerCarritoPorUsuario($idUsuario);
        $prendas = Carrito::obtenerPrendasPorUsuario($idUsuario);

        foreach($prendas as $prenda){
            $total += $prenda->precio * $carrito[$prenda->id];
        }
        return $total;
    }

}