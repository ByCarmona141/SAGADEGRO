<?php
	require_once('../Libraries/Functions.php');
	require_once('../Libraries/ExceptionThrower.php');
	require_once('../Libraries/Session.class.php');
	$HTML = LoadContentPage('../Libraries/DataTableServer.php');
	ViewPage($HTML);
?>