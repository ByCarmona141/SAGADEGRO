<?php
	/**

	 * Fecha: 12/11/2015
	 * Descripción: Controlador de la tabla "CelaRepositorio".
	 **/

	require_once('../Libraries/Functions.php');
	require_once('../Libraries/Session.class.php');
	require_once('../CelaRepositorio/CelaRepositorio.php');
	require_once('../CelaOrigen/CelaOrigen.php');
	require_once('../Libraries/Connection.php');
	require_once('../Libraries/GetSession.php');
	require_once('../Libraries/Security.php');

	$ArgsCelaRepositorioVistaLeer = array(
		'Table'             => 'CelaRepositorio',
		'ServerSource'      => '../CelaRepositorio/CelaRepositorio.php',
		'ServerFunction'    => 'CelaRepositorioLeer',
		'RouteForm'         => $RouteForm
	);

	$ArgsCelaHeadContent['FormTitle'] = 'Listado de Archivos en Repositorio';

	$MyScripts  = '';
	$Content    = '';
	$MyStyles   = '';

	$BadKeys    = array();

	if(isset($_GET['Action'])){
		switch($_GET['Action']){
			case 'Crear':
				/*Se verifica si se tiene el privilegio de crear*/
				if(isset($Privileges['Crear']) && $Privileges['Crear'] == 1) {
					$ArgsCelaHeadContent['FormTitle'] = 'Creaci&oacute;n de Archivo';

					/*Se verifica que haya evento "submit"*/
					if((isset($_POST['CelaRepositorioInsert'])) && ($_POST['CelaRepositorioInsert'] == 'CelaRepositorioInsert')){
						/*Se invoca la funcion crear*/
						$Archivo = $_FILES['Archivo']['tmp_name'];
						$Nombre  = $_FILES['Archivo']['name'];
						$Size	 = $_FILES['Archivo']['size'];

						$FileSource = CreateFile($Archivo, $Nombre);

						$Data = array(
							'Nombre'        => $Nombre,
							'Descripci_on'  => $_POST['Descripci_on'],
							'Tama_no'       => $Size,
							'Origen'        => $_POST['Source'],
							'Tupla'         => $_POST['Tupla'],
							'Ruta'          => $FileSource,
							'idUsuario'     => $SessionUserId
						);

						$CelaRepositorio = CelaRepositorioCrear($Data);

						if($CelaRepositorio['Status'] == 'OK'){
							/*Se registra la acción "Crear" en la bitacora*/
							RecordLog('CelaRepositorio', $CelaRepositorio['idRecord'], 2, $SessionUserId, $Data);
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
								$ArgsCelaRepositorioVistaCrear = array(
									'SessionGroupId'    => $SessionGroupId,
									'Random'            => $SessionRandom,
									'FormAction'        => $RouteForm
								);

								$Content = LoadContentPage('../CelaRepositorio/CelaRepositorioVistaCrear.php', $ArgsCelaRepositorioVistaCrear);
							}else{
								if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
									$ArgsCelaHeadContent['FormTitle'] = 'Listado de Archivos en Repositorio';

									$ArgsCelaRepositorioJavascript = array(
										'Table'     => 'CelaRepositorio'
									);

									if(isset($_GET['Source']) && $_GET['Source'] != ''){
										$ArgsCelaRepositorioVistaLeer['Params']['Source']   = $_GET['Source'];
										$ArgsCelaRepositorioJavascript['Params']['Source']  = $_GET['Source'];
									}

									if(isset($_GET['Tupla']) && $_GET['Tupla'] != ''){
										$ArgsCelaRepositorioVistaLeer['Params']['Tupla']    = $_GET['Tupla'];
										$ArgsCelaRepositorioJavascript['Params']['Tupla']   = $_GET['Tupla'];
									}

									$ArgsCelaRepositorioVistaLeer['SessionRandom'] = $SessionRandom;

									$Content  .= LoadContentPage('../CelaRepositorio/CelaRepositorioVistaLeer.php', $ArgsCelaRepositorioVistaLeer);

									$MyScripts .= LoadContentPage('../CelaRepositorio/CelaRepositorioJavascriptLeer.php', $ArgsCelaRepositorioJavascript);
								}
							}
						}else{
							$Status = false;
							/*Se carga la vista de lectura con mensaje de error de creación*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error registrando el elemento',
								'TextMessage'   => $CelaRepositorio['Error'].'<br />puede <a href="CelaRepositorio.php?' . EncodeThis('Action=Crear') . '" class="alert-link">volver a intentar</a> &oacute; <a href="Escritorio.php" class="alert-link">ir al escritorio</a>'
							);

							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se carga la vista de Creación*/
						$ArgsCelaRepositorioVistaCrear = array(
							'SessionGroupId'    => $SessionGroupId,
							'Random'            => $SessionRandom,
							'FormAction'        => $RouteForm
						);

						$Content = LoadContentPage('../CelaRepositorio/CelaRepositorioVistaCrear.php', $ArgsCelaRepositorioVistaCrear);
					}
				}
				break;
			case 'Eliminar':
				/*Se verifica que haya datos para eliminar y verifica si se tiene el privilegio de crear*/
				if(isset($_GET['Key']) && $_GET['Key'] != '' && isset($Privileges['Eliminar']) && $Privileges['Eliminar'] == 1) {
					$Status = true;

					/*Se recorre cada uno de los elementos que se van a eliminar*/
					foreach ($_GET['Key'] as $Key){
						/*Se invoca la funcion eliminar*/
						$Data = GetValue(
									sprintf('SELECT * FROM CelaRepositorio WHERE `id` = %s;',
										GetSQLValueString($Key, 'int')
									)
								);
						$CelaRepositorio = CelaRepositorioEliminar($Key);

						if($CelaRepositorio['Status'] == 'ERROR'){
							$Status = false;
							$Result = array();

							$Result['Index']    = $Key;
							$Result['Error']    = $CelaRepositorio['Error'];
							$BadKeys[]          = $Result;
						}else{
							/*Se registra la acción "Eliminar" en la bitacora*/
							RecordLog('CelaRepositorio', $Key, 3, $SessionUserId, $Data);
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
							$ArgsCelaHeadContent['FormTitle'] = 'Listado de Archivos en Repositorio';

							$ArgsCelaRepositorioJavascript = array(
								'Table' => 'CelaRepositorio',
							);

							if(isset($_GET['Source']) && $_GET['Source'] != ''){
								$ArgsCelaRepositorioVistaLeer['Params']['Source']   = $_GET['Source'];
								$ArgsCelaRepositorioJavascript['Params']['Source']  = $_GET['Source'];
							}

							if(isset($_GET['Tupla']) && $_GET['Tupla'] != ''){
								$ArgsCelaRepositorioVistaLeer['Params']['Tupla']    = $_GET['Tupla'];
								$ArgsCelaRepositorioJavascript['Params']['Tupla']   = $_GET['Tupla'];
							}

							$ArgsCelaRepositorioVistaLeer['SessionRandom']      = $SessionRandom;

							$Content .= LoadContentPage('../CelaRepositorio/CelaRepositorioVistaLeer.php', $ArgsCelaRepositorioVistaLeer);

							$MyScripts .= LoadContentPage('../CelaRepositorio/CelaRepositorioJavascriptLeer.php', $ArgsCelaRepositorioJavascript);
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
						$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaRepositorio.php"
class="btn btn-danger">Aceptar</a>';

						$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
					}
				}else{
					$Connection -> close();
					header(sprintf('Location: %s', 'CelaRepositorio.php'));
				}
				break;
			case 'Actualizar':
				/*Se verifica si se tiene el privilegio de actualizar*/
				if(isset($Privileges['Actualizar']) && $Privileges['Actualizar'] == 1) {
					$ArgsCelaHeadContent['FormTitle'] = 'Actualizaci&oacute;n de Archivo';

					/*Se verifica que haya evento "submit"*/
					if(isset($_POST['CelaRepositorioUpdate']) && $_POST['CelaRepositorioUpdate'] == 'CelaRepositorioUpdate'){
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
							$NewVersion	= true;
							/*Se recorre cada uno de los elementos que se van a actualizar*/
							foreach($Keys as $Key){
								if(isset($_FILES['Archivo' . $Key]['tmp_name']) && $_FILES['Archivo' . $Key]['tmp_name'] != ''){
									/*Se registra la version nueva del archivo en el repositorio.*/
									$Archivo = $_FILES['Archivo' . $Key]['tmp_name'];
									$Nombre  = $_FILES['Archivo' . $Key]['name'];
									$Size	 = $_FILES['Archivo' . $Key]['size'];

									$FileSource = CreateFile($Archivo, $Nombre);

									$Data = array(
										'Nombre'        => $Nombre,
										'Descripci_on'  => $_POST['Descripci_on' . $Key],
										'Tama_no'       => $Size,
										'Origen'        => $_POST['Source' . $Key],
										'Tupla'         => $_POST['Tupla' . $Key],
										'Ruta'          => $FileSource,
										'idUsuario'     => $SessionUserId
									);

									$CelaRepositorio = CelaRepositorioCrear($Data);

									if($CelaRepositorio['Status'] == 'ERROR'){
										/*Se guarda el error para mostrarlo*/
										$Status = false;
										$Result = array();

										$Result['Index']    = $Key;
										$Result['Error']    = 'Create Versi&oacute;n File: ' . $CelaRepositorio['Error'];
										$BadKeys[]          = $Result;
									}else{
										/*Se registra la acción "Crear" en la bitacora*/
										RecordLog('CelaRepositorio', $Key, 2, $SessionUserId, $Data);
									}
								}else{
									$NewVersion = false;
								}

								if($NewVersion){
									/*Se Actualiza el repositorio actual.*/

									$FieldList = array(
										'Origen'    => GetSQLValueString('CelaRepositorio', 'varchar'),
										'Tupla'     => GetSQLValueString($CelaRepositorio['idRecord'], 'int'),
									);

									$WhereList = array(
										'id'    => $Key
									);

									$CelaRepositorioUpdate = UpdateValue('CelaRepositorio', $FieldList, $WhereList);

									if($CelaRepositorioUpdate['Status'] == 'ERROR'){
										/*Se guarda el error para mostrarlo*/
										$Status = false;
										$Result = array();

										$Result['Index']    = $Key;
										$Result['Error']    = 'Update Versi&oacute;n File ' . $CelaRepositorio['Error'];
										$BadKeys[]          = $Result;
									}else{
										/*Se registra la acción "Actualizar" en la bitacora*/
										RecordLog('CelaRepositorio', $Key, 5, $SessionUserId, $FieldList);
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
									$ArgsCelaHeadContent['FormTitle'] = 'Listado de Archivos en Repositorio';

									$ArgsCelaRepositorioJavascript = array(
										'Table' => 'CelaRepositorio',
									);

									if(isset($_GET['Source']) && $_GET['Source'] != ''){
										$ArgsCelaRepositorioVistaLeer['Params']['Source']   = $_GET['Source'];
										$ArgsCelaRepositorioJavascript['Params']['Source']  = $_GET['Source'];
									}

									if(isset($_GET['Tupla']) && $_GET['Tupla'] != ''){
										$ArgsCelaRepositorioVistaLeer['Params']['Tupla']    = $_GET['Tupla'];
										$ArgsCelaRepositorioJavascript['Params']['Tupla']   = $_GET['Tupla'];
									}

									$ArgsCelaRepositorioVistaLeer['SessionRandom']      = $SessionRandom;
									$Content .= LoadContentPage('../CelaRepositorio/CelaRepositorioVistaLeer.php', $ArgsCelaRepositorioVistaLeer);

									$MyScripts .= LoadContentPage('../CelaRepositorio/CelaRepositorioJavascriptLeer.php', $ArgsCelaRepositorioJavascript);
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
								$ArgsCelaActionMessage['TextMessage'] .= '<a href="CelaRepositorio.php"
class="btn btn-danger">Aceptar</a>';

								$Content .= LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
							}
						}else{
							/*Se carga la vista con error obtención de datos para eliminar*/
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'danger',
								'IconMessage'   => 'fa-times',
								'TitleMessage'  => 'Oops!... Ocurrio un error obteniendo el listado de elementos',
								'TextMessage'   => 'Algunos elementos para actualizar estan corruptos o no existen. <a href="CelaRepositorio.php"
class="btn btn-danger">Aceptar</a>'
							);
							$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						/*Se verifica que haya datos para eliminar*/
						if(isset($_GET['Key']) && $_GET['Key'] != '') {
							/*Se carga la vista de actualización*/
							$ArgsCelaRepositorioVistaActualizar = array(
								'SessionGroupId'    => $SessionGroupId,
								'Random'            => $SessionRandom,
								'FormAction'        => $RouteForm
							);
							$Content .= LoadContentPage('../CelaRepositorio/CelaRepositorioVistaActualizar.php', $ArgsCelaRepositorioVistaActualizar);
						}else{
							/*Se carga la busqueda de lectura*/
							$Connection -> close();
							header(sprintf('Location: %s', 'CelaRepositorio.php'));
						}
					}
				}
				break;
			case 'VistaPrevia':
				/*Se carga la pantalla de vista previa*/
				if(isset($_GET['Key']) && $_GET['Key'] != ''){
					$CelaRepositorio = CelaRepositorioGetFile($_GET['Key']);
					
					if($CelaRepositorio['Status'] == 'OK'){
						$ArgsCelaRepositorioVistaPrevia = array(
							'SourceFile' => $CelaRepositorio['Response']['Ruta']
						);

						$ArgsCelaHeadContent['FormTitle'] = 'Archivo: ' . $CelaRepositorio['Response']['Nombre'] . ' (' . $CelaRepositorio['Response']['FechaTupla'] . ')';

						$Content = LoadContentPage('../CelaRepositorio/CelaRepositorioVistaPrevia.php', $ArgsCelaRepositorioVistaPrevia);
					}else{
						/*Se carga la vista con error de archivo no encontrado*/
						$ArgsCelaActionMessage = array(
							'StatusMessage' => 'danger',
							'IconMessage'   => 'fa-times',
							'TitleMessage'  => 'Oops!... Ocurrio un error obteniendo el archivo',
							'TextMessage'   => $CelaRepositorio['Error'] . '. <a href="CelaRepositorio.php"
class="btn btn-danger">Aceptar</a>'
						);

						$ArgsCelaHeadContent['FormTitle'] = 'Archivo: No encontrado';

						$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
					}
				}else{
					/*Se carga la vista con error de archivo no seleccionado*/
					$ArgsCelaActionMessage = array(
						'StatusMessage' => 'danger',
						'IconMessage'   => 'fa-times',
						'TitleMessage'  => 'Oops!... Ocurrio un error obteniendo el archivo',
						'TextMessage'   => 'Parece que no se ha seleccionado ningun archivo a mostrar'
					);
					$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
				}
				break;
			case 'Descargar':
				/*Se carga la pantalla de vista previa*/
				if(isset($_GET['Key']) && $_GET['Key'] != ''){
					$CelaRepositorio = CelaRepositorioGetFile($_GET['Key']);

					if($CelaRepositorio['Status'] == 'OK'){
						$Temp = fopen($CelaRepositorio['Response']['Ruta'], 'rb');

						//Se lanza a descarga el archivo.
						header('Content-type: application/octet-stream');
						header('Content-Disposition: attachment; filename=copy_' . str_replace(array('-', ' '), '', $CelaRepositorio['Response']['Nombre']));
						fpassthru($Temp);

						fclose($Temp);
						exit();
					}else{
						/*Se carga la vista con error de archivo no encontrado*/
						$ArgsCelaActionMessage = array(
							'StatusMessage' => 'danger',
							'IconMessage'   => 'fa-times',
							'TitleMessage'  => 'Oops!... Ocurrio un error obteniendo el archivo',
							'TextMessage'   => $CelaRepositorio['Error'] . '. <a href="CelaRepositorio.php"
class="btn btn-danger">Aceptar</a>'
						);

						$ArgsCelaHeadContent['FormTitle'] = 'Archivo: No encontrado';

						$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
					}
				}else{
					/*Se carga la vista con error de archivo no seleccionado*/
					$ArgsCelaActionMessage = array(
						'StatusMessage' => 'danger',
						'IconMessage'   => 'fa-times',
						'TitleMessage'  => 'Oops!... Ocurrio un error obteniendo el archivo',
						'TextMessage'   => 'Parece que no se ha seleccionado ningun archivo a mostrar'
					);
					$Content = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
				}
				break;
		}
	}else{
		/*Se carga la vista de lectura*/
		if(isset($Privileges['Leer']) && $Privileges['Leer'] == 1) {
			$ArgsCelaRepositorioJavascript = array(
				'Table'     => 'CelaRepositorio'
			);

			if(isset($_GET['Source']) && $_GET['Source'] != ''){
				$ArgsCelaRepositorioVistaLeer['Params']['Source']   = $_GET['Source'];
				$ArgsCelaRepositorioJavascript['Params']['Source']  = $_GET['Source'];
			}

			if(isset($_GET['Tupla']) && $_GET['Tupla'] != ''){
				$ArgsCelaRepositorioVistaLeer['Params']['Tupla']    = $_GET['Tupla'];
				$ArgsCelaRepositorioJavascript['Params']['Tupla']   = $_GET['Tupla'];
			}

			$ArgsCelaRepositorioVistaLeer['SessionRandom']  = $SessionRandom;

			$Content    = LoadContentPage('../CelaRepositorio/CelaRepositorioVistaLeer.php', $ArgsCelaRepositorioVistaLeer);

			$MyScripts .= LoadContentPage('../CelaRepositorio/CelaRepositorioJavascriptLeer.php', $ArgsCelaRepositorioJavascript);
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