<?php

namespace app\models;
use app\models\entities\EntityProspectos;
use app\models\Prospectos;
use app\models\Cliente;

class Prospectos extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    public static function tableName()
    {
        return EntityProspectos::tableName();
    }

    public function rules()
    {
            return [
                [['email','rfc','numeroTelefono','nombre','apellidoPaterno', 'apellidoMaterno','pais','ciudad','fechaNacimiento'], 'required'],
                [['email'], 'unique','targetClass'=>'\app\models\Prospectos','message' => 'Usuario ya registrado.'],
                [['numeroTelefono','nombre','apellidoPaterno', 'apellidoMaterno','pais','ciudad'],'string','max' => 30],
                [['email'], 'string', 'max' => 50]           
            ];
    }


    public function attributeLabels(){
        return[ 
            'id' => 'ID',
            'nombre' => 'Nombre',
            'email' => 'Correo electrónico',
            'apellidoPaterno' => 'Apellido paterno',
            'apellidoMaterno' => 'Apellido materno',
            'pais' => 'Pais',
            'ciudad' => 'Ciudad',
            'numeroTelefono' => 'Teléfono',
            'fechaNacimiento' => 'Fecha de Nacimiento'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    public static function obtenerEnEspera()
    {
        return static::find()->where(['estatus'=>'ESPERA'])->all();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    public function setPassword($password)
    {
        $this->password = \Yii::$app->security->generatePasswordHash($password);
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function generateAuthKey(){
        $this->authKey = \Yii::$app->security->generateRandomString();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password,$this->password);
    }
}
