<?php
	include '../CelaTemplate/CelaTableTools.php';
?>
<table id="Table_Modelo" class="table table-striped table-bordered  table-hover datatable"
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
		<th width="30%" class="sortable" ><div class="text-center"> Marca </div></th>
		<th width="30%" class="sortable" ><div class="text-center"> Modelo </div></th>
	</tr>
	</thead>
	<tbody>
	</tbody>
</table>