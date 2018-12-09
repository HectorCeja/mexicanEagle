<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\entities\EntityCategoria;

class Categoria extends \yii\db\ActiveRecord{


    public static function tableName()
    {
        return EntityCategoria::tableName();
    }

     /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            ['descripcion', 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'descripcion' => 'Descripcion'
        ];
    }

    public static function obtenerCategorias()
    {
        return static::find()->all();
    }
}