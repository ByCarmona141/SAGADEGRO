<?php
	$File   = 'http://' . $_SERVER['SERVER_NAME'] . substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/') + 1) . $SourceFile;
	$Source ="http://docs.google.com/viewer?url=".$File."&embedded=true";
?>
<div class="row col-md-12">
    <div class="col-md-3">
        <a class="btn btn-success" href="CelaRepositorio?<?= EncodeThis('Action=Descargar&Key=' . $_GET['Key']) ?>"><i class="fas fa-download"></i>&nbsp; Descargar</a>
    </div>
</div>
<iframe style="width: 100%; height: 640px;" id="PreviewFrame" frameborder="0" src="<?= $Source; ?>" ></iframe>
