<div class="box-content panel-body thumbnail" style="background-color: #F9F9F9;" >
	<div class="row text-center">
		<fieldset>
			<legend>Estado de Seguimientos</legend>
		<?php
			foreach($Status as $State){
		?>
				<div style="margin-bottom:0px" class="col-xs-6  col-sm-3 col-md-3">
					<a href="SeguimientoCliente?<?= EncodeThis('Asignaciones=1&Asesor=' . $Empleado . '&Status=' . $State['status']); ?>" class="btn btn-<?= $State['acotacion'] ?> btn-outline" role="button" title="<?= $State['description'] ?>"><?= $State['value']; ?><br><?= $State['name'] ?></a>
				</div>
		<?php
			}
		?>
		</fieldset>
	</div>
</div>