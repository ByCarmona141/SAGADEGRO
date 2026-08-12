<?php
	include '../CelaTemplate/CelaTableTools.php';
?>
<table id="Table_Plantilla" class="table table-striped table-bordered  table-hover datatable"
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
		<th width="30%" class="sortable" ><div class="text-center"> Descripci&oacute;n </div></th>
		<th width="20%" class="sortable" ><div class="text-center"> Tipo Plantilla </div></th>
		<th width="9%" class="sortable" ><div class="text-center"> Esta Vigente </div></th>
		<th width="10%" class="sortable" ><div class="text-center"> Tama&ntilde;o </div></th>
	</tr>
	</thead>
	<tbody>
	</tbody>
</table>