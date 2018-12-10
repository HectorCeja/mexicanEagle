<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\entities\EntityDireccion;

class Direccion extends \yii\db\ActiveRecord{


    public static function tableName()
    {
        return EntityDireccion::tableName();
    }

    public static function obtenerDireccionPorCliente($idCliente)
    {
        return static::findOne(['idCliente' => $idCliente]);
    }

}