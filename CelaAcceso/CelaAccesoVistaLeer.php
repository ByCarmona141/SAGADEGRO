<div class="row">
	<div class="modal fade" id="CelaAccesoRecordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Datos Registrados en la Acci&oacute;n</h4>
				</div>
				<div class="modal-body" id="CelaAccesoRecordModalBody">

				</div>
				<div class="modal-footer">
					<a class="btn btn-primary" id="CelaAccesoRecordModalButton" data-dismiss="modal">
						<i class="fa fa-check"></i>&nbsp; Aceptar
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6 text-left">
		<a class="btn btn-danger DataTablePrint" data-table="Table_CelaAcceso" data-function_report="CelaAccesoLeer" data-file_name="ReporteDeAccesos" data-mime_type="application/pdf" data-config="CelaAccesoReporteConfig" data-server_source="<?= $ServerSource; ?>" ><i class="fa fa-file-pdf-o"></i>&nbsp; Exportar a PDF</a>
		<a class="btn btn-success DataTablePrint" data-table="Table_CelaAcceso" data-function_report="CelaAccesoLeer" data-file_name="ReporteDeAccesos" data-mime_type="application/vnd.ms-excel" data-config="CelaAccesoReporteConfig" data-server_source="<?= $ServerSource; ?>" ><i class="fa fa-file-excel-o"></i>&nbsp; Exportar a XLS</a>
		<a class="btn btn-primary DataTablePrint" data-table="Table_CelaAcceso" data-function_report="CelaAccesoLeer" data-file_name="ReporteDeAccesos" data-mime_type="text/html" data-config="CelaAccesoReporteConfig" data-server_source="<?= $ServerSource; ?>" ><i class="fa fa-print"></i>&nbsp; Imprimir</a>
		<label style="display: inherit !important;">
			<img width="38" height="22" alt="Para los elementos que est&aacute;n marcados:"
				src="bootstrap/img/arrow_ltr.png" class="selectallarrow">
			&nbsp; Para los filtros seleccionados
		</label>
	</div>
	<div class="col-md-2 text-left form-inline">
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
<table id="Table_CelaAcceso" class="table table-striped table-bordered  table-hover datatable" data-source="<?= $ServerSource; ?>" data-function="<?= $ServerFunction; ?>" data-form="<?= $RouteForm; ?>">
	<thead>
	<tr>
		<th class="sortable" width="10%">
			<div align="center">
				<?php
					$OpcDate['Name']    = 'Fecha';
					$OpcDate['Class']   = 'form-control input-sm';
					$Query =    array(
						'-01-' => 'ENERO',
						'-02-' => 'FEBRERO',
						'-03-' => 'MARZO',
						'-04-' => 'ABRIL',
						'-05-' => 'MAYO',
						'-06-' => 'JUNIO',
						'-07-' => 'JULIO',
						'-08-' => 'AGOSTO',
						'-09-' => 'SEPTIEMBRE',
						'-10-' => 'OCTUBRE',
						'-11-' => 'NOVIEMBRE',
						'-12-' => 'DICIEMBRE'
					);
					print FillSelect($Query, $OpcDate);
				?>
			</div>
		</th>
		<th class="sortable" width="30%">
			<div align="center">
				<?php
					$OpcUser['Name']    = 'Usuario';
					$OpcUser['Class']   = 'form-control input-sm';
					$Query = CelaUsuarioComboQuery(true);
					print FillSelect($Query, $OpcUser);
				?>
			</div>
		</th>
		<th class="sortable" width="25%">
			<div align="center">
				<?php
					$OpcTable['Name']   = 'Origen';
					$OpcTable['Class']  = 'form-control input-sm';
					$Query = CelaAccesoComboQuery('Origen');
					print FillSelect($Query, $OpcTable);
				?>
			</div>
		</th>
		<th class="sortable" width="10%">
			<div align="center">
				<input type="text" class="form-control input-sm" name="Registro" id="Registro" placeholder="id Registro">
			</div>
		</th>
		<th class="sortable"  width="15%">
			<div align="center">
				<?php
					$OpcAction['Name']  = 'Accion';
					$OpcAction['Class'] = 'form-control input-sm';
					$Query = CelaAccionComboQuery(true);
					print FillSelect($Query, $OpcAction);
				?>
			</div>
		</th>
		<th class="" width="10%">
			<div align="center">
				Datos
			</div>
		</th>
	</tr>
	</thead>
	<tbody>
	</tbody>
</table>