<?php

namespace app\models;

use Yii;
use yii\base\Model;


class FormSearch extends Model{


    public $q;

    public function rules(){
        return [
            ['q','string']
        ];
    }

    public function attributeLabels(){
        return [
            'q' => "Buscar",
        ];
    }


    
}