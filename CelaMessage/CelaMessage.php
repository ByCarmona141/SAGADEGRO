<?php
	function CelaMessageCrear($FormData){
		global $Connection;
		$InsertQuery =  sprintf('INSERT INTO
									cometchat ( `id`, `from`, `to`, `message`, `sent`, `read`, `direction`, `tipo`, `subject`, `time_send`, `status`)
								 VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s );',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($FormData['from'], 'int'),
							GetSQLValueString($FormData['to'], 'int'),
							GetSQLValueString($FormData['message'], 'varchar'),
							GetSQLValueString($FormData['sent'], 'int'),
							GetSQLValueString($FormData['read'], 'int'),
							GetSQLValueString($FormData['direction'], 'int'),
							GetSQLValueString($FormData['tipo'], 'int'),
							GetSQLValueString($FormData['subject'], 'varchar'),
							GetSQLValueString($FormData['time_send'], 'datetime'),
							GetSQLValueString($FormData['status'], 'int')
		);
		if($InsertResult = $Connection -> query($InsertQuery)){
			$idRecordCelaMessage    = $Connection -> insert_id;
			$Data['Status']         = 'OK';
			$Data['idRecord']       = $idRecordCelaMessage;
		}else{
			$Data['Status']         = 'ERROR';
			$Data['Error']          = $Connection -> error;
		}

		return $Data;
	}

	function CelaMessageEliminar($Key){
		global $Connection;
		$DeleteQuery =  sprintf('UPDATE cometchat SET status = %s WHERE `id` = %s;',
			GetSQLValueString(0, 'int'), GetSQLValueString($Key, 'int')
		);
		if($ResultadoElimina = $Connection -> query($DeleteQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	function CelaMessageLeer($Params){
		global $Privileges;
		//print_r($Params);
		$Box = ($Params['Box'] == 2 ? 'c.`to`':'c.`from`');
		$Box1 = ($Params['Box'] == 2 ? 'c.`from`':'c.`to`');

		$Query = '  (SELECT
					    cu.`NombreCompleto` as `From`,
					    CONCAT_WS(\' - \', c.subject, SUBSTR(c.message, 1, 50)) as `Message`,
					    ctm.`icon` as `Icon`,
					    ctm.`class` as `Class`,
					    CONCAT(
					       FLOOR(HOUR(TIMEDIFF(NOW(), c.time_send)) / 24), \',\',
					       MOD(HOUR(TIMEDIFF(NOW(), c.time_send)), 24), \',\',
					       MINUTE(TIMEDIFF(NOW(), c.time_send)), \',\',
					       SECOND(TIMEDIFF(NOW(), c.time_send))
					   ) as `Time`,
					   ctm.`id` as `Tipo`,
					   c.`time_send` as `FechaEnvio`,
					   c.`id`,
					   c.`read` as `IsRead`
					FROM cometchat c
						INNER JOIN cometchat_tipo_message ctm ON ( c.tipo = ctm.id  )
						INNER JOIN CelaUsuario cu ON ( ' . $Box . ' = cu.id  )
					WHERE
						' . $Box1 . ' = ' . $Params['User'] . ' AND c.`status` = ' . $Params['Status'] . '
					)';
		$ServerQuery = array(
			'Table'     => array(
				'TableName'  => $Query,
				'Alias' => 'CelaMessage'
			),
			'Index'     => array(
				'IndexName' => 'CelaMessage.`id`',
				'Alias' => 'id'
			),
			'Columns'   =>  array(
				0 => array(
					'Type'      => 1,
					'ColumName' => '',
					'Alias'     => '',
					'Extra'     => 'ActionsMail',
					'Render'    => ''
				),
				1 => array(
					'Type'      => 2,
					'ColumName' => 'CelaMessage.`Icon`',
					'Alias'     => 'Icono',
					'Extra'     => '',
					'Render'    => 'Icon'
				),
				2 => array(
					'Type'      => 2,
					'ColumName' => 'CelaMessage.`From`',
					'Alias'     => 'De',
					'Extra'     => '',
					'Render'    => ''
				),
				3 => array(
					'Type'      => 2,
					'ColumName' => 'CelaMessage.`Message`',
					'Alias'     => 'Message',
					'Extra'     => '',
					'Render'    => 'StriptTags'
				),
				4 => array(
					'Type'      => 1,
					'ColumName' => '',
					'Alias'     => '',
					'Extra'     => 'FileMail',
					'Render'    => ''
				),
				5 => array(
					'Type'      => 2,
					'ColumName' => 'CelaMessage.`Time`',
					'Alias'     => 'Time',
					'Extra'     => '',
					'Render'    => 'MailTime'
				),
				6 => array(
					'Type'      => 0,
					'ColumName' => 'CelaMessage.`Tipo`',
					'Alias'     => 'Tipo',
					'Extra'     => '',
					'Render'    => ''
				),
				7 => array(
					'Type'      => 0,
					'ColumName' => 'CelaMessage.`Class`',
					'Alias'     => 'Class',
					'Extra'     => '',
					'Render'    => ''
				),
				8 => array(
					'Type'      => 0,
					'ColumName' => 'CelaMessage.`FechaEnvio`',
					'Alias'     => 'Fecha',
					'Extra'     => '',
					'Render'    => ''
				),
				9 => array(
					'Type'      => 0,
					'ColumName' => 'CelaMessage.`IsRead`',
					'Alias'     => 'IsRead',
					'Extra'     => '',
					'Render'    => ''
				)
			),
			'Condition'     => '',
			'Group'         => '',
			'Order'         => ' CelaMessage.`FechaEnvio` DESC ',
			'RenderRow'     => 'Class',
			'Privileges'    => $Privileges,
			'Debug'         => 1
		);

		return $ServerQuery;
	}

	function CelaMessageActualizar($Key, $FormData){
		global $Connection;

		$UpdateQuery =  sprintf('UPDATE CelaMessage
								 SET `Nombre` = %s, `Tabla` = %s
								 WHERE `id` = %s;',
							GetSQLValueString($FormData['Nombre'], 'varchar'),
							GetSQLValueString($FormData['Tabla'], 'varchar'), GetSQLValueString($Key, 'int')
		);

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}
		return $Data;
	}

	function CelaMessageQueryCombo($InText = false){
		if($InText === true){
			$Query = sprintf('SELECT `Nombre`, `Nombre` FROM CelaMessage  ORDER BY `Nombre` ASC');
		}elseif($InText === false){
			$Query = sprintf('SELECT `id`, `Nombre` FROM CelaMessage  ORDER BY `Nombre` ASC');
		}

		return $Query;
	}

	function CelaMessageGetStatus($SessionUserId){
		$MessagesStat = GetValue(
			sprintf('SELECT (SELECT
    COUNT(*)
FROM cometchat c
WHERE
    c.to = %s AND
    c.status = %s AND
    c.`read` = %s) as Unread, (SELECT
        COUNT(*)
    FROM cometchat c2
    WHERE
        c2.from = %s AND
        c2.status = %s) as Sent, (SELECT
            COUNT(*)
        FROM cometchat c3
        WHERE
            c3.to = %s AND
            c3.status = %s) as Trash;',
				GetSQLValueString($SessionUserId, 'int'),
				GetSQLValueString('1', 'int'),
				GetSQLValueString('0', 'varchar'),
				GetSQLValueString($SessionUserId, 'int'),
				GetSQLValueString('1', 'int'),
				GetSQLValueString($SessionUserId, 'int'),
				GetSQLValueString('0', 'int')
			)
		);

		return $MessagesStat;
	}

	function CelaMessageGetAnnouncements($Key = null, $Filter = 'ca.id', $Condition = ' 1=1 '){
		global $Connection;

		if(is_array($Key)){
			extract($Key, EXTR_OVERWRITE);
		}

		$CelaAnnouncementsQuery =    sprintf('SELECT *
				FROM annoucements_group ag
					INNER JOIN cometchat_announcements ca ON ( ag.annoucement = ca.id  )
				WHERE %s IN (%s) AND %s ORDER BY ca.set_order ASC, ca.send_time DESC;',
			$Filter,
			$Key,
			$Condition
		);

		if($CelaAnnouncementsResult = $Connection -> query($CelaAnnouncementsQuery)){
			$CelaAnnouncementss = array();
			while($CelaAnnouncementsRecord = $CelaAnnouncementsResult -> fetch_assoc()){
				$CelaAnnouncementss[] = $CelaAnnouncementsRecord;
			}

			$Data = array(
				'Status' => 'OK',
				'Data' => $CelaAnnouncementss
			);
		}else{
			$Data = array(
				'Status'    => 'ERROR',
				'Error'     => $Connection -> error
			);
		}

		return $Data;
	}
?>