<?php
	function ReporteCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO Reporte
									( `id`, `Descripci_on`, `Formato` )
								 VALUES
								    (  %s,  %s,  %s );', 
							GetSQLValueString(NULL, 'int'), 
							GetSQLValueString($FormData['Descripci_on'], 'varchar'), 
							GetSQLValueString($FormData['Formato'], 'text')
						);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordReporte    = $Connection -> insert_id;
			$Data['Status']           = 'OK';
			$Data['idRecord']         = $idRecordReporte;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function ReporteEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('DELETE FROM Reporte WHERE `id` = %s;',
								GetSQLValueString($Key, 'tinyint unsigned')
							);
		if($ResultadoElimina = $Connection -> query($ConsultaElimina)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	function ReporteLeer(){
		global $Privileges;
		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'Reporte',
				'Alias' => ''
			),
			'Index'     => array(
				'IndexName' => 'id',
				'Alias' => ''
			),
			'Columns'   =>  array(
				0 => array(
					'Type'      => 1,
					'ColumName' => '',
					'Alias'     => '',
					'Extra'     => 'Actions',
					'Render'    => ''
				),
				1 => array(
					'Type'      => 2,
					'ColumName' => 'id',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				2 => array(
					'Type'      => 2,
					'ColumName' => 'Descripci_on',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				3 => array(
					'Type'      => 2,
					'ColumName' => 'Formato',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				)
			),
			'Condition'     => '',
			'Group'         => '',
			'Order'         => ' id ASC ',
			'RenderRow'     => '',
			'Privileges'    => $Privileges,
			'Debug'         => 0
		);

		return $ServerQuery;
	}

	function ReporteActualizar($Key, $FormData){
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE Reporte
								SET `Descripci_on` = %s, `Formato` = %s
								WHERE `id` = %s;',
							GetSQLValueString($FormData['Descripci_on'], 'varchar'), 
							GetSQLValueString($FormData['Formato'], 'text'),
							GetSQLValueString($Key, 'int')
						);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function ReporteQueryCombo($InText = false){
		if($InText === true){
			$Query = sprintf('SELECT `Descripci_on`, `Descripci_on` FROM Reporte  ORDER BY `Descripci_on` ASC');
		}elseif($InText === false){
			$Query = sprintf('SELECT `id`, `Descripci_on` FROM Reporte  ORDER BY `Descripci_on` ASC');
		}

		return $Query;
	}

	function ReporteGeneraPDF($FileContend, $FileName = false, $Savein = false, $PDFConfig = array()){
		if(is_array($FileContend)){
			extract($FileContend, EXTR_OVERWRITE);
		}

		$SourceFile = '';
		try{
			$Name   = ($FileName !== false && $FileName != '' ? $FileName:rand(10000000, 99999999) . '.pdf') ;
			$Source = ($Savein !== false && $Savein != '' ? $Savein:'repositorio/temporal/');

			if(!isset($PDFConfig['Constructor']) || count($PDFConfig['Constructor']) <= 0){
				$PDFConfig['Constructor'] = array(
					'path' => $Source,
					'FooterStyleRight' => 'Pag. [page] de [toPage]'
				);
			}else{
				$PDFConfig['Constructor']['path'] = (isset($PDFConfig['Constructor']['path']) && $PDFConfig['Constructor']['path'] != '' ? $PDFConfig['Constructor']['path']:$Source);
			}

			if(!isset($PDFConfig['Mode'])){
				$PDFConfig['Mode'] = 3;
			}


			$Wkhtmltopdf = new  Wkhtmltopdf($PDFConfig['Constructor']);

			$Wkhtmltopdf -> setHtml($FileContend);

			switch($PDFConfig['Mode']){
				case 0:
					$Wkhtmltopdf -> output(Wkhtmltopdf::MODE_DOWNLOAD, $Name);
					break;
				case 1:
					$Wkhtmltopdf -> output(Wkhtmltopdf::MODE_STRING, $Name);
					break;
				case 2:
					$Wkhtmltopdf -> output(Wkhtmltopdf::MODE_EMBEDDED, $Name);
					exit();
					break;
				case 3:
					$Wkhtmltopdf -> output(Wkhtmltopdf::MODE_SAVE, $Name);
					break;
			}

			$SourceFile = $Source . $Name;

			$Data = array(
				'Status' => 'OK',
				'SourceFile' => $SourceFile
			);
		}catch (Exception $e){
			$Data = array(
				'Status' => 'ERROR',
				'Error' => $e -> getMessage()
			);
		}

		return $Data;
	}

	function ReporteGeneraXLS($FileContend, $FileName = false, $Savein = false){
		if(is_array($FileContend)){
			extract($FileContend, EXTR_OVERWRITE);
		}

		$SourceFile = '';

		$Name   = ($FileName !== false && $FileName != '' ? $FileName:rand(10000000, 99999999) . '.html') ;
		$Source = ($Savein !== false && $Savein != '' ? $Savein:'repositorio/temporal/');

		if(!file_exists($Source)){
			mkdir($Source, 0755, true);
		}

		$File   = fopen($Source . $Name, 'a');
		$Write  = fwrite($File, $FileContend);

		if($Write === false){
			$Data = array(
				'Status' => 'ERROR',
				'Error' => 'The File "' . $Source . $Name . '" could not be written'
			);
		}else{
			$SourceFile = $Source . $Name;

			$Data = array(
				'Status' => 'OK',
				'SourceFile' => $SourceFile
			);
		}

		fclose($File);

		return $Data;
	}

	function validarExtensionArchivo($RutaArchivo){

		$info 		= pathinfo($RutaArchivo);
		$extension  = $info['extension'];
		$Nombre 	= $info['filename'];
		if($extension == 'xls' || $extension == 'xlsx'){

			$Data = array(
				'Status' => 'OK',
				'Attendance' => $extension,
				'Nombre' => $Nombre
			);

		}
		else{
			$Data = array(
				'Status' => 'ERROR',
				'Error' => $extension,
				'Nombre' => $Nombre
			);
		}

		return $Data;
	}

	function lecturaArchivo($RutaArchivo,$Extension){

	if($Extension == 'xlsx'){
		$objReader 				= PHPExcel_IOFactory::createReader('Excel2007');
	}else{
		$objReader 				= PHPExcel_IOFactory::createReader('Excel5');
	}

	//$inputFileType = PHPExcel_IOFactory::identify($ruta);
	$objReader				->setReadDataOnly(true);
	$objPHPExcel 			= $objReader->load($RutaArchivo);
		
	}

?>