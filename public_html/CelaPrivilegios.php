<?php
	/**

	 * Fecha: 12/11/2015
	 * Descripción: Controlador de la tabla "CelaPrivilegios".
	 **/

	require_once('../Libraries/Functions.php');
	require_once('../Libraries/Session.class.php');
	require_once('../CelaPrivilegios/CelaPrivilegios.php');
	require_once('../CelaPrivilegio/CelaPrivilegio.php');
	require_once('../CelaOrigen/CelaOrigen.php');
	require_once('../Libraries/Connection.php');
	require_once('../Libraries/GetSession.php');
	require_once('../Libraries/Security.php');

	$ArgsCelaPrivilegiosVistaLeer = array(
		'Table'             => 'CelaPrivilegios',
		'ServerSource'      => '../CelaPrivilegios/CelaPrivilegios.php',
		'ServerFunction'    => 'CelaPrivilegiosLeer',
		'RouteForm'         => $RouteForm
	);

	$ArgsCelaHeadContent['FormTitle'] = 'Listado de Privilegios (Objetos y Recursos)';

	$MyScripts  = '';
	$Content    = '';
	$MyStyles   = '';

	$BadKeys    = array();

	if(isset($_GET['Action'])){
		switch($_GET['Action']){
			case 'Crear':
				/*Se verifica si se tiene el privilegio de crear*/
				if(isset($Privileges['Crear']) && $Privileges['Crear'] == 1) {
					$ArgsCelaHeadContent['FormTitle'] = 'Creaci&oacute;n de Privilegios (Objetos y Recursos)';

					/*Se verifica que haya evento "submit"*/
					if((isset($_POST['CelaPrivilegiosInsert'])) && ($_POST['CelaPrivilegiosInsert'] == 'CelaPrivilegiosInsert')){
						/*Se invoca la funcion crear*/
						$CelaPrivilegios = CelaPrivilegiosCrear($_POST);

						if($CelaPrivilegios['Status'] == 'OK'){
							/*Se registra la acción "Crear" en la bitacora*/
							RecordLog('CelaPrivilegios', $CelaPrivilegios['idRecord'], 2, $SessionUserId, $_POST);
							$Status = true;

							/*Se carga la vista de lectura con mensaje creación correcta*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'success',
								'IconMessage'   => 'fa-check',
								'TitleMessage'  => 'Registro exitoso!',
								'TextMessage'   => 'El nuevo elemento se registr&oacute; correctamente.'
							);

							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

							if(isset($_POST['InsertBack']) && $_POST['InsertBack'] == 1){
								/*Se carga la vista de Creación*/
								$ArgsCelaPrivilegiosVistaCrear = array(
									'SessionGroupId'    => $SessionGroupId,
									'Random'            => $SessionRandom,
									'FormAction'        => $RouteForm
								);

								$Content = LoadContentPage('../CelaPrivilegios/CelaPrivilegiosVistaCrear.php', $ArgsCelaPrivilegiosVistaCrear);
							}else{
								if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
									$ArgsCelaHeadContent['FormTitle'] = 'Listado de Privilegios (Objetos y Recursos)';

									$Content  .= LoadContentPage('../CelaPrivilegios/CelaPrivilegiosVistaLeer.php', $ArgsCelaPrivilegiosVistaLeer);

									$ArgsCelaPrivilegiosJavascript = array(
										'Table' => 'CelaPrivilegios',
									);

									$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaPrivilegiosJavascript);
								}
							}
						}else{
							$Status = false;
							/*Se carga la vista de lectura con mensaje de error de creación*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error registrando el elemento',
								'TextMessage'   => $CelaPrivilegios['Error'].'<br />puede <a href="CelaPrivilegios.php?' . EncodeThis('Action=Crear') . '" class="alert-link">volver a intentar</a> &oacute; <a href="Escritorio.php" class="alert-link">ir al escritorio</a>'
							);

							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se carga la vista de Creación*/
						$ArgsCelaPrivilegiosVistaCrear = array(
							'SessionGroupId'    => $SessionGroupId,
							'Random'            => $SessionRandom,
							'FormAction'        => $RouteForm
						);

						$Content = LoadContentPage('../CelaPrivilegios/CelaPrivilegiosVistaCrear.php', $ArgsCelaPrivilegiosVistaCrear);
					}
				}
				break;
			case 'Eliminar':
				/*Se verifica que haya datos para eliminar y verifica si se tiene el privilegio de crear*/
				if(isset($_GET['Key']) && $_GET['Key'] != '' && isset($Privileges['Eliminar']) && $Privileges['Eliminar'] == 1) {
					$Status = true;

					/*Se recorre cada uno de los elementos que se van a eliminar*/
					foreach ($_GET['Key'] as $Key) {
						/*Se invoca la funcion eliminar*/
						$Data = GetValue(
									sprintf('SELECT * FROM CelaPrivilegios WHERE `id` = %s;',
										GetSQLValueString($Key, 'int')
									)
								);
						$CelaPrivilegios = CelaPrivilegiosEliminar($Key);

						if($CelaPrivilegios['Status'] == 'ERROR'){
							/*Se guarda el error para mostrarlo*/
							$Status = false;
							$Result = array();

							$Result['Index']    = $Key;
							$Result['Error']    = $CelaPrivilegios['Error'];
							$BadKeys[]          = $Result;
						}else{
							/*Se registra la acción "Eliminar" en la bitacora*/
							RecordLog('CelaPrivilegios', $Key, 3, $SessionUserId, $Data);
						}
					}
					if($Status){
						/*Se carga la vista de lectura con mensaje de eliminación correcta*/
						$ArgsCelaActionMessage = array(
							'StatusMessage' => 'success',
							'IconMessage'   => 'fa-check',
							'TitleMessage'  => 'Eliminaci&oacute;n correcta!',
							'TextMessage'   => 'El/Los elemento(s) se eliminar&oacute;n correctamente.'
						);
						$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

						if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
							$ArgsCelaHeadContent['FormTitle'] = 'Listado de Privilegios (Objetos y Recursos)';

							$Content .= LoadContentPage('../CelaPrivilegios/CelaPrivilegiosVistaLeer.php', $ArgsCelaPrivilegiosVistaLeer);

							$ArgsCelaPrivilegiosJavascript = array(
								'Table' => 'CelaPrivilegios',
							);
							$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaPrivilegiosJavascript);
						}
					}else{
						/*Se carga la vista con el mensaje de error de eliminación*/
						$ArgsCelaActionMessage = array(
							'StatusMessage' => 'danger',
							'IconMessage'   => 'fa-times',
							'TitleMessage'  => 'Oops!... Ocurrio un error eliminando el/los elemento(s)',
							'TextMessage'   => 'Algunos elementos pudieron no haberse eliminado'
						);

						$ArgsCelaActionMessage['TextMessage'] .= '<div class="list-group">';
						for($i = 0; $i < count($BadKeys); $i++){
							$ArgsCelaActionMessage['TextMessage'] .= '<a href="#" class="list-group-item">( "Elemento" => "' . $BadKeys[$i]['Index'] . '", "Error" => "' . $BadKeys[$i]['Error'] . '" )</a>';
						}
						$ArgsCelaActionMessage['TextMessage'] .= '</div>';
						$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaPrivilegios.php"
	class="btn btn-danger">Aceptar</a>';

						$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
					}
				}else{
					$Connection -> close();
					header(sprintf('Location: %s', 'CelaPrivilegios.php'));
				}
				break;
			case 'Actualizar':
				/*Se verifica si se tiene el privilegio de actualizar*/
				if(isset($Privileges['Actualizar']) && $Privileges['Actualizar'] == 1) {
					$ArgsCelaHeadContent['FormTitle'] = 'Actualizaci&oacute;n de Privilegios (Objetos y Recursos)';

					/*Se verifica que haya evento "submit"*/
					if(isset($_POST['CelaPrivilegiosUpdate']) && $_POST['CelaPrivilegiosUpdate'] == 'CelaPrivilegiosUpdate'){
						/*Se decodicia el arreglo de las claves para actualizar*/
						$EncryptedKey = (isset($_POST[Encrypt('id', $SessionRandom)]) ? $_POST[Encrypt('id', $SessionRandom)]:'');
						if($EncryptedKey != ''){
							$Keys = Decrypt($EncryptedKey, $SessionRandom);
							$Keys = explode(',', $Keys);
							$Status = true;
						}else{
							$Status = false;
						}

						if($Status){
							$Status = true;
							/*Se recorre cada uno de los elementos que se van a actualizar*/
							foreach($Keys as $Key){
								/*Se invoca la funcion actualizar*/
								$Data = array(
									'Privilegio'    => $_POST['Privilegio' . $Key],
									'Origen'        => $_POST['Origen' . $Key],
									'Tupla'         => $_POST['Tupla' . $Key],
									'TuplaAcceso'   => $_POST['TuplaAcceso' . $Key],
								);
								$CelaPrivilegios = CelaPrivilegiosActualizar($Key, $Data);

								if($CelaPrivilegios['Status'] == 'ERROR'){
									/*Se guarda el error para mostrarlo*/
									$Status = false;
									$Result = array();

									$Result['Index']    = $Key;
									$Result['Error']    = $CelaPrivilegios['Error'];
									$BadKeys[]          = $Result;
								}else{
									/*Se registra la acción "Actualizar" en la bitacora*/
									RecordLog('CelaPrivilegios', $Key, 5, $SessionUserId, $Data);
								}
							}

							if($Status){
								/*Se carga la vista de lectura con mensaje de actualización correcta*/
								$ArgsCelaActionMessage = array(
									'StatusMessage' => 'success',
									'IconMessage'   => 'fa-check',
									'TitleMessage'  => 'Actualizaci&oacute;n exitosa!',
									'TextMessage'   => 'El/Los elemento(s) se actualizar&oacute;n correctamente.'
								);
								$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

								if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
									$ArgsCelaHeadContent['FormTitle'] = 'Listado de Privilegios (Objetos y Recursos)';

									$Content .= LoadContentPage('../CelaPrivilegios/CelaPrivilegiosVistaLeer.php', $ArgsCelaPrivilegiosVistaLeer);

									$ArgsCelaPrivilegiosJavascript = array(
										'Table' => 'CelaPrivilegios',
									);
									$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaPrivilegiosJavascript);
								}
							}else{
								/*Se carga la vista con el mensaje de error de actualización*/
								$ArgsCelaActionMessage = array(
									'StatusMessage' => 'danger',
									'IconMessage'   => 'fa-times',
									'TitleMessage'  => 'Oops!... Ocurrio un error actualizando el/los elemento(s)',
									'TextMessage'   => 'Algunos elementos pudieron no haberse actualizado'
								);

								$ArgsCelaActionMessage['TextMessage'] .= '<div class="list-group">';
								for($i = 0; $i < count($BadKeys); $i++){
									$ArgsCelaActionMessage['TextMessage'] .= '<a href="#" class="list-group-item">( "Elemento" => "' . $BadKeys[$i]['Index'] . '", "Error" => "' . $BadKeys[$i]['Error'] . '" )</a>';
								}
								$ArgsCelaActionMessage['TextMessage'] .= '</div>';
								$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaPrivilegios.php"
	class="btn btn-danger">Aceptar</a>';

								$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
							}
						}else{
							/*Se carga la vista con error obtención de datos para eliminar*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error obteniendo el listado de elementos',
								'TextMessage'   => 'Algunos elementos para actualizar estan corruptos o no existen. <a href="CelaPrivilegios.php"
	class="btn btn-danger">Aceptar</a>'
							);
							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se verifica que haya datos para actualizar*/
						if(isset($_GET['Key']) && $_GET['Key'] != '') {
							/*Se carga la vista de actualización*/
							$ArgsCelaPrivilegiosVistaActualizar = array(
								'SessionGroupId'    => $SessionGroupId,
								'Random'            => $SessionRandom,
								'FormAction'        => $RouteForm
							);
							$Content = LoadContentPage('../CelaPrivilegios/CelaPrivilegiosVistaActualizar.php', $ArgsCelaPrivilegiosVistaActualizar);
						}else{
							/*Se carga la vista de lectura y busqueda*/
							$Connection -> close();
							header(sprintf('Location: %s', 'CelaPrivilegios.php'));
						}
					}
				}
				break;
			case 'Privilegios':
				if(isset($Privileges['Administrar']) && $Privileges['Administrar'] == 1){
					$Status = true;
					/*Se verifica que haya evento "submit"*/
					if(isset($_POST['CelaPrivilegiosUpdate']) && $_POST['CelaPrivilegiosUpdate'] == 'CelaPrivilegiosUpdate'){

						/*Se Obtienen los Formulario que adminsitra el rol/grupo actual*/
						$Admin =    strtoupper(
										GetValue(
											sprintf('SELECT Nombre FROM CelaRol WHERE `id` = %s;',
												GetSQLValueString($SessionGroupId, 'varchar')
											),
											'Nombre'
										)
									);

						if($Admin == 'DEVELOPER' || $Admin == 'DESARROLLADOR'){
							/*Se Obtienen los provilegios que administra el rol/grupo actual*/
							$PrivilegeQuery = CelaPrivilegioQueryCombo();
							
							$OriginQuery = sprintf('SELECT * FROM %s WHERE `id` != %s ORDER BY `id` ASC;', $_POST['TablaOrigen'], 0);
						}else{
							/*Se Obtienen los provilegios que administra el rol/grupo actual*/
							$PrivilegeQuery = CelaPrivilegioQueryCombo('InPrivilege', $SessionGroupId, 4);

							$OriginQuery = sprintf('SELECT * FROM %s WHERE `id` IN (select `Tupla`
																					from CelaPrivilegios
																					where
																						`TuplaAcceso` = %s and
																						`Origen` = %s
																				   ) AND
														`id` != %s',
												$_POST['TablaOrigen'],
												$SessionGroupId,
												2,
												0
											);
						}

						$PrivilegeResult    = $Connection -> query($PrivilegeQuery);
						$Privileges         = array();
						while($PrivilegeRecod = $PrivilegeResult -> fetch_assoc()){
							$Data = array(
										'id' => $PrivilegeRecod['id'],
										'Nombre' => $PrivilegeRecod['Nombre'],
										'Descripci_on' => $PrivilegeRecod['Descripci_on']
									);
							$Privileges[] = $Data;
						}

						$OriginResult = $Connection -> query($OriginQuery);
						while($OriginRecord = $OriginResult -> fetch_assoc()){
							for($i = 0; $i < count($Privileges); $i++){
								/*Se verifica si existe el privilegio, de lo contrario se crea.*/
								if(!isset($_POST[$Privileges[$i]['Nombre'] . $OriginRecord['id']])){
									/*Se elimina el privilegio*/
									$DeleteQuery =  sprintf('DELETE FROM CelaPrivilegios
													     WHERE
													        `TuplaAcceso` = %s AND
													        `Origen` = %s AND
													        `Tupla` = %s AND
													        `Privilegio` = %s;',
										GetSQLValueString($_POST['TuplaAcceso'], 'int'),
										GetSQLValueString($_POST['Origen'], 'int'),
										GetSQLValueString($OriginRecord['id'], 'int'),
										GetSQLValueString($Privileges[$i]['id'], 'int')
									);

									if($Connection -> query($DeleteQuery)){
										$Status = true;
									}else{
										/*Se guarda el error para mostrarlo*/
										$Status = false;
										$Result = array();

										$Result['Index']    = $OriginRecord['id'] . ' - ' . $Privileges[$i]['Nombre'];
										$Result['Error']    = $Connection -> error;;
										$BadKeys[]          = $Result;
									}
								}else{
									$InPrivilege =  GetValue(
														sprintf('SELECT `id`
																 FROM CelaPrivilegios
															     WHERE
															        `TuplaAcceso` = %s AND
															        `Origen` = %s AND
															        `Tupla` = %s AND
															        `Privilegio` = %s;',
															GetSQLValueString($_POST['TuplaAcceso'], 'int'),
															GetSQLValueString($_POST['Origen'], 'int'),
															GetSQLValueString($OriginRecord['id'], 'int'),
															GetSQLValueString($Privileges[$i]['id'], 'int')
														),
														'id'
													);
									if($InPrivilege == 'NULL'){
										$Data = array(
											'Privilegio'    => $Privileges[$i]['id'],
											'Origen'        => $_POST['Origen'],
											'Tupla'         => $OriginRecord['id'],
											'TuplaAcceso'   => $_POST['TuplaAcceso']
										);

										$CelaPrivilegios = CelaPrivilegiosCrear($Data);
										if($CelaPrivilegios['Status'] == 'OK'){
											/*Se registra la acción "Crear" en la bitacora*/
											RecordLog('CelaPrivilegios', $CelaPrivilegios['idRecord'], 2, $SessionUserId, $Data);
										}else{
											/*Se guarda el error para mostrarlo*/
											$Status = false;
											$Result = array();

											$Result['Index']    = $OriginRecord['id'] . ' - ' . $Privileges[$i]['Nombre'];;
											$Result['Error']    = $CelaPrivilegios['Error'];
											$BadKeys[]          = $Result;
										}
									}
								}

								/*Se busca si existe un menu para el formulario y se asigna el menu*/
								//print $MenuQuery =   sprintf('SELECT cu.id
								//					FROM CelaMen_u cu
								//						INNER JOIN CelaFormulario c ON ( cu.Referencia = c.Ruta  )
								//					WHERE c.id = %s;',
								//	GetSQLValueString($OriginRecord['id'], 'int')
								//);

								//$Menu = GetValue($MenuQuery, 'id');

								//print_r($Menu);
								//if($Menu != 'NULL'){

								//	if($_POST['Origen'] == 3){
								//		$TuplaAcceso = GetValue('SELECT Rol FROM CelaUsuario WHERE id = ' . $_POST['TuplaAcceso'] .';', 'Rol');
								//	}else{
								//		$TuplaAcceso = $_POST['TuplaAcceso'];
								//	}

								//	/*Se asigna el privilegio de menu*/
								//	$Data = array();
								//	$Data['Privilegio']     = 1;
								//	$Data['Origen']         = 1;
								//	$Data['Tupla']          = $Menu;
								//	$Data['TuplaAcceso']    = $TuplaAcceso;
								//	$CelaPrivilegios = CelaPrivilegiosCrear($Data);

								//	if($CelaPrivilegios['Status'] == 'ERROR'){
								//		/*Se guarda el error para mostrarlo*/
								//		$Status = false;
								//		$Result = array();

								//		$Result['Index']    = $Key;
								//		$Result['Error']    = $CelaPrivilegios['Error'];
								//		$BadKeys[]          = $Result;
								//	}else{
								//		/*Se registra la acción "Crear" en la bitacora*/
								//		RecordLog('CelaPrivilegios', $CelaPrivilegios['idRecord'], 2, $SessionUserId, $Data);
								//	}
								//}
							}
						}

						if($Status){
							/*Se carga la vista de lectura con mensaje de actualización correcta*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'success',
								'IconMessage'   => 'fa-check',
								'TitleMessage'  => 'Actualizaci&oacute;n correcta!',
								'TextMessage'   => 'Los privilegios se actualizar&oacute;n correctamente.'
							);
							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

							/*Se verifica que haya parametros para actualizar los privilegios*/
							if(isset($_POST['Origen']) && $_POST['Origen'] != '' && isset($_POST['TuplaAcceso']) && $_POST['TuplaAcceso'] != '') {
								$Title =    GetValue(
									sprintf('SELECT Nombre FROM CelaOrigen WHERE id = %s;',
										GetSQLValueString($_POST['Origen'], 'int')
									),
									'Nombre'
								);

								if($_POST['Origen'] == 2){
									$Title .= ': ' . GetValue(
											sprintf('SELECT Nombre FROM CelaRol WHERE id = %s;',
												GetSQLValueString($_POST['TuplaAcceso'], 'int')
											),
											'Nombre'
										);
								}elseif($_POST['Origen'] == 3){
									$Title .= ': ' . GetValue(
											sprintf('SELECT NombreCompleto FROM CelaUsuario WHERE id = %s;',
												GetSQLValueString($_POST['TuplaAcceso'], 'int')
											),
											'NombreCompleto'
										);
								}

								$ArgsCelaHeadContent['FormTitle'] = $Title;
								/*Se carga la vista de actualización admin*/
								$ArgsCelaPrivilegiosVistaAdmin = array(
									'Origen'        => $_POST['Origen'],
									'TuplaAcceso'   => $_POST['TuplaAcceso'],
									'TablaOrigen'   =>  GetValue(
										sprintf('SELECT Tabla FROM CelaOrigen WHERE `id` = %s;',
											GetSQLValueString($_POST['Origen'], 'int')
										),
										'Tabla'
									),
									'Group'         => $SessionGroupId,
									'FormAction'    => $RouteForm
								);
								$Content    .= LoadContentPage('../CelaPrivilegios/CelaPrivilegiosVistaAdmin.php', $ArgsCelaPrivilegiosVistaAdmin);
								$MyScripts  .= LoadContentPage('../CelaPrivilegios/CelaPrivilegiosJavascriptAdmin.php');
								$MyStyles   .= '<style>
												.dataTables_scrollHead{
													width: 100% !importat;
												}
											</style>';
							}else{
								/*Se carga la vista de lectura y busqueda*/
								$Connection -> close();
								header(sprintf('Location: %s', 'CelaPrivilegios.php'));
							}
						}else{
							/*Se carga la vista con el mensaje de error de actualizacion*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error actualizando los privilegios',
								'TextMessage'   => 'Algunos elementos pudieron no haberse actualizado'
							);

							$ArgsCelaActionMessage['TextMessage'] .= '<div class="list-group">';
							for($i = 0; $i < count($BadKeys); $i++){
								$ArgsCelaActionMessage['TextMessage'] .= '<a href="#" class="list-group-item">( "Elemento" => "' . $BadKeys[$i]['Index'] . '", "Error" => "' . $BadKeys[$i]['Error'] . '" )</a>';
							}
							$ArgsCelaActionMessage['TextMessage'] .= '</div>';
							$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaPrivilegios.php"
	class="btn btn-danger">Aceptar</a>';

							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

							/*Se verifica que haya parametros para actualizar los privilegios*/
							if(isset($_POST['Origen']) && $_POST['Origen'] != '' && isset($_POST['TuplaAcceso']) && $_POST['TuplaAcceso'] != '') {
								$Title =    GetValue(
									sprintf('SELECT Nombre FROM CelaOrigen WHERE id = %s;',
										GetSQLValueString($_POST['Origen'], 'int')
									),
									'Nombre'
								);

								if($_POST['Origen'] == 2){
									$Title .= ': ' . GetValue(
											sprintf('SELECT Nombre FROM CelaRol WHERE id = %s;',
												GetSQLValueString($_POST['TuplaAcceso'], 'int')
											),
											'Nombre'
										);
								}elseif($_POST['Origen'] == 3){
									$Title .= ': ' . GetValue(
											sprintf('SELECT NombreCompleto FROM CelaUsuario WHERE id = %s;',
												GetSQLValueString($_POST['TuplaAcceso'], 'int')
											),
											'NombreCompleto'
										);
								}

								$ArgsCelaHeadContent['FormTitle'] = $Title;
								/*Se carga la vista de actualización admin*/
								$ArgsCelaPrivilegiosVistaAdmin = array(
									'Origen'        => $_POST['Origen'],
									'TuplaAcceso'   => $_POST['TuplaAcceso'],
									'TablaOrigen'   =>  GetValue(
										sprintf('SELECT Tabla FROM CelaOrigen WHERE `id` = %s;',
											GetSQLValueString($_POST['Origen'], 'int')
										),
										'Tabla'
									),
									'Group'         => $SessionGroupId,
									'FormAction'    => $RouteForm
								);
								$Content    .= LoadContentPage('../CelaPrivilegios/CelaPrivilegiosVistaAdmin.php', $ArgsCelaPrivilegiosVistaAdmin);
								$MyScripts  .= LoadContentPage('../CelaPrivilegios/CelaPrivilegiosJavascriptAdmin.php');
								$MyStyles   .= '<style>
												.dataTables_scrollHead{
													width: 100% !importat;
												}
											</style>';
							}else{
								/*Se carga la vista de lectura y busqueda*/
								$Connection -> close();
								header(sprintf('Location: %s', 'CelaPrivilegios.php'));
							}
						}
					}else{
						/*Se verifica que haya parametros para actualizar los privilegios*/
						if(isset($_GET['Origen']) && $_GET['Origen'] != '' && isset($_GET['TuplaAcceso']) && $_GET['TuplaAcceso'] != '') {
							$Title =    GetValue(
								sprintf('SELECT Nombre FROM CelaOrigen WHERE id = %s;',
									GetSQLValueString($_GET['Origen'], 'int')
								),
								'Nombre'
							);

							if($_GET['Origen'] == 2){
								$Title .= ': ' . GetValue(
										sprintf('SELECT Nombre FROM CelaRol WHERE id = %s;',
											GetSQLValueString($_GET['TuplaAcceso'], 'int')
										),
										'Nombre'
									);
							}elseif($_GET['Origen'] == 3){
								$Title .= ': ' . GetValue(
										sprintf('SELECT NombreCompleto FROM CelaUsuario WHERE id = %s;',
											GetSQLValueString($_GET['TuplaAcceso'], 'int')
										),
										'NombreCompleto'
									);
							}

							$ArgsCelaHeadContent['FormTitle'] = $Title;
							/*Se carga la vista de actualización admin*/
							$ArgsCelaPrivilegiosVistaAdmin = array(
								'Origen'        => $_GET['Origen'],
								'TuplaAcceso'   => $_GET['TuplaAcceso'],
								'TablaOrigen'   =>  GetValue(
														sprintf('SELECT Tabla FROM CelaOrigen WHERE `id` = %s;',
															GetSQLValueString($_GET['Origen'], 'int')
														),
														'Tabla'
													),
								'Group'         => $SessionGroupId,
								'FormAction'    => $RouteForm
							);
							$Content    .= LoadContentPage('../CelaPrivilegios/CelaPrivilegiosVistaAdmin.php', $ArgsCelaPrivilegiosVistaAdmin);
							$MyScripts  .= LoadContentPage('../CelaPrivilegios/CelaPrivilegiosJavascriptAdmin.php');
							$MyStyles   .= '<style>
												.dataTables_scrollHead{
													width: 100% !importat;
												}
											</style>';
						}else{
							/*Se carga la vista de lectura y busqueda*/
							$Connection -> close();
							header(sprintf('Location: %s', 'CelaPrivilegios.php'));
						}
					}
				}
				break;
		}
	}else{
		/*Se carga la vista de lectura*/
		if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
			$Content  .= LoadContentPage('../CelaPrivilegios/CelaPrivilegiosVistaLeer.php', $ArgsCelaPrivilegiosVistaLeer);

			$ArgsCelaPrivilegiosJavascript = array(
				'Table' => 'CelaPrivilegios',
			);
			$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaPrivilegiosJavascript);
		}
	}

	/*---Se carga el contenido de la pagina---*/
	$Header         = LoadContentPage('../CelaTemplate/CelaHead.php', $ArgsCelaHead);
	$HeadBar        = LoadContentPage('../CelaTemplate/CelaHeadBar.php', $ArgsCelaHeadBar);
	$SideBar        = LoadContentPage('../CelaTemplate/CelaSideBar.php', $ArgsCelaSideBar);
	$About          = LoadContentPage('../CelaTemplate/CelaAbout.php', $ArgsCelaAbout);
	$LockSession    = LoadContentPage('../CelaTemplate/CelaLockSession.php', $ArgsCelaLockSession);
	$Breadcrumb     = LoadContentPage('../CelaTemplate/CelaBreadcrumb.php', $ArgsCelaBreadcrumb);
	$HeaderForm     = LoadContentPage('../CelaTemplate/CelaHeadContent.php', $ArgsCelaHeadContent);
	$FooterForm     = LoadContentPage('../CelaTemplate/CelaFooterContent.php');
	$Footer         = LoadContentPage('../CelaTemplate/CelaFooter.php', $ArgsCelaFooter);
	$Scripts        = LoadContentPage('../CelaTemplate/CelaJavascript.php');

	/*---Se carga la plantilla HTML---*/
	$HTML   = LoadTemplatePage('../CelaTemplate/CelaTemplate.php');

	$TemplateTag = array(
		'<!--#HEADER#-->',
		'<!--#MYSTYLE#-->',
		'<!--#HORIZONTALMENU#-->',
		'<!--#VERTICALMENU#-->',
		'<!--#ABOUT#-->',
		'<!--#LOCKSESSION#-->',
		'<!--#BREADCRUMBS#-->',
		'<!--#HEADCONTENT#-->',
		'<!--#BODYCONTENT#-->',
		'<!--#FOOTERCONTENT#-->',
		'<!--#FOOTERPAGE#-->',
		'<!--#SCRIPTS#-->',
		'<!--#MYSCRIPTS#-->'
	);

	$HTMLContent = array(
		$Header,
		$MyStyles,
		$HeadBar,
		$SideBar,
		$About,
		$LockSession,
		$Breadcrumb,
		$HeaderForm,
		$Content,
		$FooterForm,
		$Footer,
		$Scripts,
		$MyScripts
	);

	$HTML   = ReplaceContentPage($TemplateTag, $HTMLContent, $HTML);
	$Connection -> close();
	ViewPage($HTML);
?>