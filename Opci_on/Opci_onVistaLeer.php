<?php
	include '../CelaTemplate/CelaTableTools.php';
?>
<table id="Table_Opci_on" class="table table-striped table-bordered  table-hover datatable"
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
		<th width="9%" class="sortable" ><div class="text-center"> # </div></th>
		<th width="90%" class="sortable" ><div class="text-center"> Descripci&oacute;n </div></th>
	</tr>
	</thead>
	<tbody>
	</tbody>
</table>