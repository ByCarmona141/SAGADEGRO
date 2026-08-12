<!-- Modal Bloqueo -->
<div class="modal fade bs-example-modal-sm" id="CelaModalLockSession" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
	<div class="modal-dialog modal-sm modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel">
				<?= $HostName; ?>
				</h3>
			</div>
			<div class="modal-body text-center">
				<img style="max-height: 70px !important;" class="img-circle"
				     src="https://lh3.googleusercontent.com/-uBVxKrZp7yw/AAAAAAAAAAI/AAAAAAAAAAA/AMZuucmAk8ZAkEJH5spu1YCJXr54Xbmk7Q/"/>
				<br/>
				<h4>
					<span class="label label-primary">
					<?= 'Usuario: ' . $SessionUser; ?>
					</span>
				</h4>
				<br/>
				<input type="password" class="form-control" name="txtcontrasena" id="txtcontrasena" type="password" autocomplete="off" placeholder="Contrase&ntilde;a"/><br/>
				<label class="label error" id="CelaLabelMessageLockSession" style="font-size: 12pt; max-height: 24px !important;"></label>
				<hr/>
				<span class="clear-fix"></span>
				<button class="btn btn-lg btn-primary btn-block" id="CelaBoto_oUnLockSession" name="CelaUnLockSession">
					Desbloquear
				</button>
			</div>
			<div class="modal-footer">
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div><!-- /.modal -->
