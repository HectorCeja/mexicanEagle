<?php

namespace app\models\entities;

class EntityCliente extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'clientes';
    }
}