<div class="brand">
	<small>Captura tu nueva contrase&ntilde;a, y pulsa el bot&oacute;n Actualizar</small>
</div>
<form class="smart-form fs-13px form_validate" id="Form_Join" method="POST" name="Form_Join" id="Form_Join"
	action="PassReset?<?= Encrypt('Action=ConfirmReset&Usuario=' . $Usuario); ?>">
	<div class="form-floating mb-15px">
		<input type="password" class="form-control h-45px fs-13px e_requerido e_password" name="Contrase_na" id="Contrase_na" />
		<label for="Contrase_na" class="d-flex align-items-center fs-13px text-gray-600">Nueva Contrase&ntilde;a</label>
	</div>
	<div class="form-floating mb-15px">
		<input type="password"  class="form-control h-45px fs-13px e_requerido e_igual"  name="RepiteContrase_na" id="RepiteContrase_na" data-igual_a="Contrase_na"/>
		<label for="Contrase_na" class="d-flex align-items-center fs-13px text-gray-600">Repite la Contrase&ntilde;a</label>
	</div>
	<div class="mb-15px">
		<input type="hidden" name="ConfirmReset" value="ConfirmReset">
		<button type="submit" class="btn btn-success d-block h-45px w-100 btn-lg fs-14px">Actualizar</button>
	</div>
	<hr class="bg-gray-600 opacity-2"/>
	<div class="text-gray-600 text-center text-gray-500-darker mb-0">
		&copy; MSPV - Seguridad Privada
	</div>
</form>