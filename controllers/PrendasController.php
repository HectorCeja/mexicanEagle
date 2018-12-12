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
use app\models\FormSearch;
use yii\web\UploadedFile;


class PrendasController extends Controller
{

    public function actionPrendas(){
        $prendas = Prenda::obtenerPrendas();
        $urlbase=Url::base(true);
        return $this->render('prendas',['model'=>$prendas,'urlbase'=>$urlbase, 'msg'=>"",'tipo'=>""]);
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
             ,'categoria'=>$descripcionCategoria,'subcategoria'=>$descripcionSubCategoria,'componentes'=>$componentes,
             'msg'=>'','tipo'=>'']);
            }
        }else{
             $prendas = Prenda::obtenerPrendas();
             return $this->render('prendas',['model'=>$prendas,'urlbase'=>Url::base(true),'msg'=>"",'tipo'=>'']);
        }
        
    }
    public function actionMostrarprenda(){
        if(Yii::$app->request->get("id")){
            $idPrenda = Html::encode($_GET["id"]);
            if((int)$idPrenda){
             $model = Prenda::findOne($idPrenda);
             Yii::$app->session['idPrenda'] = $idPrenda;
             $componentes = Componente::obtenerComponentesPrenda($idPrenda);
             $descripciontemporada= Temporada::findOne($model->idTemporada)->tipoTemporada;
             $descripcionCategoria = Categoria::findOne($model->idCategoria)->descripcion;
             $descripcionSubCategoria = SubCategoria::findOne($model->idSubCategoria)->descripcion;
             return $this->render('prendaPersonalizar',['model'=>$model,
                                                        'temporada'=>$descripciontemporada,
                                                        'categoria'=>$descripcionCategoria,
                                                        'subcategoria'=>$descripcionSubCategoria,
                                                        'componentes'=>$componentes,
                                                        'tipo'=>'',
                                                        'msg'=>""]);
            }
        }else{
             $prendas = Prenda::obtenerPrendas();
             return $this->render('buscar',['model'=>$prendas,'urlbase'=>Url::base(true),'msg'=>""]);
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
                                                'im1'=>""]);
    }

    public function actionAgregarimagen(){
        $model = new Prenda();

        if (Yii::$app->request->post()) {
            
            $model->urlImagen = Html::encode($_FILES["imagenCompleta"]['name']);

            $file1 = $_FILES['imagenCompleta']['name'];

            $expensions= array("jpeg","jpg","png");
    
            if(!is_dir("../web/files/clothes/")) {
                mkdir("../web/files/clothes/", 0777);
            }
                
            $urlNueva = "../web/files/clothes/".$file1;

            move_uploaded_file($_FILES['imagenCompleta']['tmp_name'],$urlNueva);

            $categorias = Categoria::obtenerCategorias();
            $listCategorias=ArrayHelper::map($categorias,'id','descripcion');
            $subCategorias = SubCategoria::obtenerSubCategorias();
            $listSubCategorias = ArrayHelper::map($subCategorias,'id','descripcion');
            $temporadas = Temporada::obtenerTemporadas();
            $listTemporadas=ArrayHelper::map($temporadas,'id','tipoTemporada');

            $im1 = "/files/clothes/".$file1;
            return $this->render('agregarPrenda',['model'=>$model,
                                                'listCategorias'=>$listCategorias,
                                                'listSubCategorias'=>$listSubCategorias,
                                                'listTemporadas'=>$listTemporadas, 
                                                'msg'=>"",'cargada'=>"SI",
                                                'im1'=>$im1]);
        } else if(Yii::$app->request->get()){
            return $this->render('agregarPrenda',['model'=>$model,
                                                'listCategorias'=>'',
                                                'listSubCategorias'=>'',
                                                'listTemporadas'=>'', 
                                                'msg'=>"",'cargada'=>"NO",
                                                'im1'=>'']);
        }
    }

    public function actionGuardarprenda(){
        $model = new Prenda();
        if ($model->load(Yii::$app->request->post())){

            if(Prenda::guardarPrenda($model, $_POST['Prenda']['tipoPrenda']) !==false){
                $prendas = Prenda::obtenerPrendas();
                return $this->render('prendas',['model'=>$prendas,
                                                'urlbase'=>Url::base(true), 
                                                'msg'=>"Prenda guardada correctamente.",
                                                'tipo'=>1]);
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
                                                'im1'=>""]);    
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
                                                           'im1'=>""]);
            }else{
                $model = Prenda::findOne($idPrenda);
                $descripciontemporada= Temporada::findOne($model->idTemporada)->tipoTemporada;
                $descripcionCategoria = Categoria::findOne($model->idCategoria)->descripcion;
                $descripcionSubCategoria = SubCategoria::findOne($model->idSubCategoria)->descripcion;
                return $this->render('prenda',['model'=>$model,'temporada'=>$descripciontemporada
                ,'categoria'=>$descripcionCategoria,'subcategoria'=>$descripcionSubCategoria,
                'msg'=>'','tio'=>'']);
            }
        }
    }

    public function actionAgregarimagencomponente(){
        $model = new Componente();

        if (Yii::$app->request->post()) {
            
            $model->urlImagen = Html::encode($_FILES["componenteCompleto"]['name']);

            $file1 = $_FILES['componenteCompleto']['name'];

            $expensions= array("jpeg","jpg","png");
    
            if(!is_dir("../web/files/components/")) {
                mkdir("../web/files/components/", 0777);
            }
                
            $urlNueva = "../web/files/components/".$file1;

            move_uploaded_file($_FILES['componenteCompleto']['tmp_name'],$urlNueva);

            $im1 = "/files/components/".$file1;
            $idPrenda = Yii::$app->session['idPrenda'];
            return $this->render('agregarComponente',['model'=>$model,
                                                      'id'=>$idPrenda,
                                                      'msg'=>"",'cargada'=>"SI",
                                                      'im1'=>$im1]);
        }elseif(Yii::$app->request->get()){
            return $this->render('agregarComponente',['model'=>$model,
                                                'listCategorias'=>'',
                                                'listSubCategorias'=>'',
                                                'listTemporadas'=>'', 
                                                'msg'=>"",'cargada'=>"NO",
                                                'im1'=>'']);
        }
    }

    public function actionGuardarcomponente(){
        $model = new Componente();
        if ($model->load(Yii::$app->request->post())){
                $idPrenda = Yii::$app->session['idPrenda'];
                if(Componente::guardarComponente($model,$idPrenda) !==false){
                        $model = Prenda::findOne($idPrenda);
                        $descripciontemporada= Temporada::findOne($model->idTemporada)->tipoTemporada;
                        $componentes = Componente::obtenerComponentesPrenda($idPrenda);
                        $descripcionCategoria = Categoria::findOne($model->idCategoria)->descripcion;
                        $descripcionSubCategoria = SubCategoria::findOne($model->idSubCategoria)->descripcion;
                        return $this->render('prenda',['model'=>$model,'temporada'=>$descripciontemporada
                        ,'categoria'=>$descripcionCategoria,'subcategoria'=>$descripcionSubCategoria,'componentes'=>$componentes,
                        'msg'=>"El componente fue dado de alta exitósamente.", 'tipo'=>'1']);
                }else{
                    $msg="Ocurrió un problema al guardar el componente, vuelva a intentarlo.";
                    return $this->render('agregarComponente', ['model'=>$model, 
                                                               'msg'=>$msg,
                                                               'id'=>$model->idPrenda,
                                                               'cargada'=>'NO',
                                                               'im1'=>"",'tipo'=>'']);
                }
        }
    }
}
