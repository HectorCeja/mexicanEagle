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
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\User;

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
        $modelPerfilOpcion = new PerfilOpcion();
        $modelOpcion = new Opcion();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = $model->getUser();
            $opcionesPorPerfil = $modelPerfilOpcion->obtenerOpcionesPorPerfil($user->idPerfil);
            $listOpcionesPorPerfil = ArrayHelper::map($opcionesPorPerfil, 'idOpcion', 'idOpcion');
            $opciones = $modelOpcion->obtenerOpciones($listOpcionesPorPerfil);
            $listOpciones = ArrayHelper::map($opciones, 'descripcion', 'url');
            Yii::$app->session['opciones'] = $listOpciones;
            return $this->render('index', [
                'model' => $model
            ]);
        }
        
        return $this->render('login', [
            'model' => $model, 
            'msg' => ""
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
        $msg = "";
        return $this->render('ingresarcontrasenia',['msg'=>$msg]);
    }

    public function actionCambiar(){
        if(Yii::$app->request->post()){
            $email = Html::encode($_POST["email"]);
            $password = Html::encode($_POST["password"]);

            $user=User::findByEmail($email);
            if($user!=null){
                $user->setPassword($password);
                if($user->update(false)){
                    $msg="Te has registrado correctamente.";
                    $model = new LoginForm();
                    return $this->render('login',['model'=>$model,'msg'=>$msg]);
                }else{

                    $msg="Ocurrió un problema, vuelva a intentarlo.";
                    $model = new LoginForm(); 
                    return $this->render('ingresarcontrasenia',['msg'=>$msg]);
                }
            }else{
                $msg="Ocurrió un problema, vuelva a intentarlo.";
                $model = new LoginForm(); 
                return $this->render('ingresarcontrasenia',['msg'=>$msg]);
            }
            
        }
    }

   
}
