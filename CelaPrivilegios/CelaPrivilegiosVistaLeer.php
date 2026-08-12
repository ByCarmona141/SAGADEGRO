<?php
	include '../CelaTemplate/CelaTableTools.php';
?>
	<table id="Table_CelaPrivilegios" class="table table-striped table-bordered  table-hover datatable" data-source="<?= $ServerSource; ?>" data-function="<?= $ServerFunction; ?>" data-form="<?= $RouteForm; ?>">
		<thead>
			<tr>
				<th width="1%" title="Seleccionar todo">
					<div class="text-center">
						<label>
							<input type="checkbox" id="All<?= $Table; ?>" data-intro="Selecciona todos los registros de esta p&aacute;gina" data-position="bottom"/>
						</label>
					</div>
				</th>
				<th class="sortable" width="9%">
					<div class="text-center"> # </div>
				</th>
				<th class="sortable" width="20%">
					<div class="text-center"> Privilegio</div>
				</th>
				<th class="sortable" width="30%">
					<div class="text-center"> Origen </div>
				</th>
				<th class="sortable" width="20%">
					<div class="text-center"> Tupla </div>
				</th>
				<th class="sortable" width="20%">
					<div class="text-center"> TuplaAcceso </div>
				</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>