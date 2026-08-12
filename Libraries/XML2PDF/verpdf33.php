<?php 
	$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","S&aacute;bado");
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	$hay_resultado=false;
	$busqueda=false;
	require_once('../ef_sistema/lib/Conexion.php');
	require_once('../ef_sistema/lib/Funciones.php');
	require_once '../conector_eee/invoice/numero_a_letra.php'; 
	require_once "../conector_eee/invoice/phpqrcode.php";
	//print_r( $Conexion);

	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
		
		$mixml = $_FILES['mixml']['tmp_name'];
		$xml = @simplexml_load_file($mixml);
		$ns = $xml->getNamespaces(true);
		$xml->registerXPathNamespace('c', $ns['cfdi']);
		$xml->registerXPathNamespace('t', $ns['tfd']);

    	//EMPIEZO A LEER LA INFORMACION DEL CFDI E IMPRIMIRLA 
foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){} 
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){}
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal){}
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:RegimenFiscal') as $RegimenFiscal){}
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:ExpedidoEn') as $ExpedidoEn){}
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){}
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor//cfdi:Domicilio') as $ReceptorDomicilio){} 

$i=0;
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Concepto){ 
	$concepto[$i]['cantidad']=$Concepto['Cantidad'];
	$concepto[$i]['unidad']=$Concepto['Unidad'];
	$concepto[$i]['descripcion']=$Concepto['Descripcion'];
	$concepto[$i]['precio']=$Concepto['ValorUnitario']."";
	$concepto[$i]['importe']=$Concepto['Importe']."";
	$i++;
}
$numerodeconceptos=$i;

foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado){} 
foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Retencion') as $Retencion){} 
foreach ($xml->xpath('//t:TimbreFiscalDigital') as $TimbreFiscalDigital) {}

