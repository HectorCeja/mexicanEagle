<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\entities\EntityTemporada;

class Temporada extends \yii\db\ActiveRecord{


    public static function tableName()
    {
        return EntityTemporada::tableName();
    }

     /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            ['tipoTemporada', 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'tipoTemporada' => 'Temporada'
        ];
    }

    public static function obtenerTemporadas()
    {
        return static::find()->all();
    }
}