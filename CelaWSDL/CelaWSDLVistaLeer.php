<?php
	include '../CelaTemplate/CelaTableTools.php';
?>
	<table id="Table_CelaWSDL" class="table table-striped table-bordered  table-hover datatable" data-source="<?= $ServerSource; ?>" data-function="<?= $ServerFunction; ?>" data-form="<?= $RouteForm; ?>">
		<thead>
			<tr>
				<th width="1%" title="Seleccionar todo">
					<div class="text-center">
						<label>
							<input type="checkbox" id="All<?= $Table; ?>" data-intro="Selecciona todos los registros de esta p&aacute;gina" data-position="bottom"/>
						</label>
					</div>
				</th>
				<th class="sortable" width="19%">
					<div class="text-center"> Nombre</div>
				</th>
				<th class="sortable" width="35%">
					<div class="text-center"> URL </div>
				</th>
				<th class="sortable" width="15%">
					<div class="text-center"> Usuario</div>
				</th>
				<th class="sortable" width="15%">
					<div class="text-center"> Contrase&ntilde;a </div>
				</th>
				<th class="sortable" width="15%">
					<div class="text-center"> Tipo </div>
				</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>