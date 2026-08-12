<?php
	include '../CelaTemplate/CelaTableTools.php';
?>
<table id="Table_CelaConfiguraci_on" class="table table-striped table-bordered table-hover datatable" data-source="<?= $ServerSource; ?>" data-function="<?= $ServerFunction; ?>" data-form="<?= $RouteForm; ?>">
	<thead>
	<tr>
		<th width="1%" title="Seleccionar todo">
			<div class="text-center">
				<label>
					<input type="checkbox" id="All<?= $Table; ?>" data-intro="Selecciona todos los registros de esta p&aacute;gina" data-position="bottom"/>
				</label>
			</div>
		</th>
		<th class="sortable" width="29%">
			<div class="text-center"> Nombre</div>
		</th>
		<th class="sortable" width="40%">
			<div class="text-center"> Valor </div>
		</th>
		<th class="sortable" width="5%">
			<div class="text-center"> Tipo </div>
		</th>
		<th class="sortable" width="5%">
			<div class="text-center"> Referencia </div>
		</th>
		<th class="sortable" width="10%">
			<div class="text-center"> Clases </div>
		</th>
		<th class="sortable" width="10%">
			<div class="text-center"> Roles </div>
		</th>
	</tr>
	</thead>
	<tbody>
	</tbody>
</table>