/*********************************************************************************************/
/*********************************************************************************************/
/*********************************************************************************************/

	//-----------------------------------------------------------------
	//Para los datos de la factura EMISOR, RECEPTOR, CONCEPTOS e IMPUESTOS
	//-----------------------------------------------------------------	
	
	//Para estado de la factura  1=vista previa  2= Emitido   3=cancelado
	$est_factura='';
	$tipocomp="factura";
	$tipo_factura="Factura Electrónica";
	$fecha = $cfdiComprobante['Fecha'];
	$folio=$cfdiComprobante['Folio'];
	$serie = $cfdiComprobante['Serie'];
	$formadepago=$cfdiComprobante['MetodoPago'];
	$metodopago = $cfdiComprobante['FormaPago'];
	$tipocomprobante =$cfdiComprobante['TipoDeComprobante'];
	$numcuentapago= $cfdiComprobante['CuentaPago'];
	$lugarexpedicion = $cfdiComprobante['LugarExpedicion'];
	$moneda=$cfdiComprobante['Moneda'];
	$cadenaoriginal=$cfdiComprobante['CadenaOriginal'];
	//datos fiscales EMISOR
	//$url_logo = "repositorio/logos/LOGO_EFECTOSFISCALES.png";
	$url_logo = null;
	$fondo_color="#3DACFD";
	$texto_color="#FFFFFF";	
	$plantilla ="1";
	$redondeo="2";
	$pais = $DomicilioFiscal['pais'];
	$estado = $DomicilioFiscal['estado'];
	$municipio = $DomicilioFiscal['municipio'];
	$colonia = $DomicilioFiscal['colonia'];
	$calle = $DomicilioFiscal['calle'];
	$numexterior = $DomicilioFiscal['noExterior'];
	$numinterior = $DomicilioFiscal['noInterior'];
	$cp = $DomicilioFiscal['codigoPostal'];
	$razonsocial = $Emisor['Nombre'];
	$rfc = $Emisor['Rfc'];
	$regimen = $RegimenFiscal['Regimen'];

	//datos de RECEPTOR

	$Rpais = $ReceptorDomicilio['pais'];
	$Restado =  $ReceptorDomicilio['estado'];
	$Rmunicipio =  $ReceptorDomicilio['municipio'];
	$Rlocalidad =  $ReceptorDomicilio['localidad'];
	$Rcolonia =  $ReceptorDomicilio['colonia'];
	$Rcalle =  $ReceptorDomicilio['calle'];
	$Rnumexterior = $ReceptorDomicilio['noExterior'];
	$Rnuminterior = $ReceptorDomicilio['noInterior'];
	$Rcp = $ReceptorDomicilio['codigoPostal'];
	$Rrazonsocial = $Receptor['Nombre'];
	$Rrfc = $Receptor['Rfc'];
	$referencia = $Receptor['referencia'];
	

	
	$subtotal = $cfdiComprobante['SubTotal']."";
	$total = $cfdiComprobante['Total']."";
	
	$tasaIVA=$Traslado['Tasa'];
	$iva = $Traslado['Importe'].""; 
	
	$subtotal2=$subtotal."";
	$ISRretenido=$cfdiComprobante['ISRretenido']."";
	$IVAretenido=$cfdiComprobante['IVAretenido']."";

	//cantidad con letra y total

    $letras=utf8_decode(num2letras($total,0,0)." pesos  ");
	$total_cadena=$total;
	$ultimo = substr (strrchr ($total, "."), 1, 2); //recupero lo que este despues del decimal
	$letras = $letras." ".$ultimo."/100 M. N.";
	$numeroconceros=number_format($total, 6, '.','');

	$selloCFDI = $cfdiComprobante['Sello']; 
	$certificado = $cfdiComprobante['Certificado']; 
	$certificadoCSD = $cfdiComprobante['NoCertificado']; 

	$selloCFD = $TimbreFiscalDigital['SelloCFD']; 
	$FechaTimbrado = $TimbreFiscalDigital['FechaTimbrado']; 
	$UUID = $TimbreFiscalDigital['UUID']; 
	$noCertificadoSAT = $TimbreFiscalDigital['NoCertificadoSAT']; 
	$selloSAT = $TimbreFiscalDigital['SelloSAT']; 
	$version = $TimbreFiscalDigital['Version']; 

	$cadenaoriginal="||".$version."|".$UUID."|".$FechaTimbrado."|".$selloCFD."|".$noCertificadoSAT."||";

		//-----------------------------------------------------------------
		//Para generar QR
		//-----------------------------------------------------------------
		$rutaQR_code='PNG/'.$UUID.'.png';
		if(!file_exists($rutaQR_code)){
			//Contenido del QR
			$Direccion="http://efectosfiscales.mx/SAT/?re=".$rfc."&rr=".$Rrfc."&tt=".$numeroconceros2."&id=".$UUID;
			QRcode::png($Direccion, $rutaQR_code, 'M' , 4, 2);
		}
		$rutatimbradosimg="http://efectosfiscales.mx/XML2PDF/".$rutaQR_code;
		//-----------------------------------------------------------------
		//Termina para generar QR
		//-----------------------------------------------------------------
	 $pacquecertifico = "CFDI Timbrado por ".ObtenValor("SELECT RFC FROM PACS WHERE col1='".$noCertificadoSAT."'", "RFC");
	$pacquecertifico ="";
	//Incluyo el archivo de plantilla 
	include_once "Formato1.php"; 
	//echo $Direccion;
	/** echo $mihtml;
	/**/include_once("../ef_sistema/lib/libPDF.php");
	
		try 
		{
		    $wkhtmltopdf = new Wkhtmltopdf(array('path' =>'../ef_sistema/repositorio/temporal/','lowquality'=>true));
		    $wkhtmltopdf->setHtml($mihtml);
		    $wkhtmltopdf->output(Wkhtmltopdf::MODE_EMBEDDED, $rfc."_".$serie."-".$folio.".pdf");		
		
		} 
		catch (Exception $e) 
		{
		    echo "<script>alert('Hubo un error al generar el PDF: ".$e->getMessage()."');</script>";	
		}		

/*********************************************************************************************/
/*********************************************************************************************/
/*********************************************************************************************/

	
	}

	


?>