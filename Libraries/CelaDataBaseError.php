<!DOCTYPE html>
<html lang="es">
	<head>
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="Sistema de control y seguimiento.">
		<meta name="author" content="">
		<title>
			Error Conection
		</title>
		<!-- start: Mobile Specific -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- end: Mobile Specific -->
		<link id="bootstrap-style" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link id="font" href="bootstrap/font/css/font-awesome.css" rel="stylesheet">
		<!--[if lt IE 9]>
		<script src="docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
	<div class="container" style="width: 99%;"><!--Main Container-->
		<div class="clearfix"></div>
		<hr>
		<div class="row"><!--Row Container-->
			<div class="col-md-12"><!--Body-->
				<div class="row sortable"><!--Row Body Content-->
					<div class="alert alert-danger">
						<h3>Oops!...</h3>

						<p> Ocurrio un error al establecer conexi&oacute;n con la base de datos: <?= $Error; ?></p>
					</div>
				</div>
				<!--End Row Body Content-->
			</div>
			<!--End Body-->
		</div>
		<!--End Row Container-->
		<hr>
		<div class="clearfix"></div>
		<div class="row">
			<footer>
				<p class="pull-left">&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="http://www.ambet.com.mx" target="_blank">

					</a>&nbsp; &copy;
					<?= date('Y'); ?>&nbsp; <?= $HostName; ?>
				</p>
			</footer>
		</div>
	</div>
	<!--End Main Container-->
	</body>
</html>