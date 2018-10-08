<?php

namespace app\models\entities;

class EntityUser extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'user';
    }
}
