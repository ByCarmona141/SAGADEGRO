<?php
	function Opci_onCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO Opci_on
									( `id`, `Descripci_on` )
								 VALUES
								    (  %s,  %s );', 
							GetSQLValueString($FormData['id'], 'tinyint'), 
							GetSQLValueString($FormData['Descripci_on'], 'varchar')
						);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordOpci_on    = $Connection -> insert_id;
			$Data['Status']           = 'OK';
			$Data['idRecord']         = $idRecordOpci_on;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function Opci_onEliminar($Key){
		global $Connection;
		$ConsultaElimina =  sprintf('DELETE FROM Opci_on WHERE `id` = %s;',
								GetSQLValueString($Key, 'tinyint')
							);
		if($ResultadoElimina = $Connection -> query($ConsultaElimina)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	function Opci_onLeer(){
		global $Privileges;
		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => 'Opci_on',
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

	function Opci_onActualizar($Key, $FormData){
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE Opci_on
								SET `id` = %s, `Descripci_on` = %s
								WHERE `id` = %s;',
							GetSQLValueString($FormData['id'], 'tinyint'), 
							GetSQLValueString($FormData['Descripci_on'], 'varchar'),
							GetSQLValueString($Key, 'tinyint')
						);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function Opci_onQueryCombo($InText = false){
		if($InText === true){
			$Query = sprintf('SELECT `Descripci_on`, `Descripci_on` FROM Opci_on  ORDER BY `Descripci_on` ASC');
		}elseif($InText === false){
			$Query = sprintf('SELECT `id`, `Descripci_on` FROM Opci_on  ORDER BY `Descripci_on` ASC');
		}

		return $Query;
	}
?>