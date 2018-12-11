<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Email extends Model
{
    public function sendEmail($emailFrom, $emailTo, $subject, $message)
    {   
        if(Yii::$app->mailer->compose()
            ->setTo($emailTo)
            ->setFrom([$emailFrom])
            ->setSubject($subject)
            ->setTextBody($message)
            ->send()){
                return true;
            }

        return false;
    }
}
