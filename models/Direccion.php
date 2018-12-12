<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\entities\EntityDireccion;

class Direccion extends \yii\db\ActiveRecord{

    public static function tableName()
    {
        return EntityDireccion::tableName();
    }

    public function rules()
    {
        return [
            [['pais'], 'required'],
            [['estado'], 'required', 'message'=>'El estado no debe estar vacío.'],
            [['ciudad'], 'required', 'message'=>'La ciudad no debe estar vacía.'],
            [['codigoPostal'], 'required', 'message'=>'El codigo postal no debe estar vacío.'],
            [['calle'], 'required', 'message'=>'La calle no debe estar vacía.'],
            [['noExterior'], 'required', 'message'=>'El numero exterior no debe estar vací0.'],
            [['colonia'], 'required', 'message'=>'La colonia no debe estar vacío.'],
        ];
    }

    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    public static function obtenerDireccionPorUsuario($idUsuario)
    {
        return static::findOne(['idUsuario' => $idUsuario]);
    }

    public  function guardarDireccion($direccion,$idUsuario){
        $direccion->setIdUsuario($idUsuario);
        $direccion->save();
        return $direccion;
    }

    public  function obtenerDireccionesPorUsuario($idUsuario){
      return static::find()
        ->where(['idUsuario'=> $idUsuario])
        ->all();
    }

}