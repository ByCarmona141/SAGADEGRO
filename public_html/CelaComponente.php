<?php
	/**

	 * Fecha: 12/11/2015
	 * Descripción: Controlador de la tabla "CelaComponente".
	 **/

	require_once('../Libraries/Functions.php');
	require_once('../Libraries/Session.class.php');
	require_once('../CelaComponente/CelaComponente.php');
	require_once('../CelaTipoComponente/CelaTipoComponente.php');
	require_once('../CelaUsuario/CelaUsuario.php');
	require_once('../Libraries/Connection.php');
	require_once('../Libraries/GetSession.php');
	require_once('../Libraries/Security.php');

	$ArgsCelaComponenteVistaLeer = array(
		'Table'             => 'CelaComponente',
		'ServerSource'      => '../CelaComponente/CelaComponente.php',
		'ServerFunction'    => 'CelaComponenteLeer',
		'RouteForm'         => $RouteForm
	);

	$ArgsCelaHeadContent['FormTitle'] = 'Listado de Componentes';

	$MyScripts  = '';
	$Content    = '';
	$MyStyles   = '';

	$BadKeys    = array();

	//print_r($_POST);

	if(isset($_GET['Action'])){
		switch($_GET['Action']){
			case 'Crear':
				/*Se verifica si se tiene el privilegio de crear*/
				if(isset($Privileges['Crear']) && $Privileges['Crear'] == 1) {
					$ArgsCelaHeadContent['FormTitle'] = 'Creaci&oacute;n de Componente';

					/*Se verifica que haya evento "submit"*/
					if((isset($_POST['CelaComponenteInsert'])) && ($_POST['CelaComponenteInsert'] == 'CelaComponenteInsert')){
						/*Se invoca la funcion crear*/
						$Data = array(
							'Componente' => $_POST['Componente'],
							'Acci_on' => '',
							'FechaSolicitud' => $_POST['FechaSolicitud'],
							'Descripci_on' => $_POST['Descripci_on'],
							'Solicitante' => $_POST['Solicitante'],
							'FechaRealizado' => '',
							'Reviso' => '',
							'Autorizo' => '',
							'Conclusi_on' => '',
							'TipoDeComponente' => $_POST['TipoDeComponente']
						);
						$CelaComponente = CelaComponenteCrear($Data);

						if($CelaComponente['Status'] == 'OK'){
							/*Se registra la acción "Crear" en la bitacora*/
							RecordLog('CelaComponente', $CelaComponente['idRecord'], 2, $SessionUserId, $_POST);
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
								$ArgsCelaComponenteVistaCrear = array(
									'SessionGroupId'    => $SessionGroupId,
									'Random'            => $SessionRandom,
									'FormAction'        => $RouteForm
								);

								$Content = LoadContentPage('../CelaComponente/CelaComponenteVistaCrear.php', $ArgsCelaComponenteVistaCrear);
							}else{
								if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
									if(isset($_GET['Component']) && $_GET['Component'] != ''){
										$ArgsCelaComponenteJavascript['Params']['Component']    = $_GET['Component'];
										$ArgsCelaComponenteVistaLeer['Params']['Component']     = $_GET['Component'];
									}

									$ArgsCelaComponenteVistaLeer['SessionRandom']  = $SessionRandom;

									$Content  .= LoadContentPage('../CelaComponente/CelaComponenteVistaLeer.php', $ArgsCelaComponenteVistaLeer);

									$ArgsCelaComponenteJavascript = array(
										'Table' => 'CelaComponente',
									);

									$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaComponenteJavascript);
									$MyScripts .= LoadContentPage('../CelaComponente/CelaComponenteJavascriptLeer.php');
								}
							}
						}else{
							$Status = false;
							/*Se carga la vista de lectura con mensaje de error de creación*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error registrando el elemento',
								'TextMessage'   => $CelaComponente['Error'].'<br />puede <a href="CelaComponente.php?' . EncodeThis('Action=Crear') . '" class="alert-link">volver a intentar</a> &oacute; <a href="Escritorio.php" class="alert-link">ir al escritorio</a>'
							);

							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se carga la vista de Creación*/
						$ArgsCelaComponenteVistaCrear = array(
							'SessionGroupId'    => $SessionGroupId,
							'Random'            => $SessionRandom,
							'FormAction'        => $RouteForm
						);

						$Content = LoadContentPage('../CelaComponente/CelaComponenteVistaCrear.php', $ArgsCelaComponenteVistaCrear);
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
							sprintf('SELECT * FROM CelaComponente WHERE `id` = %s;',
								GetSQLValueString($Key, 'int')
							)
						);
						$CelaComponente = CelaComponenteEliminar($Key);

						if($CelaComponente['Status'] == 'ERROR'){
							/*Se guarda el error para mostrarlo*/
							$Status = false;
							$Result = array();

							$Result['Index']    = $Key;
							$Result['Error']    = $CelaComponente['Error'];
							$BadKeys[]          = $Result;
						}else{
							/*Se registra la acción "Eliminar" en la bitacora*/
							RecordLog('CelaComponente', $Key, 3, $SessionUserId, $Data);
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
							if(isset($_GET['Component']) && $_GET['Component'] != ''){
								$ArgsCelaComponenteJavascript['Params']['Component']    = $_GET['Component'];
								$ArgsCelaComponenteVistaLeer['Params']['Component']     = $_GET['Component'];
							}

							$ArgsCelaComponenteVistaLeer['SessionRandom']  = $SessionRandom;
							$Content .= LoadContentPage('../CelaComponente/CelaComponenteVistaLeer.php', $ArgsCelaComponenteVistaLeer);

							$ArgsCelaComponenteJavascript = array(
								'Table' => 'CelaComponente',
							);
							$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaComponenteJavascript);
							$MyScripts .= LoadContentPage('../CelaComponente/CelaComponenteJavascriptLeer.php');
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
						$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaComponente.php"
	class="btn btn-danger">Aceptar</a>';

						$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
					}
				}else{
					$Connection -> close();
					header(sprintf('Location: %s', 'CelaComponente.php'));
				}
				break;
			case 'Actualizar':
				/*Se verifica si se tiene el privilegio de actualizar*/
				if(isset($Privileges['Actualizar']) && $Privileges['Actualizar'] == 1) {
					$ArgsCelaHeadContent['FormTitle'] = 'Actualizaci&oacute;n de Componente';

					/*Se verifica que haya evento "submit"*/
					if(isset($_POST['CelaComponenteUpdate']) && $_POST['CelaComponenteUpdate'] == 'CelaComponenteUpdate'){
						/*Se decodicia el arreglo de las claves para actualizar*/
						$EncryptedKey = (isset($_POST[Encrypt('id', $SessionRandom)]) ? $_POST[Encrypt('id', $SessionRandom)]:'');

						if($EncryptedKey != ''){
							$Keys = Decrypt($EncryptedKey, $SessionRandom);
							$Keys = explode(',', $Keys);
							$Status = true;
						}else{
							$Status = false;
						}

						$_GET['Component'] = $_POST['Component'];
						if($Status){
							$Status = true;

							/*Se recorre cada uno de los elementos que se van a actualizar*/
							foreach($Keys as $Key){
								/*Se invoca la funcion actualizar*/
								$Data = array(
									'FechaSolicitud'    => $_POST['FechaSolicitud' . $Key],
									'Descripci_on'      => $_POST['Descripci_on' . $Key],
									'Solicitante'       => $_POST['Solicitante' . $Key],
									'TipoDeComponente'  => $_POST['TipoDeComponente' . $Key]
								);
								$CelaComponente = CelaComponenteActualizar($Key, $Data);

								if($CelaComponente['Status'] == 'ERROR'){
									/*Se guarda el error para mostrarlo*/
									$Status = false;
									$Result = array();

									$Result['Index']    = $Key;
									$Result['Error']    = $CelaComponente['Error'];
									$BadKeys[]          = $Result;
								}else{
									/*Se registra la acción "Actualizar" en la bitacora*/
									RecordLog('CelaComponente', $Key, 5, $SessionUserId, $Data);
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
									if(isset($_GET['Component']) && $_GET['Component'] != ''){
										$ArgsCelaComponenteJavascript['Params']['Component']    = $_GET['Component'];
										$ArgsCelaComponenteVistaLeer['Params']['Component']     = $_GET['Component'];
									}

									$ArgsCelaComponenteVistaLeer['SessionRandom']  = $SessionRandom;
									$Content .= LoadContentPage('../CelaComponente/CelaComponenteVistaLeer.php', $ArgsCelaComponenteVistaLeer);

									$ArgsCelaComponenteJavascript = array(
										'Table' => 'CelaComponente',
									);

									$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaComponenteJavascript);
									$MyScripts .= LoadContentPage('../CelaComponente/CelaComponenteJavascriptLeer.php');
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
								$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaComponente.php"
	class="btn btn-danger">Aceptar</a>';

								$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
							}
						}else{
							/*Se carga la vista con error obtención de datos para eliminar*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error obteniendo el listado de elementos',
								'TextMessage'   => 'Algunos elementos para actualizar estan corruptos o no existen. <a href="CelaComponente.php"
	class="btn btn-danger">Aceptar</a>'
							);
							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se verifica que haya datos para eliminar*/
						if(isset($_GET['Key']) && $_GET['Key'] != '') {
							/*Se carga la vista de actualización*/
							$ArgsCelaComponenteVistaActualizar = array(
								'SessionGroupId'    => $SessionGroupId,
								'Random'            => $SessionRandom,
								'FormAction'        => $RouteForm
							);
							$Content = LoadContentPage('../CelaComponente/CelaComponenteVistaActualizar.php', $ArgsCelaComponenteVistaActualizar);
						}else{
							/*Se carga la busqueda de lectura*/
							$Connection -> close();
							header(sprintf('Location: %s', 'CelaComponente.php'));
						}
					}
				}
				break;
			case 'Finalizar':
				$ArgsCelaHeadContent['FormTitle'] = 'Finalizar Componente';

				/*Se verifica que haya evento "submit"*/
				if(isset($_POST['CelaComponenteUpdate']) && $_POST['CelaComponenteUpdate'] == 'CelaComponenteUpdate'){
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
						$_GET['Component'] = $_POST['Component'];
						/*Se recorre cada uno de los elementos que se van a actualizar*/
						foreach($Keys as $Key){
							/*Se invoca la funcion actualizar*/
							$Data = array(
								'FechaRealizado'    => $_POST['FechaRealizado' . $Key],
								'Reviso'            => $_POST['Reviso' . $Key],
								'Autorizo'          => $_POST['Autorizo' . $Key],
								'Conclusi_on'       => $_POST['Conclusi_on' . $Key]
							);
							$CelaComponente = CelaComponenteFinalizar($Key, $Data);

							if($CelaComponente['Status'] == 'ERROR'){
								/*Se guarda el error para mostrarlo*/
								$Status = false;
								$Result = array();

								$Result['Index']    = $Key;
								$Result['Error']    = $CelaComponente['Error'];
								$BadKeys[]          = $Result;
							}else{
								/*Se registra la acción "Actualizar" en la bitacora*/
								RecordLog('CelaComponente', $Key, 5, $SessionUserId, $Data);
							}
						}

						if($Status){
							/*Se carga la vista de lectura con mensaje de actualización correcta*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'success',
								'IconMessage'   => 'fa-check',
								'TitleMessage'  => 'Operaci&oacuten exitosa!',
								'TextMessage'   => 'El componente se ha finalizado correctamente.'
							);
							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

							if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
								if(isset($_GET['Component']) && $_GET['Component'] != ''){
									$ArgsCelaComponenteJavascript['Params']['Component']    = $_GET['Component'];
									$ArgsCelaComponenteVistaLeer['Params']['Component']     = $_GET['Component'];
								}

								$ArgsCelaComponenteVistaLeer['SessionRandom']  = $SessionRandom;
								$Content .= LoadContentPage('../CelaComponente/CelaComponenteVistaLeer.php', $ArgsCelaComponenteVistaLeer);

								$ArgsCelaComponenteJavascript = array(
									'Table' => 'CelaComponente',
								);

								$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaComponenteJavascript);
								$MyScripts .= LoadContentPage('../CelaComponente/CelaComponenteJavascriptLeer.php');
							}
						}else{
							/*Se carga la vista con el mensaje de error de actualización*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error finalizando el componente',
								'TextMessage'   => 'Algunos elementos pudieron no haberse actualizado'
							);

							$ArgsCelaActionMessage['TextMessage'] .= '<div class="list-group">';
							for($i = 0; $i < count($BadKeys); $i++){
								$ArgsCelaActionMessage['TextMessage'] .= '<a href="#" class="list-group-item">( "Elemento" => "' . $BadKeys[$i]['Index'] . '", "Error" => "' . $BadKeys[$i]['Error'] . '" )</a>';
							}
							$ArgsCelaActionMessage['TextMessage'] .= '</div>';
							$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaComponente.php"
	class="btn btn-danger">Aceptar</a>';

							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se carga la vista con error obtención de datos para eliminar*/
						$ArgsCelaActionMessage = array(
							'StatusMessage' => 'danger',
							'IconMessage'   => 'fa-times',
							'TitleMessage'  => 'Oops!... Ocurrio un error obteniendo el listado de elementos',
							'TextMessage'   => 'Algunos elementos para actualizar estan corruptos o no existen. <a href="CelaComponente.php"
	class="btn btn-danger">Aceptar</a>'
						);
						$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
					}
				}else{
					/*Se verifica que haya datos para eliminar*/
					if(isset($_GET['Key']) && $_GET['Key'] != '') {
						/*Se carga la vista de actualización*/
						$ArgsCelaComponenteVistaFinalizar = array(
							'SessionGroupId'    => $SessionGroupId,
							'Random'            => $SessionRandom,
							'FormAction'        => $RouteForm
						);
						$Content = LoadContentPage('../CelaComponente/CelaComponenteVistaFinalizar.php', $ArgsCelaComponenteVistaFinalizar);
					}else{
						/*Se carga la busqueda de lectura*/
						$Connection -> close();
						header(sprintf('Location: %s', 'CelaComponente.php'));
					}
				}
			break;
		}
	}else{
		/*Se carga la vista de lectura*/
		if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
			if(isset($_GET['Component']) && $_GET['Component'] != ''){
				$ArgsCelaComponenteJavascript['Params']['Component']    = $_GET['Component'];
				$ArgsCelaComponenteVistaLeer['Params']['Component']     = $_GET['Component'];
			}

			$ArgsCelaComponenteVistaLeer['SessionRandom']  = $SessionRandom;

			$Content  .= LoadContentPage('../CelaComponente/CelaComponenteVistaLeer.php', $ArgsCelaComponenteVistaLeer);

			$ArgsCelaComponenteJavascript = array(
				'Table' => 'CelaComponente',
			);
			$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaComponenteJavascript);
			$MyScripts .= LoadContentPage('../CelaComponente/CelaComponenteJavascriptLeer.php');
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