<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\entities\EntityPerfilOpcion;

class PerfilOpcion extends \yii\db\ActiveRecord{


    public static function tableName()
    {
        return EntityPerfilOpcion::tableName();
    }

    public static function obtenerOpcionesPorPerfil($idPerfil)
    {
        return static::find()
            ->where(['idPerfil' => $idPerfil])
            ->all();
    }
}