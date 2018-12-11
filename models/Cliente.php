<?php

namespace app\models;
use app\models\entities\EntityCliente;

class Cliente extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    public static function tableName()
    {
        return EntityCliente::tableName();
    }

    public function rules()
    {
            return [
                [['numeroTelefono','nombre','apellidoPaterno', 'apellidoMaterno','pais','ciudad'],'string','max' => 30],      
            ];
    }


    public function attributeLabels(){
        return[ 
            'id' => 'ID',
            'nombre' => 'Nombre',
            'apellidoPaterno' => 'Apellido paterno',
            'apellidoMaterno' => 'Apellido materno',
            'pais' => 'Pais',
            'ciudad' => 'Ciudad',
            'numeroTelefono' => 'Teléfono'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
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

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password,$this->password);
    }

    public function guardarNuevoCliente($prospecto,$user_id){
        $cliente= new Cliente();
        $cliente->nombre = $prospecto->nombre;
        $cliente->apellidoPaterno = $prospecto->apellidoPaterno;
        $cliente->apellidoMaterno = $prospecto->apellidoMaterno;
        $cliente->numeroTelefono = $prospecto->numeroTelefono;
        $cliente->pais= $prospecto->pais;
        $cliente->ciudad= $prospecto->ciudad;
        $cliente->rfc= $prospecto->rfc;
        $cliente->fechaNacimiento= $prospecto->fechaNacimiento;
        $cliente->idUsuario=$user_id;
        $cliente->save(false);
        return $cliente;
    }
}
