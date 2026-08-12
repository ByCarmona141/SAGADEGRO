<?php
	require_once('../Libraries/Functions.php');
	require_once('../Libraries/Session.class.php');
	require_once('../Libraries/Connection.php');

	/*Se obtienen las configuraciones*/
	$GlobalConfig = array();
	$Query = 'SELECT * FROM CelaConfiguraci_on';
	$Result = $Connection->query($Query);
    
	while($Record = $Result->fetch_assoc()){
		$GlobalConfig[(isset($Record['Code']) && $Record['Code'] != ''? $Record['Code']:$Record['Nombre'])] = $Record['Valor'];
	}

	@session_start();

	//Si ya hay un usuario registrado redireccionamos al escritorio
	if(isset($_COOKIE['CelaRandom']) && $_COOKIE['CelaRandom'] != '' && isset($_COOKIE['idUsuario']) && $_COOKIE['idUsuario'] != ''){
		$Session = new CelaSession();
		$Session -> SetUser($_COOKIE['idUsuario']);
		$Session -> SetCookie($_COOKIE['CelaRandom']);
		$Session -> SetConnection($Connection);

		$Session -> Start();

		$RedirectLoginSuccess = GetValue(
			sprintf('SELECT Referencia FROM CelaMen_u WHERE `id` = %s;',
				GetSQLValueString($Session -> Value('CelaCurrentMenu'), 'int')
			),
			'Referencia'
		);

		$Action = ($Session -> Value('CelaFormAction'));

		$RedirectLoginSuccess = ($RedirectLoginSuccess == 'NULL' || $RedirectLoginSuccess == '' || empty($RedirectLoginSuccess) ? 'Escritorio':$RedirectLoginSuccess);
		$Connection -> close();
		header(sprintf('Location: %s?%s', $RedirectLoginSuccess, ($Action)));
		exit();
	}else{
		DestroySession();
	}

	$_GET       = NULL;
	$String     = substr(strrchr($_SERVER['REQUEST_URI'], '?'), 1); //Obtener la url desde el ?
	$String     = Decrypt($String, 'b5s1i4t5a1316');
	$GET = preg_split ('[&]', $String); //separo la url por &

	foreach($GET as $Value){
		$GET = preg_split ('[=]', $Value); //asigno los valores al GET

		if(substr_count($GET[0], '[]') == 1){
			//Es un arreglo
			$GET[0]             = str_replace('[]', '', $GET[0]);
			$_GET[$GET[0]][]    = (isset($GET[1]) ? $GET[1]:'');
		}
		else{
			$_GET[$GET[0]]      = (isset($GET[1]) ? $GET[1]:'');
		}
	}

	//Obtenemos la configuración para el capcha.
	//	$captcha = GetValue("SELECT Valor FROM CelaConfiguraci_on WHERE Nombre='Captcha'",'Valor');
	$captcha = $GlobalConfig['Captcha'];
	if($captcha == 1) {
		require_once('../Libraries/securimage/securimage.php');
		$securimage = new Securimage();
		//		$Length     = (int) GetValue(
		//								sprintf('SELECT Valor FROM CelaConfiguraci_on WHERE Nombre = %s;',
		//									GetSQLValueString('Tama_noCaptcha', 'varchar')
		//								),
		//								'Valor'
		//							);
		$Length     = (int) $GlobalConfig['Tama_noCaptcha'];
	}

	//	$HostName       =   GetValue(
	//							sprintf('SELECT Valor FROM CelaConfiguraci_on WHERE Nombre = %s;',
	//								GetSQLValueString('NombreSistema', 'varchar')
	//							),
	//							'Valor'
	//						);
	$HostName       =   $GlobalConfig['NombreSistema'];

	//	$SloganSystem   =   GetValue(
	//							sprintf('SELECT Valor FROM CelaConfiguraci_on WHERE Nombre = %s;',
	//								GetSQLValueString('Slogan', 'varchar')
	//							),
	//							'Valor'
	//						);
	$SloganSystem   =   $GlobalConfig['Slogan'];

	//	$SystemLogo     =   GetValue(
	//							sprintf('SELECT Valor FROM CelaConfiguraci_on WHERE Nombre = %s;',
	//								GetSQLValueString('LogoPantallaInicial(RecomendadoUtilizarImagen246x52SinColorDeFondo)', 'varchar')
	//							),
	//							'Valor'
	//						);
	$SystemLogo     =   $GlobalConfig['Logotipo'];

	//	$SystemImage    =   GetValue(
	//							sprintf('SELECT Valor FROM CelaConfiguraci_on WHERE Nombre = %s;',
	//								GetSQLValueString('ImagenPantallaInicial', 'varchar')
	//							),
	//							'Valor'
	//						);
	$SystemImage    =   $GlobalConfig['ImagenPantallaInicial'];

	//	$SystemIcon     =   GetValue(
	//							sprintf('SELECT Valor FROM CelaConfiguraci_on WHERE Nombre = %s;',
	//								GetSQLValueString('IconoDeSistema', 'varchar')
	//							),
	//							'Valor'
	//						);
	$SystemIcon     =   $GlobalConfig['IconoDeSistema'];

