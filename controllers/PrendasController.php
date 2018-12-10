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
use app\models\UploadForm;
use yii\web\UploadedFile;


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

        return $this->render('agregarPrenda',['model'=>$model,
                                                'listCategorias'=>$listCategorias,
                                                'listSubCategorias'=>$listSubCategorias,
                                                'listTemporadas'=>$listTemporadas, 
                                                'msg'=>"",'cargada'=>"NO",
                                                'im1'=>"",
                                                'im2'=>""]);
        
    }
    public function actionSaveprenda(){
        $model = new Prenda();

        if (Yii::$app->request->post()) {
            
            $model->urlImagen = Html::encode($_FILES["imagenCompleta"]['name']);
            $model->urlImagenMiniatura = Html::encode($_FILES["imagenMiniatura"]['name']);

            $file1 = $_FILES['imagenCompleta']['name'];
            $file2 = $_FILES['imagenMiniatura']['name'];

            $expensions= array("jpeg","jpg","png");
    
            if(!is_dir("../files/clothes/")) {
                mkdir("../files/clothes/", 0777);
            }
                
            $urlNueva = "../files/clothes/".$file1;
            $urlNueva2 = "../files/clothes/".$file2;

            move_uploaded_file($_FILES['imagenCompleta']['tmp_name'],$urlNueva);
            move_uploaded_file($_FILES['imagenMiniatura']['tmp_name'],$urlNueva2);

            $categorias = Categoria::obtenerCategorias();
            $listCategorias=ArrayHelper::map($categorias,'id','descripcion');
            $subCategorias = SubCategoria::obtenerSubCategorias();
            $listSubCategorias = ArrayHelper::map($subCategorias,'id','descripcion');
            $temporadas = Temporada::obtenerTemporadas();
            $listTemporadas=ArrayHelper::map($temporadas,'id','tipoTemporada');

            $im1 = dirname( dirname(__FILE__) ) . "/files/clothes/".$file1;
            $im2 = dirname( dirname(__FILE__) ) . "/files/clothes/".$file2;
            return $this->render('agregarPrenda',['model'=>$model,
                                                'listCategorias'=>$listCategorias,
                                                'listSubCategorias'=>$listSubCategorias,
                                                'listTemporadas'=>$listTemporadas, 
                                                'msg'=>"",'cargada'=>"SI",
                                                'im1'=>$im1,
                                                'im2'=>$im2]);
        }
    }
    public function actionGuardarprenda(){
        $model = new Prenda();
        if ($model->load(Yii::$app->request->post())){
            $fechaAlta = date("Y-m-d");
            $model->fechaAlta = $fechaAlta;

            if($model->save(false)){
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
                                                'msg'=>"Prenda guardada correctamente.",
                                                'cargada'=>"",
                                                'im1'=>"",
                                                'im2'=>""]);
            }else{
                $categorias = Categoria::obtenerCategorias();
                $listCategorias=ArrayHelper::map($categorias,'id','descripcion');
                $subCategorias = SubCategoria::obtenerSubCategorias();
                $listSubCategorias = ArrayHelper::map($subCategorias,'id','descripcion');
                $temporadas = Temporada::obtenerTemporadas();
                $listTemporadas=ArrayHelper::map($temporadas,'id','tipoTemporada');
                $msg="Ocurrió un problema al guardar la prenda, vuelva a intentarlo.";
                $cargada = "NO";

                return $this->render('agregarPrenda',['model'=>$model,
                                                'listCategorias'=>$listCategorias,
                                                'listSubCategorias'=>$listSubCategorias,
                                                'listTemporadas'=>$listTemporadas, 
                                                'msg'=>"Ocurrió un problema al guardar la prenda, vuelva a intentarlo.",
                                                'cargada'=>"NO",
                                                'im1'=>"",
                                                'im2'=>""]);    
            }
        }
    }

    

   
}