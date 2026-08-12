<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="Sistema de control y seguimiento.">
		<meta name="author" content="">
		<!-- start: Mobile Specific -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- end: Mobile Specific -->
		<link href="http://sc.ambet.com.mx/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<style>
			body {
				width: 1500px;
			}

			table thead tr th div {
				font-size: 12px !important;
			}

			table tfoot tr td {
				font-size: 12px !important;
			}

			table tbody tr td div {
				font-size: 10px !important;
			}

			table tbody tr td {
				font-size: 10px !important;
			}

			.table > thead > tr > th {
				vertical-align: middle;
			}

			.table > tbody > tr > td {
				vertical-align: middle;
			}

			thead{
				display: table-header-group;
			}

			tfoot{
				display: table-row-group;
			}

			tr{
				page-break-inside: avoid;
			}
		</style>
	</head>
	<body>
		<div id="exportado">
			<table class="table table-bordered table-striped" id="tabla_exportado" width="<!--#PAGESIZE#-->" border="0"
			       frame="void">
				<thead>
				<tr>
					<td colspan="<!--COLSPAN-->" width="100%" class="encabezado centrado bl br bt">
						<div>
							<div class="izquierda" style="float: left">
								<!--#SIDELEFT#-->
							</div>
							<div class="derecha" style="float: right; padding: 15px 0 0 0;">
								<!--#SIDERIGHT#-->
							</div>
							<div align="center">
								<h4><!--#REPORTTITLE#--></h4>
							</div>
						</div>
					</td>
				</tr>
					<!--#HEADER#-->
				</thead>
				<tbody>
					<!--#BODY#-->
				</tbody>
				<tfoot>
					<!--#FOOTER#-->
				</tfoot>
			</table>
		</div>
	</body>
</html>