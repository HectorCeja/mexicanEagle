<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Prenda;
use app\models\Categoria;
use app\models\SubCategoria;
use app\models\Temporada;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


class PrendasController extends Controller
{
    
    public function actionPrendas(){
        $prendas = Prenda::obtenerPrendas();
        $urlbase=Url::base(true);
        return $this->render('prendas',['model'=>$prendas,'urlbase'=>$urlbase]);
    }

    public function actionMostrardetalle(){
        if(Yii::$app->request->get("id")){
            $idPrenda = Html::encode($_GET["id"]);
            if((int)$idPrenda){
             $model = Prenda::findOne($idPrenda);
             $descripciontemporada= Temporada::findOne($model->idTemporada)->tipoTemporada;
             $descripcionCategoria = Categoria::findOne($model->idCategoria)->descripcion;
             $descripcionSubCategoria = SubCategoria::findOne($model->idSubCategoria)->descripcion;
             return $this->render('prenda',['model'=>$model,'temporada'=>$descripciontemporada
             ,'categoria'=>$descripcionCategoria,'subcategoria'=>$descripcionSubCategoria]);
            }
        }else{
             $prendas = Prenda::obtenerPrendas();
             return $this->render('prendas',['model'=>$prendas]);
        }
    }
    public function actionAgregar(){
        $model = new Prenda();
        $categorias = Categoria::obtenerCategorias();
        $listCategorias=ArrayHelper::map($categorias,'id','descripcion');
        $subCategorias = SubCategoria::obtenerSubCategorias();
        $listSubCategorias = ArrayHelper::map($subCategorias,'id','descripcion');
        $temporadas = Temporada::obtenerTemporadas();
        $listTemporadas=ArrayHelper::map($temporadas,'id','tipoTemporada');
        $msg="";
        return $this->render('agregarPrenda',['model'=>$model,
                                                'listCategorias'=>$listCategorias,
                                                'listSubCategorias'=>$listSubCategorias,
                                                'listTemporadas'=>$listTemporadas, 
                                                'msg'=>$msg]);
    }
    public function actionGuardarprenda(){
        $model = new Prenda();
        if ($model->load(Yii::$app->request->post())){
            $fechaAlta = date("Y-m-d");
            $model->fechaAlta = $fechaAlta;
            if($model->save(false)){
                $msg="Prenda guardada correctamente.";
                $categorias = Categoria::obtenerCategorias();
                $listCategorias=ArrayHelper::map($categorias,'id','descripcion');
                $subCategorias = SubCategoria::obtenerSubCategorias();
                $listSubCategorias = ArrayHelper::map($subCategorias,'id','descripcion');
                $temporadas = Temporada::obtenerTemporadas();
                $listTemporadas=ArrayHelper::map($temporadas,'id','tipoTemporada');
                return $this->render('agregarPrenda',['model'=>$model,
                                                'listCategorias'=>$listCategorias,
                                                'listSubCategorias'=>$listSubCategorias,
                                                'listTemporadas'=>$listTemporadas, 
                                                'msg'=>$msg]);
            }else{
                $categorias = Categoria::obtenerCategorias();
                $listCategorias=ArrayHelper::map($categorias,'id','descripcion');
                $subCategorias = SubCategoria::obtenerSubCategorias();
                $listSubCategorias = ArrayHelper::map($subCategorias,'id','descripcion');
                $temporadas = Temporada::obtenerTemporadas();
                $listTemporadas=ArrayHelper::map($temporadas,'id','tipoTemporada');
                $msg="Ocurrió un problema al guardar la prenda, vuelva a intentarlo.";
                return $this->render('agregarPrenda',['model'=>$model,
                                                'listCategorias'=>$listCategorias,
                                                'listSubCategorias'=>$listSubCategorias,
                                                'listTemporadas'=>$listTemporadas, 
                                                'msg'=>$msg]);    
            }
        }
        /*if(Yii::$app->request->post()){
            $email = Html::encode($_POST["nombre"]);
            $password = Html::encode($_POST["tipoPrenda"]);

            $user=User::findByEmail($email);
            $user->setPassword($password);
            if($user->update(false)){
                $msg="Te has registrado correctamente.";
                return $this->render('agregarPrenda',['model'=>$model,'listCategorias'=>$listCategorias,'listSubCategorias'=>$listSubCategorias]);
            }else{

                $msg="Ocurrió un problema, vuelva a intentarlo.";
                $model = new LoginForm(); 
                return $this->render('agregarPrenda',['model'=>$model,'listCategorias'=>$listCategorias,'listSubCategorias'=>$listSubCategorias]);
            }
        }*/
    }

    

   
}