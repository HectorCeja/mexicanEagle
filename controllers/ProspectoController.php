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
                $user->setPassword("_");
                $user->activo = 1;
                $user->idPerfil = 1;

                $cliente = static::asignarDatos($prospecto);

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

    public function asignarDatos($prospecto){
        $cliente = new Cliente();
        $cliente->nombre = $prospecto->nombre;
        $cliente->apellidoPaterno = $prospecto->apellidoPaterno;
        $cliente->apellidoMaterno = $prospecto->apellidoMaterno;
        $cliente->numeroTelefono = $prospecto->numeroTelefono;
        $cliente->pais= $prospecto->pais;
        $cliente->ciudad= $prospecto->ciudad;
        $cliente->rfc= $prospecto->rfc;
        $cliente->fechaNacimiento= $prospecto->fechaNacimiento;
        return $cliente;
    }

    public function actionAceptacion()
    {
        $model= Prospectos::obtenerEnEspera();
        $msg=null;
        $tipo=3;
        return $this->render('aceptacion',['model'=>$model,'msg'=>$msg,'tipo'=>$tipo]);
    }
}