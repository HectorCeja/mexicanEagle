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
use app\models\FormSearch;
use yii\helpers\Html;
use app\models\Cliente;

class ProspectoController extends Controller
{
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

    public function actionDelete(){
        if(Yii::$app->request->post()){
            $msg=null;
            $tipo=0;
            $id = Html::encode($_POST["id"]);
            if((int)$id){
                $form = new FormSearch;
                $search = null;
                if(Prospectos::rechazar($id) !==false){
                    $model = Prospectos::obtenerEnEspera();
                    $msg="Prospecto borrado correctamente";
                    $tipo=1;
                    return $this->render('aceptacion',['model'=>$model,'form'=>$form,'msg'=>$msg,'tipo'=>$tipo,'search'=>$search]);
                }else{
                    $model = Prospectos::obtenerEnEspera();
                    $msg="Prospecto no pudo ser borrado";
                    return $this->render('aceptacion',['model'=>$model,'form'=>$form,'msg'=>$msg,'tipo'=>$tipo,'search'=>$search]);
                }
            }else{
                $form = new FormSearch;
                $search = null;
                $model = Prospectos::obtenerEnEspera();
                $msg="Prospecto no pudo ser borrado";
                return $this->render('aceptacion',['model'=>$model,'form'=>$form,'msg'=>$msg,'tipo'=>$tipo,'search'=>$search]);
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
                $user = User::guardarUserNuevo($prospecto);
                $cliente=Cliente::guardarNuevoCliente($prospecto,$user->id);             
                Prospectos::actualizar($prospecto);
                $form = new FormSearch;
                $contact = new ContactForm();
                ContactForm::contactProspect(Yii::$app->params['adminEmail'],$user->email, $cliente->nombre);
                $model = Prospectos::obtenerEnEspera();
                $search = null;
                $msg="Prospecto fue aceptado ";
                return $this->render('aceptacion',['model'=>$model,'form'=>$form,'msg'=>$msg,'tipo'=>1,'search'=>$search]);
            }
        }else{
            $form = new FormSearch;
            $search = null;
            $model = Prospectos::find()->obtenerEnEspera();
            $msg="";
            return $this->render('aceptacion',['model'=>$model,'form'=>$form,'msg'=>$msg,'tipo'=>$tipo,'search'=>$search]);
        }      
    }

    public function actionRegister(){
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new Prospectos();
        if ($model->load(Yii::$app->request->post())){
            if($model->validate()){
                $model->save(false);
                Yii::$app->session->setFlash('success','Has sido registrado como prospecto, espere un correo de aceptación.');
                return $this->redirect(['site/login']);
            }
        }
        return $this->render('signup',[
            'model' => $model,
        ]);

    }


    public function actionAceptacion()
    {
        $model= Prospectos::obtenerEnEspera();
        $msg=null;
        $tipo=3;
        $form = new FormSearch;
        $search = null;
        if($form->load(Yii::$app->request->get())){
            if($form->validate()){
                $search = Html::encode($form->q);
                $model = Prospectos::obtenerClientesBuscador($search);   
            }
        }
        return $this->render('aceptacion',['model'=>$model,'form'=>$form,'msg'=>$msg,'tipo'=>$tipo,'search'=>$search]);
    }
}