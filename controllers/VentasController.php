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
        return $this->render('direccion',[
            'model' => $direccion
        ]);
    }

    public function actionAgregardireccion(){
        $direccion = new Direccion();
        if ($direccion->load(Yii::$app->request->post())){
            if($direccion->validate()){
                $direccion= Direccion::guardarDireccion($direccion,Yii::$app->session['idUsuario']);
              //  $direccion->setIdUsuario();
               // $direccion->save();
                Yii::$app->session['idDireccion'] = $direccion->id;
                Yii::$app->session->setFlash('success','Direción de envío agregada con éxito.');             
                $total = Carrito::totalCarrito(Yii::$app->session['idUsuario']);
                $venta= Venta::cambiarTotalSubtotal($total);
                return $this->render('pago',[
                    'model' => $venta
                ]);
            }
        }
        Yii::$app->session->setFlash('error','Ha ocurrido un error al guardar dirección');
        return $this->render('direccion',[
            'model' => $direccion,
        ]);
    }

    public function actionAgregarpago(){
        ini_set('max_execution_time', 300);
        $venta = new Venta();
        if ($venta->load(Yii::$app->request->post())){
            $total = Carrito::totalCarrito(Yii::$app->session['idUsuario']);
           //  $folio = Venta::obtenerFolio();
            $idTipoPago = (int) Yii::$app->request->post()['Venta']['idTipoPago'];
            $idDireccion=Yii::$app->session['idDireccion'];
            $idUsuario=Yii::$app->session['idUsuario'];
           // $status = ($idTipoPago == 1) ? 'SALDADA' : 'NO SALDADA';
            $venta=Venta::guardarVenta($total,$idUsuario,$idDireccion,$idTipoPago);
         /*   $venta = new Venta();
            $venta->setFolio($folio);
            $venta->setTotal($total);
            $venta->setSubtotal($total * 0.84);
            $venta->setIva($total * 0.16);
            $venta->setIdUsuario(;
            $venta->setIdDireccion();
            $venta->setIdTipoPago($idTipoPago);
            $venta->setFechaVenta($fechaActual);
            $venta->setStatus($status);
            $venta->save();*/
            
            $carrito = Carrito::obtenerCarritoPorUsuario(Yii::$app->session['idUsuario']);
            $prendasCarrito = Carrito::obtenerPrendasPorUsuario(Yii::$app->session['idUsuario']);
            VentaDetalle::guardarVentasDetalle($carrito,$prendasCarrito,$venta->folio);
           /* foreach($prendasCarrito as $prendaCarrito) {
                $ventaDetalle = new VentaDetalle();
                $ventaDetalle->setIdFolio($venta->folio);
                $ventaDetalle->setIdPrenda($prendaCarrito->id);
                $ventaDetalle->setCantidad($carrito[$prendaCarrito->id]);
                $ventaDetalle->setPrecio($prendaCarrito->precio);
                $ventaDetalle->save();
            }

            */
           
            if ($idTipoPago == 1) {
                Pago::guardarPago($venta->folio,$total);
                
            }

            $emailFrom = Yii::$app->params['adminEmail'];
            $emailTo = Yii::$app->session['emailUsuario'];
            $subject = "Pedido Ropalinda";
            $message =  "Enhorabuena! Su pedido se ha completado con éxito.";
            $message .= "Sus prendas serán enviadas a su hogar en la fecha especificada. ";
            $message .= "Gracias por su preferencia."; 
            Email::sendEmail($emailFrom, $emailTo, $subject, $message);

            Carrito::limpiarCarritoPorIdUsuario(Yii::$app->session['idUsuario']);

            Yii::$app->session->setFlash('success','El pago se realizó con exito. Un correo de confirmación fue enviado a su correo.');
            
            $prendasTemporada = Prenda::obtenerPrendasPorTemporadas();
            $prendas = Prenda::obtenerPrendasSite();

            return $this->render('//site/index',[
                'model' => $venta,
                'prendas'=>$prendas,
                'prendasTemporada'=>$prendasTemporada
            ]);

        }
        Yii::$app->session->setFlash('error','Ha ocurrido un error al procesar el pago.');
        return $this->render('pago',[
            'model' => $venta
        ]);

    }

    public function actionAgregarcarrito(){
        $model = new Carrito();
        if (Yii::$app->request->post()){
                $model->idUsuario = Yii::$app->session['idUsuario'];
                $model->idPrenda = $_POST['idprenda'];

                if($model->idUsuario != null){
                    $existe = Carrito::obtenerUsuarioPrenda(Yii::$app->session['idUsuario'],$_POST['idprenda']);

                    $flag = false;
                    if(!isset($_POST['idComponente'])){
                        $flag=true;
                    }

                    if($flag==true){ //personalizada
                        $ids = explode("|", $_POST['idcomponente']);
                        $fechaAlta = date("Y-m-d");

                        $prendaAPersonalizar = Prenda::findOne($_POST['idprenda']);
                        $prendaAPersonalizar->tipoPrenda = "PERSONALIZADA";
                        $prendaAPersonalizar->fechaAlta = $fechaAlta;

                        $prendaAPersonalizar->save();

                        $idPrendaPersonalizada = $prendaAPersonalizar->id;

                        foreach($ids as $id){
                            $componentePersonalizar = new PrendaPersonalizada();
                            $componentePersonalizar->idUsuario = Yii::$app->session['idUsuario'];
                            $componentePersonalizar->idPrenda = $_POST['idprenda'];
                            $componentePersonalizar->idComponente = $id;
                            $componentePersonalizar->fechaAlta = $fechaAlta;
                            $componentePersonalizar->save();
                        }
                        $model = Prenda::findOne($_POST['idprenda']);
                        $descripciontemporada= Temporada::findOne($model->idTemporada)->tipoTemporada;
                        $componentes = Componente::obtenerComponentesPrenda($_POST['idprenda']);
                        $descripcionCategoria = Categoria::findOne($model->idCategoria)->descripcion;
                        $descripcionSubCategoria = SubCategoria::findOne($model->idSubCategoria)->descripcion;
                        return $this->render('//prendas/prendaPersonalizar',['msg'=>'Artículo personalizado agregado al carrito.',
                                                                                    'model'=>$model,
                                                                                'temporada'=>$descripciontemporada,
                                                                                'categoria'=>$descripcionCategoria,
                                                                                'subcategoria'=>$descripcionSubCategoria,
                                                                                'componentes'=>$componentes, 
                                                                                'tipo'=>1]);

                    }else{ //nopersonalizada

                        if($existe==null){
                            $model->save();
                            $model = Prenda::findOne($model->idPrenda);
                            $descripciontemporada= Temporada::findOne($model->idTemporada)->tipoTemporada;
                            $componentes = Componente::obtenerComponentesPrenda($_POST['idprenda']);
                            $descripcionCategoria = Categoria::findOne($model->idCategoria)->descripcion;
                            $descripcionSubCategoria = SubCategoria::findOne($model->idSubCategoria)->descripcion;
                            return $this->render('//prendas/prendaPersonalizar',['msg'=>'Artículo agregado al carrito.',
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
                            return $this->render('//prendas/prendaPersonalizar',['msg'=>'Artículo agregado al carrito.',
                                                                                    'model'=>$model,
                                                                                'temporada'=>$descripciontemporada,
                                                                                'categoria'=>$descripcionCategoria,
                                                                                'subcategoria'=>$descripcionSubCategoria,
                                                                                'componentes'=>$componentes, 
                                                                                'tipo'=>1]);
                        }
                    }
       
                }else{
                    $idPrenda = $_POST['idprenda'];
                    $model = Prenda::findOne($idPrenda);
                    Yii::$app->session['idPrenda'] = $idPrenda;
                    $componentes = Componente::obtenerComponentesPrenda($idPrenda);
                    $descripciontemporada= Temporada::findOne($model->idTemporada)->tipoTemporada;
                    $descripcionCategoria = Categoria::findOne($model->idCategoria)->descripcion;
                    $descripcionSubCategoria = SubCategoria::findOne($model->idSubCategoria)->descripcion;
                    return $this->render('//prendas/prendaPersonalizar',['msg'=>'Necesita loguearse para agregar al carrito.',
                                                                         'model'=>$model,
                                                                         'temporada'=>$descripciontemporada,
                                                                         'categoria'=>$descripcionCategoria,
                                                                         'subcategoria'=>$descripcionSubCategoria,
                                                                         'componentes'=>$componentes, 
                                                                         'tipo'=>0]);
                    
                }
        }
    }

    public function actionBorrarcarrito(){
        if (Yii::$app->request->post()){
            $idPrenda = Html::encode($_POST["id"]);
            $idUsuario = Yii::$app->session['idUsuario'];
            
            //PrendaPersonalizada::borrarPersonalizado($idUsuario, $idPrenda);
            //Prenda::borrarPrenda($idPrenda);
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
