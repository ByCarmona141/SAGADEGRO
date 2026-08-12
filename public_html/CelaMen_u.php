<?php
	/**

	 * Fecha: 12/11/2015
	 * Descripción: Controlador de la tabla "CelaMen_u".
	 **/

	require_once('../Libraries/Functions.php');
	require_once('../Libraries/Session.class.php');
	require_once('../CelaMen_u/CelaMen_u.php');
	require_once('../CelaFormulario/CelaFormulario.php');
	require_once('../CelaPrivilegios/CelaPrivilegios.php');
	require_once('../CelaRepositorio/CelaRepositorio.php');
	require_once('../CelaRol/CelaRol.php');
	require_once('../CelaTipoDeElemento/CelaTipoDeElemento.php');
	require_once('../CelaIcono/CelaIcono.php');
	require_once('../Libraries/Connection.php');
	require_once('../Libraries/GetSession.php');
	require_once('../Libraries/Security.php');

	$ArgsCelaMen_uVistaLeer = array(
		'Table'             => 'CelaMen_u',
		'ServerSource'      => '../CelaMen_u/CelaMen_u.php',
		'ServerFunction'    => 'CelaMen_uLeer',
		'RouteForm'         => $RouteForm,
		'Params'            => array(
			'GroupId' => $SessionGroupId
		),
		'SessionRandom'     => $SessionRandom
	);

	$ArgsCelaHeadContent['FormTitle'] = 'Listado de Men&uacute;';

	$MyScripts  = '';
	$MyStyles   = '';
	$Content    = '';

	$BadKeys = array();

	if(isset($_GET['Action'])){
		switch($_GET['Action']){
			case 'Crear':
				/*Se verifica si se tiene el privilegio de crear*/
				if(isset($Privileges['Crear']) && $Privileges['Crear'] == 1){
					$ArgsCelaHeadContent['FormTitle'] = 'Creaci&oacute;n de Men&uacute;';

					/*Se verifica que haya evento "submit"*/
					if((isset($_POST['CelaMen_uInsert'])) && ($_POST['CelaMen_uInsert'] == 'CelaMen_uInsert')){
						/*Se invoca la funcion crear*/
						$CelaMen_u = CelaMen_uCrear($_POST);

						if($CelaMen_u['Status'] == 'OK'){
							/*Se registra la acción "Crear" en la bitacora*/
							RecordLog('CelaMen_u', $CelaMen_u['idRecord'], 2, $SessionUserId);

							$Status = true;

							if(isset($_FILES['Archivo']) && $_FILES['Archivo']['name'] != '' ){
								$Archivo = $_FILES['Archivo']['tmp_name'];
								$Nombre  = $_FILES['Archivo']['name'];
								$Size	 = $_FILES['Archivo']['size'];

								$FileSource = CreateFile($Archivo, $Nombre);

								$Data = array(
									'Nombre'        => $Nombre,
									'Descripci_on'  => $_POST['Descripci_on'],
									'Tama_no'       => $Size,
									'Origen'        => 'CelaMen_u',
									'Tupla'         => $CelaMen_u['idRecord'],
									'Ruta'          => $FileSource,
									'idUsuario'     => $SessionUserId
								);

								$CelaReporitorio = CelaRepositorioCrear($Data);

								if($CelaReporitorio['Status'] == 'OK'){
									/*Se registra la acción "Crear" en la bitacora*/
									RecordLog('CelaRepositorio', $CelaReporitorio['idRecord'], 2, $SessionUserId, $_POST);

									$UpdateCelaMen_u = UpdateValue('CelaMen_u', array('Referencia' => $FileSource), array('id' => $CelaMen_u['idRecord']));

									$Status = true;
								}else{
									$Status = false;
								}
							}

							/*Se verifica que exista el formulario para este menú*/
							$Form = GetValue(
										sprintf('SELECT id FROM CelaFormulario WHERE Ruta = %s',
											GetSQLValueString($_POST['Referencia'], 'varchar')
										),
										'id'
									);

							$CelaFormulario = array('Status' => 'OK');

							if($Form == 'NULL'){
								/*Se registra el formulario*/
								$Data = array();
								$Data['Nombre'] = $_POST['Nombre'];
								$Data['Descripci_on'] = $_POST['Descripci_on'];
								$Data['Ruta'] = $_POST['Referencia'];
								$CelaFormulario = CelaFormularioCrear($Data);
							}

							if($CelaFormulario['Status'] == 'OK'){
								/*Se registran los privilegios del formulario*/
								if(isset($_POST['Rol']) && count($_POST['Rol']) > 0){
									for($i = 0; $i < count($_POST['Rol']); $i++){
										$Data = array();
										$Data['Privilegio']     = 1;
										$Data['Origen']         = 1;
										$Data['Tupla']          = $CelaMen_u['idRecord'];
										$Data['TuplaAcceso']    = $_POST['Rol'][$i];
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

								if($Status){
									/*Se carga la vista de lectura con mensaje creación correcta*/
									$ArgsCelaActionMessage = array(
										'StatusMessage' => 'success',
										'IconMessage'   => 'fa-check',
										'TitleMessage'  => 'Registro exitoso!',
										'TextMessage'   => 'El nuevo elemento se registr&oacute; correctamente.'
									);

									$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);

									if(isset($_POST['InsertBack']) && $_POST['InsertBack'] ==1){
										/*Se carga la vista de Creación*/
										$ArgsCelaMen_uVistaCrear = array(
											'SessionGroupId'    => $SessionGroupId,
											'Random'            => $SessionRandom,
											'FormAction'        => $RouteForm
										);

										$Content    .= LoadContentPage('../CelaMen_u/CelaMen_uVistaCrear.php', $ArgsCelaMen_uVistaCrear);

										$MyScripts  .= LoadContentPage('../CelaMen_u/CelaMen_uJavascriptCrear.php');
										$MyStyles   .= '<style>
														.SelectRol{
															width: 100% !important;
														}
													</style>';
									}else{
										if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1){
											$ArgsCelaHeadContent['FormTitle'] = 'Listado de Men&uacute;';

											$Content  .= LoadContentPage('../CelaMen_u/CelaMen_uVistaLeer.php', $ArgsCelaMen_uVistaLeer);

											$ArgsCelaMen_uJavascript = array(
												'Table' => 'CelaMen_u',
											);

											$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaMen_uJavascript);
										}
									}
								}else{
									/*Se carga la vista con el mensaje de error de Creacion de privilegio*/
									$ArgsCelaActionMessage = array(
										'StatusMessage' => 'danger',
										'IconMessage'   => 'fa-times',
										'TitleMessage'  => 'Oops!... Ocurrio un error registrando privilegios',
										'TextMessage'   => 'Algunos roles no tienen este privilegio'
									);

									$ArgsCelaActionMessage['TextMessage'] .= '<div class="list-group">';
									for($i = 0; $i < count($BadKeys); $i++){
										$ArgsCelaActionMessage['TextMessage'] .= '<a href="#" class="list-group-item">( "Elemento" => "' . $BadKeys[$i]['Index'] . '", "Error" => "' . $BadKeys[$i]['Error'] . '" )</a>';
									}
									$ArgsCelaActionMessage['TextMessage'] .= '</div>';
									$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaMen_u.php"
	class="btn btn-danger">Aceptar</a>';

									$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
								}
							}else{
								$Status = false;
								/*Se carga la vista de lectura con mensaje de error de creación*/
								$ArgsCelaActionMessage = array(
									'StatusMessage' => 'danger',
									'IconMessage'   => 'fa-times',
									'TitleMessage'  => 'Oops!... Ocurrio un error registrando el formulario',
									'TextMessage'   => $CelaMen_u['Error'].'<br />puede <a href="CelaMen_u.php?' . EncodeThis('Action=Crear') . '" class="alert-link">volver a intentar</a> &oacute; <a href="Escritorio.php" class="alert-link">ir al escritorio</a>'
								);

								$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
							}
						}else{
							$Status = false;
							/*Se carga la vista de lectura con mensaje de error de creación*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error registrando el elemento',
								'TextMessage'   => $CelaMen_u['Error'].'<br />puede <a href="CelaMen_u.php?' . EncodeThis('Action=Crear') . '" class="alert-link">volver a intentar</a> &oacute; <a href="Escritorio.php" class="alert-link">ir al escritorio</a>'
							);

							$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se carga la vista de Creación*/
						$ArgsCelaMen_uVistaCrear = array(
							'SessionGroupId'    => $SessionGroupId,
							'Random'            => $SessionRandom,
							'FormAction'        => $RouteForm
						);

						$Content    .= LoadContentPage('../CelaMen_u/CelaMen_uVistaCrear.php', $ArgsCelaMen_uVistaCrear);
						$MyScripts  .= LoadContentPage('../CelaMen_u/CelaMen_uJavascriptCrear.php');
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
				if(isset($_GET['Key']) && $_GET['Key'] != '' && isset($Privileges['Eliminar']) && $Privileges['Eliminar'] == 1){
					$Status = true;
					/*Se recorre cada uno de los elementos que se van a eliminar*/
					foreach ($_GET['Key'] as $Key){
						/*Se invoca la funcion eliminar*/
						$Data = GetValue(
									sprintf('SELECT * FROM CelaMen_u WHERE `id` = %s;',
										GetSQLValueString($Key, 'int')
									)
								);
						$CelaMen_u = CelaMen_uEliminar($Key);

						if($CelaMen_u['Status'] == 'ERROR'){
							/*Se guarda el error para mostrarlo*/
							$Status = false;
							$Result = array();

							$Result['Index']    = $Key;
							$Result['Error']    = $CelaMen_u['Error'];
							$BadKeys[]          = $Result;
						}else{
							/*Se registra la acción "Eliminar" en la bitacora*/
							RecordLog('CelaMen_u', $Key, 3, $SessionUserId, $Data);

							$CelaPrivilegios = CelaPrivilegiosEliminar($Key, 1);
							if($CelaPrivilegios['Status'] == 'ERROR'){
								/*Se guarda el error para mostrarlo*/
								$Status = false;
								$Result = array();

								$Result['Index']    = $Key;
								$Result['Error']    = $CelaMen_u['Error'];
								$BadKeys[]          = $Result;
							}
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

						if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1){
							$ArgsCelaHeadContent['FormTitle'] = 'Listado de Men&uacute;';

							$Content .= LoadContentPage('../CelaMen_u/CelaMen_uVistaLeer.php', $ArgsCelaMen_uVistaLeer);

							$ArgsCelaMen_uJavascript = array(
								'Table' => 'CelaMen_u',
							);

							$MyScripts = LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaMen_uJavascript);
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
						$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaMen_u.php"
	class="btn btn-danger">Aceptar</a>';

						$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
					}
				}else{
					$Connection -> close();
					header(sprintf('Location: %s', 'CelaMen_u.php'));
				}
				break;
			case 'Actualizar':
				/*Se verifica si se tiene el privilegio de actualizar*/
				if(isset($Privileges['Actualizar']) && $Privileges['Actualizar'] == 1){
					$ArgsCelaHeadContent['FormTitle'] = 'Actualizaci&oacute;n de Men&uacute;';

					/*Se verifica que haya evento "submit"*/
					if(isset($_POST['CelaMen_uUpdate']) && $_POST['CelaMen_uUpdate'] == 'CelaMen_uUpdate'){
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
								$Categoria  = ($_POST['Categor_ia' . $Key] == '' ? $Key:$_POST['Categor_ia' . $Key]);
								$Referencia = $_POST['Referencia' . $Key];

								if($_POST['TipoDeElemento' . $Key] == 5){
									if(isset($_FILES['Archivo' . $Key]) && $_FILES['Archivo' . $Key]['name'] != '' ){
										$Archivo = $_FILES['Archivo' . $Key]['tmp_name'];
										$Nombre  = $_FILES['Archivo' . $Key]['name'];
										$Size	 = $_FILES['Archivo' . $Key]['size'];

										$FileSource = CreateFile($Archivo, $Nombre);

										$Data = array(
											'Nombre'        => $Nombre,
											'Descripci_on'  => $_POST['Descripci_on' . $Key],
											'Tama_no'       => $Size,
											'Origen'        => 'CelaMen_u',
											'Tupla'         => $Key,
											'Ruta'          => $FileSource,
											'idUsuario'     => $SessionUserId
										);

										$CelaReporitorio = CelaRepositorioCrear($Data);

										if($CelaReporitorio['Status'] == 'OK'){
											/*Se registra la acción "Crear" en la bitacora*/
											RecordLog('CelaMen_u', $CelaReporitorio['idRecord'], 2, $SessionUserId, $Data);
											$Status = true;
											$Referencia = $FileSource;
										}else{
											$Status = false;
										}
									}else{
										$Referencia =   GetValue(
															sprintf('SELECT Referencia FROM CelaMen_u WHERE id = %s;',
																GetSQLValueString($Key, 'int')
															),
															'Referencia'
														);
									}
								}

								$Data = array(
									'Nombre'            => $_POST['Nombre' . $Key],
									'Descripci_on'      => $_POST['Descripci_on' . $Key],
									'Referencia'        => $Referencia,
									'Icono'             => $_POST['Icono' . $Key],
									'TipoDeElemento'    => $_POST['TipoDeElemento' . $Key],
									'Categor_ia'        => $Categoria,
									'Prioridad'         => $_POST['Prioridad' . $Key],
									'Orientaci_on'      => $_POST['Orientaci_on' . $Key],
								);
								$CelaMen_u = CelaMen_uActualizar($Key, $Data);

								if($CelaMen_u['Status'] == 'ERROR'){
									/*Se guarda el error para mostrarlo*/
									$Status = false;
									$Result = array();

									$Result['Index']    = $Key;
									$Result['Error']    = $CelaMen_u['Error'];
									$BadKeys[]          = $Result;

								}else{
									/*Se registra la acción "Actualizar" en la bitacora*/
									RecordLog('CelaMen_u', $Key, 5, $SessionUserId, $Data);

									/*Se eliminan los registros anteriores, solo para los roles seleccionados, los demas roles se quedan intactos*/
									$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');
									$CelaPrivilegios = CelaPrivilegiosEliminar($Key, 1, false, $Group);

									if($CelaPrivilegios['Status'] == 'OK'){
										/*Se registran los privilegios del formulario*/
										if(isset($_POST['Rol' . $Key]) && count($_POST['Rol' . $Key]) > 0){
											for($i = 0; $i < count($_POST['Rol' . $Key]); $i++){
												$Data = array(
													'Privilegio'    => 1,
													'Origen'        => 1,
													'Tupla'         => $Key,
													'TuplaAcceso'   => $_POST['Rol' . $Key][$i]
												);

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
									}else{
										/*Se guarda el error para mostrarlo*/
										$Status = false;
										$Result = array();

										$Result['Index']    = $Key;
										$Result['Error']    = $CelaPrivilegios['Error'];
										$BadKeys[]          = $Result;
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

								if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1){
									$ArgsCelaHeadContent['FormTitle'] = 'Listado de Men&uacute;';

									$Content .= LoadContentPage('../CelaMen_u/CelaMen_uVistaLeer.php', $ArgsCelaMen_uVistaLeer);

									$ArgsCelaMen_uJavascript = array(
										'Table' => 'CelaMen_u',
									);

									$MyScripts = LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaMen_uJavascript);
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
								$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaMen_u.php"
	class="btn btn-danger">Aceptar</a>';

								$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
							}
						}else{
							/*Se carga la vista con error obtención de datos para eliminar*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error obteniendo el listado de elementos',
								'TextMessage'   => 'Algunos elementos para actualizar estan corruptos o no existen. <a href="CelaMen_u.php"
	class="btn btn-danger">Aceptar</a>'
							);

							$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se verifica que haya datos para eliminar*/
						if(isset($_GET['Key']) && $_GET['Key'] != ''){
							/*Se carga la vista de actualización*/
							$ArgsCelaMen_uVistaActualizar = array(
								'SessionGroupId'    => $SessionGroupId,
								'Random'            => $SessionRandom,
								'FormAction'        => $RouteForm
							);

							$Content    .= LoadContentPage('../CelaMen_u/CelaMen_uVistaActualizar.php', $ArgsCelaMen_uVistaActualizar);
							$MyScripts  .= LoadContentPage('../CelaMen_u/CelaMen_uJavascriptActualizar.php');
							$MyStyles   .= '<style>
												.SelectRol{
													width: 100% !important;
												}
											</style>';
						}else{
							/*Se carga la busqueda de lectura*/
							$Connection -> close();
							header(sprintf('Location: %s', 'CelaMen_u.php'));
						}
					}
				}
				break;
		}
	}else{
		/*Se carga la vista de lectura*/
		if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1){
			$Content .= LoadContentPage('../CelaMen_u/CelaMen_uVistaLeer.php', $ArgsCelaMen_uVistaLeer);

			$ArgsCelaMen_uJavascript = array(
				'Table' => 'CelaMen_u',
			);

			$MyScripts .= LoadContentPage('../CelaTemplate/CelaTableToolsScript.php', $ArgsCelaMen_uJavascript);
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