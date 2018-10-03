<?php

namespace app\models;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
            return [
                [['username','password'], 'required'],
                [['email'], 'email'],
                [['username'], 'unique','targetClass'=>'\app\models\User','message' => 'usuario ya registrado.'],
                [['email'], 'unique','targetClass'=>'\app\models\User','message' => 'correo ya registrado.'],
                [['phone_number','nombre','apellidoPaterno', 'apellidoMaterno','pais','ciudad'],'string','max' => 30],
                [['username','password'], 'string', 'max' => 250],
                [['email'], 'string', 'max' => 500]           
            ];
    }


    public function attributeLabels(){
        return[ 
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'nombre' => 'Nombre',
            'apellidoPaterno' => 'Apellido paterno',
            'apellidoMaterno' => 'Apellido materno',
            'pais' => 'Pais',
            'ciudad' => 'Ciudad'
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
}
