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
use app\models\Componente;
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
        return $this->render('prendas',['model'=>$prendas,'urlbase'=>$urlbase, 'msg'=>""]);
    }

    public function actionMostrardetalle(){
        if(Yii::$app->request->get("id")){
            $idPrenda = Html::encode($_GET["id"]);
            if((int)$idPrenda){
             $model = Prenda::findOne($idPrenda);
             Yii::$app->session['idPrenda'] = $idPrenda;
             $componentes = Componente::obtenerComponentesPrenda($idPrenda);
             $descripciontemporada= Temporada::findOne($model->idTemporada)->tipoTemporada;
             $descripcionCategoria = Categoria::findOne($model->idCategoria)->descripcion;
             $descripcionSubCategoria = SubCategoria::findOne($model->idSubCategoria)->descripcion;
             return $this->render('prenda',['model'=>$model,'temporada'=>$descripciontemporada
             ,'categoria'=>$descripcionCategoria,'subcategoria'=>$descripcionSubCategoria,'componentes'=>$componentes]);
            }
        }else{
             $prendas = Prenda::obtenerPrendas();
             return $this->render('prendas',['model'=>$prendas,'urlbase'=>Url::base(true),'msg'=>""]);
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
    
            if(!is_dir("../web/files/clothes/")) {
                mkdir("../web/files/clothes/", 0777);
            }
                
            $urlNueva = "../web/files/clothes/".$file1;
            $urlNueva2 = "../web/files/clothes/".$file2;

            move_uploaded_file($_FILES['imagenCompleta']['tmp_name'],$urlNueva);
            move_uploaded_file($_FILES['imagenMiniatura']['tmp_name'],$urlNueva2);

            $categorias = Categoria::obtenerCategorias();
            $listCategorias=ArrayHelper::map($categorias,'id','descripcion');
            $subCategorias = SubCategoria::obtenerSubCategorias();
            $listSubCategorias = ArrayHelper::map($subCategorias,'id','descripcion');
            $temporadas = Temporada::obtenerTemporadas();
            $listTemporadas=ArrayHelper::map($temporadas,'id','tipoTemporada');

            $im1 = "/files/clothes/".$file1;
            $im2 = "/files/clothes/".$file2;
            return $this->render('agregarPrenda',['model'=>$model,
                                                'listCategorias'=>$listCategorias,
                                                'listSubCategorias'=>$listSubCategorias,
                                                'listTemporadas'=>$listTemporadas, 
                                                'msg'=>"",'cargada'=>"SI",
                                                'im1'=>$im1,
                                                'im2'=>$im2]);
        }elseif(Yii::$app->request->get()){
            return $this->render('agregarPrenda',['model'=>$model,
                                                'listCategorias'=>'',
                                                'listSubCategorias'=>'',
                                                'listTemporadas'=>'', 
                                                'msg'=>"",'cargada'=>"NO",
                                                'im1'=>'',
                                                'im2'=>'']);
        }
    }

    public function actionGuardarprenda(){
        $model = new Prenda();
        if ($model->load(Yii::$app->request->post())){
            $fechaAlta = date("Y-m-d");
            $model->fechaAlta = $fechaAlta;

            if($model->save(false)){
                $prendas = Prenda::obtenerPrendas();
                return $this->render('prendas',['model'=>$prendas,
                                                'urlbase'=>Url::base(true), 
                                                'msg'=>"Prenda guardada correctamente."]);
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

    public function actionAgregarcomponente(){
        $model = new Componente();
        if(Yii::$app->request->get("id")){
            $idPrenda = Html::encode($_GET["id"]);
            if((int)$idPrenda){
                return $this->render('agregarComponente', ['model'=>$model, 
                                                           'msg'=>"",
                                                           'id'=>$idPrenda,
                                                           'cargada'=>'NO',
                                                           'im1'=>"",
                                                           'im2'=>""]);
            }else{
                $model = Prenda::findOne($idPrenda);
                $descripciontemporada= Temporada::findOne($model->idTemporada)->tipoTemporada;
                $descripcionCategoria = Categoria::findOne($model->idCategoria)->descripcion;
                $descripcionSubCategoria = SubCategoria::findOne($model->idSubCategoria)->descripcion;
                return $this->render('prenda',['model'=>$model,'temporada'=>$descripciontemporada
                ,'categoria'=>$descripcionCategoria,'subcategoria'=>$descripcionSubCategoria]);
            }
        }
    }

    public function actionSavecomponente(){
        $model = new Componente();

        if (Yii::$app->request->post()) {
            
            $model->urlImagen = Html::encode($_FILES["componenteCompleto"]['name']);
            $model->urlImagenMiniatura = Html::encode($_FILES["imagenMiniatura"]['name']);

            $file1 = $_FILES['componenteCompleto']['name'];
            $file2 = $_FILES['imagenMiniatura']['name'];

            $expensions= array("jpeg","jpg","png");
    
            if(!is_dir("../web/files/components/")) {
                mkdir("../web/files/components/", 0777);
            }
                
            $urlNueva = "../web/files/components/".$file1;
            
            $urlNueva2 = "../web/files/components/".$file2;

            move_uploaded_file($_FILES['componenteCompleto']['tmp_name'],$urlNueva);
            move_uploaded_file($_FILES['imagenMiniatura']['tmp_name'],$urlNueva2);

            $im1 = "/files/components/".$file1;
            $im2 = "/files/components/".$file2;
            $idPrenda = Yii::$app->session['idPrenda'];
            return $this->render('agregarComponente',['model'=>$model,
                                                      'id'=>$idPrenda,
                                                      'msg'=>"",'cargada'=>"SI",
                                                      'im1'=>$im1,
                                                      'im2'=>$im2]);
        }elseif(Yii::$app->request->get()){
            return $this->render('agregarComponente',['model'=>$model,
                                                'listCategorias'=>'',
                                                'listSubCategorias'=>'',
                                                'listTemporadas'=>'', 
                                                'msg'=>"",'cargada'=>"NO",
                                                'im1'=>'',
                                                'im2'=>'']);
        }
    }

    public function actionGuardarcomponente(){
        $model = new Componente();
        if ($model->load(Yii::$app->request->post())){
                $fechaAlta = date("Y-m-d");
                $precio = 0.0;
                $model->fechaAlta = $fechaAlta;
                $model->precio = $precio;
                $idPrenda = Yii::$app->session['idPrenda'];
                $model->idPrenda=$idPrenda;
                if($model->save(false)){
                        $model = Prenda::findOne($idPrenda);
                        $descripciontemporada= Temporada::findOne($model->idTemporada)->tipoTemporada;
                        $componentes = Componente::obtenerComponentesPrenda($idPrenda);
                        $descripcionCategoria = Categoria::findOne($model->idCategoria)->descripcion;
                        $descripcionSubCategoria = SubCategoria::findOne($model->idSubCategoria)->descripcion;
                        return $this->render('prenda',['model'=>$model,'temporada'=>$descripciontemporada
                        ,'categoria'=>$descripcionCategoria,'subcategoria'=>$descripcionSubCategoria,'componentes'=>$componentes]);
                }else{
                    $msg="Ocurrió un problema al guardar la prenda, vuelva a intentarlo.";
                    return $this->render('agregarComponente', ['model'=>$model, 
                                                               'msg'=>$msg,
                                                               'id'=>$model->idPrenda,
                                                               'cargada'=>'NO',
                                                               'im1'=>"",
                                                               'im2'=>""]);
                }
        }
    }
}
