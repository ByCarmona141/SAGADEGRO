<div class="brand">
	<small>Introduce tu correo electr&oacute;nicoy pulsa el bot&oacute;n Recuperar</small>
</div>
<form class="smart-form fs-13px form_validate" id="Form_Join" method="POST" name="Form_Join" id="Form_Join"
	action="PassReset?<?= Encrypt('Action=ChangePassword'); ?>">
	<div class="form-floating mb-15px">
		<input type="email" required="required" name="CorreoElectr_onico" id="CorreoElectr_onico"
			class=" form-control h-45px fs-13px" >
		<label for="txtusuario" class="d-flex align-items-center fs-13px text-gray-600">Correo
			Electr&oacute;onico</label>
	</div>
	<div class="mb-15px">
		<button type="submit" class="btn btn-success d-block h-45px w-100 btn-lg fs-14px">Recuperar</button>
	</div>
	<hr class="bg-gray-600 opacity-2"/>
	<div class="text-gray-600 text-center text-gray-500-darker mb-0">
		&copy; MSPV - Seguridad Privada
	</div>
</form>