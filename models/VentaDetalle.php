<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\entities\EntityVentaDetalle;

class VentaDetalle extends \yii\db\ActiveRecord{

    public static function tableName()
    {
        return EntityVentaDetalle::tableName();
    }

    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    public function setIdPrenda($idPrenda)
    {
        $this->idPrenda = $idPrenda;
    }

    public function setIdFolio($idFolio)
    {
        $this->idFolio = $idFolio;
    }

    public static function obtenerDetalleVenta($folio)
    {
        return static::find()
            ->where(['idFolio' => $folio])
            ->all();
    }

}