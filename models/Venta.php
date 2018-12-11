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

    public function attributeLabels(){
        return[ 
            'idTipoPago' => 'Tipo de pago',
        ];
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

    public function setIdDireccion($idDireccion)
    {
        $this->idDireccion = $idDireccion;
    }

    public function setIdTipoPago($idTipoPago)
    {
        $this->idTipoPago = $idTipoPago;
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

    public function cambiarTotalSubtotal($total){
        $venta = new Venta();
        $venta->setTotal($total);
        $venta->setSubtotal($total * 0.84);
        $venta->setIva($total * 0.16);
        return $venta;
    }
    public function guardarVenta($total,$idUsuario,$idDireccion,$idTipoPago){
        $venta = new Venta();
        date_default_timezone_set('America/Mazatlan');
        $fechaActual = date("Y-m-d");
        $status = ($idTipoPago == 1) ? 'SALDADA' : 'NO SALDADA';
        $venta->setFolio(obtenerFolio());
        $venta->setTotal($total);
        $venta->setSubtotal($total * 0.84);
        $venta->setIva($total * 0.16);
        $venta->setIdUsuario($idUsuario);
        $venta->setIdDireccion($idDireccion);
        $venta->setIdTipoPago($idTipoPago);
        $venta->setFechaVenta($fechaActual);
        $venta->setStatus($status);
        $venta->save();
        return $venta;
    }

}