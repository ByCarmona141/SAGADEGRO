<?php
	if(isset($Privileges['Eliminar']) && $Privileges['Eliminar'] == 1){
?>
		<!-- #modal-dialog -->
		<div id="<?= $Table; ?>ModalEliminar" tabindex="100" class="modal fade" aria-hidden="false" aria-labelledby="myModalLabel" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Confirmaci&oacute;n de Acci&oacute;n</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body" id="<?= $Table; ?>ModalEliminarBody">
						<span>&iquest;Realmente desea eliminar el/los elemento(s) seleccionado(s)?</span>
					</div>
					<div class="modal-footer">
						<a class="btn btn-white" id="<?= $Table; ?>Bot_onEliminarCancelar" data-bs-dismiss="modal">
							<i class="fa fa-undo"></i>&nbsp; Cancelar
						</a>
						<a class="btn btn-danger" id="<?= $Table; ?>Bot_onEliminarAceptar" href="">
							<i class="fa fa-trash-alt"></i>&nbsp; Eliminar
						</a>
					</div>
				</div>
			</div>
		</div>
<?php
	}

	if(isset($Params)){
		$Params = 'data-params="' . Encrypt(json_encode($Params), $SessionRandom) . '"';
	}else{
		$Params = 'data-params="' . Encrypt(json_encode(array('User' => 1, 'Status' => 1)), $SessionRandom) . '"';
	}
?>
<div class="mailbox-controls">
	<div class="btn-group">
		<!-- Check all button -->
		<div class="btn-group" data-toggle="buttons">
			<label class="btn btn-default btn-sm" data-intro="Selecciona todos los registros de esta p&aacute;gina" data-position="top">
				<input type="checkbox" id="All<?= $Table; ?>" /><i class="fa fa-check"></i>
			</label>
		</div>
		<a id="<?= $Table; ?>Bot_onEliminar" disabled="disabled" class="btn btn-default btn-sm delete<?= $Table; ?>" href="#" title="Eliminar seleccionados" data-position="right" data-intro="Elimina todos los elementos seleccionados">
			<i class="fa fa-trash-alt"></i>
		</a>
		<a id="UpdateMailbox" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></a>
	</div>
<?php
	if($Action == 'Trash'){
?>
		<a id="<?= $Table; ?>Bot_onMover" disabled="disabled"  class="btn btn-default btn-sm" href="#" title="Mover a Inbox">
			<i class="fa fa-external-link"></i>
		</a>
<?php
	}
?>
</div>
<table class="table table-hover table-striped" id="Table_<?= $Table; ?>" data-source="<?= $ServerSource; ?>" data-function="<?= $ServerFunction; ?>" data-form="<?= $RouteForm; ?>" style="width: 100% !important;" <?= $Params; ?>>
	<thead>
		<tr>
			<th width="5%">&nbsp;</th>
			<th width="5%">&nbsp;</th>
			<th width="20%">&nbsp;</th>
			<th width="55%">&nbsp;</th>
			<th width="5%">&nbsp;</th>
			<th width="10%">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
