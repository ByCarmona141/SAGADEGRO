<div class="form-group last offset-3">
	<div class="col-md-offset-3 col-md-9">
		<button id="Actualiza<?= $FormAction; ?>" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">
			<i class="fa fa-save"></i>&nbsp; Guardar Cambios
		</button>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<button type="reset" class="btn btn-default" onclick = "location.href='<?= $Back; ?>'" id="Cancela<?= $FormAction; ?>">
			<i class="fa fa-undo"></i>&nbsp; Cancelar
		</button>
	</div>
</div>