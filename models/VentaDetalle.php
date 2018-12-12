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

    public function setTalla($talla)
    {
        $this->talla = $talla;
    }

    public function setColor($color)
    {
        $this->color = $color;
    }

    public static function obtenerDetalleVenta($folio)
    {
        return static::find()
            ->where(['idFolio' => $folio])
            ->all();
    }
    public function guardarVentasDetalle($carrito,$prendasCarrito,$folio){
        foreach($prendasCarrito as $prendaCarrito) {
            $ventaDetalle = new VentaDetalle();
            $ventaDetalle->setIdFolio($folio);
            $ventaDetalle->setIdPrenda($prendaCarrito->id);
            $ventaDetalle->setCantidad($carrito[$prendaCarrito->id]->cantidad);
            $ventaDetalle->setTalla($carrito[$prendaCarrito->id]->talla);
            $ventaDetalle->setColor($carrito[$prendaCarrito->id]->color);
            $ventaDetalle->setPrecio($prendaCarrito->precio);
            $ventaDetalle->save();
        }
    }
}