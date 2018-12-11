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
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Componente;
use app\models\Categoria;
use app\models\Temporada;
use app\models\SubCategoria;


class VentasController extends Controller
{
    public function actionCarrito(){
        $prendas = Carrito::obtenerPrendasPorUsuario(Yii::$app->session['idUsuario']);
        $total = Carrito::totalCarrito(Yii::$app->session['idUsuario']);
        $urlbase = Url::base(true);
        return $this->render('carrito',[
            'model' => $prendas,
            'total' => $total,
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

    public function actionAgregarpago(){
        $pago = new Pago();

        if ($pago->load(Yii::$app->request->post()['Pago'])){
            date_default_timezone_set('America/Mazatlan');
            $fechaActual = date("Y-m-d");
            $total = Carrito::totalCarrito(Yii::$app->session['idUsuario']);
            $folio = 5;

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
            $ventaDetalle = new VentaDetalle();
            foreach($prendasCarrito as $prendaCarrito) {
                $ventaDetalle->setIdFolio($folio);
                $ventaDetalle->setIdPrenda($prendaCarrito->id);
                $ventaDetalle->setCantidad($carrito[$prendaCarrito->id]);
                $ventaDetalle->save();
            }

            $pago->setIdFolio($folio);
            $pago->setTotal($total);
            $pago->setSubtotal($total * 0.84);
            $pago->setIva($total * 0.16);
            $pago->setFechaPago($fechaActual);
            $pago->save();

            Yii::$app->session->setFlash('success','El pago se realizó con exito. Un correo de confirmación fue enviado a su correo.');
            
            return $this->render('index',[
                'model' => $venta
            ]);

        }
        Yii::$app->session->setFlash('error','Ha ocurrido un error al procesar el pago.');
        return $this->render('pago',[
            'model' => $pago,
        ]);

    }

    public function actionAgregarcarrito(){
        $model = new Carrito();
        if (Yii::$app->request->post()){
                $model->idUsuario = Yii::$app->session['idUsuario'];
                $model->idPrenda= $_POST['idprenda'];

                if($model->idUsuario != null){
                    $existe = Carrito::obtenerUsuarioPrenda(Yii::$app->session['idUsuario'],$_POST['idprenda']);

                    if($existe==null){
                        $model->save();
                        $model = Prenda::findOne($model->idPrenda);
                        $descripciontemporada= Temporada::findOne($model->idTemporada)->tipoTemporada;
                        $componentes = Componente::obtenerComponentesPrenda($idPrenda);
                        $descripcionCategoria = Categoria::findOne($model->idCategoria)->descripcion;
                        $descripcionSubCategoria = SubCategoria::findOne($model->idSubCategoria)->descripcion;
                        return $this->render('//prendas/prendaPersonalizar',['msg'=>'Articulo agregado al carrito.',
                                                                                'model'=>$model,
                                                                            'temporada'=>$descripciontemporada,
                                                                            'categoria'=>$descripcionCategoria,
                                                                            'subcategoria'=>$descripcionSubCategoria,
                                                                            'componentes'=>$componentes, 
                                                                            'tipo'=>1]);
                    }else{
                        $existe->cantidad=$existe->cantidad+1; 
                        $existe->save();
                        $model = Prenda::findOne($model->idPrenda);
                        $descripciontemporada= Temporada::findOne($model->idTemporada)->tipoTemporada;
                        $componentes = Componente::obtenerComponentesPrenda($_POST['idprenda']);
                        $descripcionCategoria = Categoria::findOne($model->idCategoria)->descripcion;
                        $descripcionSubCategoria = SubCategoria::findOne($model->idSubCategoria)->descripcion;
                        return $this->render('//prendas/prendaPersonalizar',['msg'=>'Articulo agregado al carrito.',
                                                                                'model'=>$model,
                                                                            'temporada'=>$descripciontemporada,
                                                                            'categoria'=>$descripcionCategoria,
                                                                            'subcategoria'=>$descripcionSubCategoria,
                                                                            'componentes'=>$componentes, 
                                                                            'tipo'=>1]);
                    }
       
                }else{
                    $idPrenda = $_POST['idprenda'];
                    $model = Prenda::findOne($idPrenda);
                    Yii::$app->session['idPrenda'] = $idPrenda;
                    $componentes = Componente::obtenerComponentesPrenda($idPrenda);
                    $descripciontemporada= Temporada::findOne($model->idTemporada)->tipoTemporada;
                    $descripcionCategoria = Categoria::findOne($model->idCategoria)->descripcion;
                    $descripcionSubCategoria = SubCategoria::findOne($model->idSubCategoria)->descripcion;
                    return $this->render('//prendas/prendaPersonalizar',['msg'=>'Necesita logearse para agregar al carrito.',
                                                                         'model'=>$model,
                                                                         'temporada'=>$descripciontemporada,
                                                                         'categoria'=>$descripcionCategoria,
                                                                         'subcategoria'=>$descripcionSubCategoria,
                                                                         'componentes'=>$componentes, 
                                                                         'tipo'=>0]);
                    
                }
        }
    }
}
