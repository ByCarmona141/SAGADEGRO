<?php
	require_once('../Libraries/Functions.php');
	require_once('../Libraries/Session.class.php');
	require_once('../Libraries/AjaxsFunctions.php');
	require_once('../Libraries/Connection.php');
	require_once('../Libraries/GetSession.php');

	/*Se verifica si hay libreriras a utilizar y se incluyen*/
	if(isset($_POST['Require']) && $_POST['Require'] != ''){
		$Require = explode(';', $_POST['Require']);
		for($i = 0; $i < count($Require); $i++){
			require_once($Require[$i]);
		}
	}

	/*Se verifica si existe la variable $_GET y se decodifica*/
	if($_GET)
		DecodeGet($_SERVER['REQUEST_URI']);

	$Output = null;

	if(function_exists($_POST['Function']) && $_POST['Function'] != 'phpinfo'){
		if(isset($_POST['GetPrivileges']) && $_POST['GetPrivileges'] == 1 && isset($_POST['RouteForm']) && $_POST['RouteForm'] != ''){
			/*Se Obtienen los privilegios del rol*/
			$PrivilegesGroup    = GetFormPrivileges($_POST['RouteForm'], $SessionGroupId, 2);
			/*Se Obtienen los privilegios del usuario*/
			$PrivilegesUser     = GetFormPrivileges($_POST['RouteForm'], $SessionUserId, 3);

			$Privileges = array_merge($PrivilegesGroup, $PrivilegesUser);
		}

		$GetOutput  = $_POST['Function'];
		$Output     = $GetOutput($_POST);
		if(is_array($Output)){
			print json_encode($Output);
		}else{
			print ($Output);
		}

	}else{
		$Output = array(
			'Status'    => 'ERROR',
			'Error'     => 'No existe al funcion que intenta invocar'
		);
		print json_encode($Output);
		exit();
	}
?>