<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?> 
<h1>Lista de Prospectos de Usuario</h1>
<?php if($tipo==1): ?>
    <div class="alert alert-success">
    <h4 ><?= $msg ?></h4>
        </div>
        <?php endif; ?>
<?php if($tipo==0): ?>
    <div class="alert alert-danger">
        <?= nl2br(Html::encode($msg)) ?>
    </div>
    <?php endif; ?>
<table class="table table-bordered">
    <tr>
        <th>Nombre Completo</th>
        <th>Telefono</th>
        <th>Email</th>
        <th>Pais</th>
        <th>Ciudad</th>
        <th></th>
        <th></th>
    </tr>
    <?php foreach($model as $row): ?>
    <tr>
        <td><?= $row->nombre  ?><?= " "?><?= $row->apellidoPaterno  ?><?= " "?><?= $row->apellidoMaterno  ?></td>
        <td><?= $row->phone_number ?> </td>
        <td><?= $row->email ?></td>
        <td><?= $row->pais ?></td>
        <td><?= $row->ciudad ?></td>
        <td><a href="<?= Url::toRoute(["site/aceptar", "id" => $row->id]) ?>">Aceptar</a></td>
        <td>
            <a href="#" data-toggle="modal" data-target="#id_username_<?= $row->username ?>">Eliminar</a>
            <div class="modal fade" role="dialog" aria-hidden="true" id="id_username_<?= $row->username ?>">
                      <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    <h4 class="modal-title">Eliminar Prospecto</h4>
                              </div>
                              <div class="modal-body">
                                    <p>¿Realmente deseas eliminar al prospecto <?= $row->username ?>?</p>
                              </div>
                              <div class="modal-footer">
                              <?= Html::beginForm(Url::toRoute("site/delete"), "POST") ?>
                                    <input type="hidden" name="id" value="<?= $row->id ?>">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Eliminar</button>
                              <?= Html::endForm() ?>
                              </div>
                            </div><!-- /.modal-content -->
                      </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </td>
    </tr>
    <?php endforeach ?>
</table>
