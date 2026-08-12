
<form class="form-horizontal form_validate" method="POST" name="Form_CelaComponente" id="Form_CelaComponente" action="<?= $FormAction . '?' . EncodeThis('Action=Finalizar'); ?>" >
	<fieldset>
		<span class="clearfix"></span>
		<hr />
		<?php
			$Count=0;
			$CelaComponenteId = '';
			foreach($_GET['Key'] as $Key){
				$CelaComponenteQuery =  sprintf('SELECT * FROM CelaComponente WHERE id = %s;',
					GetSQLValueString($Key, 'int')
				);
				$CelaComponenteResult = $Connection -> query($CelaComponenteQuery);
				$CelaComponenteRecord = $CelaComponenteResult -> fetch_assoc();
				if($Count == 0){
					$CelaComponenteId = $CelaComponenteRecord['id'];
				}else{
					$CelaComponenteId .= ',' . $CelaComponenteRecord['id'];
				}
		?>
				<div class="thumbnail" style="background-color: <?= ($Count%2==0?'#F9F9F9':'#FFFFF'); ?>">
					<fieldset>
						<div class="form-group">
							<div class="group-validate">
								<label class="col-md-2 control-label" for="focusedInput"><font color="red">*</font> Fecha de Realizado:
								</label>

								<div class="col-md-10">
									<div class="col-xs-12 validate">
										<input class="form-control focused e_requerido e_fecha_hora" name="FechaRealizado<?= $CelaComponenteRecord['id']; ?>" id="FechaRealizado<?= $CelaComponenteRecord['id']; ?>" type="text" value="<?= date('Y-m-d H:i:s'); ?>" data-rango='{"minimo":"1900-01-01 00:00:00", "msximo":"<?= date('Y-m-d H:i:s'); ?>", "mensaje":"Fecha "}' value="<?= $CelaComponenteRecord['FechaRealizado']; ?>" />
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="group-validate">
								<label class="col-md-2 control-label" for="focusedInput"> Conclusi&oacute;n:
								</label>

								<div class="col-md-10">
									<div class="col-xs-12 validate">
										<textarea class="form-control focused" name="Conclusi_on<?= $CelaComponenteRecord['id']; ?>" id="Conclusi_on<?= $CelaComponenteRecord['id']; ?>" rows="3"><?= $CelaComponenteRecord['Conclusi_on']; ?></textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="group-validate">
								<label class="col-md-2 control-label" for="focusedInput"><font color="red">*</font> Nombre de Quien Reviza:
								</label>

								<div class="col-md-10">
									<div class="col-xs-12 validate">
										<?php
											$OpcReviso['Name']  = 'Reviso' . $CelaComponenteRecord['id'];
											$OpcReviso['Class'] = 'form-control focused e_requerido';
											$Query = CelaUsuarioComboQuery();

											print SFillSelect($Query, $OpcReviso, $CelaComponenteRecord['Reviso'], 1)
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="group-validate">
								<label class="col-md-2 control-label" for="focusedInput"><font color="red">*</font> Nombre de Quien Autoriza:
								</label>

								<div class="col-md-10">
									<div class="col-xs-12 validate">
										<?php
											$OpcAutorizo['Name']     = 'Autorizo' . $CelaComponenteRecord['id'];
											$OpcAutorizo['Class']    = 'form-control focused e_requerido';
											$Query = CelaUsuarioComboQuery();

											print SFillSelect($Query, $OpcAutorizo, $CelaComponenteRecord['Autorizo'], 1)
										?>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
				</div>
		<?php
				$Count++;
				$Component = $CelaComponenteRecord['Componente'];
			}
		?>
		<input type="hidden" name="CelaComponenteUpdate" value="CelaComponenteUpdate">
		<input type="hidden" name="<?= Encrypt('id', $Random); ?>" value="<?= Encrypt($CelaComponenteId, $Random); ?>">
		<input type="hidden" name="Component" value="<?= $Component; ?>">
		<span class="clearfix"></span>
		<hr />
		<div class="form-group last">
			<div class="col-md-offset-3 col-md-9">
				<button id="Actualiza" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">
					<i class="fa fa-save"></i>&nbsp; Guardar Cambios
				</button>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="reset" class="btn btn-default" onclick = "location.href='<?= $FormAction . '?' . EncodeThis('Component=' . $Component); ?>'" >
					<i class="fa fa-undo"></i>&nbsp; Cancelar
				</button>
			</div>
		</div>
	</fieldset>
</form>
