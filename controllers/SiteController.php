<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Prenda;
use app\models\Componente;
use app\models\User;
use app\models\Cliente;
use app\models\Categoria;
use app\models\SubCategoria;
use app\models\Temporada;
use app\models\entities\EntitySubCategoria;
use app\models\entities\EntityCategoria;
use app\models\entities\EntityTemporadas;
use app\models\Prospectos;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;



class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */

    public function actionRequest(){

    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionPrendas(){
        return $this->render('prendas');
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
    public function actionComponentes(){
        return $this->render('componentes');
    }
    public function actionAgregarcomponente(){
        $model = new Componente();
        return $this->render('agregarComponente',['model'=>$model]);
    }
    public function actionAceptacion()
    {
        $model= Prospectos::obtenerEnEspera();
        $msg=null;
        $tipo=3;
        return $this->render('aceptacion',['model'=>$model,'msg'=>$msg,'tipo'=>$tipo]);
    }

    public function actionIngresar()
    {
        $msg = "";
        return $this->render('ingresarcontrasenia',['msg'=>$msg]);
    }

    public function actionCambiar(){
        if(Yii::$app->request->post()){
            $email = Html::encode($_POST["email"]);
            $password = Html::encode($_POST["password"]);

            $user=User::findByEmail($email);
            $user->setPassword($password);
            if($user->update(false)){
                $msg="Te has registrado correctamente.";
                return $this->render('ingresarcontrasenia',['msg'=>$msg]);
            }else{

                $msg="Ocurrió un problema, vuelva a intentarlo.";
                $model = new LoginForm(); 
                return $this->render('ingresarcontrasenia',['msg'=>$msg]);
            }
        }
    }

    public function actionDelete(){
        if(Yii::$app->request->post()){
            $msg=null;
            $tipo=0;
            $id = Html::encode($_POST["id"]);
            if((int)$id){
                $prospect=Prospectos::findOne($id);
                $prospect->estatus='RECHAZADO';
                if($prospect->update()){
                    $model = Prospectos::obtenerEnEspera();
                    $msg="Prospecto borrado correctamente";
                    $tipo=1;
                    return $this->render('aceptacion',['model'=>$model,'msg'=>$msg,'tipo'=>$tipo]);
                }else{
                    $model = Prospectos::obtenerEnEspera();
                    $msg="Prospecto no pudo ser borrado";
                    return $this->render('aceptacion',['model'=>$model,'msg'=>$msg,'tipo'=>$tipo]);
                }
            }else{
                $model = Prospectos::obtenerEnEspera();
                $msg="Prospecto no pudo ser borrado";
                return $this->render('aceptacion',['model'=>$model,'msg'=>$msg,'tipo'=>$tipo]);
            }
        }
    }
    public function actionAceptar(){
        $msg=null;
        $tipo=0;
        if(Yii::$app->request->get("id")){
              $idProspect = Html::encode($_GET["id"]);
              if((int)$idProspect){
                $prospecto = Prospectos::findOne($idProspect);
                $user = new User();
                $user->email=$prospecto->email;
                $user->password="";
                $user->activo = 1;
                $user->idPerfil = 1;

                $cliente = new Cliente();
                $cliente->nombre = $prospecto->nombre;
                $cliente->apellidoPaterno = $prospecto->apellidoPaterno;
                $cliente->apellidoMaterno = $prospecto->apellidoMaterno;
                $cliente->numeroTelefono = $prospecto->numeroTelefono;
                $cliente->pais= $prospecto->pais;
                $cliente->ciudad= $prospecto->ciudad;
                $cliente->rfc= $prospecto->rfc;
                $cliente->fechaNacimiento= $prospecto->fechaNacimiento;

                $user->save(false);
                
                $cliente->idUsuario=$user->id;
                $cliente->save(false);

                $prospecto->estatus='ACEPTADO';
                $prospecto->update();

                $contact = new ContactForm();

                ContactForm::contactProspect(Yii::$app->params['adminEmail'],$user->email, $cliente->nombre);

                $model = Prospectos::obtenerEnEspera();
                $msg="Prospecto fue aceptado ";
                return $this->render('aceptacion',['model'=>$model,'msg'=>$msg,'tipo'=>1]);
            }
        }else{
            $model = Prospectos::find()->obtenerEnEspera();
            $msg="Entro por otro lado";
            return $this->render('aceptacion',['model'=>$model,'msg'=>$msg,'tipo'=>$tipo]);
        }      
    }

    public function actionRegister(){
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new Prospectos();
        if ($model->load(Yii::$app->request->post())){
            if($model->validate()){
                $model->save();
                Yii::$app->session->setFlash('success','Has sido registrado como prospecto, espere un correo de aceptación.');
                return $this->redirect(['site/login']);
            }
        }
        return $this->render('signup',[
            'model' => $model,
        ]);

    }
}
