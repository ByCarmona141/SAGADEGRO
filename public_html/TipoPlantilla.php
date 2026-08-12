<?php
	require_once('../Libraries/Functions.php');
	require_once('../Libraries/Session.class.php');
	require_once('../TipoPlantilla/TipoPlantilla.php');
	require_once('../Libraries/Connection.php');
	require_once('../Libraries/GetSession.php');
	require_once('../Libraries/Security.php');

	$ArgsTipoPlantillaVistaLeer = array(
		'Table'             => 'TipoPlantilla',
		'ServerSource'      => '../TipoPlantilla/TipoPlantilla.php',
		'ServerFunction'    => 'TipoPlantillaLeer',
		'RouteForm'         => $RouteForm
	);

	$ArgsCelaHeadContent['FormTitle'] = 'Listado de Tipo Plantilla';

	$MyScripts  = '';
	$Content    = '';
	$MyStyles   = '';

	if(isset($_GET['Action'])){
		switch($_GET['Action']){
			case 'Crear':
				/*Se verifica si se tiene el privilegio de crear*/
				if(isset($Privileges['Crear']) && $Privileges['Crear'] == 1) {
					$ArgsCelaHeadContent['FormTitle'] = 'Creaci&oacute;n de Tipo Plantilla';

					/*Se verifica que haya evento "submit"*/
					if((isset($_POST['TipoPlantillaInsert'])) && ($_POST['TipoPlantillaInsert'] == 'TipoPlantillaInsert')){
						/*Se invoca la funcion crear*/
						$Data = array(
							'Nombre' => $_POST['Nombre']
						);
						$TipoPlantilla = TipoPlantillaCrear($Data);

						if($TipoPlantilla['Status'] == 'OK'){
							

							/*Se registra la acción "Crear" en la bitacora*/
							RecordLog('TipoPlantilla', $TipoPlantilla['idRecord'], 2, $SessionUserId, $Data);
							$Status = true;

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
								$ArgsTipoPlantillaVistaCrear = array(
									'SessionGroupId'    => $SessionGroupId,
									'Random'            => $SessionRandom,
									'FormAction'        => $RouteForm
								);

								$Content .= LoadContentPage('../TipoPlantilla/TipoPlantillaVistaCrear.php', $ArgsTipoPlantillaVistaCrear);
							}else{
								/*Se carga la vista de Leer*/
								if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
									$ArgsCelaHeadContent['FormTitle'] = 'Listado de Tipo Plantilla';
									$Content  .= LoadContentPage('../TipoPlantilla/TipoPlantillaVistaLeer.php', $ArgsTipoPlantillaVistaLeer);

									$ArgsTipoPlantillaJavascript = array(
										'Table' => 'TipoPlantilla',
									);

									$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsTipoPlantillaJavascript);
								}
							}
						}else{
							$Status = false;
							/*Se carga la vista de lectura con mensaje de error de creación*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error registrando el elemento',
								'TextMessage'   => $TipoPlantilla['Error'].'<br />puede <a href="TipoPlantilla?' . EncodeThis('Action=Crear') . '" class="alert-link">volver a intentar</a> &oacute; <a href="Escritorio.php" class="alert-link">ir al escritorio</a>'
							);

							$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se carga la vista de Creación*/
						$ArgsTipoPlantillaVistaCrear = array(
							'SessionGroupId'    => $SessionGroupId,
							'Random'            => $SessionRandom,
							'FormAction'        => $RouteForm
						);

						$Content .= LoadContentPage('../TipoPlantilla/TipoPlantillaVistaCrear.php', $ArgsTipoPlantillaVistaCrear);
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
							sprintf('SELECT * FROM TipoPlantilla WHERE `id` = %s;',
								GetSQLValueString($Key, 'tinyint unsigned')
							)
						);
						$TipoPlantilla = TipoPlantillaEliminar($Key);

						if($TipoPlantilla['Status'] == 'ERROR'){
							/*Se guarda el error para mostrarlo*/
							$Status = false;
							$Result = array(
								'Index' => $Key,
								'Error' => $TipoPlantilla['Error']
							);

							$BadKeys[] = $Result;
						}else{
							/*Se registra la acción "Eliminar" en la bitacora*/
							RecordLog('TipoPlantilla', $Key, 3, $SessionUserId, $Data);
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
							$ArgsCelaHeadContent['FormTitle'] = 'Listado de Tipo Plantilla';
							$Content .= LoadContentPage('../TipoPlantilla/TipoPlantillaVistaLeer.php', $ArgsTipoPlantillaVistaLeer);

							$ArgsTipoPlantillaJavascript = array(
								'Table' => 'TipoPlantilla',
							);
							$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsTipoPlantillaJavascript);
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
						$ArgsCelaActionMessage['TextMessage'] .= '<a href="TipoPlantilla"
	class="btn btn-danger">Aceptar</a>';

						$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
					}
				}else{
					$Connection -> close();
					header(sprintf('Location: %s', 'TipoPlantilla'));
				}
				break;
			case 'Actualizar':
				/*Se verifica si se tiene el privilegio de actualizar*/
				if(isset($Privileges['Actualizar']) && $Privileges['Actualizar'] == 1) {
					$ArgsCelaHeadContent['FormTitle'] = 'Actualizaci&oacute;n de Tipo Plantilla';

					/*Se verifica que haya evento "submit"*/
					if(isset($_POST['TipoPlantillaUpdate']) && $_POST['TipoPlantillaUpdate'] == 'TipoPlantillaUpdate'){
						/*Se decodicia el arreglo de las claves para actualizar*/
						$EncryptedKey = (isset($_POST[Encrypt('id', $SessionRandom)]) ? $_POST[Encrypt('id', $SessionRandom)]:'');
						if($EncryptedKey != ''){
							$Keys = Decrypt($EncryptedKey, $SessionRandom);
							$Keys = explode(',', $Keys);
							$Status = true;
						}else{
							$Status = false;
						}

						$BadKeys = array();
						if($Status){
							$Status = true;
							/*Se recorre cada uno de los elementos que se van a actualizar*/
							foreach($Keys as $Key){
								

								/*Se invoca la funcion actulizar*/
								$Data = array(
									'Nombre' => $_POST['Nombre' . $Key]
								);
								$TipoPlantilla = TipoPlantillaActualizar($Key, $Data);

								if($TipoPlantilla['Status'] == 'ERROR'){
									/*Se guarda el error para mostrarlo*/
									$Status = false;
									$Result = array(
										'Index' => $Key,
										'Error' => $TipoPlantilla['Error']
									);

									$BadKeys[] = $Result;
								}else{
									/*Se registra la acción "Actualizar" en la bitacora*/
									RecordLog('TipoPlantilla', $Key, 5, $SessionUserId, $Data);
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
									$ArgsCelaHeadContent['FormTitle'] = 'Listado de Tipo Plantilla';
									$Content .= LoadContentPage('../TipoPlantilla/TipoPlantillaVistaLeer.php', $ArgsTipoPlantillaVistaLeer);

									$ArgsTipoPlantillaJavascript = array(
										'Table' => 'TipoPlantilla',
									);
									$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsTipoPlantillaJavascript);
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
								$ArgsCelaActionMessage['TextMessage'] .= '<a href="TipoPlantilla"
	class="btn btn-danger">Aceptar</a>';

								$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
							}
						}else{
							/*Se carga la vista con error obtención de datos para eliminar*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error obteniendo el listado de elementos',
								'TextMessage'   => 'Algunos elementos para actualizar estan corruptos o no existen. <a href="TipoPlantilla"
	class="btn btn-danger">Aceptar</a>'
							);
							$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se verifica que haya datos para actualizar*/
						if(isset($_GET['Key']) && $_GET['Key'] != '') {
							/*Se carga la vista de actualización*/
							$ArgsTipoPlantillaVistaActualizar = array(
								'SessionGroupId'    => $SessionGroupId,
								'Random'            => $SessionRandom,
								'FormAction'        => $RouteForm
							);
							$Content .= LoadContentPage('../TipoPlantilla/TipoPlantillaVistaActualizar.php', $ArgsTipoPlantillaVistaActualizar);
						}else{
							/*Se carga la vista de lectura*/
							$Connection -> close();
							header(sprintf('Location: %s', 'TipoPlantilla'));
						}
					}
				}
				break;
		}
	}else{
		/*Se carga la vista de lectura*/
		if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
			$Content  .= LoadContentPage('../TipoPlantilla/TipoPlantillaVistaLeer.php', $ArgsTipoPlantillaVistaLeer);

			$ArgsTipoPlantillaJavascript = array(
				'Table' => 'TipoPlantilla',
			);
			$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsTipoPlantillaJavascript);
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