<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use app\models\Prenda;
use app\models\Carrito;
use app\models\Direccion;
use app\models\Pago;
use app\models\Venta;
use app\models\VentaDetalle;
use app\models\Email;
use app\models\FechaEntrega;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


class VentasController extends Controller
{
    public function actionCarrito(){
        $idUsuario = Yii::$app->session['idUsuario'];
        $prendas = Carrito::obtenerPrendasPorUsuario($idUsuario);
        $total = Carrito::totalCarrito($idUsuario);
        $urlbase = Url::base(true);
        $fechaEntrega = FechaEntrega::obtenerFechaEntrega($idUsuario);
        return $this->render('carrito',[
            'model' => $prendas,
            'total' => $total,
            'fechaEntrega' => $fechaEntrega,
            'urlbase' => $urlbase
        ]);
    }

    public function actionProceder(){
        $direccion = new Direccion();
        return $this->render('direccion',[
            'model' => $direccion
        ]);
    }

    public function actionAgregardireccion(){
        $direccion = new Direccion();

        if ($direccion->load(Yii::$app->request->post())){

            if($direccion->validate()){

                $direccion->setIdUsuario(Yii::$app->session['idUsuario']);
                $direccion->save();
                Yii::$app->session['idDomicilio'] = $direccion->id;
                Yii::$app->session->setFlash('success','Direción de envío agregada con éxito.');
                
                $pago = new Pago();
                $total = Carrito::totalCarrito(Yii::$app->session['idUsuario']);
                $subtotal = $total * 0.84;
                $iva = $total * 0.16;

                return $this->render('pago',[
                    'model' => $pago,
                    'total' => $total,
                    'subtotal' => $subtotal,
                    'iva' => $iva
                ]);

            }

        }
        Yii::$app->session->setFlash('error','Ha ocurrido un error al guardar dirección');
        return $this->render('direccion',[
            'model' => $direccion,
        ]);
    }

    // TODO -> fecha
    public function actionAgregarpago(){
        $pago = new Pago();
        $total = 0;
        $subtotal = 0;
        $iva = 0;

        if ($pago->load(Yii::$app->request->post())){
            date_default_timezone_set('America/Mazatlan');
            $fechaActual = date("Y-m-d");
            $total = Carrito::totalCarrito(Yii::$app->session['idUsuario']);
            $folio = Venta::obtenerFolio();

            $venta = new Venta();
            $venta->setFolio($folio);
            $venta->setTotal($total);
            $venta->setSubtotal($total * 0.84);
            $venta->setIva($total * 0.16);
            $venta->setIdUsuario(Yii::$app->session['idUsuario']);
            $venta->setFechaVenta($fechaActual);
            $venta->setStatus('SALDADA');
            $venta->save();
            
            $carrito = Carrito::obtenerCarritoPorUsuario(Yii::$app->session['idUsuario']);
            $prendasCarrito = Carrito::obtenerPrendasPorUsuario(Yii::$app->session['idUsuario']);
            foreach($prendasCarrito as $prendaCarrito) {
                $ventaDetalle = new VentaDetalle();
                $ventaDetalle->setIdFolio($folio);
                $ventaDetalle->setIdPrenda($prendaCarrito->id);
                $ventaDetalle->setCantidad($carrito[$prendaCarrito->id]);
                $ventaDetalle->setPrecio($prendaCarrito->precio);
                $ventaDetalle->save();
            }

            $pago->setIdFolio($folio);
            $pago->setIdPago(Yii::$app->request->post()['Pago']);
            $pago->setTotal($total);
            $pago->setSubtotal($total * 0.84);
            $pago->setIva($total * 0.16);
            $pago->setFechaPago($fechaActual);
            $pago->save();

            $emailFrom = Yii::$app->params['adminEmail'];
            $emailTo = Yii::$app->session['emailUsuario'];
            $subject = "Pedido Ropalinda";
            $message =  "Enhorabuena! Su pedido se ha completado con éxito.";
            $message .= "Sus prendas serán enviadas a su hogar en la fecha especificada. ";
            $message .= "Gracias por su preferencia."; 
            Email::sendEmail($emailFrom, $emailTo, $subject, $message);

            Carrito::limpiarCarritoPorIdUsuario(Yii::$app->session['idUsuario']);

            Yii::$app->session->setFlash('success','El pago se realizó con exito. Un correo de confirmación fue enviado a su correo.');
            
            return $this->render('//site/index',[
                'model' => $venta
            ]);

        }
        Yii::$app->session->setFlash('error','Ha ocurrido un error al procesar el pago.');
        return $this->render('pago',[
            'model' => $pago,
            'total' => $total,
            'subtotal' => $subtotal,
            'iva' => $iva
        ]);

    }

}