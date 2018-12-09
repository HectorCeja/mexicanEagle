<?php

namespace app\models;

use Yii;
use yii\base\Model;


class Componente extends \yii\db\ActiveRecord {
    public $id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $idPrenda;
    public $urlImagen;
    public $urlImagenMiniatura;
    public $fechaAlta;
    public $created_at;
    public $updated_at;



    public static function tableName()
    {
        return EntityComponentes::tableName();
    }
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['nombre', 'descripcion', 'urlImagen', 'urlImagenMiniatura','idPrenda'], 'required'],
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
            'urlImagen' => 'Imagen',
            'urlImagenMiniatura' => 'Imagen Miniatura',
            'idPrenda' => 'Prena',
        ];
    }
}