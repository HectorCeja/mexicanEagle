<?php

namespace app\models\entities;

class EntityPrendaPersonalizada extends \yii\db\ActiveRecord{

    public static function tableName()
    {
        return 'prendasPersonalizadas';
    }
}