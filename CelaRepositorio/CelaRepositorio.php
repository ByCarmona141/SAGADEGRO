<?php
	function CelaRepositorioCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
									 CelaRepositorio
									 ( `id`, `Nombre`, `Descripci_on`, `Tama_no`, `Origen`, `Tupla`, `Ruta`, `idUsuario`, `Status`, `FechaTupla`)
								 VALUES
									 ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s );',
							GetSQLValueString(NULL, 'int') ,
							GetSQLValueString($FormData['Nombre'], 'varchar') ,
							GetSQLValueString($FormData['Descripci_on'], 'varchar') ,
							GetSQLValueString($FormData['Tama_no'], 'varchar') ,
							GetSQLValueString($FormData['Origen'], 'varchar') ,
							GetSQLValueString($FormData['Tupla'], 'int') ,
							GetSQLValueString($FormData['Ruta'], 'varchar') ,
							GetSQLValueString($FormData['idUsuario'], 'int') ,
							GetSQLValueString(1, 'tinyint') ,
							GetSQLValueString(date("Y-m-d H:i:s"), 'timestamp')
						);

		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaRepositorio    = $Connection -> insert_id;
			$Data['Status']         = 'OK';
			$Data['idRecord']       = $idRecordCelaRepositorio;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function CelaRepositorioEliminar($Key, $Unlink = false, $Origin = false){
		global $Connection;
		if($Unlink === false){
			$ConsultaElimina =  sprintf('UPDATE CelaRepositorio SET `Status` = %s WHERE `id` = %s;',
									GetSQLValueString(2, 'int'),
									GetSQLValueString($Key, 'int')
								);
		}else{
			if($Origin === false){
				$ConsultaElimina = sprintf('DELETE FROM CelaRepositorio WHERE `id` = %s;',
										GetSQLValueString(2, 'int'),
										GetSQLValueString($Key, 'int')
									);

				$DataRepositorio =  GetValue(
										sprintf('SELECT * FROM CelaRepositorio WHERE `id` = %s;',
											GetSQLValueString($Key, 'int')
										)
									);
			}else{
				$ConsultaElimina =  sprintf('DELETE FROM CelaRepositorio WHERE `Origen` = %s AND `Tupla` = %s;',
										GetSQLValueString($Origin, 'varchar'),
										GetSQLValueString($Key, 'int')
									);


				$DataRepositorio =  GetValue(
										sprintf('SELECT * FROM CelaRepositorio WHERE `Origen` = %s AND `Tupla` = %s;',
											GetSQLValueString($Origin, 'varchar'),
											GetSQLValueString($Key, 'int')
										)
									);
			}
		}

		if($ResultadoElimina = $Connection -> query($ConsultaElimina)){
			if($Unlink === false){
				$Data['Status'] = 'OK';
			}elseif(isset($DataRepositorio['Ruta']) && file_exists($DataRepositorio['Ruta'])){
				if(unlink($DataRepositorio['Ruta'])){
					$Data['Status'] = 'OK';
				}else{
					$Data['Status'] = 'ERROR';
					$Data['Error'] = 'No se pudo eliminar fisicamente el archivo, solo se eliminó el registro en la base de datos';
				}
			}else{
				$Data['Status'] = 'OK';
			}
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	function CelaRepositorioLeer($Params = null){
		global $Privileges;
		$Condition = ' Status = 1 AND ';
		if(isset($Params['Source']) && $Params['Source'] != ''){
			$Condition .=   sprintf(' Origen = %s AND ',
								GetSQLValueString($Params['Source'], 'varchar')
							);
		}

		if(isset($Params['Source']) && $Params['Source'] != ''){
			$Condition .=   sprintf(' Tupla = %s AND ',
								GetSQLValueString($Params['Tupla'], 'varchar')
							);
		}

		$Condition .= ' 1=1 ';

		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'CelaRepositorio',
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
					'Extra'     => 'ActionsFile',
					'Render'    => ''
				),
				1 => array(
					'Type'      => 2,
					'ColumName' => 'Nombre',
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
					'ColumName' => '(select NombreCompleto from CelaUsuario where CelaUsuario.id = CelaRepositorio.idUsuario)',
					'Alias'     => 'idUsuario',
					'Extra'     => '',
					'Render'    => ''
				),
				4 => array(
					'Type'      => 2,
					'ColumName' => 'FechaTupla',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => 'Fecha'
				),
				5 => array(
					'Type'      => 1,
					'ColumName' => '',
					'Alias'     => '',
					'Extra'     => 'FileVersion',
					'Render'    => ''
				),
				6 => array(
					'Type'      => 0,
					'ColumName' => 'Origen',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				7 => array(
					'Type'      => 0,
					'ColumName' => 'Tupla',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				)
			),
			'Condition'     =>  $Condition,
			'Group'         => '',
			'Order'         => ' FechaTupla DESC ',
			'RenderRow'     => '',
			'Privileges'    => $Privileges,
			'Debug'         => 1
		);

		return $ServerQuery;
	}

	function CelaRepositorioActualizar($Key, $FormData){
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE CelaRepositorio
								 SET `id` = %s, `Nombre` = %s, `Descripci_on` = %s, `Tama_no` = %s, `Origen` = %s, `Tupla` = %s, `Ruta` = %s, `idUsuario` = %s, `Status` = %s, `FechaTupla` = %s
								 WHERE `id` = %s;',
							GetSQLValueString($FormData['Nombre'], 'varchar') ,
							GetSQLValueString($FormData['Descripci_on'], 'varchar') ,
							GetSQLValueString($FormData['Tama_no'], 'varchar') ,
							GetSQLValueString($FormData['Origen'], 'varchar') ,
							GetSQLValueString($FormData['Tupla'], 'int') ,
							GetSQLValueString($FormData['Ruta'], 'varchar') ,
							GetSQLValueString($FormData['idUsuario'], 'int') ,
							GetSQLValueString(1, 'tinyint') ,
							GetSQLValueString(date("Y-m-d H:i:s"), 'timestamp'), GetSQLValueString($Key, 'int')
						);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaRepositorioGetFile($FileReference){
		if(!is_numeric($FileReference)){
			$FileData = GetValue(
							sprintf('SELECT * FROM CelaRepositorio WHERE `Ruta` = %s;',
								GetSQLValueString($FileReference, 'varchar')
							)
						);
		}elseif(is_numeric($FileReference)){
			$FileData = GetValue(
				sprintf('SELECT * FROM CelaRepositorio WHERE `id` = %s;',
					GetSQLValueString($FileReference, 'varchar')
				)
			);
		}
		/**
		print_r($FileData);
		/**/
		if($FileData['Result'] == 'NULL'){
			$FileData['Ruta']   = $FileReference;

			$NameSplit  = explode('/', $FileReference);
			$Name = end($NameSplit);

			$ExtensionSplit    = explode('.', $Name);
			$Extension = end($ExtensionSplit);

			if($Extension == 'html'){
				$Name = str_replace('.html', '.xls', $Name);
			}

			$FileData['Nombre'] = $Name;
			$FileData['FechaTupla'] = date('Y-m-d H:i:s');
		}

		if(!file_exists($FileData['Ruta'])){
			$Data['Status'] = 'ERROR';
			$Data['Error'] = 'File not phisical exist, the file only exist in the data base';
			return $Data;
		}

		$Data['Status']     = 'OK';
		$Data['Response']   = $FileData;

		return $Data;
	}

	function UploadFile($Params = null){
		if(is_array($Params)){
			/*Se extraen las variables si vienen en un arreglo*/
			extract($Params, EXTR_OVERWRITE);
		}

		$Temp = 'repositorio/temp/';
		/*nos aseguramos que exista la ruta del archivo*/
		if(!file_exists($Temp)){
			mkdir($Temp, 0755, true);
		}

		$Data = array();
		foreach($_FILES as $id => $File){
			if(is_array($File['name'])){
				/*Si es un arreglo de archivos se procesan todos los archivos que existan*/
				foreach($File as $subID => $SubFile){
					foreach($SubFile as $FinalSubID => $FinalSubFile){
						foreach($FinalSubFile as $FinalID => $FinalFile){
							$NewFile = array(
								'name' => $File['name'][$FinalSubID][$FinalID],
								'type' => $File['type'][$FinalSubID][$FinalID],
								'tmp_name' => $File['tmp_name'][$FinalSubID][$FinalID],
								'error' => $File['error'][$FinalSubID][$FinalID],
								'size' => $File['size'][$FinalSubID][$FinalID]
							);

							$tmp_name = explode('/', $NewFile['tmp_name']);
							$tmp_name = end($tmp_name);
							move_uploaded_file($NewFile['tmp_name'], $Temp . $tmp_name);
							$NewFile['tmp_name'] = $Temp . $tmp_name;
							$Data[$id . $FinalSubID . $FinalID] = $NewFile;
							$Data[$id . $FinalSubID . $FinalID]['json'] = json_encode($NewFile);
						}
					}
				}
			}else{
				$tmp_name = explode('/', $File['tmp_name']);
				$tmp_name = end($tmp_name);
				move_uploaded_file($File['tmp_name'], $Temp . $tmp_name);
				$File['tmp_name'] = $Temp . $tmp_name;
				$Data[$id] = $File;
				$Data[$id]['json'] = json_encode($File);
			}
		}

		return json_encode($Data);
	}
?>
