<?php 	
$tamanio_dehoja="735px"; //735 ideal

$mihtml='
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Factura Electrónica</title>
<style type="text/css">
body, h1, h2, h3, h4, h5, h6{
	font-family: "Times New Roman",Georgia,Serif; font-size:12px; }
table {
	table-layout: fixed; word-wrap: break-word; color: #000; border-spacing: 0px; }
table, tbody, tfoot, thead, tr, th, td{ 
	margin: 0; padding: 0; }
.valorUnitario {
	text-align: right; font-size:12px; padding: 0 5px 0 0; }
.centrado {
	text-align: center; clear: both; }
.derecha {
	text-align: right; }
.izquierda {
	text-align: left; }
.cadena {
	text-autospace:none; white-space: pre; white-space: pre-wrap; white-space: pre-line; white-space: -pre-wrap; white-space: -o-pre-wrap; white-space: -moz-pre-wrap; white-space: -hp-pre-wrap; word-wrap: break-word; padding:3px 20px 3px 3px !important; margin:3px 20px 3px 3px !important; }
.letrapequenia{
	font-size:10px; }
.letramediana{
	font-size:12px; }
.encabezado{
	font-size:12px; font-weight:bold; background:'.$fondo_color.';color:'.$texto_color.'; }
.margin_extra{
	padding: 5px 5px 5px 5px; margin: 5px 5px 5px 5px; }
.margin_simple{
	margin: 5px 0; }
.tipodocumento{
	font-size:14px; }
table tr td{ border:1px solid black  !important; }
table tr th{ border:1px solid black  !important; }
.bl{
	border-left:#FFF none !important; }
.br{
	border-right:#FFF none !important; }
.bt{
	border-top:#FFF none !important; }
.bb{
	border-bottom:#FFF none !important; }
#conceptos{
	width:'.$tamanio_dehoja.'; min-height:290px; border:1px solid black; } 
#imagen_fondo{
	width:'.$tamanio_dehoja.'; min-height:270px; position:absolute; top:280px;
	background: url("http://efectosfiscales.mx/'.$url_logo.'")  no-repeat center;  
	background-size: 350px auto; -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"; filter: alpha(opacity=20); -moz-opacity:0.2; -khtml-opacity: 0.2; opacity: 0.2; }
#tabla_conceptos{
	-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"; filter: alpha(opacity=100); -moz-opacity:1; -khtml-opacity: 1; opacity: 1; }
.marca_propia{
	height:20px; float:right; }
.concepto{
	border-top:#FFF none !important; border-bottom:#FFF none !important; border-left: 1px solid black !important; border-right: 1px solid black !important; }
.concepto td{
	border:#FFF none !important; }
.qr_imagen{
	heigth:140px; width:140px; text-align: left;}
.fila_qr{
	padding:5px 5px; margin:5px 5px;	}
.emisor{
	font-size:14px; display:block; margin: 0 0 5px 0;	}
.preview{  font-size:23px; z-index:10000; top:10px; left:10px; color:#C1C1C1; position:absolute; writing-mode: lr-tb; }
.cancelado{ font-size:100px; z-index:10000; top:300px; left:50px; color:red; position:absolute; -webkit-transform: rotate(320deg); -moz-transform: rotate(320deg); -o-transform: rotate(320deg); writing-mode: lr-tb; }
</style>
</head>

<body>
<table width="'.$tamanio_dehoja.'" border="1" frame="void">
  <tr>
    <td colspan="6" rowspan="5" align="center" class="bl br bt bd" >';
 //if(!is_null($url_logo))
 
 $mihtml .=' 	
 	</td>
    <td colspan="4" class="encabezado centrado tipodocumento bb">'.$tipo_factura.'</td>
  </tr>
  <tr>
    <td colspan="2" class="derecha bb br">Serie:</td>
    <td colspan="2" class="izquierda letramediana bb">&nbsp;'.$serie.'</td>
  </tr>
  <tr>
    <td colspan="2" class="derecha bb br">Folio:</td>
    <td colspan="2" class="izquierda letramediana bb">&nbsp;'.$folio.'</td>
  </tr>
  <tr>
    <td colspan="2" class="derecha br">Fecha:</td>
    <td colspan="2" class="izquierda letramediana">&nbsp;'.$fecha.'</td>
  </tr>
  <tr class="bl br">
    <td colspan="2" class="derecha bt bl br">&nbsp;</td>
    <td colspan="2" class="izquierda letramediana bt bl br">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"class="encabezado bt bb br"><span class="emisor">'.$razonsocial.'</span>
    Lugar de expedición: '.$lugarexpedicion.'<br />
	

    </td>
    <td colspan="5"class="encabezado bt bb bl"><span class="emisor">'.$rfc.'</span> '.$calle.' '.$numexterior.' '.$numinterior.' '.$colonia.'<br />
    	C.P: '.$cp.', '.$municipio.', '.$estado.'. '.$pais.'</td>
  </tr>
  <tr>
  	<td colspan="10" class="encabezado bt"><span class="">'.$regimen.'</span></td>
  </tr>
  </table>
  <div class="margin_simple"></div>
  <table width="'.$tamanio_dehoja.'" border="1" frame="void">
  <tr>
    <td colspan="10 margin_extra">
    Cliente: <strong>'.$Rrazonsocial.'</strong><br /> RFC: <strong>'.$Rrfc.'</strong><br />'.$Rcalle." ".$Rnumexterior." ".$Rnuminterior." "."Colonia: ".$Rcolonia. ". ".$Rmunicipio.", ".$Restado." "."C.P.: ".$Rcp.'<br />										              	
	</td>
  </tr>
  </table>
  <div class="margin_simple"></div>
  <div id="imagen_fondo"></div>
  <div id="conceptos">
  <table  id="tabla_conceptos"  width="'.$tamanio_dehoja.'" border="1" frame="void">
  <thead>
  <tr>
    <th width="10%" class="encabezado centrado bt bl br">Cantidad</th>
    <th width="10%" class="encabezado centrado bt br">Unidad</th>
    <th colspan="6" width="40%" class="encabezado centrado bt br">Descripción</th>
    <th width="15%"class="encabezado centrado bt br">Valor Unitario</th>
    <th width="15%"class="encabezado centrado bt br">Importe</th>
  </tr>
  </thead>';
  
   for($i=0; $i<$numerodeconceptos;$i++)
			{
				$mihtml .= 
				'<tr class="concepto">
    <td width="10%"  class="centrado">'.$concepto[$i]['cantidad'].'</td>
    <td width="10%" class="centrado" >'.$concepto[$i]['unidad'].'</td>
    <td width="40%" colspan="6">'.$concepto[$i]['descripcion'].'</td>
    <td width="15%" class="valorUnitario">$'.number_format($concepto[$i]['precio'], $redondeo,'.', ',').'</td>
    <td width="15%" class="valorUnitario">$'.number_format($concepto[$i]['importe'], $redondeo, '.', ',').'</td>
  </tr>';
			}	
		
   
  $mihtml.= '  
  </table>
  </div>
  <div class="margin_simple"></div>';
 if($tipocomp=="factura"){   //para el tipo de factura electronica
  $mihtml.= '  
  <table width="'.$tamanio_dehoja.'" border="1" frame="void">
  <tr>
    <td colspan="7" class="encabezado centrado bb br">Importe con letra</td>
    <td class="bt bb">&nbsp;</td>
    <td width="10%" class="encabezado bl br bb">Subtotal</td>
    <td width="15%" class="valorUnitario  bb">$'.number_format($subtotal, $redondeo, '.', ',').'</td>
  </tr>
  <tr>
    <td colspan="7" class="bb br">'.$letras.'</td>
    <td class="bt bb">&nbsp;</td>
    <td class="encabezado bl br bb">I.V.A.</td>
    <td width="15%" class="valorUnitario  bb">$'.number_format($iva, $redondeo, '.', ',').'</td>
  </tr>
  <tr>
    <td colspan="3" class="encabezado centrado bb br">Metodo de Pago</td>
    <td colspan="2" class="encabezado centrado bb br">Num. de Cuenta</td>
    <td colspan="2" class="encabezado centrado bb br">Tasa IVA</td>
    <td class="bt bb">&nbsp;</td>
    <td class="encabezado bl br">TOTAL</td>
    <td width="15%" class="valorUnitario ">$'.number_format($total, $redondeo, '.', ',').'</td>
  </tr>
  <tr>
    <td colspan="3" class="centrado br">'.$metodopago.'</td>
    <td colspan="2" class="centrado br">'.$numcuentapago.'</td>
    <td colspan="2" class="centrado br">'.$tasaIVA.' %</td>
    <td class="bt bb br">&nbsp;</td>
    <td class="bt bb bl br">&nbsp;</td>
    <td class="bt bb bl br">&nbsp;</td>
  </tr>
  </table>';
  }  
  else if($tipocomp=="honorarios" OR $tipocomp=="arrendamiento"  ){  // para el tipo recibo de honorarios
  $mihtml.= '  
  <table width="'.$tamanio_dehoja.'" border="1" frame="void">
  <tr>
    <td colspan="7" class="encabezado centrado bb br">Importe con letra</td>
    <td class="bt bb">&nbsp;</td>
    <td width="10%" class="encabezado bl bb br">Subtotal</td>
    <td width="15%" class="valorUnitario bb">$'.number_format($subtotal, $redondeo, '.', ',').'</td>
  </tr>
  <tr>
    <td colspan="7" class="bb br">'.$letras.'</td>
    <td class="bt bb">&nbsp;</td>
    <td width="10%" class="encabezado bl bb br">I.V.A.</td>
    <td width="15%" class="valorUnitario bb">$'.number_format($iva, $redondeo, '.', ',').'</td>
  </tr>
  <tr>
    <td colspan="3" class="encabezado centrado bb br">Metodo de Pago</td>
    <td colspan="2" class="encabezado centrado bb br">Num. de Cuenta</td>
    <td colspan="2" class="encabezado centrado bb br">Tasa IVA</td>
    <td class="bt bb">&nbsp;</td>
    <td class="encabezado bl bb br">RET. I.V.A.</td>
    <td width="15%" class="valorUnitario bb">$'.number_format($IVAretenido, $redondeo, '.', ',').'</td>
  </tr>
  <tr>
    <td colspan="3" class="centrado bb br">'.$metodopago.'</td>
    <td colspan="2" class="centrado bb br">'.$numcuentapago.'</td>
    <td colspan="2" class="centrado bb br">'.$tasaIVA.' %</td>
    <td class="bt bb">&nbsp;</td>
    <td class="encabezado bl bb br">RET I.S.R.</td>
    <td width="15%" class="valorUnitario bb">$'.number_format($ISRretenido, $redondeo, '.', ',').'</td>
  </tr>
  <tr>
    <td colspan="7" class="centrado bl br bb">&nbsp;</td>
    <td class="bt bl bb">&nbsp;</td>
    <td class="encabezado bl br">TOTAL</td>
    <td width="15%" class="valorUnitario">$'.number_format($total, $redondeo, '.', ',').'</td>
  </tr>
  </table>';
  }
  if($tipocomp=="hotel"){   //para el tipo de factura electronica para hoteles
  $mihtml.= '  
  <table width="'.$tamanio_dehoja.'" border="1" frame="void">
  <tr>
    <td colspan="7" class="encabezado centrado bb br">Importe con letra</td>
    <td class="bt bb">&nbsp;</td>
    <td width="10%" class="encabezado bl bb br">Subtotal</td>
    <td width="15%" class="valorUnitario bb">$'.number_format($subtotal, $redondeo, '.', ',').'</td>
  </tr>
  <tr>
    <td colspan="7" class="bb br">'.$letras.'</td>
    <td class="bt bb">&nbsp;</td>
    <td width="10%" class="encabezado bl bb br">I.V.A.</td>
    <td width="15%" class="valorUnitario bb">$'.number_format($iva, $redondeo, '.', ',').'</td>
  </tr>
  <tr>
    <td colspan="3" class="encabezado centrado bb br">Metodo de Pago</td>
    <td colspan="2" class="encabezado centrado bb br">Num. de Cuenta</td>
    <td colspan="2" class="encabezado centrado bb br">Tasa IVA</td>
    <td class="bt bb">&nbsp;</td>
    <td class="encabezado bl bb br">I.S.H.</td>
    <td width="15%" class="valorUnitario bb">$'.number_format($montoish, $redondeo, '.', ',').'</td>
  </tr>
  <tr>
    <td colspan="3" class="centrado  br">'.$metodopago.'</td>
    <td colspan="2" class="centrado  br">'.$numcuentapago.'</td>
    <td colspan="2" class="centrado  br">'.$tasaIVA.' %</td>
    <td class="bt bb">&nbsp;</td>
    <td class="encabezado bl br">TOTAL</td>
    <td width="15%" class="valorUnitario">$'.number_format($total, $redondeo, '.', ',').'</td>
  </tr>
  </table>';
  }
    

  $mihtml.= '
  <div class="margin_simple"></div>
  <table width="'.$tamanio_dehoja.'" border="1" frame="void">
  <tr>
    <td colspan="2" rowspan="6" width="20%" align="center" class="bl bt bb br"><img src="'.$rutatimbradosimg.'"  class="qr_imagen"/></td>
    <td colspan="8" class="centrado fila_qr bb">Este documento es una representación impresa de un CFDI</td>
  </tr>
  <tr>
    <td colspan="4" width="30%" class="derecha bordesimple letramediana fila_qr bb br">'.$formadepago.' </td>
    <td colspan="4" width="30%" class="izquierda bordesimple letramediana fila_qr bb">'.$pacquecertifico.'</td>
  </tr>
  <tr>
    <td colspan="4" class="derecha bordesimple letramediana fila_qr bb br">Serie del Certificado del emisor: </td>
    <td colspan="4" class="izquierda bordesimple letramediana fila_qr bb">'.$certificadoCSD.'</td>
  </tr>
  <tr>
    <td colspan="4" class="derecha bordesimple letramediana fila_qr bb br">Folio Fiscal: </td>
    <td colspan="4" class="izquierda bordesimple letramediana fila_qr bb">'.$UUID.'</td>
  </tr>
  <tr>
    <td colspan="4" class="derecha bordesimple letramediana fila_qr bb br">No. de Serie del Certificado del SAT: </td>
    <td colspan="4" class="izquierda bordesimple letramediana fila_qr bb">'.$noCertificadoSAT.'</td>
  </tr>
  <tr>
    <td colspan="4" class="derecha bordesimple letramediana fila_qr br">Fecha y hora de certificación:</td>
    <td colspan="4" class="izquierda bordesimple letramediana fila_qr ">'.$FechaTimbrado.'</td>
  </tr>  
  </table>
  <div class="margin_simple"></div>
  <table width="'.$tamanio_dehoja.'" border="1" frame="void">
  <tr>
    <td colspan="10" class="encabezado centrado bb">Sello digital del CFDI</td>
  </tr>
  <tr>
    <td colspan="10" class="cadena letrapequenia margin_extra bb">'.$selloCFD.'</td>
  </tr>
   <tr>
    <td colspan="10" class="encabezado centrado bb">Sello del SAT</td>
  </tr>
  <tr>
    <td colspan="10" class="cadena letrapequenia margin_extra bb">'. $selloSAT.'</td>
  </tr>
   <tr>
    <td colspan="10" class="encabezado centrado bb">Cadena original del complemento de certificación digital del SAT</td>
  </tr>
  <tr>
    <td colspan="10" class="cadena letrapequenia margin_extra">'.$cadenaoriginal.'</td>
  </tr>
  <!--tr>
    <td colspan="10" class="centrado bt">Facturación Electrónica facil, rapida y económica con efectosfiscales.mx <img class="marca_propia" src="http://efectosfiscales.mx/ef_sistema/repositorio/logos/LOGO_EFECTOSFISCALES.png" /></td>
  </tr-->
</table>'.$est_factura.'
</body>
</html>
';	
	
?>