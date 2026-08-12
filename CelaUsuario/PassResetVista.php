<?php
	$SystemIcon     =   GetValue(
							sprintf('SELECT Valor FROM CelaConfiguraci_on WHERE Nombre = %s;',
								GetSQLValueString('IconoDeSistema', 'varchar')
							),
							'Valor'
						);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>MSPV - Portal de Clientes</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
	<meta content="" name="description"/>
	<meta content="" name="author"/>

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet"/>
	<link href="assets/css/vendor.min.css" rel="stylesheet"/>
	<link href="assets/css/facebook/app.min.css" rel="stylesheet"/>
	<link href="assets/css/facebook/app.min.css" rel="stylesheet"/>
	<link href="assets/plugins/passchecker/css/passtrength.css" rel="stylesheet"/>
	<link rel="shortcut icon" href="<?= $SystemIcon; ?>">

</head>
<body class='pace-top'>

<div id="loader" class="app-loader">
	<span class="spinner"></span>
</div>


<div id="app" class="app">

	<div class="login login-with-news-feed">

		<div class="news-feed">
			<?php
				$SystemImage    =   GetValue(
										sprintf('SELECT Valor FROM CelaConfiguraci_on WHERE Nombre = %s;',
											GetSQLValueString('ImagenPantallaInicial', 'varchar')
										),
										'Valor'
									);
			?>
			<div class="news-image" style="background-image: url('<?= $SystemImage ?>'); no-repeat center center fixed !important;
				-webkit-background-size: cover !important;
				-moz-background-size: cover !important;
				-o-background-size: cover !important;
				background-size: cover !important;"></div>
			<div class="news-caption">
				<h4 class="caption-title"><b>MSPV</b> Portal de Clientes</h4>

				<p>
					Lago Superior No.25, Col. Tacuba, Alc. Miguel Hidalgo, C.P. 11410 CDMX
				</p>
			</div>
		</div>


		<div class="login-container">

			<div class="login-header mb-30px">
				<div class="brand">
					<div class="d-flex align-items-center">
						<b>MSPV</b> Seguridad Privada
					</div>
					<small>&iquest;Olvidaste tu contrase&ntilde;a? No te preocupes, vamos a recuperarla</small>
				</div>
				<div class="icon">
					<i class="fa fa-question"></i>
				</div>
			</div>
			<div class="login-content">
				<!--#Content#-->
			</div>
		</div>
	</div>
</div>


<?php
	$GlobalConfig['TiempoDeBloqueoDeSesi_on(EnSegundos)'] = 30;
	include '../CelaTemplate/CelaJavascript.php';

	if(isset($_GET['User']) && $_GET['User'] != ''){
?>

<script src="assets/plugins/passchecker/js/jquery.passtrength.js" type="f8293aa45fd314b76f6f06dd-text/javascript"></script>
<script type="f8293aa45fd314b76f6f06dd-text/javascript">
	$(document).ready(function(){
		$(function(){
			$('#Contrase_na').rules('add', {
				remote: {
					url: 'CheckPassword.php',
					type: 'post',
					data: {
						Password: function(){
							return  CryptoJS.MD5($('#Contrase_na').val()).toString();
						},
						Us: function(){
							return  <?= $_GET['User']; ?>;
						}
					}
				},
				messages:{
					remote: function(){return 'No puede utilizar una contraseña anterior'}
				}
			});
			$('#Contrase_na').passtrength({
                minChars: 8,
                passwordToggle: true,
                eyeImg: 'assets/plugins/passchecker/css/eye.svg',
                tooltip: true
            });
		})
	});
</script>
<?php
	}
?>
</body>
</html>