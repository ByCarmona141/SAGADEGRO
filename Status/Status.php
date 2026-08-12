<?php
	function StatusCrear($FormData){
		global $Connection;

		if(is_array($FormData)){
			/*Se extraen las variables si vienen en un arreglo*/
			extract($FormData, EXTR_OVERWRITE);
		}

		$InsertQuery =  sprintf('INSERT INTO Status
									( `id`, `Descripci_on`, `Origen`, `Acotaci_on` )
								 VALUES
								    (  %s,  %s,  %s,  %s );', 
							GetSQLValueString(NULL, 'int'), 
							GetSQLValueString($FormData['Descripci_on'], 'varchar'), 
							GetSQLValueString($FormData['Origen'], 'varchar'), 
							GetSQLValueString($FormData['Acotaci_on'], 'varchar')
						);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordStatus    = $Connection -> insert_id;
			$Data['Status']           = 'OK';
			$Data['idRecord']         = $idRecordStatus;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function StatusEliminar($Key){
		global $Connection;

		if(is_array($Key)){
			/*Se extraen las variables si vienen en un arreglo*/
			extract($Key, EXTR_OVERWRITE);
		}

		$ConsultaElimina =  sprintf('DELETE FROM Status WHERE `id` = %s;',
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

	function StatusLeer($Params = false){
		global $Privileges;

		if($Params !== false && is_array($Params)){
			/*Se extraen las variables si vienen en un arreglo*/
			extract($Params, EXTR_OVERWRITE);
		}

		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'Status',
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
					'ColumName' => 'Origen',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				),
				4 => array(
					'Type'      => 2,
					'ColumName' => 'Acotaci_on',
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

	function StatusGetData($Key, $Filter = 'id'){
		global $Connection;

		if(is_array($Key)){
			/*Se extraen las variables si vienen en un arreglo*/
			extract($Key, EXTR_OVERWRITE);
		}

		$StatusQuery = sprintf('SELECT * FROM Status WHERE %s IN (%s)',
									GetSQLValueString($Filter, 'SQL'),
									$Key
								);

		if($StatusResult = $Connection -> query($StatusQuery)){
			$Statuss = array();
			while($StatusRecord = $StatusResult -> fetch_assoc()){
				$Statuss[] = $StatusRecord;
			}

			$Data = array(
				'Status' => 'OK',
				'Data' => $Statuss
			);
		}else{
			$Data = array(
				'Status'    => 'ERROR',
				'Error'     => $Connection -> error
			);
		}

		return $Data;
	}

	function StatusActualizar($Key, $FormData = null){
		global $Connection;

		if(is_array($Key)){
			/*Se extraen las variables si vienen en un arreglo*/
			extract($Key, EXTR_OVERWRITE);
		}

		$UpdateQuery =  sprintf('UPDATE Status
								SET `Descripci_on` = %s, `Origen` = %s, `Acotaci_on` = %s
								WHERE `id` = %s;',
							GetSQLValueString($FormData['Descripci_on'], 'varchar'), 
							GetSQLValueString($FormData['Origen'], 'varchar'), 
							GetSQLValueString($FormData['Acotaci_on'], 'varchar'),
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

	function StatusQueryCombo($InText = false){

		if(is_array($InText)){
			/*Se extraen las variables si vienen en un arreglo*/
			extract($InText, EXTR_OVERWRITE);
		}

		if($InText === true){
			$Query = sprintf('SELECT `Descripci_on`, `Descripci_on` FROM Status  ORDER BY `Descripci_on` ASC');
		}elseif($InText === false){
			$Query = sprintf('SELECT `id`, `Descripci_on` FROM Status  ORDER BY `Descripci_on` ASC');
		}

		return $Query;
	}
?>