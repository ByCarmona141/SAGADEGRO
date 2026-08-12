<?php
	include '../CelaTemplate/CelaTableTools.php';
?>
<table id="Table_CelaCategor_iaConfiguraci_on" class="table table-striped table-bordered  table-hover datatable" data-source="<?= $ServerSource; ?>" data-function="<?= $ServerFunction; ?>" data-form="<?= $RouteForm; ?>">
	<thead>
	<tr>
		<th width="1%" title="Seleccionar todo">
			<div class="text-center">
				<label>
					<input  type="checkbox" id="All<?= $Table; ?>" data-intro="Selecciona todos los registros de esta p&aacute;gina" data-position="bottom"/>
				</label>
			</div>
		</th>
		<th class="sortable" width="9%"><div class="text-center"> # </div></th>
		<th class="sortable" width="90%"><div class="text-center"> Nombre de la Categor&iacute;a </div></th>
	</tr>
	</thead>
	<tbody>
	</tbody>
</table>