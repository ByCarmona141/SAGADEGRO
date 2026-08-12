<?php
	if(isset($Privileges['Eliminar']) && $Privileges['Eliminar'] == 1 && $Cleavable == 1){
?>
<div id="CelaMessageModalEliminar" tabindex="100" class="modal fade modal-message" aria-hidden="false" aria-labelledby="myModalLabel" role="dialog">
	<div class="modal-dialog  modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h4><i class="fa fa-warning"></i>&nbsp; Precauci&oacute;n...!!!</h4>
			</div>
			<div class="modal-body" id="CelaMessageModalEliminarBody">
				<span>&iquest;Realmente desea eliminar el/los elemento(s) seleccionado(s)?</span>
			</div>
			<div class="modal-footer">
				<a class="btn btn-default" id="CelaMessageBot_onEliminarCancelar" data-dismiss="modal">
					<i class="fa fa-undo"></i>&nbsp; Cancelar
				</a>
				<a class="btn btn-danger pull-left" id="CelaMessageBot_onEliminarAceptar" href="">
					<i class="fa fa-trash-alt"></i>&nbsp; Eliminar
				</a>
			</div>
		</div>
	</div>
</div>
<?php
	}
?>
<div class="mailbox-read-info">
	<h3><?= $Message['Subject']; ?></h3>
	<h5>
		<span data-localize="mailbox-read.FROM">De:</span><?= $Message['From']; ?>
		<span class="mailbox-read-time pull-right"><?= date('d/m/Y H:i:s', strtotime($Message['Time'])) ?></span>
	</h5>
</div>
<!-- /.mailbox-read-info -->
<div class="mailbox-controls with-border text-center">
	<a href="CelaMessage" class="btn btn-default btn-sm" title="Regresar">
		<i class="fa fa-level-down fa-rotate-90" aria-hidden="true"></i>
	</a>
	<div class="btn-group">
<?php
	if(isset($Privileges['Eliminar']) && $Privileges['Eliminar'] == 1 && $Cleavable == 1){
?>
		<a id="DeleteMessage" href="CelaMessage<?= EncodeThis('Action=Eliminar&Key[]=' . $idMessage); ?>" class="btn btn-default btn-sm" title="Eliminar">
			<i class="fa fa-trash-alt"></i>
		</a>
<?php
	}
?>
		<a class="btn btn-default btn-sm" title="Responder" href="CelaMessage?<?= EncodeThis('Action=Crear&RR=1&idMessage=' . $idMessage) ?>">
			<i class="fa fa-reply"></i>
		</a>
		<a type="button" class="btn btn-default btn-sm" title="Reenviar" href="CelaMessage?<?= EncodeThis('Action=Crear&RR=2&idMessage=' . $idMessage) ?>">
			<i class="fa fa-share"></i>
		</a>
	</div>
	<!-- /.btn-group -->
	<button id="PrintMessage" type="button" class="btn btn-default btn-sm" title="Imprimir">
		<i class="fa fa-print"></i>
	</button>
</div>
<!-- /.mailbox-controls -->
<div class="mailbox-read-message">
	<div id="MessageArea">
		<?= $Message['Message']; ?>
	</div>
</div>
<ul class="mailbox-attachments clearfix">
<?php
	for($f = 0; $f < count($Files); $f++){
		$Extension = explode('.',  $Files[$f]['Nombre']);
		$Extension = end($Extension);

		switch(strtolower($Extension)){
			case 'pdf':
				$Extension = 'fa-file-pdf-o';
				break;
			case 'xls':
			case 'xlsx':
				$Extension = 'fa-file-excel-o';
				break;
			case 'php':
			case 'html':
			case 'xml':
				$Extension = 'fa-file-code-o';
				break;
			case 'doc':
			case 'docx':
			case 'rtf':
				$Extension = 'fa-file-word-o';
				break;
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':
				$Extension = 'fa-file-image-o';
				break;
			case 'ppt':
			case 'pptx':
				$Extension = 'fa-file-powerpoint-o';
				break;
			case 'zip':
			case 'rar':
			case 'targz':
				$Extension = 'fa-file-zip-o';
				break;
			default:
				$Extension = 'fa-file-o';
				break;
		}
?>
		<li>
			<span class="mailbox-attachment-icon"><i class="fa <?= $Extension; ?>"></i></span>
			<div class="mailbox-attachment-info">
				<a href="CelaRepositorio?<?= EncodeThis('Action=Descargar&Key=' . $Files[$f]['id']) ?>" class="mailbox-attachment-name">
					<i class="fa fa-paperclip"></i> <?= $Files[$f]['Nombre']; ?>
				</a>
				<span class="mailbox-attachment-size"><?= number_format(($Files[$f]['Tama_no']/1024), 2); ?> KB
					<a href="CelaRepositorio?<?= EncodeThis('Action=Descargar&Key=' . $Files[$f]['id']) ?>" class="btn btn-default btn-xs pull-right">
						<i class="fa fa-cloud-download"></i>
					</a>
				</span>
			</div>
		</li>
<?php
	}
?>
</ul>