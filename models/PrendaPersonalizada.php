<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\entities\EntityPrendaPersonalizada;

class PrendaPersonalizada extends \yii\db\ActiveRecord {


    public static function tableName()
    {
        return EntityPrendaPersonalizada::tableName();
    }
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['idPrenda'], 'required', 'message' => 'La prenda no debe ser vacía.']
         ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'idPrenda' => 'Prenda',
            'idComponente' => 'Componente',
            'idUsuario'=>'Usuario'
        ];
    }

    public static function obtenerPrendas()
    {
        return static::find()->all();
    }

    public static function obtenerFolio()
    {
        $result = \Yii::$app->db->createCommand("CALL aumentar_folio('prendasPersonalizadas', @folio);") 
                      ->execute();
        return \Yii::$app->db->createCommand("select @folio;")->queryScalar();
    }

    public static function obtenerPrendasPorIds($prendas)
    {
        return static::find()
            ->where(['in', 'id', $prendas])
            ->all();
    }

    public static function borrarPersonalizado($idUsuario, $idPrenda)
    {

        static::deleteAll(['idUsuario' => $idUsuario,'idPrenda'=>$idPrenda]);;
        
        /*$model = $connection->createCommand('DELETE FROM prendasPersonalizadas WHERE idUsuario=:idUsuario AND idPrenda=:idPrenda');
        $model->bindParam(':idUsuario', $idUsuario);
        $model->bindParam(':idPrenda', $idPrenda);
        $model->execute();*/
    }

    public function guardarPrendasPersonalizadas($idUsuario,$idPrenda,$ids){
        $folioPrendaPersonalizada = static::obtenerFolio();
        $fechaAlta = date("Y-m-d");
        foreach($ids as $id){
            $componentePersonalizar = new PrendaPersonalizada();
            $componentePersonalizar->id = $folioPrendaPersonalizada;
            $componentePersonalizar->idUsuario = $idUsuario;
            $componentePersonalizar->idPrenda = $idPrenda;

            $componentePersonalizar->idComponente = $id;
            $componentePersonalizar->fechaAlta = $fechaAlta;
            $componentePersonalizar->save();
        }
    }
}