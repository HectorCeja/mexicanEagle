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
use app\models\Componente;
use app\models\Categoria;
use app\models\Temporada;
use app\models\SubCategoria;
use app\models\PrendaPersonalizada;


class VentasController extends Controller
{
    public function actionCarrito(){
        $idUsuario = Yii::$app->session['idUsuario'];
        $carrito = Carrito::obtenerCarritoPorUsuario($idUsuario);
        $prendas = Carrito::obtenerPrendasPorUsuario($idUsuario);
        $total = Carrito::totalCarrito($idUsuario);
        $urlbase = Url::base(true);
        $fechaEntrega = FechaEntrega::obtenerFechaEntrega($idUsuario);
        return $this->render('carrito',[
            'carrito' => $carrito,
            'prendas' => $prendas,
            'total' => $total,
            'fechaEntrega' => $fechaEntrega,
            'urlbase' => $urlbase
        ]);
    }

    public function actionProceder(){
        $direccion = new Direccion();
        $idUsuario = Yii::$app->session['idUsuario'];
        $direcciones = Direccion::obtenerDireccionesPorUsuario($idUsuario);
        return $this->render('direccion',[
            'model' => $direccion,
            'direcciones' => $direcciones
        ]);
    }

    public function actionAgregardireccion(){
        $direccion = new Direccion();
        if ($direccion->load(Yii::$app->request->post())){
            if($direccion->validate()){
                $direccion= Direccion::guardarDireccion($direccion,Yii::$app->session['idUsuario']);
                Yii::$app->session['idDireccion'] = $direccion->id;
                Yii::$app->session->setFlash('success','Direción de envío agregada con éxito al pedido.');             
                $total = Carrito::totalCarrito(Yii::$app->session['idUsuario']);
                $venta = Venta::cambiarTotalSubtotal($total);
                return $this->render('pago',[
                    'model' => $venta
                ]);
            }
        }
        Yii::$app->session->setFlash('error','Ha ocurrido un error al guardar dirección');
        $idUsuario = Yii::$app->session['idUsuario'];
        $direcciones = Direccion::obtenerDireccionesPorUsuario($idUsuario);
        return $this->render('direccion',[
            'model' => $direccion,
            'direcciones' => $direcciones
        ]);
    }
    public function actionUsardireccion(){
        $idDireccion = Html::encode($_POST["id"]);
        Yii::$app->session['idDireccion']=$idDireccion;
        Yii::$app->session->setFlash('success','Direción de envío agregada con éxito.');             
        $total = Carrito::totalCarrito(Yii::$app->session['idUsuario']);
        $venta= Venta::cambiarTotalSubtotal($total);
        return $this->render('pago',[
            'model' => $venta
        ]);
    }

    public function actionAgregarpago(){
        ini_set('max_execution_time', 300);
        $venta = new Venta();

        if ($venta->load(Yii::$app->request->post())){
            $idTipoPago = (int) Yii::$app->request->post()['Venta']['idTipoPago'];
            $idDireccion = Yii::$app->session['idDireccion'];
            $idUsuario = Yii::$app->session['idUsuario'];
            $total = Carrito::totalCarrito($idUsuario);
            $venta = Venta::guardarVenta($total, $idUsuario, $idDireccion, $idTipoPago);

            $carrito = Carrito::obtenerCarritoPorUsuario($idUsuario);
            $prendasCarrito = Carrito::obtenerPrendasPorUsuario($idUsuario);
            VentaDetalle::guardarVentasDetalle($carrito, $prendasCarrito, $venta->folio);

            Pago::guardarPago($idTipoPago, $venta->folio, $total);

            $fechaEntrega = FechaEntrega::obtenerFechaEntrega($idUsuario);
            Email::enviarCorreoConfirmacion($prendasCarrito, $carrito, $total, $fechaEntrega);

            Carrito::limpiarCarritoPorIdUsuario($idUsuario);
            Yii::$app->session->setFlash('success','El pago se realizó con exito. Un correo de confirmación fue enviado a su correo.');
            
            $prendasTemporada = Prenda::obtenerPrendasPorTemporadas();
            $prendas = Prenda::obtenerPrendasSite();
            
            return $this->render('//site/index',[
                'model' => $venta,
                'prendas'=>$prendas,
                'prendasTemporada'=> $prendasTemporada,
                'model' => $venta
            ]);

        }
        Yii::$app->session->setFlash('error','Ha ocurrido un error al procesar el pago.');
        return $this->render('pago',[
            'model' => $venta
        ]);

    }