//	print_r($_GET);
?>

<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title><?= $HostName?></title>
		<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
		<meta content="" name="description"/>
		<meta content="" name="author"/>

		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet"/>
		<link href="assets/css/vendor.min.css" rel="stylesheet"/>
		<link href="assets/css/facebook/app.min.css" rel="stylesheet"/>
		<link href="assets/css/facebook/app.min.css" rel="stylesheet"/>
		<link rel="shortcut icon" href="<?= $SystemIcon; ?>">

	</head>
	<body class='pace-top'>

	<div id="loader" class="app-loader">
		<span class="spinner"></span>
	</div>


	<div id="app" class="app">

		<div class="login login-with-news-feed">

			<div class="news-feed">
				<div class="news-image" style="background-image: url('<?= $SystemImage ?>'); no-repeat center center fixed !important;
					-webkit-background-size: cover !important;
					-moz-background-size: cover !important;
					-o-background-size: cover !important;
					background-size: cover !important;"></div>
				<div class="news-caption">
					<h4 class="caption-title"><b><?= $HostName?></b> Dispositivos</h4>

					<p>
						Oficina de gobierno local en Chilpancingo de los Bravo
					</p>
				</div>
			</div>


			<div class="login-container">

				<div class="login-header mb-30px">
					<div class="brand">
						<div class="d-flex align-items-center">
							<b><?= $HostName?></b>
