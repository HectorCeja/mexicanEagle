<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\entities\EntityVenta;

class Venta extends \yii\db\ActiveRecord{

    public static function tableName()
    {
        return EntityVenta::tableName();
    }

    public function setFolio($folio)
    {
        $this->folio = $folio;
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }

    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;
    }

    public function setIva($iva)
    {
        $this->iva = $iva;
    }

    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    public function setFechaVenta($fechaVenta)
    {
        $this->fechaVenta = $fechaVenta;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public static function obtenerVentaPorFolio($folio)
    {
        return static::findOne(['folio' => $folio]);
    }

    public static function obtenerFolio()
    {
        $result = \Yii::$app->db->createCommand("CALL aumentar_folio(@folio);") 
                      ->execute();
        return \Yii::$app->db->createCommand("select @folio;")->queryScalar();
    }

}