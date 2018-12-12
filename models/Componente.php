<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\entities\EntityComponentes;

class Componente extends \yii\db\ActiveRecord {
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
            [['nombre', 'descripcion', 'precio', 'urlImagen', 'urlImagenMiniatura'], 'required'],
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
        ];
    }
    public static function obtenerComponentesPrenda($id)
    {
        return static::find()->where(['idPrenda'=>$id])->all();
    }

    public static function guardarComponente($componente,$idPrenda){
        $fechaAlta = date("Y-m-d");
        $precio = 0.0;
        $componente->fechaAlta = $fechaAlta;
        $componente->precio = $precio;
        $componente->idPrenda=$idPrenda;
        $componente->urlImagenMiniatura = '';
        return $componente->save(false);
    }
}