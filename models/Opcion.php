<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\entities\EntityOpcion;

class Opcion extends \yii\db\ActiveRecord{


    public static function tableName()
    {
        return EntityOpcion::tableName();
    }

    public static function obtenerOpciones($opciones)
    {
        return static::find()
            ->where(['in', 'id', $opciones])
            ->all();
    }
}