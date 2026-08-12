<?php
	/*<div class="form-group">
		<div class="col-md-offset-3 col-md-9 col-sm-offset-3 col-sm-9 col-xs-offset-3 col-xs-9">
			<div class="row">
				<div class="text-left col-md-2 col-sm-2 col-xs-2" >
					<button class="btn btn-info Save SaveBack" title="Guardar registro y regresar para insertar otro" data-loading-text="Guardando..." disabled="disabled">
						<input type="checkbox" class="InsertBack" autocomplete="off" name="InsertBack" id="InsertBack" value="1">
						<i class="fa fa-check"></i>&nbsp; Guardar
					</button>
				</div>
				<div class="text-center col-md-2 col-sm-2 col-xs-2">
					<button id="Guardar" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">
						<i class="fa fa-save"></i>&nbsp; Guardar
					</button>
				</div>
				<div class="text-right col-md-2 col-sm-2 col-xs-2">
					<button type="reset" class="btn btn-default" onclick="location.href='<?= $Back; ?>'">
						<i class="fa fa-undo"></i>&nbsp; Cancelar
					</button>
				</div>
			</div>
		</div>
	</div>*/
?>
<div class="form-group offset-3">
	<div class="col-md-9 col-sm-9 col-md-9">
		<button class="btn btn-info Save SaveBack" title="Guardar registro y regresar para insertar otro" data-loading-text="Guardando..." disabled="disabled">
			<input type="checkbox" class="InsertBack" autocomplete="off" name="InsertBack" id="InsertBack<?= $FormAction;?>" value="1">
			<i class="fa fa-check"></i>&nbsp; Guardar y Regresar
		</button>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<button id="Guardar<?= $FormAction;?>" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">
			<i class="fa fa-save"></i>&nbsp; Guardar y Continuar
		</button>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<button id="Cancela<?= $FormAction;?>" type="reset" class="btn btn-default" onclick="location.href='<?= $Back; ?>'">
			<i class="fa fa-undo"></i>&nbsp; Cancelar
		</button>

	</div>
</div>