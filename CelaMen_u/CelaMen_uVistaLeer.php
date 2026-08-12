<?php
	include '../CelaTemplate/CelaTableTools.php';
?>
<div class="table-responsive" align="left">
	<table id="Table_CelaMen_u" class="table table-striped table-bordered hover datatable" data-source="<?= $ServerSource; ?>" data-function="<?= $ServerFunction; ?>" data-form="<?= $RouteForm; ?>" data-params="<?= Encrypt(json_encode($Params), $SessionRandom); ?>">
		<thead>
		<tr>
			<th width="1%" title="Seleccionar todo">
				<div align="center">
					<label>
						<input type="checkbox" id="All<?= $Table; ?>" data-intro="Seleeciona todos los registros de esta p&aacute;gina" data-position="bottom"/>
					</label>
				</div>
			</th>
			<th class="sortable" width="25%">
				<div align="center"> Nombre</div>
			</th>
			<th class="sortable" width="25%">
				<div align="center"> Descripci&oacute;n</div>
			</th>
			<th class="sortable" width="20%">
				<div align="center"> Ruta</div>
			</th>
			<th class="sortable" width="10%">
				<div align="center"> Categor&iacute;a</div>
			</th>
			<th class="sortable" width="5%">
				<div align="center"> Prioridad</div>
			</th>
			<th width="4%">
				<div align="center"> Icono</div>
			</th>
			<th class="sortable" width="10%">
				<div align="center"> Tipo de Elemento</div>
			</th>
		</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>