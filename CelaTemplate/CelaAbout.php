<!-- #modal-dialog -->
<div class="modal fade" id="CelaModalAcercaDe" data-keyboard="false">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?= $HostName; ?></h4>
				<button type="button" class="btn-close" data-dismiss="modal" aria-hidden="true"></button>
			</div>
			<div class="modal-body">
				<div class="row fluid-sortable">
					<div class="box col-md-5">
						<div class="image-inner">
							<img src="<?= $Logo; ?>" class="img-responsive" style="height: 100px !important;"/>

						</div>
					</div>
					<div class="box col-md-7">
						<div class="box-content" align="center">
							<p>
								<strong>
									EEE de M&eacute;xico SA de CV
								</strong>
							</p>
							<br/>
							<p>
								<?= $HostName . ' (' . $Slogan . ')'; ?>
							</p><br/>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Aceptar</a>
			</div>
		</div>
	</div>
</div>