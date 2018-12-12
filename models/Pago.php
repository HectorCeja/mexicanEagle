<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\entities\EntityPago;

class Pago extends \yii\db\ActiveRecord{

    public static function tableName()
    {
        return EntityPago::tableName();
    }

    public function setIdFolio($idFolio)
    {
        $this->idFolio = $idFolio;
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

    public function setFechaPago($fechaPago)
    {
        $this->fechaPago = $fechaPago;
    }

    public static function obtenerPagoPorFolio($idFolio)
    {
        return static::findOne(['idFolio' => $idFolio]);
    }

    public function guardarPago($idTipoPago, $folio, $total)
    {
        if ($idTipoPago == 1) {
            $pago = new Pago();
            $fechaActual = date("Y-m-d");
            $pago->setIdFolio($folio);
            $pago->setTotal($total);
            $pago->setSubtotal($total * 0.84);
            $pago->setIva($total * 0.16);
            $pago->setFechaPago($fechaActual);
            $pago->save();
        }
    }

}