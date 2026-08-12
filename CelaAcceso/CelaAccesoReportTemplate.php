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
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<style>
		body {
			width: <!--#PAGESIZE#-->;
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
	<table class="table table-bordered table-striped" id="tabla_exportado" width="<!--#PAGESIZE#-->" border="0" frame="void">
		<thead>
		<tr>
			<td colspan="" width="100%" class="encabezado centrado bl br bt">
				<div>
					<div class="izquierda" style="float: left">
						<img width="175px" src="repositorio/configuracion/logo.jpg" class="img-polaroid" alt="">
					</div>
					<div class="derecha" style="float: right; padding: 15px 0 0 0;">
						<span class="letrapequenia">Cela Theme</span>
					</div>
					<div align="center">
						<h4>Listado de Accesos</h4>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<th width="10%">
				<div align="center">
					Fecha
				</div>
			</th>
			<th width="30%">
				<div align="center">
					Usuario
				</div>
			</th>
			<th width="25%">
				<div align="center">
					Origen
				</div>
			</th>
			<th width="10%">
				<div align="center">
					id Registro
				</div>
			</th>
			<th  width="15%">
				<div align="center">
					Acci&oacute;n
				</div>
			</th>
			<th width="10%">
				<div align="center">
					Datos
				</div>
			</th>
		</tr>
		</thead>
		<tbody>
		</tbody>
			<!--#BODY#-->
		<tfoot>
			<tr>
				<div style="" >
					<div style="float: left;" align="left"> <span class="encabezado-lg">TOTAL DE REGISTROS: <!--#TOTALRECORDS#--></div>
					<div style="float: right;" align="right"><span class="encabezado-lg">FECHA: <!--#DATE#--></div>
				</div>
			</tr>
		</tfoot>
	</table>
</div>
