<?php

namespace app\models\entities;

class EntityEmpleado extends \yii\db\ActiveRecord{

    public static function tableName()
    {
        return 'empleados';
    }
}