<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
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
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        $msg="";
        return $this->render('login', [
            'model' => $model,'msg'=>$msg
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
        $tipo= 0;
        return $this->render('ingresarcontrasenia',['msg'=>$msg,'tipo'=>$tipo]);
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
                    $msg="Correo no registrado";
                    $model = new LoginForm(); 
                    $tipo=1;
                    return $this->render('ingresarcontrasenia',['msg'=>$msg,'tipo'=>$tipo]);
                }
            }else{
                $msg="Correo no registrado";
                $model = new LoginForm(); 
                $tipo=1;
                return $this->render('ingresarcontrasenia',['msg'=>$msg,'tipo'=>$tipo]);
            }      
        }
    }

   
}
