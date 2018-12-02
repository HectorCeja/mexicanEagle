<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\Prospectos;
use yii\helpers\Html;

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
    public function actionAceptacion()
    {
        $model= Prospectos::find()->where(['estatus'=>1])->all();
        $msg=null;
        $tipo=3;
        return $this->render('aceptacion',['model'=>$model,'msg'=>$msg,'tipo'=>$tipo]);
    }
    public function actionDelete(){
        if(Yii::$app->request->post()){
            $msg=null;
            $tipo=0;
            $id = Html::encode($_POST["id"]);
            if((int)$id){
                $prospect=Prospectos::findOne($id);
                $prospect->estatus=3;
                if($prospect->update()){
                    $model = Prospectos::find()->where(['estatus'=>1])->all();
                    $msg="Prospecto borrado correctamente";
                    $tipo=1;
                    return $this->render('aceptacion',['model'=>$model,'msg'=>$msg,'tipo'=>$tipo]);
                }else{
                    $model = Prospectos::find()->where(['estatus'=>1])->all();
                    $msg="Prospecto no pudo ser borrado";
                    return $this->render('aceptacion',['model'=>$model,'msg'=>$msg,'tipo'=>$tipo]);
                }
            }else{
                $model = Prospectos::find()->where(['estatus'=>1])->all();
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
              $user->nombre = $prospecto->nombre;
              $user->apellidoPaterno = $prospecto->apellidoPaterno;
              $user->apellidoMaterno = $prospecto->apellidoMaterno;
              $user->phone_number = $prospecto->phone_number;
              $user->pais= $prospecto->pais;
              $user->ciudad= $prospecto->ciudad;
              $user->username= $prospecto->username;
              $user->email=$prospecto->email;
              $user->password=$prospecto->password;
              $user->authKey= $prospecto->authKey;
              $prospecto->estatus=2;
              $prospecto->update();
              if($user->validate()){
                  $user->save();
                  $tipo=1;              
                  $model = Prospectos::find()->where(['estatus'=>1])->all();
                  $msg="Prospecto fue aceptado";
                  return $this->render('aceptacion',['model'=>$model,'msg'=>$msg,'tipo'=>$tipo]);
              }
              else{
                $model = Prospectos::find()->where(['estatus'=>1])->all();
                $msg="Ocurrio un error al aceptar prospecto";
                return $this->render('aceptacion',['model'=>$model,'msg'=>$msg,'tipo'=>$tipo]);
              }
            }
        }else{
            $model = Prospectos::find()->where(['estatus'=>1])->all();
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
            $model->setPassword($model->password);
            $model->generateAuthKey();
            if($model->validate()){
                $model->save();
                Yii::$app->session->setFlash('success','Has sido registrado.');
                return $this->redirect(['site/login']);
            }
        }
        return $this->render('signup',[
            'model' => $model,
        ]);

    }
}
