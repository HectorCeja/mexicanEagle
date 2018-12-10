<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\entities\EntityCarrito;

class Carrito extends \yii\db\ActiveRecord{


    public static function tableName()
    {
        return EntityCarrito::tableName();
    }

    public static function obtenerPrendasPorCliente($idUsuario)
    {
        return static::find()
            ->where(['idUsuario' => $idUsuario])
            ->all();
    }

    public static function limpiarCarritoPorCliente()
    {
        return static::findModel($id)
            ->delete();
    }

}