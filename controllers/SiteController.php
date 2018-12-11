<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\PerfilOpcion;
use app\models\Opcion;
use app\models\Prenda;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\User;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Url;


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
        $data = Prenda::find()
        ->select(['nombre as value', 'nombre as  label','id as id'])
        ->asArray()
        ->all();
        $prendas = Prenda::obtenerPrendasSite();
        $urlbase = Url::base(true);
        $prendasTemporada = Prenda::obtenerPrendasPorTemporadas();
        Yii::$app->session['prenda'] = $data;
        return $this->render('index',['model'=>$data, 'data'=>$data, 'prendas'=>$prendas, 'urlbase'=>$urlbase, 'prendasTemporada'=>$prendasTemporada]);
    }

    public function actionBuscar()
    {
        if(Yii::$app->request->post()){
            $campoBusqueda = Html::encode($_POST["Prenda"]);

            $prendas = Prenda::obtenerPrendasBuscador($campoBusqueda);
            if($prendas!=null){
                if($campoBusqueda!=""){
                    $urlbase=Url::base(true);
                    return $this->render('buscar',['model'=>$prendas,'msg'=>"", 'urlbase'=>$urlbase]);
                }else{
                    $msg="No se ingresaron datos para buscar.";
                    return $this->render('buscar',['model'=>'','msg'=>$msg,'urlbase'=>'']);
                }
            }else{
                $msg="No hay resultados en la búsqueda.";
                return $this->render('buscar',['model'=>'','msg'=>$msg,'urlbase'=>'']);
            }      
        }
        
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
        $modelPerfilOpcion = new PerfilOpcion();
        $modelOpcion = new Opcion();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = $model->getUser();
            $opcionesPorPerfil = $modelPerfilOpcion->obtenerOpcionesPorPerfil($user->idPerfil);
            $listOpcionesPorPerfil = ArrayHelper::map($opcionesPorPerfil, 'idOpcion', 'idOpcion');
            $opciones = $modelOpcion->obtenerOpcionesPorIds($listOpcionesPorPerfil);
            $listOpciones = ArrayHelper::map($opciones, 'descripcion', 'url');
            Yii::$app->session['idUsuario'] = $user->id;
            Yii::$app->session['emailUsuario'] = $user->email;
            Yii::$app->session['opciones'] = $listOpciones;
            $prendasTemporada = Prenda::obtenerPrendasPorTemporadas();
            $prendas = Prenda::obtenerPrendasSite();
            return $this->render('index', [
                'model' => $model,
                'prendasTemporada'=>$prendasTemporada,
                'prendas'=>$prendas
            ]);
        }
        
        return $this->render('login', [
            'model' => $model, 
            'msg' => "",
            'tipo' => 0,
            'prendasTemporada'=>''
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
    public function actionIngresar()
    {
        return $this->render('ingresarcontrasenia',['msg'=>"",'tipo'=>0]);
    }

    public function actionCambiar(){
        if(Yii::$app->request->post()){
            $email = Html::encode($_POST["email"]);
            $password = Html::encode($_POST["password"]);
            $user = User::findByEmail($email);
            if($user!=null){
                if(User::cambiarContraseña($user,$password) !==false){
                    $msg="Te has registrado correctamente, inicia sesión.";
                    $model = new LoginForm();
                    return $this->render('login',['model'=>$model,'msg'=>$msg,'tipo'=>1]);
                }else{
                    $msg="Correo no registrado, verifique sus datos.";
                    $model = new LoginForm(); 
                    return $this->render('ingresarcontrasenia',['msg'=>$msg,'tipo'=>1]);
                }
            }else{
                $msg="El correo ingresado no es correcto, verifique sus datos.";
                $model = new LoginForm(); 
                return $this->render('ingresarcontrasenia',['msg'=>$msg,'tipo'=>1]);
            }      
        }
    }
}
