<?php
	include '../CelaTemplate/CelaTableTools.php';
	if(isset($Params)){
		$Params = 'data-params="' . Encrypt(json_encode($Params), $SessionRandom) . '"';
	}else{
		$Params = '';
	}
?>
	<table id="Table_CelaUsuario" class="table table-striped table-bordered table-hover datatable" data-source="<?= $ServerSource; ?>" data-function="<?= $ServerFunction; ?>" data-form="<?= $RouteForm; ?>" <?= $Params; ?>>
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
					<div class="text-center"> Nombre Completo</div>
				</th>
				<th class="sortable" width="15%">
					<div class="text-center"> Usuario</div>
				</th>
				<th class="sortable" width="20%">
					<div class="text-center"> Correo Electr&oacute;nico</div>
				</th>
				<th class="sortable" width="30%">
					<div class="text-center"> Rol</div>
				</th>
				<th class="" width="5%">
					<div class="text-center"> Privilegios</div>
				</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>