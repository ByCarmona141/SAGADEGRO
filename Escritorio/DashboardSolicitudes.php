<div class="box-content panel-body thumbnail" style="background-color: #F9F9F9;" >
	<div class="row text-center">
		<fieldset>
			<legend>Estado de Solicitudes de Compra</legend>
		<?php
			$Cont = 1;
			foreach($Status as $State){
		?>
				<div style="margin-bottom:0px" class="col-xs-6  col-sm-3 col-md-3">
					<a href="SolicitudDeCompra?<?= EncodeThis('Status=' . $State['status'] . ($State['status'] == 7 ? '&Usuario=' . $SessionUserId:'')); ?>" class="btn btn-<?= $State['acotacion'] ?> btn-outline" role="button" title="<?= $State['description'] ?>"><?= $State['value']; ?><br><?= $State['name'] ?></a>
				</div>
		<?php
				$Cont++;
				if($Cont == 5){
					print '<div class="col-xs-12  col-sm-12 col-md-12"><br> </div>';
					$Cont = 1;
				}
			}
		?>
		</fieldset>
	</div>
</div>