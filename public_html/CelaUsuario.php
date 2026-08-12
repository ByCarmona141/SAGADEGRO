<?php
	/**

	 * Fecha: 12/11/2015
	 * Descripción: Controlador de la tabla "CelaUsuario".
	 **/

	require_once('../Libraries/Functions.php');
	require_once('../Libraries/Session.class.php');
	require_once('../CelaUsuario/CelaUsuario.php');
	require_once('../CelaRol/CelaRol.php');
	require_once('../CelaStatus/CelaStatus.php');
	require_once('../Libraries/Connection.php');
	require_once('../Libraries/GetSession.php');
	require_once('../Libraries/Security.php');

	$ArgsCelaUsuarioVistaLeer = array(
		'Table'             => 'CelaUsuario',
		'ServerSource'      => '../CelaUsuario/CelaUsuario.php',
		'ServerFunction'    => 'CelaUsuarioLeer',
		'RouteForm'         => $RouteForm
	);

	$ArgsCelaHeadContent['FormTitle'] = 'Listado de Usuarios';

	$MyScripts  = '';
	$MyStyles   = '';
	$Content    = '';

	$BadKeys    = array();

	if(isset($_GET['Action'])){
		switch($_GET['Action']){
			case 'Crear':
				/*Se verifica si se tiene el privilegio de crear*/
				if(isset($Privileges['Crear']) && $Privileges['Crear'] == 1) {
					$ArgsCelaHeadContent['FormTitle'] = 'Creaci&oacute;n de Usuario';

					/*Se verifica que haya evento "submit"*/
					if((isset($_POST['CelaUsuarioInsert'])) && ($_POST['CelaUsuarioInsert'] == 'CelaUsuarioInsert')){
						/*Se invoca la funcion crear*/
						$_POST['Contrase_na'] = md5($_POST['Contrase_na']);
						$CelaUsuario = CelaUsuarioCrear($_POST);

						if($CelaUsuario['Status'] == 'OK'){
							/*Se registra la acción "Crear" en la bitacora*/
							RecordLog('CelaUsuario', $CelaUsuario['idRecord'], 2, $SessionUserId, $_POST);
							$Status = true;

							/*Se carga la vista de lectura con mensaje creación correcta*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'success',
								'IconMessage'   => 'fa-check',
								'TitleMessage'  => 'Registro exitoso!',
								'TextMessage'   => 'El nuevo elemento se registr&oacute; correctamente.'
							);

							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

							if($Status){
								if(isset($_POST['InsertBack']) && $_POST['InsertBack'] == 1){
									/*Se carga la vista de Creación*/
									$ArgsCelaUsuarioVistaCrear = array(
										'SessionGroupId'    => $SessionGroupId,
										'Random'            => $SessionRandom,
										'FormAction'        => $RouteForm
									);

									$Content .= LoadContentPage('../CelaUsuario/CelaUsuarioVistaCrear.php', $ArgsCelaUsuarioVistaCrear);
									$MyScripts .= LoadContentPage('../CelaUsuario/CelaUsuarioJavascriptCrear.php');
									$MyStyles   .= '<style>
													.SelectPrivilege{
														width: 100% !important;
													}
												</style>';
									if(isset($_GET['OnSave']) && $_GET['OnSave'] == 'Close'){
										/*Se cierra la ventana una vez guardado el Empleado*/
										$MyScripts .= '<script>
															$(function(){
																window.close();
															});
														</script>';
									}
								}else{
									if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
										$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');

										$ArgsCelaUsuarioVistaLeer['Params']['Group']    = $Group;
										$ArgsCelaUsuarioVistaLeer['SessionRandom']      = $SessionRandom;

										$Content  .= LoadContentPage('../CelaUsuario/CelaUsuarioVistaLeer.php', $ArgsCelaUsuarioVistaLeer);

										$ArgsCelaUsuarioJavascript = array(
											'Table' => 'CelaUsuario',
										);

										$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaUsuarioJavascript);
										if(isset($_GET['OnSave']) && $_GET['OnSave'] == 'Close'){
											/*Se cierra la ventana una vez guardado el Empleado*/
											$MyScripts .= '<script>
															$(function(){
																window.close();
															});
														</script>';
										}
									}
								}
							}else{
								/*Se carga la vista con el mensaje de error de creación*/
								$ArgsCelaActionMessage = array(
									'StatusMessage' => 'danger',
									'IconMessage'   => 'fa-times',
									'TitleMessage'  => 'Oops!... Ocurrio un error asignando el proyecto al usuario',
									'TextMessage'   => 'Algunos elementos pudieron no haberse registrado'
								);

								$ArgsCelaActionMessage['TextMessage'] .= '<div class="list-group">';
								for($i = 0; $i < count($BadKeys); $i++){
									$ArgsCelaActionMessage['TextMessage'] .= '<a href="#" class="list-group-item">( "Elemento" => "' . $BadKeys[$i]['Index'] . '", "Error" => "' . $BadKeys[$i]['Error'] . '" )</a>';
								}
								$ArgsCelaActionMessage['TextMessage'] .= '</div>';
								$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaUsuario.php"
	class="btn btn-danger">Aceptar</a>';

								$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
							}
						}else{
							$Status = false;
							/*Se carga la vista de lectura con mensaje de error de creación*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error registrando el elemento',
								'TextMessage'   => $CelaUsuario['Error'].'<br />puede <a href="CelaUsuario.php?' . EncodeThis('Action=Crear') . '" class="alert-link">volver a intentar</a> &oacute; <a href="Escritorio.php" class="alert-link">ir al escritorio</a>'
							);

							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se carga la vista de Creación*/
						$ArgsCelaUsuarioVistaCrear = array(
							'SessionGroupId'    => $SessionGroupId,
							'Random'            => $SessionRandom,
							'FormAction'        => $RouteForm
						);

						$Content .= LoadContentPage('../CelaUsuario/CelaUsuarioVistaCrear.php', $ArgsCelaUsuarioVistaCrear);
						$MyScripts .= LoadContentPage('../CelaUsuario/CelaUsuarioJavascriptCrear.php');
						$MyStyles   .= '<style>
											.SelectPrivilege{
												width: 100% !important;
											}
										</style>';
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
									sprintf('SELECT * FROM CelaUsuario WHERE `id` = %s;',
										GetSQLValueString($Key, 'int')
									)
								);
						$CelaUsuario = CelaUsuarioEliminar($Key);

						if($CelaUsuario['Status'] == 'ERROR'){
							/*Se guarda el error para mostrarlo*/
							$Status = false;
							$Result = array();

							$Result['Index']    = $Key;
							$Result['Error']    = $CelaUsuario['Error'];
							$BadKeys[]          = $Result;
						}else{
							/*Se registra la acción "Eliminar" en la bitacora*/
							RecordLog('CelaUsuario', $Key, 3, $SessionUserId, $Data);
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
							$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');

							$ArgsCelaUsuarioVistaLeer['Params']['Group']    = $Group;
							$ArgsCelaUsuarioVistaLeer['SessionRandom']      = $SessionRandom;

							$Content .= LoadContentPage('../CelaUsuario/CelaUsuarioVistaLeer.php', $ArgsCelaUsuarioVistaLeer);

							$ArgsCelaUsuarioJavascript = array(
								'Table' => 'CelaUsuario',
							);

							$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaUsuarioJavascript);
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
						$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaUsuario.php"
	class="btn btn-danger">Aceptar</a>';

						$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
					}
				}else{
					$Connection -> close();
					header(sprintf('Location: %s', 'CelaUsuario.php'));
				}
				break;
			case 'Actualizar':
				/*Se verifica si se tiene el privilegio de actualizar*/
				if(isset($Privileges['Actualizar']) && $Privileges['Actualizar'] == 1) {
					$ArgsCelaHeadContent['FormTitle'] = 'Actualizaci&oacute;n de Usuario';

					/*Se verifica que haya evento "submit"*/
					if(isset($_POST['CelaUsuarioUpdate']) && $_POST['CelaUsuarioUpdate'] == 'CelaUsuarioUpdate'){
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

								if($_POST['Contrase_na' . $Key] == '' || $_POST['Contrase_na' . $Key] == NULL){
									$Password = Decrypt($_POST[Encrypt('Contrase_naant' . $Key, $SessionRandom)], $SessionRandom);
								}else{
									$Password = md5($_POST['Contrase_na' . $Key]);
								}

								$Data = array(
									'NombreCompleto'        => $_POST['NombreCompleto' . $Key],
									'Usuario'               => $_POST['Usuario' . $Key],
									'Contrase_na'           => $Password,
									'CorreoElectr_onico'    => $_POST['CorreoElectr_onico' . $Key],
									'Status'                => $_POST['Status' . $Key],
									'Rol'                   => $_POST['Rol' . $Key]
								);
								$CelaUsuario = CelaUsuarioActualizar($Key, $Data);

								if($CelaUsuario['Status'] == 'ERROR'){
									/*Se guarda el error para mostrarlo*/
									$Status = false;
									$Result = array();

									$Result['Index']    = $Key;
									$Result['Error']    = $CelaUsuario['Error'];
									$BadKeys[]          = $Result;
								}else{
									/*Se registra la acción "Actualizar" en la bitacora*/
									RecordLog('CelaUsuario', $Key, 5, $SessionUserId, $Data);

									/*Se se ha selaccionado un empleado*/
									if(isset($_POST['Empleado' . $Key]) && $_POST['Empleado' . $Key] != ''){
										$UpdateValue = UpdateValue('CelaUsuario', array('Empleado' => $_POST['Empleado' . $Key]), array('id' => $Key));
									}
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
									$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');

									$ArgsCelaUsuarioVistaLeer['Params']['Group']    = $Group;
									$ArgsCelaUsuarioVistaLeer['SessionRandom']      = $SessionRandom;

									$Content .= LoadContentPage('../CelaUsuario/CelaUsuarioVistaLeer.php', $ArgsCelaUsuarioVistaLeer);

									$ArgsCelaUsuarioJavascript = array(
										'Table' => 'CelaUsuario',
									);

									$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaUsuarioJavascript);
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
								$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaUsuario.php"
	class="btn btn-danger">Aceptar</a>';

								$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
							}
						}else{
							/*Se carga la vista con error obtención de datos para eliminar*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error obteniendo el listado de elementos',
								'TextMessage'   => 'Algunos elementos para actualizar estan corruptos o no existen. <a href="CelaUsuario.php"
	class="btn btn-danger">Aceptar</a>'
							);

							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se verifica que haya datos para eliminar*/
						if(isset($_GET['Key']) && $_GET['Key'] != '') {
							/*Se carga la vista de actualización*/
							$ArgsCelaUsuarioVistaActualizar = array(
								'SessionGroupId'    => $SessionGroupId,
								'Random'            => $SessionRandom,
								'FormAction'        => $RouteForm
							);
							$Content .= LoadContentPage('../CelaUsuario/CelaUsuarioVistaActualizar.php', $ArgsCelaUsuarioVistaActualizar);
							$MyScripts .= LoadContentPage('../CelaUsuario/CelaUsuarioJavascriptActualizar.php');
							$MyStyles   .= '<style>
												.SelectPrivilege{
													width: 100% !important;
												}
											</style>';
						}else{
							/*Se carga la busqueda de lectura*/
							$Connection -> close();
							header(sprintf('Location: %s', 'CelaUsuario.php'));
						}
					}
				}
				break;
			case 'LoginAs':
				$ArgsCelaHeadContent['FormTitle'] = 'Inicio de Sessi&oacute; de Usuario';

				/*Se invoca la función de login*/
				$CelaUsuario = CelaUsuarioLoginAs($_GET['Key']);
				if($CelaUsuario){
					/*Se carga el escritorio principal del sistema*/
					$Connection -> close();
					header(sprintf('Location: %s', 'Escritorio'));
				}else{
					/*Se carga la vista con error de login*/
					$Status = false;

					$ArgsCelaActionMessage = array(
						'StatusMessage' => 'danger',
						'IconMessage'   => 'fa-times',
						'TitleMessage'  => 'Oops!... No pudimos iniciar sesi&oacute;n',
						'TextMessage'   => 'tal vez este perfil no este disponible por el momento'
					);

					$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
				}
				break;
			case 'Activar':
				$ArgsCelaHeadContent['FormTitle'] = 'Activaci&oacute;n de Usuario';

				/*Se verifica que haya evento "submit"*/
				if(isset($_POST['CelaUsuarioUpdate']) && $_POST['CelaUsuarioUpdate'] == 'CelaUsuarioUpdate'){
					/*Se actualiza el usuario*/
					$UpdateUser = UpdateValue(
						'CelaUsuario',
						array(
							'Contrase_na' => GetSQLValueString(md5($_POST['Contrase_na']), 'varchar'),
							'Activo' => GetSQLValueString(1, 'int')
						),
						array('id' => $SessionUserId)
					);

					if($UpdateUser['Status'] == 'OK'){
						/*Se actualiza el valor de la variable de sesión*/
						$Session->Update('Activo', '1');

						/*Se guarda la contraseña*/
						$QueryInsert = sprintf('INSERT INTO CelaHistoriaContrase_na (id, Usuario, Contrase_na, UltimoCambio) VALUES (%s, %s, %s, %s);',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($SessionUserId, 'int'),
							GetSQLValueString(md5($_POST['Contrase_na']), 'varchar'),
							GetSQLValueString(date('Y-m-d'), 'date')
						);

						if($ResultInsert = $Connection->query($QueryInsert)){
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'success',
								'IconMessage'   => 'fa-check',
								'TitleMessage'  => 'Exito,...!!!',
								'TextMessage'   => 'La contrase&ntilde;a se ha guardado, ahora puedes hacer uso del sistema.'
							);

							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}else{
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... No pudimos registrar la contrase&ntilde;a',
								'TextMessage'   => $Connection->error
							);

							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						$ArgsCelaActionMessage = array(
							'StatusMessage' => 'danger',
							'IconMessage'   => 'fas fa-times',
							'TitleMessage'  => 'Oops!... No pudimos guardar la contrase&ntilde;a',
							'TextMessage'   => $UpdateUser['Error']
						);

						$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
					}
				}else{
					if(isset($_GET['Expire']) && $_GET['Expire'] == 1){
						$ArgsCelaActionMessage = array(
							'StatusMessage' => 'warning',
							'IconMessage'   => 'fas fa-info',
							'TitleMessage'  => 'Aviso Importante',
							'TextMessage'   => 'Por motivos de seguridad en d&iacute;as pasados se te ha invitado a actualizar tu contrase&ntilde;a. Por favor actualizala ahora.'
						);

						$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
					}
					/*Se carga la vista de activación*/
					$ArgsCelaUsuarioVistaActivar = array(
						'SessionGroupId'    => $SessionGroupId,
						'Random'            => $SessionRandom,
						'FormAction'        => $RouteForm
					);
					$Content .= LoadContentPage('../CelaUsuario/CelaUsuarioVistaActivar.php', $ArgsCelaUsuarioVistaActivar);
					$MyScripts .= LoadContentPage('../CelaUsuario/CelaUsuarioJavascriptActivar.php');
				}
				break;
		}
	}else{
		/*Se carga la vista de lectura*/
		if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {

			$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');

			$ArgsCelaUsuarioVistaLeer['Params']['Group']    = $Group;
			$ArgsCelaUsuarioVistaLeer['SessionRandom']      = $SessionRandom;

			$Content  = LoadContentPage('../CelaUsuario/CelaUsuarioVistaLeer.php', $ArgsCelaUsuarioVistaLeer);

			$ArgsCelaUsuarioJavascript = array(
				'Table' => 'CelaUsuario',
			);

			$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaUsuarioJavascript);
			if(isset($_GET['OnSave']) && $_GET['OnSave'] == 'Close'){
				/*Se cierra la ventana una vez guardado el Empleado*/
				$MyScripts .= '<script>
									$(function(){
										window.close();
									});
								</script>';
}
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
