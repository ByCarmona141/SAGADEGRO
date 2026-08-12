<?php
	include '../CelaTemplate/CelaTableTools.php';

    $Params = 'data-params="' . Encrypt(json_encode($Params), $SessionRandom) . '"';
?>
<table id="Table_Acceso" class="table table-striped table-bordered  table-hover datatable"
	data-source="<?= $ServerSource; ?>" data-function="<?= $ServerFunction; ?>" data-form="<?= $RouteForm; ?>" <?= $Params; ?>>
	<thead>
	<tr>
		<th width="1%" title="Seleccionar todo">
			<div class="text-center">
				<label>
					<input  type="checkbox" id="All<?= $Table; ?>" data-intro="Selecciona todos los registros de esta p&aacute;gina" data-position="bottom"/>
				</label>
			</div>
		</th>
		<th width="30%" class="sortable" ><div class="text-center"> Tipo de Acceso </div></th>
		<th width="30%" class="sortable" ><div class="text-center"> Host </div></th>
		<th width="30%" class="sortable" ><div class="text-center"> Puerto </div></th>
		<th width="30%" class="sortable" ><div class="text-center"> Usuario </div></th>
		<th width="30%" class="sortable" ><div class="text-center"> Password </div></th>
		<th width="30%" class="sortable" ><div class="text-center"> Dispositivo </div></th>
	</tr>
	</thead>
	<tbody>
	</tbody>
</table>