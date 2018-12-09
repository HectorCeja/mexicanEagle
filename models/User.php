<?php

namespace app\models;
use app\models\entities\EntityUser;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    public static function tableName()
    {
        return EntityUser::tableName();
    }

    public function rules()
    {
            return [
                [['email','password'], 'required'],
                [['email'], 'email'],
                [['email'], 'unique','targetClass'=>'\app\models\User','message' => 'correo ya registrado.'],
                [['password'], 'string', 'max' => 50],
                [['email'], 'string', 'max' => 50]           
            ];
    }


    public function attributeLabels(){
        return[ 
            'id' => 'ID',
            'password' => 'Contraseña',
            'email' => 'Correo electrónico',
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

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
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
