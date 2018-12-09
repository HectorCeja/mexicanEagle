<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Categoria extends \yii\db\ActiveRecord{

    public $id;
    public $descripcion;
    public $created_at;
    public $updated_at;


    public static function tableName()
    {
        return EntityCategorias::tableName();
    }

     /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            ['descripcion', 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'descripcion' => 'Descripcion'
        ];
    }


}