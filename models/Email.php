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

    public function enviarCorreoConfirmacion($prendasCarrito, $carrito, $total, $fechaEntrega)
    {
        $emailFrom = Yii::$app->params['adminEmail'];
        $emailTo = Yii::$app->session['emailUsuario'];
        $subject = "Pedido Ropalinda";
        $message =  '¡Enhorabuena! Su pedido se ha completado con éxito.'."\n";
        $message .= 'Sus prendas serán enviadas a su dirección el día ';
        $message .= $fechaEntrega.'.'."\n";
        $message .= 'Su pedido:'."\n"."\n";
        
        foreach($prendasCarrito as $prendaCarrito) {
            $message .= ' - '.$carrito[$prendaCarrito->id];
            $message .= ' '.$prendaCarrito->descripcion;
            $message .= ' -> $'.$prendaCarrito->precio."\n";
        }
        $message .= "\n".'Total del pedido: '.number_format($total,2,".",","); 
        $message .= "\n\n".'Gracias por su preferencia.'; 

        static::sendEmail($emailFrom, $emailTo, $subject, $message);
    }
}
