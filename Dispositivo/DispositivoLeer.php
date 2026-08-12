<?php
	include '../CelaTemplate/CelaTableTools.php';
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