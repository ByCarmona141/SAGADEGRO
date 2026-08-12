<?php
	/**

	 * Fecha: 12/11/2015
	 * Descripción: Controlador de la tabla "CelaOrigen".
	 **/

	require_once('../Libraries/Functions.php');
	require_once('../Libraries/Session.class.php');
	require_once('../CelaOrigen/CelaOrigen.php');
	require_once('../Libraries/Connection.php');
	require_once('../Libraries/GetSession.php');
	require_once('../Libraries/Security.php');

	$ArgsCelaOrigenVistaLeer = array(
		'Table'             => 'CelaOrigen',
		'ServerSource'      => '../CelaOrigen/CelaOrigen.php',
		'ServerFunction'    => 'CelaOrigenLeer',
		'RouteForm'         => $RouteForm
	);

	$ArgsCelaHeadContent['FormTitle'] = 'Listado de Origenes';

	$MyScripts  = '';
	$Content    = '';
	$MyStyles   = '';

	$BadKeys    = array();

	if(isset($_GET['Action'])){
		switch($_GET['Action']){
			case 'Crear':
				/*Se verifica si se tiene el privilegio de crear*/
				if(isset($Privileges['Crear']) && $Privileges['Crear'] == 1) {
					$ArgsCelaHeadContent['FormTitle'] = 'Creaci&oacute;n de Origen';

					/*Se verifica que haya evento "submit"*/
					if((isset($_POST['CelaOrigenInsert'])) && ($_POST['CelaOrigenInsert'] == 'CelaOrigenInsert')){
						/*Se invoca la funcion crear*/
						$CelaOrigen = CelaOrigenCrear($_POST);

						if($CelaOrigen['Status'] == 'OK'){
							/*Se registra la acción "Crear" en la bitacora*/
							RecordLog('CelaOrigen', $CelaOrigen['idRecord'], 2, $SessionUserId, $_POST);
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
								$ArgsCelaOrigenVistaCrear = array(
									'SessionGroupId'    => $SessionGroupId,
									'Random'            => $SessionRandom,
									'FormAction'        => $RouteForm
								);

								$Content = LoadContentPage('../CelaOrigen/CelaOrigenVistaCrear.php', $ArgsCelaOrigenVistaCrear);
							}else{
								if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
									$ArgsCelaHeadContent['FormTitle'] = 'Listado de Origenes';

									$Content  .= LoadContentPage('../CelaOrigen/CelaOrigenVistaLeer.php', $ArgsCelaOrigenVistaLeer);

									$ArgsCelaOrigenJavascript = array(
										'Table' => 'CelaOrigen',
									);

									$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaOrigenJavascript);
								}
							}
						}else{
							$Status = false;
							/*Se carga la vista de lectura con mensaje de error de creación*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error registrando el elemento',
								'TextMessage'   => $CelaOrigen['Error'].'<br />puede <a href="CelaOrigen.php?' . EncodeThis('Action=Crear') . '" class="alert-link">volver a intentar</a> &oacute; <a href="Escritorio.php" class="alert-link">ir al escritorio</a>'
							);

							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se carga la vista de Creación*/
						$ArgsCelaOrigenVistaCrear = array(
							'SessionGroupId'    => $SessionGroupId,
							'Random'            => $SessionRandom,
							'FormAction'        => $RouteForm
						);

						$Content = LoadContentPage('../CelaOrigen/CelaOrigenVistaCrear.php', $ArgsCelaOrigenVistaCrear);
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
									sprintf('SELECT * FROM CelaOrigen WHERE `id` = %s;',
										GetSQLValueString($Key, 'int')
									)
								);
						$CelaOrigen = CelaOrigenEliminar($Key);

						if($CelaOrigen['Status'] == 'ERROR'){
							/*Se guarda el error para mostrarlo*/
							$Status = false;
							$Result = array();

							$Result['Index']    = $Key;
							$Result['Error']    = $CelaOrigen['Error'];
							$BadKeys[]          = $Result;
						}else{
							/*Se registra la acción "Eliminar" en la bitacora*/
							RecordLog('CelaOrigen', $Key, 3, $SessionUserId, $Data);
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
							$ArgsCelaHeadContent['FormTitle'] = 'Listado de Origenes';

							$Content .= LoadContentPage('../CelaOrigen/CelaOrigenVistaLeer.php', $ArgsCelaOrigenVistaLeer);

							$ArgsCelaOrigenJavascript = array(
								'Table' => 'CelaOrigen',
							);
							$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaOrigenJavascript);
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
						$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaOrigen.php"
	class="btn btn-danger">Aceptar</a>';

						$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
					}
				}else{
					$Connection -> close();
					header(sprintf('Location: %s', 'CelaOrigen.php'));
				}
				break;
			case 'Actualizar':
				/*Se verifica si se tiene el privilegio de actualizar*/
				if(isset($Privileges['Actualizar']) && $Privileges['Actualizar'] == 1) {
					$ArgsCelaHeadContent['FormTitle'] = 'Actualizaci&oacute;n de Origen';

					/*Se verifica que haya evento "submit"*/
					if(isset($_POST['CelaOrigenUpdate']) && $_POST['CelaOrigenUpdate'] == 'CelaOrigenUpdate'){
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
									'Nombre' => $_POST['Nombre' . $Key],
									'Tabla' => $_POST['Tabla' . $Key]
								);
								$CelaOrigen = CelaOrigenActualizar($Key, $Data);

								if($CelaOrigen['Status'] == 'ERROR'){
									/*Se guarda el error para mostrarlo*/
									$Status = false;
									$Result = array();

									$Result['Index']    = $Key;
									$Result['Error']    = $CelaOrigen['Error'];
									$BadKeys[]          = $Result;
								}else{
									/*Se registra la acción "Actualizar" en la bitacora*/
									RecordLog('CelaOrigen', $Key, 5, $SessionUserId, $Data);
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
									$ArgsCelaHeadContent['FormTitle'] = 'Listado de Origenes';

									$Content .= LoadContentPage('../CelaOrigen/CelaOrigenVistaLeer.php', $ArgsCelaOrigenVistaLeer);

									$ArgsCelaOrigenJavascript = array(
										'Table' => 'CelaOrigen',
									);
									$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaOrigenJavascript);
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
								$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaOrigen.php"
	class="btn btn-danger">Aceptar</a>';

								$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
							}
						}else{
							/*Se carga la vista con error obtención de datos para eliminar*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error obteniendo el listado de elementos',
								'TextMessage'   => 'Algunos elementos para actualizar estan corruptos o no existen. <a href="CelaOrigen.php"
	class="btn btn-danger">Aceptar</a>'
							);
							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se verifica que haya datos para eliminar*/
						if(isset($_GET['Key']) && $_GET['Key'] != '') {
							/*Se carga la vista de actualización*/
							$ArgsCelaOrigenVistaActualizar = array(
								'SessionGroupId'    => $SessionGroupId,
								'Random'            => $SessionRandom,
								'FormAction'        => $RouteForm
							);
							$Content = LoadContentPage('../CelaOrigen/CelaOrigenVistaActualizar.php', $ArgsCelaOrigenVistaActualizar);
						}else{
							/*Se carga la busqueda de lectura*/
							$Connection -> close();
							header(sprintf('Location: %s', 'CelaOrigen.php'));
						}
					}
				}
				break;
		}
	}else{
		/*Se carga la vista de lectura*/
		if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
			$Content  = LoadContentPage('../CelaOrigen/CelaOrigenVistaLeer.php', $ArgsCelaOrigenVistaLeer);

			$ArgsCelaOrigenJavascript = array(
				'Table' => 'CelaOrigen',
			);
			$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaOrigenJavascript);
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