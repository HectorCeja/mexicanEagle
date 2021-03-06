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

    public static function obtenerPrendasSite()
    {
        return static::find()->Where(['>', 'precio', 0.0])->all();
    }

    public static function obtenerPrendasPorTemporadas()
    {
        return static::find()->where(['in', 'idTemporada', 1])->all();
    }

    public static function obtenerPrendasPorIds($prendas)
    {
        return static::find()
            ->where(['in', 'id', $prendas])
            ->all();
    }

    public static function obtenerPrendasBuscador($search){
        return static::find()->where(['LIKE','nombre',$search])->orWhere(['LIKE','descripcion',[$search]])->all();        
    }

    public static function guardarPrenda($prenda,$tipoPrenda){
        $fechaAlta = date("Y-m-d");
        $prenda->tipoPrenda = $tipoPrenda;
        $prenda->fechaAlta = $fechaAlta;
        $prenda->urlImagenMiniatura = '';
        return $prenda->save(false);
    }

    public function borrarPrenda($idPrenda){
        static::deleteAll(['id'=>$idPrenda]);
    }

    public  function guardarPrendaPersonalizada($idPrenda,$totalComponentes){
        $prendaAPersonalizar = static::findOne($idPrenda);
        $fechaAlta = date("Y-m-d");
        $prendaNueva = new Prenda();
        $prendaNueva->tipoPrenda = "PERSONALIZADA";
        $prendaNueva->fechaAlta = $fechaAlta;
        $prendaNueva->nombre = $prendaAPersonalizar->nombre;
        $prendaNueva->precio = $prendaAPersonalizar->precio + $totalComponentes;
        $prendaNueva->descripcion = $prendaAPersonalizar->descripcion;
        $prendaNueva->idTemporada = $prendaAPersonalizar->idTemporada;
        $prendaNueva->urlImagen = $prendaAPersonalizar->urlImagen;
        $prendaNueva->urlImagenMiniatura = '';
        $prendaNueva->idCategoria = $prendaAPersonalizar->idCategoria;
        $prendaNueva->idSubCategoria = $prendaAPersonalizar->idSubCategoria;
        $prendaNueva->save(false);
        return $prendaNueva;
    }

}