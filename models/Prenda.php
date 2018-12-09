<?php

namespace app\models;

use Yii;
use yii\base\Model;


class Prenda extends \yii\db\ActiveRecord {
    public $id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $tipoPrenda;
    public $idTemporada;
    public $urlImagen;
    public $urlImagenMiniatura;
    public $idCategoria;
    public $idSubCategoria;
    public $fechaAlta;
    public $created_at;
    public $updated_at;



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