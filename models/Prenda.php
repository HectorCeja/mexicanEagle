<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\entities\EntityPrendas;

class Prenda extends \yii\db\ActiveRecord {


    public static function tableName()
    {
        return EntityPrendas::tableName();
    }
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['nombre', 'descripcion', 'urlImagen', 'urlImagenMiniatura','tipoPrenda','idCategoria','idSubCategoria','idTemporada'], 'required'],
            
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'nombre' => 'Ingrese nombre de la prenda',
            'descripcion' => 'Ingrese descripcion',
            'tipoPrenda' => 'Selecciones tipo de Prenda',
            'urlImagen' => 'Seleccione imagen de prenda',
            'urlImagenMiniatura' => 'Seleccione imagen miniatura de prenda',
            'idCategoria' => 'Seleccione categoria',
            'idTemporada' => 'Seleccione temporada',
            'idSubCategoria'=>'Seleccione sub categoria'
        ];
    }
}