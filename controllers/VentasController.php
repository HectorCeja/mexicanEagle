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
        $venta = new Venta();
        if ($venta->load(Yii::$app->request->post())){
            $total = Carrito::totalCarrito(Yii::$app->session['idUsuario']);
            $idTipoPago = (int) Yii::$app->request->post()['Venta']['idTipoPago'];
            $idDireccion=Yii::$app->session['idDireccion'];
            $idUsuario=Yii::$app->session['idUsuario'];

            $venta=Venta::guardarVenta($total,$idUsuario,$idDireccion,$idTipoPago);

            
            $carrito = Carrito::obtenerCarritoPorUsuario(Yii::$app->session['idUsuario']);
            $prendasCarrito = Carrito::obtenerPrendasPorUsuario(Yii::$app->session['idUsuario']);
            VentaDetalle::guardarVentasDetalle($carrito,$prendasCarrito,$venta->folio);
           
            if ($idTipoPago == 1) {
                $pago = new Pago();
                $pago->setIdFolio($folio);
                $pago->setTotal($total);
                $pago->setSubtotal($total * 0.84);
                $pago->setIva($total * 0.16);
                $pago->setFechaPago($fechaActual);
                $pago->save();
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
            
            return $this->render('//site/index',[
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

                if(Yii::$app->session['idUsuario'] != null){
                    $existe = Carrito::obtenerUsuarioPrenda($idUsuario,$idPrenda);

                    if(isset($_POST['idcomponente'])){ //personalizada
                        $ids = explode("|", $_POST['idcomponente']);
                        $fechaAlta = date("Y-m-d");

                        $prendaAPersonalizar = Prenda::findOne($idPrenda);

                        $prendaNueva = new Prenda();
                        $prendaNueva->tipoPrenda = "PERSONALIZADA";
                        $prendaNueva->fechaAlta = $fechaAlta;
                        $prendaNueva->nombre = $prendaAPersonalizar->nombre;
                        $prendaNueva->precio = $prendaAPersonalizar->precio;
                        $prendaNueva->descripcion = $prendaAPersonalizar->descripcion;
                        $prendaNueva->idTemporada = $prendaAPersonalizar->idTemporada;
                        $prendaNueva->urlImagen = $prendaAPersonalizar->urlImagen;
                        $prendaNueva->urlImagenMiniatura = '';
                        $prendaNueva->idCategoria = $prendaAPersonalizar->idCategoria;
                        $prendaNueva->idSubCategoria = $prendaAPersonalizar->idSubCategoria;

                        $prendaNueva->save(false);

                        $idPrendaPersonalizada = $prendaNueva->id;

                        $folioPrendaPersonalizada = PrendaPersonalizada::obtenerFolio();
                        foreach($ids as $id){

                            $componentePersonalizar = new PrendaPersonalizada();
                            $componentePersonalizar->id = $folioPrendaPersonalizada;
                            $componentePersonalizar->idUsuario = $idUsuario;
                            $componentePersonalizar->idPrenda = $idPrenda;

                            $componentePersonalizar->idComponente = $id;
                            $componentePersonalizar->fechaAlta = $fechaAlta;
                            $componentePersonalizar->save();

                        }

                        $prendaPersonalizadaNueva = new Carrito();
                        $prendaPersonalizadaNueva->idPrenda = $idPrendaPersonalizada;
                        $prendaPersonalizadaNueva->idUsuario = $idUsuario;
                        $prendaPersonalizadaNueva->cantidad = 1;
                        $prendaPersonalizadaNueva->talla = $talla;
                        $prendaPersonalizadaNueva->color = $color;
                        $prendaPersonalizadaNueva->save();

                        $model = Prenda::findOne($idPrenda);

                        $descripciontemporada= Temporada::findOne($model->idTemporada)->tipoTemporada;
                        $componentes = Componente::obtenerComponentesPrenda($idPrenda);
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
                            $prendaBasicaNueva = new Carrito();
                            $prendaBasicaNueva->idUsuario =$idUsuario;
                            $prendaBasicaNueva->idPrenda = $idPrenda;
                            $prendaBasicaNueva->talla = $talla;
                            $prendaBasicaNueva->color = $color;
                            $prendaBasicaNueva->cantidad=1; 
                            $prendaBasicaNueva->save();
                            $model = Prenda::findOne($idPrenda);
                            $descripciontemporada= Temporada::findOne($model->idTemporada)->tipoTemporada;
                            $componentes = Componente::obtenerComponentesPrenda($idPrenda);
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
                            $model = Prenda::findOne($idPrenda);
                            $descripciontemporada= Temporada::findOne($model->idTemporada)->tipoTemporada;
                            $componentes = Componente::obtenerComponentesPrenda($idPrenda);
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
