<?php
	if(isset($Params)){
		$Create = EncodeThis('Action=Crear&Source=' . $Params['Source'] . '&Tupla=' . $Params['Tupla']);
		$Return = $Params['Source'];
		$Params = 'data-params="' . Encrypt(json_encode($Params), $SessionRandom) . '"';
	}else{
		$Create = EncodeThis('Action=Crear');
		$Params = '';
		$Return = 'Escritorio';
	}
?>
	<div class="row">
		<div class="col-md-2 text-left">
		<?php
			if (isset($Privileges['Crear']) && $Privileges['Crear'] == 1) {
		?>
			<a href="<?= $Table.'?' . $Create; ?>" title="Agregar" class="btn btn btn-success" data-position="top" data-intro="Insertar nuevo <?= $Table; ?>" id="<?= $Table; ?>Bot_onCrear">
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
			<a id="<?= $Table; ?>Bot_onActualizar" disabled="disabled" href="#" title="Editar seleccionados" class="btn btn btn-warning" data-position="bottom" data-intro="Modifica los elementos seleccionados">
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
			<a href="<?= $Return; ?>" title="Volver arriba" class="btn btn btn-info" data-position="top" data-intro="Regresar al origen" id="<?= $Table; ?>Bot_onRegresar">
				<span>
					<i class="fa fa-level-up"></i>&nbsp; Volver al Origen
				</span>
			</a>
		</div>
		<div class="col-md-4 text-right">
			<div data-position="bottom" data-intro="Busqueda general" class="form-group">
				<label for="Search-<?= $Table; ?>" class="sr-only">Busqueda:&nbsp; </label>
				<div align="right" class="input-group">
					<input type="text" autocomplete="off" placeholder="Buscar..." class="form-control DataTableFilter" id="CelaInputSearch<?= $Table; ?>" data-tablesearch="Table_<?= $Table; ?>">
					<span title="Buscar..." class="input-group-btn">
						<a id="<?= $Table; ?>Bot_onBuscar<?= $Table; ?>" data-tablesearch="Table_<?= $Table; ?>" class="btn btn-default btn-filter">
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
	<table id="Table_CelaRepositorio" class="table table-striped table-bordered  table-hover datatable" data-source="<?= $ServerSource; ?>" data-function="<?= $ServerFunction; ?>" data-form="<?= $RouteForm; ?>" <?= $Params; ?>>
		<thead>
			<tr>
				<th width="1%" title="Seleccionar todo">
					<div class="text-center">
						<label>
							<input type="checkbox" id="All<?= $Table; ?>" data-intro="Selecciona todos los registros de esta p&aacute;gina" data-position="bottom"/>
						</label>
					</div>
				</th>
				<th class="sortable" width="25%">
					<div align="center"> <input type="text" class="form-control input-sm" id="FiltraNombre" placeholder="Nombre"> </div>
				</th>
				<th class="sortable" width="35%">
					<div align="center"> Descripci&oacute;n </div>
				</th>
				<th class="sortable" width="24%">
					<div align="center"> Usuario </div>
				</th>
				<th class="sortable" width="10%">
					<div align="center"> <input type="date" name="FechaCreacion" id="FechaCreacion" class="form-control input-sm" placeholder="Fecha de Creaci&oacute;n"/> </div>
				</th>
				<th class="" width="5%">
					<div align="center"> Otras Versiones </div>
				</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
