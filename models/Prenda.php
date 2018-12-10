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
            [['nombre'], 'required', 'message' => 'El nombre no debe ser vacío.'],
            [['descripcion'], 'required', 'message' => 'La descripción no debe ser vacía.'],
            [['idCategoria'], 'required', 'message' => 'La categoría no debe ser vacía.'],
            [['idSubCategoria'], 'required', 'message' => 'La subCategoría no debe ser vacía.'],
            [['idTemporada'], 'required', 'message' => 'La temporada no debe ser vacía.'],
            [['urlImagen'], 'required', 'message' => 'Debe seleccionar una imagen para la prenda.'],
            [['urlImagenMiniatura'], 'required', 'message' => 'Debe seleccionar una imagen miniatura para la prenda.']
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

    public static function obtenerPrendas()
    {
        return static::find()->all();
    }
}