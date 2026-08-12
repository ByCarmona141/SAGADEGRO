<?php
	/**

	 * Fecha: 12/11/2015
	 * Descripción: Error Pagina no Encontrada.
	 **/

	require_once('../Libraries/Functions.php');
	$HTML = LoadContentPage('../document_errors/404.html');
	ViewPage($HTML);
?>