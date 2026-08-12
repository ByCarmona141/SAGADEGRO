<?php
	require_once('../Libraries/Functions.php');

	$HTML = LoadContentPage('../Libraries/securimage/securimage_show.php', array('Length' => $_GET['Length']));
	ViewPage($HTML);
?>