<!--                            Seguridad Privada-->
						</div>
						<small>Introduce tus credenciales para iniciar sesi&oacute;n</small>
					</div>
					<div class="icon">
						<i class="fa fa-sign-in-alt"></i>
					</div>
				</div>
				<div class="login-content">
					<form action="Accesar.php" id="form1" class="smart-form fs-13px form_validate" method="post">
						<div class="form-floating mb-15px">
							<input type="text" name="txtusuario" id="txtusuario" class=" form-control h-45px fs-13px" placeholder="Nombre de Usuario" required="required">
							<label for="txtusuario" class="d-flex align-items-center fs-13px text-gray-600">Usuario</label>
						</div>
						<div class="form-floating mb-15px">
							<input type="password" name="txtcontrasena" id="txtcontrasena" class="form-control h-45px fs-13px" autocomplete="off" placeholder="Contraseña" required="required">
							<label for="txtcontrasena" class="d-flex align-items-center fs-13px text-gray-600">Contrase&ntilde;a</label>
						</div>

				<?php
					if($captcha == 1){
				?>
						<div class="form-floating mb-15px">
							<input type="text" name="txtcaptcha" class="form-control h-45px fs-13px" id="txtcaptcha" required="required" autocomplete="off" maxlength="<?= $Length; ?>" placeholder="Captcha de seguridad">
							<label for="txtcontrasena" class="d-flex align-items-center fs-13px text-gray-600">Captcha de Seguridad</label>
						</div>
						<div align="center">
							<br>
							<img class="img-polaroid" id="captcha" src="SecurImageShow.php?Length=<?= $Length; ?>" alt="Codigo de seguridad" style="width: 200px !important; height: 80px !important;" />
							<a class="text-center new-account" href="#" onclick="document.getElementById('captcha').src = 'SecurImageShow.php?Length=<?= $Length; ?>&' + Math.random(); return false"><br />[ Otra imagen ] / [ Different Image ]</a>
						</div>
				<?php
					}
				?>

						<div class="mb-15px">
							<input type="hidden" name="CelaAction" value="Login">
							<button type="submit" class="btn btn-primary d-block h-45px w-100 btn-lg fs-14px">ENTRAR</button>
						</div>
						<div class="mb-40px pb-40px text-inverse">
							&iquest;Olvidaste tu contraseña? <a href="PassReset" class="text-primary">haz click aqu&iacute;</a>.
						</div>
						<hr class="bg-gray-600 opacity-2"/>
						<div class="text-gray-600 text-center text-gray-500-darker mb-0">
							&copy; MSPV - Seguridad Privada
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


	<script src="assets/js/vendor.min.js" type="ce483d7d120b55f49724313d-text/javascript"></script>
	<script src="assets/js/app.min.js" type="ce483d7d120b55f49724313d-text/javascript"></script>
	<script src="assets/js/theme/facebook.min.js" type="ce483d7d120b55f49724313d-text/javascript"></script>

	<script src="https://ajax.cloudflare.com/cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js" data-cf-settings="ce483d7d120b55f49724313d-|49" defer=""></script>
	<script defer src="https://static.cloudflareinsights.com/beacon.min.js" data-cf-beacon='{"rayId":"66c28c476be6365b", "version":"2021.6.0","r":1,"token":"4db8c6ef997743fda032d4f73cfeff63","si":10}'></script>
	<script src="assets/plugins/sweetalert/dist/sweetalert.min.js"></script>
	<script>
		var error = '';
		<?php
			if(isset($_GET['CaptchaFail']) && $_GET['CaptchaFail']==1){
				if($_GET['Try'] <= 0){
		?>
		error = 'El usuaurio ha sido bloqueado por superar el número de intentos permitidos. Pongase en contacto con el adminsitrador';
		<?php
				}else{
		?>
		error = 'El código de acceso no coincide, vuelva a intentarlo.\nRestan <?= $_GET['Try']; ?> intentos';
		<?php
				}
			}
			if(isset($_GET['UserLock']) && $_GET['UserLock']==1){
		?>
		error = 'Este usuario se encuentra bloquedo, pongase en contacto con el administrador';
		<?php
			}
			if(isset($_GET['LoginFail']) && $_GET['LoginFail']==1){
				if($_GET['Try'] <= 0){
		?>
		error = 'El usuaurio ha sido bloqueado por superar el número de intentos permitidos. Pongase en contacto con el adminsitrador';
		<?php
				}else{
		?>
		error = 'Error al iniciar sesión, verifique el nombre de usuario y la contraseña.\nRestan <?= $_GET['Try']; ?> intentos';
		<?php
			}
		}
			if(isset($_GET['LogFail']) && $_GET['LogFail']==1){
		?>
		error = 'Error al registrar el log';
		<?php
			}
		?>

		if(error != ''){
			swal({
				title: 'Error al Iniciar Sesión',
				text: error,
				icon: 'error',
				buttons: {
					confirm: {
						text: 'Aceptar',
						value: true,
						visible: true,
						className: 'btn btn-primary',
						closeModal: true
					}
				}
			});
		}

	</script>
	</body>
</html>
<?php
	$Connection -> close();
?>