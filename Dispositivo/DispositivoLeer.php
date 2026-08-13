<div class="row col-md-12">
	<div class="col-md-2 text-left">
		<?php
			if (isset($Privileges['Crear']) && $Privileges['Crear'] == 1) {
				?>
				<a href="<?= $Table.'?' . EncodeThis(  'Action=Crear' . (isset($Vars) && $Vars != '' ? '&' . $Vars:'')); ?>" title="Agregar" class="btn btn btn-success" data-position="top" data-intro="Insertar nuevo <?= $Table; ?>" id="<?= $Table; ?>Bot_onCrear">
			<span>
				<i class="fa fa-plus"></i>&nbsp; Agregar
			</span>
				</a>
				<?php
			}
		?>
	</div>
	<div class="col-md-3 text-left form-inline">
		<?php
			$Label = false;
			if(isset($Privileges['Actualizar']) && $Privileges['Actualizar'] == 1){
				?>
				<a id="<?= $Table; ?>Bot_onActualizar" disabled="disabled" href="#" title="Editar seleccionados" class="btn btn btn-warning update<?= $Table; ?>" data-position="bottom" data-intro="Modifica los elementos seleccionados">
			<span>
				<i class="fa fa-pencil-alt"></i>&nbsp; Editar
			</span>
				</a>
				<?php
				$Label=true;
			}
			if(isset($Privileges['Eliminar']) && $Privileges['Eliminar'] == 1){
				?>
				<a id="<?= $Table; ?>Bot_onEliminar" disabled="disabled" class="btn btn btn-danger delete<?= $Table; ?>" href="#" title="Eliminar seleccionados" data-position="right" data-intro="Elimina todos los elementos seleccionados">
			<span>
				<i class="fa fa-trash-alt"></i>&nbsp; Eliminar
			</span>
				</a>
				<?php
				$Label = true;
			}
			if($Label == true){
				?>
				<label style="display: inherit !important;">
					<img width="38" height="22" alt="Para los elementos que est&aacute;n marcados:"
						src="bootstrap/img/arrow_ltr.png" class="selectallarrow">
					&nbsp; Para los datos seleccionados
				</label>
				<?php
			}
		?>
	</div>
	<div class="col-md-3 text-left form-inline">
	<?php
			if (isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
				?>
				<a href="<?= $Table.'?' . EncodeThis(  'Action=Topologia' . (isset($Vars) && $Vars != '' ? '&' . $Vars:'')); ?>" title="Topologia" class="btn btn btn-primary" data-position="top" data-intro="Cambiar VIsta <?= $Table; ?>" id="<?= $Table; ?>Bot_onTopologia">
			<span>
				<i class="fas fa-sitemap"></i>&nbsp; Topologia
			</span>
				</a>
				<?php
			}
		?>
	</div>
	<div class="col-md-4 text-right">
		<div data-position="bottom" data-intro="Busqueda general" class="form-group">
			<label for="Search-<?= $Table; ?>" class="sr-only">Busqueda:&nbsp; </label>
			<div align="right" class="input-group">
				<input type="text" autocomplete="off" placeholder="Buscar..." class="form-control DataTableFilter" id="CelaInputSearch<?= $Table; ?>" data-tablesearch="Table_<?= $Table; ?>">
				<span title="Buscar..." class="input-group-btn">
					<a id="CelaBot_onBuscar<?= $Table; ?>" data-tablesearch="Table_<?= $Table; ?>" class="btn btn-default btn-filter">
						<i class="fa fa-search"></i>
					</a>
				</span>
			</div>
		</div>
	</div>
</div>
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
?>

<table id="Table_Dispositivo" class="table table-striped table-bordered  table-hover datatable"
	data-source="<?= $ServerSource; ?>" data-function="<?= $ServerFunction; ?>" data-form="<?= $RouteForm; ?>">
	<thead>
	<tr>
		<th width="1%" title="Seleccionar todo">
			<div class="text-center">
				<label>
					<input  type="checkbox" id="All<?= $Table; ?>" data-intro="Selecciona todos los registros de esta p&aacute;gina" data-position="bottom"/>
				</label>
			</div>
		</th>
		<th width="30%" class="sortable" ><div class="text-center"> Nombre </div></th>
		<th width="30%" class="sortable" ><div class="text-center"> Tipo de Dispositivo </div></th>
		<th width="30%" class="sortable" ><div class="text-center"> Dispositivo Padre </div></th>
		<th width="30%" class="sortable" ><div class="text-center"> Marca </div></th>
		<th width="30%" class="sortable" ><div class="text-center"> Modelo </div></th>
		<th width="30%" class="sortable" ><div class="text-center"> Estatus del Dispositivo </div></th>
		<th width="30%" class="sortable" ><div class="text-center"> MAC </div></th>
		<th width="30%" class="sortable" ><div class="text-center"> Ubicacion </div></th>
		<th width="30%" class="sortable" ><div class="text-center"> Rack </div></th>
		<th width="30%" class="sortable" ><div class="text-center"> IP </div></th>
		<th width="30%" class="sortable" ><div class="text-center"> Serial </div></th>
		<th width="30%" class="sortable" ><div class="text-center"> Acciones </div></th>
	</tr>
	</thead>
	<tbody>
	</tbody>
</table>