    public function actionAgregarcarrito(){
        
        if (Yii::$app->request->post()){
                $idUsuario = Yii::$app->session['idUsuario'];
                $idPrenda = $_POST['idprenda'];
                $talla = $_POST['talla'];
                $color = $_POST['color'];
                $cantidad = $_POST['cantidad'];

                if(Yii::$app->session['idUsuario'] != null){
                    $existeElemento = Carrito::obtenerUsuarioPrenda($idUsuario,$idPrenda);

                    if(isset($_POST['idcomponente'])){
                        $ids = explode("|", $_POST['idcomponente']);
                        $precio = Componente::obtenerSumaPrecio($ids);
                        $prendaNueva =Prenda::guardarPrendaPersonalizada($idPrenda,$precio);
                        $idPrendaPersonalizada = $prendaNueva->id;
                        PrendaPersonalizada::guardarPrendasPersonalizadas($idUsuario,$idPrenda,$ids);
                        Carrito::guardarNuevaLineaVenta($idPrendaPersonalizada,$idUsuario,$cantidad,$talla,$color);
                        $mensaje = 'Artículo personalizado agregado al carrito.';
                        $tipoMensaje = 1;
                        $model = Prenda::findOne($idPrenda);
                    }else{

                        if($existeElemento==null){
                            Carrito::guardarNuevaLineaVentaBasica($idPrenda,$idUsuario,$cantidad,$talla,$color);
                            $mensaje = 'Artículo agregado al carrito.';
                            $tipoMensaje = 1;
                        }else{
                            if($existeElemento->color==$color && $existeElemento->talla==$talla){
                                Carrito::actualizarLineaVenta($existeElemento,$cantidad);
                                $mensaje = 'Artículo agregado al carrito.';
                                $tipoMensaje = 1;
                            }else{
                                Carrito::guardarNuevaLineaVentaBasica($idPrenda,$idUsuario,$cantidad,$talla,$color);
                                $mensaje = 'Artículo agregado al carrito.';
                                $tipoMensaje = 1;
                            }
                        }

                    }
       
                }else{
                    $mensaje = 'Necesita autenticarse para agregar al carrito.';
                    $tipoMensaje = 0;
                    
                }
                $model = Prenda::findOne($idPrenda);
                Yii::$app->session['idPrenda'] = $idPrenda;
                $componentes = Componente::obtenerComponentesPrenda($idPrenda);
                $descripciontemporada= Temporada::findOne($model->idTemporada)->tipoTemporada;
                $descripcionCategoria = Categoria::findOne($model->idCategoria)->descripcion;
                $descripcionSubCategoria = SubCategoria::findOne($model->idSubCategoria)->descripcion;
                return $this->render('//prendas/prendaPersonalizar',['msg'=>$mensaje,
                                                                        'model'=>$model,
                                                                        'temporada'=>$descripciontemporada,
                                                                        'categoria'=>$descripcionCategoria,
                                                                        'subcategoria'=>$descripcionSubCategoria,
                                                                        'componentes'=>$componentes, 
                                                                        'tipo'=>$tipoMensaje]);
        }
    }

    public function actionBorrarcarrito(){
        if (Yii::$app->request->post()){
            $idPrenda = Html::encode($_POST["id"]);
            $idUsuario = Yii::$app->session['idUsuario'];

            Carrito::borrarElemento($idUsuario, $idPrenda);

            $carrito = Carrito::obtenerCarritoPorUsuario($idUsuario);
            $prendas = Carrito::obtenerPrendasPorUsuario($idUsuario);
            $total = Carrito::totalCarrito($idUsuario);
            $urlbase = Url::base(true);
            $fechaEntrega = FechaEntrega::obtenerFechaEntrega($idUsuario);
            return $this->render('carrito',[
                'carrito' => $carrito,
                'prendas' => $prendas,
                'total' => $total,
                'fechaEntrega' => $fechaEntrega,
                'urlbase' => $urlbase
            ]);
        }
    }
}
