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
            [['nombre', 'descripcion', 'urlImagen', 'urlImagenMiniatura','tipoPrenda','idCategoria','idSubCategoria'], 'required'],
            
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'tipoPrenda' => 'Tipo de Prenda',
            'urlImagen' => 'Imagen',
            'urlImagenMiniatura' => 'Imagen Miniatura',
            'idCategoria' => 'Categoria',
            'idSubCategoria'=>'SubCategoria'
        ];
    }
}