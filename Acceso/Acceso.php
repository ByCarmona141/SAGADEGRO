<?php
	function AccesoCrear($FormData) {

		global $Connection;

		$Dispositivo = (int) $FormData['Dispositivo'];
		$Principal = isset($FormData['Principal'])
			? (int) $FormData['Principal']
			: 0;

		$InsertQuery = sprintf(
			'INSERT INTO Acceso
				(
					`TipoAcceso`,
					`Host`,
					`Puerto`,
					`Usuario`,
					`Password`,
					`Dispositivo`,
					`Principal`
				)
			VALUES (%s, %s, %s, %s, %s, %s, %s)',

			GetSQLValueString($FormData['TipoAcceso'], 'int'),
			GetSQLValueString($FormData['Host'], 'varchar'),
			GetSQLValueString($FormData['Puerto'], 'int'),
			GetSQLValueString($FormData['Usuario'], 'varchar'),
			GetSQLValueString($FormData['Password'], 'varchar'),
			GetSQLValueString($Dispositivo, 'int'),
			GetSQLValueString($Principal, 'int')
		);

		try {

			$Connection->begin_transaction();

			if ($Principal === 1) {

				$UpdateQuery = sprintf(
					'UPDATE Acceso
					SET `Principal` = 0
					WHERE `Dispositivo` = %s',

					GetSQLValueString($Dispositivo, 'int')
				);

				if (!$Connection->query($UpdateQuery)) {
					throw new Exception($Connection->error);
				}
			}

			if (!$Connection->query($InsertQuery)) {
				throw new Exception($Connection->error);
			}

			$idRecordAcceso = $Connection->insert_id;

			$Connection->commit();

			$Data['Status'] = 'OK';
			$Data['idRecord'] = $idRecordAcceso;

		} catch (Throwable $e) {

			$Connection->rollback();

			$Data['Status'] = 'ERROR';
			$Data['Error'] = $e->getMessage();
		}

		return $Data;
	}


	function AccesoEliminar($Key) {
		global $Connection;

		$ConsultaElimina =  sprintf('DELETE FROM Acceso WHERE `id` = %s;',
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

	function AccesoLeer($Params = NULL) {
		global $Privileges;

        $Condition = '';

        if ($Params != NULL) {
            $Condition = ' Dispositivo = ' . $Params['Dispositivo'];
        }

		$ServerQuery = [
			'Table'     => [
				'TableName'  => 'Acceso',
				'Alias' => ''
			],
			'Index'     => [
				'IndexName' => 'id',
				'Alias' => ''
			],
			'Columns'   =>  [
				[
					'Type'      => 1,
					'ColumName' => '',
					'Alias'     => '',
					'Extra'     => 'Actions',
					'Render'    => ''
				],
				[
					'Type'      => 2,
					'ColumName' => '(SELECT Nombre FROM TipoAcceso WHERE TipoAcceso.id = Acceso.TipoAcceso)',
					'Alias'     => 'TipoAcceso',
					'Extra'     => '',
					'Render'    => ''
				],
				[
					'Type'      => 2,
					'ColumName' => 'Host',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				],
				[
					'Type'      => 2,
					'ColumName' => 'Puerto',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => ''
				],
				[
					'Type'      => 2,
					'ColumName' => 'Usuario',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => 'Usuario'
				],
				[
					'Type'      => 2,
					'ColumName' => 'Password',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => 'Contrasena'
				],
				[
					'Type'      => 2,
					'ColumName' => '(SELECT Nombre FROM Dispositivo WHERE Dispositivo.id = Acceso.Dispositivo)',
					'Alias'     => 'Dispositivo',
					'Extra'     => '',
					'Render'    => ''
				],
				[
					'Type'      => 2,
					'ColumName' => 'Principal',
					'Alias'     => '',
					'Extra'     => '',
					'Render'    => 'ContrasenaPrincipal'
				]
			],
			'Condition'     => $Condition,
			'Group'         => '',
			'Order'         => ' id ASC ',
			'RenderRow'     => '',
			'Privileges'    => $Privileges,
			'Debug'         => 0
        ];

		return $ServerQuery;
	}

	function AccesoActualizar($Key, $FormData) {
		global $Connection;

		$Key = (int) $Key;
		$Dispositivo = (int) $FormData['Dispositivo'];

		$Principal = isset($FormData['Principal'])
			? (int) $FormData['Principal']
			: 0;

		try {

			$Connection->begin_transaction();

			if ($Principal === 1) {

				$UpdatePrincipalQuery = sprintf(
					'UPDATE Acceso
					SET `Principal` = 0
					WHERE `Dispositivo` = %s
					AND `id` <> %s',

					GetSQLValueString($Dispositivo, 'int'),
					GetSQLValueString($Key, 'int')
				);

				if (!$Connection->query($UpdatePrincipalQuery)) {
					throw new Exception($Connection->error);
				}
			}

			$UpdateQuery = sprintf(
				'UPDATE Acceso
				SET
					`TipoAcceso` = %s,
					`Host` = %s,
					`Puerto` = %s,
					`Usuario` = %s,
					`Password` = %s,
					`Dispositivo` = %s,
					`Principal` = %s
				WHERE `id` = %s',

				GetSQLValueString($FormData['TipoAcceso'], 'int'),
				GetSQLValueString($FormData['Host'], 'varchar'),
				GetSQLValueString($FormData['Puerto'], 'int'),
				GetSQLValueString($FormData['Usuario'], 'varchar'),
				GetSQLValueString($FormData['Password'], 'varchar'),
				GetSQLValueString($Dispositivo, 'int'),
				GetSQLValueString($Principal, 'int'),
				GetSQLValueString($Key, 'int')
			);

			if (!$Connection->query($UpdateQuery)) {
				throw new Exception($Connection->error);
			}

			$Connection->commit();

			$Data['Status'] = 'OK';

		} catch (Throwable $e) {

			$Connection->rollback();

			$Data['Status'] = 'ERROR';
			$Data['Error'] = $e->getMessage();
		}

		return $Data;
	}

	function AccesoActualizarPrincipal($IdAcceso) {

		global $Connection;

		$IdAcceso = (int) $IdAcceso;

		try {

			$Connection->begin_transaction();

			// Obtener el dispositivo al que pertenece el acceso seleccionado.
			$SelectQuery = sprintf(
				'SELECT `Dispositivo`
				FROM `Acceso`
				WHERE `id` = %s
				LIMIT 1',
				GetSQLValueString($IdAcceso, 'int')
			);

			$Resultado = $Connection->query($SelectQuery);

			if (!$Resultado || $Resultado->num_rows !== 1) {
				throw new Exception('El acceso seleccionado no existe.');
			}

			$Registro = $Resultado->fetch_assoc();
			$IdDispositivo = (int) $Registro['Dispositivo'];

			// Desmarcar todos los accesos del dispositivo.
			$UpdateQuery = sprintf(
				'UPDATE `Acceso`
				SET `Principal` = 0
				WHERE `Dispositivo` = %s',
				GetSQLValueString($IdDispositivo, 'int')
			);

			if (!$Connection->query($UpdateQuery)) {
				throw new Exception($Connection->error);
			}

			// Marcar como principal el acceso seleccionado.
			$UpdateQuery = sprintf(
				'UPDATE `Acceso`
				SET `Principal` = 1
				WHERE `id` = %s
				AND `Dispositivo` = %s',
				GetSQLValueString($IdAcceso, 'int'),
				GetSQLValueString($IdDispositivo, 'int')
			);

			if (!$Connection->query($UpdateQuery)) {
				throw new Exception($Connection->error);
			}

			$Connection->commit();

			$Data['Status'] = 'OK';

		} catch (Throwable $e) {

			$Connection->rollback();

			$Data['Status'] = 'ERROR';
			$Data['Error'] = $e->getMessage();
		}

		return $Data;
	}

	function AccesoQueryCombo($InText = false) {
		if($InText === true) {
			$Query = sprintf('SELECT `Dispositivo`, `Dispositivo` FROM Acceso  ORDER BY `Dispositivo` ASC');
		} else if($InText === false) {
			$Query = sprintf('SELECT `id`, `Dispositivo` FROM Acceso  ORDER BY `Dispositivo` ASC');
		}

		return $Query;
	}
?>