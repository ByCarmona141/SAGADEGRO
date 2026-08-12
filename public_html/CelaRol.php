<?php
	/**

	 * Fecha: 12/11/2015
	 * Descripción: Controlador de la tabla "CelaRol".
	 **/

	require_once('../Libraries/Functions.php');
	require_once('../Libraries/Session.class.php');
	require_once('../CelaRol/CelaRol.php');
	require_once('../CelaTema/CelaTema.php');
	require_once('../CelaPrivilegios/CelaPrivilegios.php');
	require_once('../CelaStatus/CelaStatus.php');
	require_once('../CelaPrivilegio/CelaPrivilegio.php');
	require_once('../Libraries/Connection.php');
	require_once('../Libraries/GetSession.php');
	require_once('../Libraries/Security.php');

	$ArgsCelaRolVistaLeer = array(
		'Table'             => 'CelaRol',
		'ServerSource'      => '../CelaRol/CelaRol.php',
		'ServerFunction'    => 'CelaRolLeer',
		'RouteForm'         => $RouteForm
	);

	$ArgsCelaHeadContent['FormTitle'] = 'Listado de Grupos/Roles';

	$MyScripts  = '';
	$Content    = '';
	$MyStyles   = '';

	$BadKeys    = array();

	if(isset($_GET['Action'])){
		switch($_GET['Action']){
			case 'Crear':
				/*Se verifica si se tiene el privilegio de crear*/
				if(isset($Privileges['Crear']) && $Privileges['Crear'] == 1) {
					$ArgsCelaHeadContent['FormTitle'] = 'Creaci&oacute;n de Grupo/Rol';

					/*Se verifica que haya evento "submit"*/
					if((isset($_POST['CelaRolInsert'])) && ($_POST['CelaRolInsert'] == 'CelaRolInsert')){
						/*Se invoca la funcion crear*/
						$_POST['Grupo'] = $SessionGroupId;

						$Data = array(
							'Nombre'        => $_POST['Nombre'],
							'Siglas'        => $_POST['Siglas'],
							'Descripci_on'  => $_POST['Descripci_on'],
							'Grupo'         => $_POST['Grupo'],
							'Status'	 => 1,
							'Tema'		 => $_POST['Tema']
						);
						$CelaRol = CelaRolCrear($Data);

						if($CelaRol['Status'] == 'OK'){
							/*Se registra la acción "Crear" en la bitacora*/
							RecordLog('CelaRol', $CelaRol['idRecord'], 2, $SessionUserId, $Data);
							$Status = true;

							if(isset($_POST['Privilegios']) && count($_POST['Privilegios']) > 0){
								/*Se registran los privilegios del formulario*/
								for($i = 0; $i < count($_POST['Privilegios']); $i++){
									$Data = array(
										'Privilegio'     => 9,
										'Origen'         => 4,
										'Tupla'          => $_POST['Privilegios'][$i],
										'TuplaAcceso'    => $CelaRol['idRecord']
									);

									$CelaPrivilegios = CelaPrivilegiosCrear($Data);

									if($CelaPrivilegios['Status'] == 'ERROR'){
										/*Se guarda el error para mostrarlo*/
										$Status = false;
										$Result = array();

										$Result['Index']    = $_POST['Privilegios'][$i];
										$Result['Error']    = $CelaPrivilegios['Error'];
										$BadKeys[]          = $Result;
									}else{
										/*Se registra la acción "Crear" en la bitacora*/
										RecordLog('CelaPrivilegios', $CelaPrivilegios['idRecord'], 2, $SessionUserId, $Data);
									}

								}
							}

							/*Se heredan los privilegios de otro rol*/
							if(isset($_POST['ClonarPrivilegios']) && $_POST['ClonarPrivilegios'] == 1){
								/*Se registran los privilegios de menú*/
								$CelaPrivilegios = CelaPrivilegiosClonePrivileges($_POST['Rol'], $CelaRol['idRecord'], '1, 2, 5');
								if($CelaPrivilegios['Status'] == 'ERROR'){
									/*Se guarda el error para mostrarlo*/
									$Status = false;
									$Result = array();

									$Result['Index']    = $_POST['Rol'] . ' Insert Menu';
									$Result['Error']    = $CelaPrivilegios['Error'];
									$BadKeys[]          = $Result;
								}else{
									/*Se registran los privilegios de formularios*/
									$CelaPrivilegios = CelaPrivilegiosClonePrivileges($_POST['Rol'], $CelaRol['idRecord'], 2);
									if($CelaPrivilegios['Status'] == 'ERROR'){
										/*Se guarda el error para mostrarlo*/
										$Status = false;
										$Result = array();

										$Result['Index']    = $_POST['Rol'] . ' Insert Privilege';
										$Result['Error']    = $CelaPrivilegios['Error'];
										$BadKeys[]          = $Result;
									}else{

									}
								}
							}

							if($Status){
								/*Se carga la vista de lectura con mensaje creación correcta*/
								$ArgsCelaActionMessage = array(
									'StatusMessage' => 'success',
									'IconMessage'   => 'fa-check',
									'TitleMessage'  => 'Registro exitoso!',
									'TextMessage'   => 'El nuevo elemento se registr&oacute; correctamente.'
								);

								$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

								if(isset($_POST['InsertBack']) && $_POST['InsertBack'] == 1){
									/*Se carga la vista de Creación*/
									$ArgsCelaRolVistaCrear = array(
										'SessionGroupId'    => $SessionGroupId,
										'Random'            => $SessionRandom,
										'FormAction'        => $RouteForm
									);

									$Content    .= LoadContentPage('../CelaRol/CelaRolVistaCrear.php', $ArgsCelaRolVistaCrear);
									$MyScripts  .= LoadContentPage('../CelaRol/CelaRolJavascriptCrear.php');
									$MyStyles   .= '<style>
														.SelectPrivilege{
															width: 100% !important;
														}
													</style>';
								}else{
									if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
										$ArgsCelaHeadContent['FormTitle']           = 'Listado de Grupos/Roles';

										$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');

										$ArgsCelaRolVistaLeer['Params']['Group']    = $Group;
										$ArgsCelaRolVistaLeer['SessionRandom']      = $SessionRandom;

										$Content  .= LoadContentPage('../CelaRol/CelaRolVistaLeer.php', $ArgsCelaRolVistaLeer);

										$ArgsCelaRolJavascript = array(
											'Table' => 'CelaRol',
										);

										$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaRolJavascript);
									}
								}
							}else{
								/*Se carga la vista con el mensaje de error de Creación*/
								$ArgsCelaActionMessage = array(
									'StatusMessage' => 'danger',
									'IconMessage'   => 'fa-times',
									'TitleMessage'  => 'Oops!... Ocurrio un error registrando el elemento',
									'TextMessage'   => 'Algunos elementos pudieron no haberse registrado'
								);

								$ArgsCelaActionMessage['TextMessage'] .= '<div class="list-group">';
								for($i = 0; $i < count($BadKeys); $i++){
									$ArgsCelaActionMessage['TextMessage'] .= '<a href="#" class="list-group-item">( "Elemento" => "' . $BadKeys[$i]['Index'] . '", "Error" => "' . $BadKeys[$i]['Error'] . '" )</a>';
								}
								$ArgsCelaActionMessage['TextMessage'] .= '</div>';
								$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaRol.php"
	class="btn btn-danger">Aceptar</a>';

								$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
							}
						}else{
							$Status = false;
							/*Se carga la vista de lectura con mensaje de error de creación*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error registrando el elemento',
								'TextMessage'   => $CelaRol['Error'].'<br />puede <a href="CelaRol.php?' . EncodeThis('Action=Crear') . '" class="alert-link">volver a intentar</a> &oacute; <a href="Escritorio.php" class="alert-link">ir al escritorio</a>'
							);

							$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se carga la vista de Creación*/
						$ArgsCelaRolVistaCrear = array(
							'SessionGroupId'    => $SessionGroupId,
							'Random'            => $SessionRandom,
							'FormAction'        => $RouteForm
						);

						$Content    .= LoadContentPage('../CelaRol/CelaRolVistaCrear.php', $ArgsCelaRolVistaCrear);
						$MyScripts  .= LoadContentPage('../CelaRol/CelaRolJavascriptCrear.php');
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
						$CelaRol = CelaRolEliminar($Key);

						if($CelaRol['Status'] == 'ERROR'){
							/*Se guarda el error para mostrarlo*/
							$Status = false;
							$Result = array();

							$Result['Index']    = $Key;
							$Result['Error']    = $CelaRol['Error'];
							$BadKeys[]          = $Result;
						}else{
							/*Se registra la acción "Eliminar" en la bitacora*/
							RecordLog('CelaRol', $Key, 3, $SessionUserId);
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
						$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

						if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
							$ArgsCelaHeadContent['FormTitle']           = 'Listado de Grupos/Roles';

							$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');

							$ArgsCelaRolVistaLeer['Params']['Group']    = $Group;
							$ArgsCelaRolVistaLeer['SessionRandom']      = $SessionRandom;

							$Content .= LoadContentPage('../CelaRol/CelaRolVistaLeer.php', $ArgsCelaRolVistaLeer);

							$ArgsCelaRolJavascript = array(
								'Table' => 'CelaRol',
							);
							$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaRolJavascript);
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
						$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaRol.php"
	class="btn btn-danger">Aceptar</a>';

						$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
					}
				}else{
					$Connection -> close();
					header(sprintf('Location: %s', 'CelaRol.php'));
				}
				break;
			case 'Actualizar':
				/*Se verifica si se tiene el privilegio de actualizar*/
				if(isset($Privileges['Actualizar']) && $Privileges['Actualizar'] == 1) {
					$ArgsCelaHeadContent['FormTitle'] = 'Actualizaci&oacute;n de Grupo/Rol';

					/*Se verifica que haya evento "submit"*/
					if(isset($_POST['CelaRolUpdate']) && $_POST['CelaRolUpdate'] == 'CelaRolUpdate'){
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
									'Nombre'        => $_POST['Nombre' . $Key],
									'Siglas'        => $_POST['Siglas' . $Key],
									'Descripci_on'  => $_POST['Descripci_on' . $Key],
									'Status'        => $_POST['Status' . $Key],
									'Grupo'         => ($_POST['Grupo' . $Key] == '' ? $SessionGroupId:$_POST['Grupo' . $Key]),
									'Tema'          => $_POST['Tema' . $Key]
								);
								$CelaRol = CelaRolActualizar($Key, $Data);

								if($CelaRol['Status'] == 'ERROR'){
									/*Se guarda el error para mostrarlo*/
									$Status = false;
									$Result = array();

									$Result['Index']    = $Key;
									$Result['Error']    = $CelaRol['Error'];
									$BadKeys[]          = $Result;
								}else{
									/*Se registra la acción "Actualizar" en la bitacora*/
									RecordLog('CelaRol', $Key, 5, $SessionUserId, $Data);

									/*Se Actualizan los privilegios que adminsitra este rol*/
									if(isset($_POST['Privilegios' . $Key]) && count($_POST['Privilegios' . $Key]) > 0){
										/*Se Eliminan los privilegios anteriores*/
										$CelaPrivilegios = CelaPrivilegiosEliminar(false, 4, $Key);

										/*Se registran los privilegios del formulario*/
										for($i = 0; $i < count($_POST['Privilegios' . $Key]); $i++){
											$Data = array();
											$Data['Privilegio']     = 9;
											$Data['Origen']         = 4;
											$Data['Tupla']          = $_POST['Privilegios' . $Key][$i];
											$Data['TuplaAcceso']    = $Key;
											$CelaPrivilegios = CelaPrivilegiosCrear($Data);

											if($CelaPrivilegios['Status'] == 'ERROR'){
												/*Se guarda el error para mostrarlo*/
												$Status = false;
												$Result = array();

												$Result['Index']    = $Key;
												$Result['Error']    = $CelaPrivilegios['Error'];
												$BadKeys[]          = $Result;
											}else{
												/*Se registra la acción "Crear" en la bitacora*/
												RecordLog('CelaPrivilegios', $CelaPrivilegios['idRecord'], 2, $SessionUserId, $Data);
											}

										}
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
								$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

								if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
									$ArgsCelaHeadContent['FormTitle']           = 'Listado de Grupos/Roles';

									$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');

									$ArgsCelaRolVistaLeer['Params']['Group']    = $Group;
									$ArgsCelaRolVistaLeer['SessionRandom']      = $SessionRandom;

									$Content .= LoadContentPage('../CelaRol/CelaRolVistaLeer.php', $ArgsCelaRolVistaLeer);

									$ArgsCelaRolJavascript = array(
										'Table' => 'CelaRol',
									);
									$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaRolJavascript);
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
								$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaRol.php"
	class="btn btn-danger">Aceptar</a>';

								$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
							}
						}else{
							/*Se carga la vista con error obtención de datos para eliminar*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error obteniendo el listado de elementos',
								'TextMessage'   => 'Algunos elementos para actualizar estan corruptos o no existen. <a href="CelaRol.php"
	class="btn btn-danger">Aceptar</a>'
							);
							$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se verifica que haya datos para eliminar*/
						if(isset($_GET['Key']) && $_GET['Key'] != '') {
							/*Se carga la vista de actualización*/
							$ArgsCelaRolVistaActualizar = array(
								'SessionGroupId'    => $SessionGroupId,
								'Random'            => $SessionRandom,
								'FormAction'        => $RouteForm
							);
							$Content    .= LoadContentPage('../CelaRol/CelaRolVistaActualizar.php', $ArgsCelaRolVistaActualizar);
							$MyScripts  .= LoadContentPage('../CelaRol/CelaRolJavascriptActualizar.php');
							$MyStyles   .= '<style>
												.SelectPrivilege{
													width: 100% !important;
												}
											</style>';
						}else{
							/*Se carga la busqueda de lectura*/
							$Connection -> close();
							header(sprintf('Location: %s', 'CelaRol.php'));
						}
					}
				}
				break;
		}
	}else{
		/*Se carga la vista de lectura*/
		if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {

			$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');

			$ArgsCelaRolVistaLeer['Params']['Group']    = $Group;
			$ArgsCelaRolVistaLeer['SessionRandom']      = $SessionRandom;

			$Content .= LoadContentPage('../CelaRol/CelaRolVistaLeer.php', $ArgsCelaRolVistaLeer);

			$ArgsCelaRolJavascript = array(
				'Table' => 'CelaRol',
			);
			$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaRolJavascript);
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