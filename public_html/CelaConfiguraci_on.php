<?php
	/**

	 * Fecha: 12/11/2015
	 * Descripción: Controlador de la tabla "CelaConfiguraci_on".
	 **/

	require_once('../Libraries/Functions.php');
	require_once('../Libraries/Session.class.php');
	require_once('../CelaConfiguraci_on/CelaConfiguraci_on.php');
	require_once('../CelaPrivilegios/CelaPrivilegios.php');
	require_once('../CelaRol/CelaRol.php');
	require_once('../CelaCategor_iaConfiguraci_on/CelaCategor_iaConfiguraci_on.php');
	require_once('../Libraries/Connection.php');
	require_once('../Libraries/GetSession.php');
	require_once('../Libraries/Security.php');

	$ArgsCelaConfiguraci_onVistaLeer = array(
		'Table'             => 'CelaConfiguraci_on',
		'ServerSource'      => '../CelaConfiguraci_on/CelaConfiguraci_on.php',
		'ServerFunction'    => 'CelaConfiguraci_onLeer',
		'RouteForm'         => $RouteForm
	);

	$ArgsCelaHeadContent['FormTitle'] = 'Configuraciones del Sistema';

	$MyScripts  = '';
	$Content    = '';
	$MyStyles   = '';

	if(isset($_GET['Action'])){
		switch($_GET['Action']){
			case 'Crear':
				/*Se verifica si se tiene el privilegio de crear*/
				if(isset($Privileges['Crear']) && $Privileges['Crear'] == 1) {
					$ArgsCelaHeadContent['FormTitle'] = 'Creaci&oacute;n de Configuraci&oacute;n';

					/*Se verifica que haya evento "submit"*/
					if((isset($_POST['CelaConfiguraci_onInsert'])) && ($_POST['CelaConfiguraci_onInsert'] == 'CelaConfiguraci_onInsert')){
						/*Se invoca la funcion crear*/
						$CelaConfiguraci_on = CelaConfiguraci_onCrear($_POST);

						if($CelaConfiguraci_on['Status'] == 'OK'){
							/*Se registra la acción "Crear" en la bitacora*/
							RecordLog('CelaConfiguraci_on', $CelaConfiguraci_on['idRecord'], 2, $SessionUserId, $_POST);

							$Status = true;
							/*Se registran los roles que ven esta configuración*/
							if(isset($_POST['Rol']) && count($_POST['Rol']) != 0){
								for($i = 0; $i < count($_POST['Rol']); $i++){
									$Data = array(
										'Privilegio'    => 9,
										'Origen'        => 5,
										'Tupla'         => $CelaConfiguraci_on['idRecord'],
										'TuplaAcceso'   => $_POST['Rol'][$i]
									);

									$CelaPrivilegios = CelaPrivilegiosCrear($Data);
									if($CelaPrivilegios['Status'] == 'OK'){
										/*Se registra la acción "Crear" en la bitacora*/
										RecordLog('CelaPrivilegios', $CelaPrivilegios['idRecord'], 2, $SessionUserId, $Data);
									}else{
										$Status = false;
										$BadKeys[]['Index'] = $Key;
										$BadKeys[]['Error'] = $CelaConfiguraci_on['Error'];
									}
								}
							}

							if($Status){
								$ArgsCelaActionMessage = array(
									'StatusMessage' => 'success',
									'IconMessage'   => 'fa-check',
									'TitleMessage'  => 'Registro exitoso!',
									'TextMessage'   => 'El nuevo elemento se registr&oacute; correctamente.'
								);

								$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

								if(isset($_POST['InsertBack']) && $_POST['InsertBack'] == 1){
									/*Se carga la vista de Creación*/
									$ArgsCelaConfiguraci_onVistaCrear = array(
										'SessionGroupId'    => $SessionGroupId,
										'Random'            => $SessionRandom,
										'FormAction'        => $RouteForm
									);

									$Content    .= LoadContentPage('../CelaConfiguraci_on/CelaConfiguraci_onVistaCrear.php', $ArgsCelaConfiguraci_onVistaCrear);
									$MyScripts  .= LoadContentPage('../CelaConfiguraci_on/CelaConfiguraci_onJavascriptCrear.php');
									$MyStyles   .= '<style>
											.SelectRol{
												width: 100% !important;
											}
										</style>';
								}else{
									if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
										$ArgsCelaHeadContent['FormTitle'] = 'Listado de Configuraciones';

										$Content  .= LoadContentPage('../CelaConfiguraci_on/CelaConfiguraci_onVistaLeer.php', $ArgsCelaConfiguraci_onVistaLeer);

										$ArgsCelaConfiguraci_onJavascript = array(
											'Table' => 'CelaConfiguraci_on',
										);

										$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaConfiguraci_onJavascript);
									}
								}
							}else{
								$ArgsCelaActionMessage = array(
									'StatusMessage' => 'danger',
									'IconMessage'   => 'fa-times',
									'TitleMessage'  => 'Oops!... Ocurrio un error registrando el elemento',
									'TextMessage'   => 'Algunos elementos pudieron no haberse registrado'
								);

								$ArgsCelaActionMessage['TextMessage'] .= '<div class="list-group">';

								for($i = 0; $i < count($BadKeys); $i++){
									$ArgsCelaActionMessage['TextMessage'] .= '<a href="#" class="list-group-item">( "Elemento" => "' . $BadKeys[$i]['Key'] . '", "Error" => "' . $BadKeys[$i]['Error'] . '" )</a>';
								}

								$ArgsCelaActionMessage['TextMessage'] .= '</div>';
								$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaConfiguraci_on.php"
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
								'TextMessage'   => $CelaConfiguraci_on['Error'].'<br />puede <a href="CelaConfiguraci_on.php?' . EncodeThis('Action=Crear') . '" class="alert-link">volver a intentar</a> &oacute; <a href="Escritorio.php" class="alert-link">ir al escritorio</a>'
							);

							$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se carga la vista de Creación*/
						$ArgsCelaConfiguraci_onVistaCrear = array(
							'SessionGroupId'    => $SessionGroupId,
							'Random'            => $SessionRandom,
							'FormAction'        => $RouteForm
						);

						$Content    .= LoadContentPage('../CelaConfiguraci_on/CelaConfiguraci_onVistaCrear.php', $ArgsCelaConfiguraci_onVistaCrear);
						$MyScripts  .= LoadContentPage('../CelaConfiguraci_on/CelaConfiguraci_onJavascriptCrear.php');
						$MyStyles   .= '<style>
											.SelectRol{
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
									sprintf('SELECT * FROM CelaConsfiguraci_on WHERE `id` = %s;',
										GetSQLValueString($Key, 'int')
									)
								);
						$CelaConfiguraci_on = CelaConfiguraci_onEliminar($Key);

						if($CelaConfiguraci_on['Status'] == 'ERROR'){
							/*Se guarda el error para mostrarlo*/
							$Status = false;
							$BadKeys[]['Index'] = $Key;
							$BadKeys[]['Error'] = $CelaConfiguraci_on['Error'];
						}else{
							/*Se registra la acción "Eliminar" en la bitacora*/
							RecordLog('CelaConfiguraci_on', $Key, 3, $SessionUserId, $Data);
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
							$ArgsCelaHeadContent['FormTitle'] = 'Listado de Configuraciones';

							$Content .= LoadContentPage('../CelaConfiguraci_on/CelaConfiguraci_onVistaLeer.php', $ArgsCelaConfiguraci_onVistaLeer);

							$ArgsCelaConfiguraci_onJavascript = array(
								'Table' => 'CelaConfiguraci_on',
							);
							$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaConfiguraci_onJavascript);
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
							$ArgsCelaActionMessage['TextMessage'] .= '<a href="#" class="list-group-item">( "Elemento" => "' . $BadKeys[$i]['Key'] . '", "Error" => "' . $BadKeys[$i]['Error'] . '" )</a>';
						}
						$ArgsCelaActionMessage['TextMessage'] .= '</div>';
						$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaConfiguraci_on.php"
	class="btn btn-danger">Aceptar</a>';

						$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
					}
				}else{
					$Connection -> close();
					header(sprintf('Location: %s', 'CelaConfiguraci_on.php?' . EncodeThis('Action=Leer')));
				}
				break;
			case 'Actualizar':
				/*Se verifica si se tiene el privilegio de actualizar*/
				if(isset($Privileges['Actualizar']) && $Privileges['Actualizar'] == 1) {
					$ArgsCelaHeadContent['FormTitle'] = 'Actualizaci&oacute;n de Configuraci&oacute;n';

					/*Se verifica que haya evento "submit"*/
					if(isset($_POST['CelaConfiguraci_onUpdate']) && $_POST['CelaConfiguraci_onUpdate'] == 'CelaConfiguraci_onUpdate'){
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
								/*Se invoca la funcion actualizar*/
								$Data = array(
									'Nombre'        => $_POST['Nombre' . $Key],
									'Valor'         => $_POST['Valor' . $Key],
									'Tipo'          => $_POST['Tipo' . $Key],
									'Categor_ia'    => $_POST['Categor_ia' . $Key],
									'Referencia'    => $_POST['Referencia' . $Key],
									'Class'         => $_POST['Class' . $Key],
									'Code'         => $_POST['Code' . $Key]
								);
								$CelaConfiguraci_on = CelaConfiguraci_onActualizar($Key, $Data);

								if($CelaConfiguraci_on['Status'] == 'ERROR'){
									/*Se guarda el error para mostrarlo*/
									$Status = false;
									$BadKeys[]['Index'] = $Key;
									$BadKeys[]['Error'] = $CelaConfiguraci_on['Error'];
								}else{
									/*Se registra la acción "Actualizar" en la bitacora*/
									RecordLog('CelaConfiguraci_on', $Key, 5, $SessionUserId, $Data);

									/*Se eliminan los registros anteriores*/
									$CelaPrivilegios = CelaPrivilegiosEliminar($Key, 5);
									if($CelaPrivilegios['Status'] == 'OK'){
										/*Se registran los roles que ven esta configuración*/
										if(isset($_POST['Rol' . $Key]) && count($_POST['Rol' . $Key]) != 0){
											for($i = 0; $i < count($_POST['Rol' . $Key]); $i++){
												$Data = array(
													'Privilegio'    => 9,
													'Origen'        => 5,
													'Tupla'         => $Key,
													'TuplaAcceso'   => $_POST['Rol' . $Key][$i]
												);

												$CelaPrivilegios = CelaPrivilegiosCrear($Data);

												if($CelaPrivilegios['Status'] == 'OK'){
													/*Se registra la acción "Crear" en la bitacora*/
													RecordLog('CelaPrivilegios', $CelaPrivilegios['idRecord'], 2, $SessionUserId, $Data);
												}else{
													$Status = false;
													$BadKeys[]['Index'] = $Key;
													$BadKeys[]['Error'] = $CelaConfiguraci_on['Error'];
												}
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
									$ArgsCelaHeadContent['FormTitle'] = 'Listado de Configuraciones';

									$Content .= LoadContentPage('../CelaConfiguraci_on/CelaConfiguraci_onVistaLeer.php', $ArgsCelaConfiguraci_onVistaLeer);

									$ArgsCelaConfiguraci_onJavascript = array(
										'Table' => 'CelaConfiguraci_on',
									);
									$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaConfiguraci_onJavascript);
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
									$ArgsCelaActionMessage['TextMessage'] .= '<a href="#" class="list-group-item">( "Elemento" => "' . $BadKeys[$i]['Key'] . '", "Error" => "' . $BadKeys[$i]['Error'] . '" )</a>';
								}
								$ArgsCelaActionMessage['TextMessage'] .= '</div>';
								$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaConfiguraci_on.php" class="btn btn-danger">Aceptar</a>';

								$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
							}
						}else{
							/*Se carga la vista con error obtención de datos para eliminar*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error obteniendo el listado de elementos',
								'TextMessage'   => 'Algunos elementos para actualizar estan corruptos o no existen. <a href="CelaConfiguraci_on.php" class="btn btn-danger">Aceptar</a>'
							);

							$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se verifica que haya datos para Actualizar*/
						if(isset($_GET['Key']) && $_GET['Key'] != '') {
							/*Se carga la vista de actualización*/
							$ArgsCelaConfiguraci_onVistaActualizar = array(
								'SessionGroupId'    => $SessionGroupId,
								'Random'            => $SessionRandom,
								'FormAction'        => $RouteForm
							);

							$Content    .= LoadContentPage('../CelaConfiguraci_on/CelaConfiguraci_onVistaActualizar.php', $ArgsCelaConfiguraci_onVistaActualizar);
							$MyScripts  .= LoadContentPage('../CelaConfiguraci_on/CelaConfiguraci_onJavascriptActualizar.php');
							$MyStyles   .= '<style>
											.SelectRol{
												width: 100% !important;
											}
										</style>';
						}else{
							/*Se carga la busqueda de lectura*/
							$Connection -> close();
							header(sprintf('Location: %s', 'CelaConfiguraci_on.php?' . EncodeThis('Action=Leer')));
						}
					}
				}
				break;
			case 'Leer':
				if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
					$ArgsCelaHeadContent['FormTitle'] = 'Listado de Configuraciones';

					$Content .= LoadContentPage('../CelaConfiguraci_on/CelaConfiguraci_onVistaLeer.php', $ArgsCelaConfiguraci_onVistaLeer);

					$ArgsCelaConfiguraci_onJavascript = array(
						'Table' => 'CelaConfiguraci_on',
					);

					$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaConfiguraci_onJavascript);
				}
				break;
		}
	}else{
		/*Se carga la vista de configuraciones*/
		if(isset($Privileges['Actualizar']) && $Privileges['Actualizar'] == 1) {
			if(isset($_POST['CelaConfiguraci_onUpdate']) && $_POST['CelaConfiguraci_onUpdate'] == 'CelaConfiguraci_onUpdate'){
				/*Se Actualiza individualmente las configuraciones.*/
				$QueryConfigurations    =   sprintf('SELECT
														co1.id as idConfiguraci_on,
														co1.Nombre as NombreConfiguraci_on,
														co1.Valor as ValorConfiguraci_on,
														co1.Tipo as TipoConfiguraci_on,
														cio.id as idCategoria,
														cio.NombreCategor_ia as NombreCategor_ia
													 FROM CelaConfiguraci_on co1
														 INNER JOIN CelaCategor_iaConfiguraci_on cio ON ( co1.Categor_ia = cio.id  )
												     WHERE
												     	 co1.id IN	(select Tupla
												     	 			 from CelaPrivilegios
												     	 			 where
												     	 			 	TuplaAcceso = %s and
												     	 			 	Origen = %s
												     	 			)
												     ORDER BY cio.id ASC;',
												GetSQLValueString($SessionGroupId, 'int'),
												GetSQLValueString(5, 'int')
											);
				$ResultConfigurations = $Connection -> query($QueryConfigurations);
				while($RecordConfigurations = $ResultConfigurations -> fetch_assoc()){
					$NameConfig = 'Element_' . $RecordConfigurations['idConfiguraci_on'];

					if(isset($_POST[$NameConfig]) && $RecordConfigurations['NombreConfiguraci_on'] != ''){
						$Value = $_POST[$NameConfig];
					}else{
						$Value = $RecordConfigurations['ValorConfiguraci_on'];
					}

					if($RecordConfigurations['TipoConfiguraci_on'] == 'file' && $_FILES[$NameConfig]['name'] != ''){
						$PublicSource = 'repositorio/configuracion/';
						if(!file_exists($PublicSource)){
							mkdir($PublicSource, 0755, true);
						}

						$Source = $PublicSource . $_FILES[$NameConfig]['name'];
						move_uploaded_file($_FILES[$NameConfig]['tmp_name'], $Source);
						$Value = $Source;
					}

					if($RecordConfigurations['TipoConfiguraci_on'] == 'password'){
						$Value = md5($_POST[$NameConfig]);
						if($_POST[$NameConfig] == ''){
							$Valor = $RecordConfigurations['Valor'];
						}
					}

					if($RecordConfigurations['TipoConfiguraci_on'] == 'checkbox'){
						$Value = (isset($_POST[$NameConfig]) && $_POST[$NameConfig] == 1) ? 1:0;
					}
					CelaConfiguraci_onActualizaValor($RecordConfigurations['idConfiguraci_on'], $Value);
				}
			}

			$ArgsCelaConfiguraci_onVistaAdmin = array(
				'SessionGroupId'    => $SessionGroupId,
				'FormAction'        => $RouteForm
			);
			$Content .= LoadContentPage('../CelaConfiguraci_on/CelaConfiguraci_onVistaAdmin.php', $ArgsCelaConfiguraci_onVistaAdmin);
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