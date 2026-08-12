	<div class="list-group">
<?php
	for($r = 0; $r < count($Reporte); $r++){
?>
		<a href="<?=  $Reporte[$r]['Referencia'] . '?' . EncodeThis('MenuId=' . $Reporte[$r]['id']); ?>" class="list-group-item <?= ($r == 0 ? 'active':''); ?>"><?= $Reporte[$r]['Nombre']; ?> &nbsp; (<?= $Reporte[$r]['Descripci_on']; ?>)</a>
<?php
	}
?>
	</div>