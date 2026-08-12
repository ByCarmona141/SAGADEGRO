<?php
	function PlantillaCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO Plantilla
									( `id`, `Nombre`, `Descripci_on`, `Plantilla`, `TipoPlantilla`, `EstaVigente`, `Tama_no` )
								 VALUES
								    (  %s,  %s,  %s,  %s,  %s,  %s, %s );',
							GetSQLValueString(NULL, 'int'), 
							GetSQLValueString($FormData['Nombre'], 'varchar'), 
							GetSQLValueString($FormData['Descripci_on'], 'varchar'), 
							GetSQLValueString($FormData['Plantilla'], 'text'),
							GetSQLValueString($FormData['TipoPlantilla'], 'tinyint unsigned'), 
							GetSQLValueString($FormData['EstaVigente'], 'tinyint'),
							GetSQLValueString($FormData['Tama_no'], 'varchar')
						);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordPlantilla    = $Connection -> insert_id;
			$Data['Status']           = 'OK';
			$Data['idRecord']         = $idRecordPlantilla;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function PlantillaEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('DELETE FROM Plantilla WHERE `id` = %s;',
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

	function PlantillaLeer(){
		global $Privileges;
		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'Plantilla',
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
					'ColumName' => '(select Nombre from TipoPlantilla where TipoPlantilla.id = Plantilla.TipoPlantilla)',
					'Alias'     => 'TipoPlantilla',
					'Extra'     => '',
					'Render'    => ''
				),
				4 => array(
					'Type'      => 2,
					'ColumName' => '(select Descripci_on from Opci_on where Opci_on.id = Plantilla.EstaVigente)',
					'Alias'     => 'EstaVigente',
					'Extra'     => '',
					'Render'    => ''
				),
				5 => array(
					'Type'      => 2,
					'ColumName' => 'Tama_no',
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

	function PlantillaActualizar($Key, $FormData){
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE Plantilla
								SET `Nombre` = %s, `Descripci_on` = %s, `Plantilla` = %s, `TipoPlantilla` = %s, `EstaVigente` = %s, `Tama_no` = %s
								WHERE `id` = %s;',
							GetSQLValueString($FormData['Nombre'], 'varchar'), 
							GetSQLValueString($FormData['Descripci_on'], 'varchar'), 
							GetSQLValueString($FormData['Plantilla'], 'text'),
							GetSQLValueString($FormData['TipoPlantilla'], 'tinyint unsigned'), 
							GetSQLValueString($FormData['EstaVigente'], 'tinyint'),
							GetSQLValueString($FormData['Tama_no'], 'varchar'),
							GetSQLValueString($Key, 'tinyint unsigned')
						);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function PlantillaQueryCombo($InText = false){
		if($InText === true){
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM Plantilla  ORDER BY `Nombre` ASC');
		}elseif($InText === false){
			$Query = sprintf('SELECT `id`, `Nombre` FROM Plantilla  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}
?>