<?php
	require_once('../CelaPrivilegio/CelaPrivilegio.php');
	require_once('../CelaPrivilegios/CelaPrivilegios.php');

	/*Se Obtienen los Formulario que adminsitra el rol/grupo actual*/
	$Admin =    strtoupper(
					GetValue(
						sprintf('SELECT Nombre FROM CelaRol WHERE `id` = %s;',
							GetSQLValueString($Group, 'varchar')
						),
						'Nombre'
					)
				);

	if($Admin == 'DEVELOPER' || $Admin == 'DESARROLLADOR'){
		$OriginQuery = sprintf('SELECT * FROM %s WHERE `id` != %s ORDER BY `id` ASC;', $TablaOrigen, 0);
		/*Se Obtienen los provilegios que administra el rol/grupo actual*/
		$PrivilegeQuery = CelaPrivilegioQueryCombo();
	}else{
		/*Se Obtienen los provilegios que administra el rol/grupo actual*/
		$PrivilegeQuery = CelaPrivilegioQueryCombo('InPrivilege', $Group, 4);

		$OriginQuery = sprintf('SELECT * FROM %s WHERE `id` IN (select Tupla
																from CelaPrivilegios
																where
																	`TuplaAcceso` = %s and
																	`Origen` = %s
															   ) AND
														  `id` != %s',
							$TablaOrigen,
							$Group,
							2,
							0
						);
	}

	//print $OriginQuery;

	$PrivilegeResult    = $Connection -> query($PrivilegeQuery);
	$Privileges         = array();

	$Sorting            =   array(
								0 => array(
									'bSortable' => false
								),
							);
	while($PrivilegeRecod = $PrivilegeResult -> fetch_assoc()){
		$Data = array(
			'id' => $PrivilegeRecod['id'],
			'Nombre' => $PrivilegeRecod['Nombre'],
			'Descripci_on' => $PrivilegeRecod['Descripci_on']
		);

		$Privileges[] = $Data;
		$Sorting[] = array(
			'bSortable' => false
		);
	}

	$Sorting[]=array(
		'bSortable' => false
	);
	$Sorting[]=array(
		'bSortable' => false
	);
	$Sorting[]=array(
		'bSortable' => false
	);
	$OriginResult       = $Connection -> query($OriginQuery);

	$DataTableOption = CelaPrivilegiosGetDataTable($Sorting);
?>
<form class="form-horizontal form_validate" method="post" name="FormCelaPrivilegios" id="CelaPrivilegios" action="<?= $FormAction . '?' . EncodeThis('Action=Privilegios'); ?>" >
	<fieldset>
		<div class="clearfix"></div>
		<hr />
		<div class="table-responsive">
			<table id="TableCelaPrivilegios" class="table table-striped table-hover table-bordered datatable" data-options='<?= json_encode($DataTableOption); ?>'>
				<thead>
					<tr>
						<th>
							<div class="text-center">
								<label>Nombre del Formulario</label>
							</div>
						</th>
						<th>
							<div class="text-center">
								<label>Descripci&oacute;n</label>
							</div>
						</th>
						<th>
							<div class="text-center">
								<label>Ruta del Formulario</label>
							</div>
						</th>
						<th title="Selecciona todos los privilegios">
							<div class="text-center">
								<label>
									Todos Los<br />Privilegios<br />
									<input type="checkbox" id="All" />
								</label>
							</div>
						</th>
				<?php
					for($i = 0; $i < count($Privileges); $i++){
						?>
						<th title="<?= $Privileges[$i]['Descripci_on']; ?>">
							<div class="text-center">
								<label>
									<?= $Privileges[$i]['Nombre']; ?><br />
									<input class="Privilegio" type="checkbox" id="<?= $Privileges[$i]['Nombre']; ?>" />
								</label>
							</div>
						</th>
						<?php
					}
				?>
					</tr>
				</thead>
				<tbody>
		<?php
			while($OriginRecord = $OriginResult -> fetch_assoc()){
		?>
				<tr>
					<td>
						<div><?= $OriginRecord['Nombre']; ?></div>
					</td>
					<td>
						<div><?= $OriginRecord['Descripci_on']; ?></div>
					</td>
					<td>
						<div><?= $OriginRecord['Ruta']; ?></div>
					</td>
					<td>
						<div class="text-center">
							<input class="All Form" type="checkbox" value="1" name="<?= $OriginRecord['id']; ?>" id="<?= $OriginRecord['id']; ?>" />
						</div>
					</td>
					<?php
						for($i = 0; $i < count($Privileges); $i++){
							?>
							<th title="<?= $Privileges[$i]['Descripci_on']; ?>">
								<div class="text-center">
									<label>
										<?php
											$QueryInPrivilege = sprintf('SELECT `id`
																		 FROM CelaPrivilegios
																	     WHERE
																	        `TuplaAcceso` = %s AND
																	        `Origen` = %s AND
																	        `Tupla` = %s AND
																	        `Privilegio` = %s;',
																	GetSQLValueString($TuplaAcceso, 'int'),
																	GetSQLValueString($Origen, 'int'),
																	GetSQLValueString($OriginRecord['id'], 'int'),
																	GetSQLValueString($Privileges[$i]['id'], 'int')
																);

											$InPrivilege =  GetValue($QueryInPrivilege, 'id');
										?>
										<input class="<?= $Privileges[$i]['Nombre']; ?> All Form_<?= $OriginRecord['id']; ?>" type="checkbox" id="<?= $Privileges[$i]['Nombre'] . $OriginRecord['id'];?>" name="<?= $Privileges[$i]['Nombre'] . $OriginRecord['id']; ?>" <?= ($InPrivilege != 'NULL' ? 'checked="checked"':''); ?> value="1"/>
									</label>
								</div>
							</th>
							<?php
						}
					?>
				</tr>
		<?php
			}
		?>
				</tbody>
			</table>
			<input type="hidden" name="TuplaAcceso" value="<?= $TuplaAcceso; ?>" />
			<input type="hidden" name="Origen" value="<?= $Origen; ?>" />
			<input type="hidden" name="TablaOrigen" value="<?= $TablaOrigen; ?>" />
			<input type="hidden" name="CelaPrivilegiosUpdate" value="CelaPrivilegiosUpdate"/>
		</div>
		<div class="clearfix"></div>
		<hr />

		<div class="form-group last offset-3">
			<div class="col-md-offset-3 col-md-9">
				<button id="Actualiza" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">
					<i class="fa fa-save"></i>&nbsp; Guardar Cambios
				</button>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="reset" class="btn btn-default" onclick = "location.href='<?= $TablaOrigen; ?>'" >
					<i class="fa fa-undo"></i>&nbsp; Cancelar
				</button>
			</div>
		</div>
	</fieldset>
</form>