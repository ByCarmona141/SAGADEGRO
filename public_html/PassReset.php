<?php
	require_once('../Libraries/Functions.php');
	require_once('../Libraries/ExceptionThrower.php');
	require_once('../Libraries/Session.class.php');
	require_once('../CelaUsuario/CelaUsuario.php');
	require_once('../Libraries/Connection.php');



	/*Clases necesarias para el uso de correo*/
	require(BASE_DIR . 'Libraries/PHPMailer/src/PHPMailer.php');
	require(BASE_DIR . 'Libraries/PHPMailer/src/SMTP.php');
	require(BASE_DIR . 'Libraries/PHPMailer/src/Exception.php');

	/*Se obtienen las variables del enlace*/
	$_GET = NULL;
	$String = Decrypt(substr(strrchr($_SERVER['REQUEST_URI'], '?'), 1)); //Obtener la url desde el ?

	$GET = preg_split('[&]', $String); //separo la url por &
	foreach($GET as $Value){
		$GET = preg_split ('[=]', $Value); //asigno los valores al GET

		if(substr_count($GET[0], '[]') == 1){
			//Es un arreglo
			$GET[0]             = str_replace('[]', '', $GET[0]);
			$_GET[$GET[0]][]    = (isset($GET[1]) ? $GET[1]:'');
		}
		else{
			$_GET[$GET[0]]      = (isset($GET[1]) ? $GET[1]:'');
		}
	}

	$ArgsJoin = array();
	$Content = '';
	if(isset($_GET['Action'])){
		switch($_GET['Action']){
			case 'ConfirmReset':
				if(isset($_POST['ConfirmReset']) && $_POST['ConfirmReset'] != ''){
					$Status = true;
					$Error = '';

					/*Se actualiza la contraseña del usuario*/
					$UpdateUsuario = UpdateValue(
						'CelaUsuario',
						array('Contrase_na' => '"' . md5($_POST['Contrase_na']) . '"', 'Activo' => 1),
						array('id' => $_GET['Usuario'])
					);

					if($UpdateUsuario['Status'] == 'OK'){
						/*Se regitra en el historial de contraseñas*/
						$QueryInsert = sprintf('INSERT INTO CelaHistoriaContrase_na (id, Usuario, Contrase_na, UltimoCambio) VALUES (%s, %s, %s, %s);',
							GetSQLValueString(NULL, 'int'),
							GetSQLValueString($_GET['Usuario'], 'int'),
							GetSQLValueString(md5($_POST['Contrase_na']), 'varchar'),
							GetSQLValueString(date('Y-m-d'), 'date')
						);

						if($ResultInsert = $Connection->query($QueryInsert)){
							$ArgsCelaActionMessage = array(
								'StatusMessage' => 'success',
								'IconMessage'   => 'fa-check',
								'TitleMessage'  => 'Contrase&ntilde;a reestablecida!',
								'TextMessage'   => 'La contrase&ntilde;a se reestabelcio correctamente, ahora puedes <a href="index">iniciar sesi&oacute;n</a> con tu nueva contrase&ntilde;a.'
							);
							$Content .= LoadContentPage(BASE_DIR . 'CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
						}
					}else{
						$ArgsCelaActionMessage = array(
							'StatusMessage' => 'danger',
							'IconMessage'   => 'fa-times',
							'TitleMessage'  => 'No se pudo reestablecer la contrase&ntilde;a!',
							'TextMessage'   => $UpdateUsuario['Error']
						);
						$Content .= LoadContentPage(BASE_DIR . 'CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
					}
				}else{
					/*Se carga la vista para reestablecer la contraseña*/
					$Content .= LoadContentPage(BASE_DIR . 'CelaUsuario/PassResetVistaConfirm.php', array('Usuario' => $_GET['User']));
				}
				break;
			case 'ChangePassword':
				$SessionUserId = GetValue('SELECT id FROM CelaUsuario WHERE CorreoElectr_onico = BINARY "' . $_POST['CorreoElectr_onico'] . '";', 'id');
				if($SessionUserId != 'NULL'){
					/*SE obtienen los datos de la persona*/
					$Persona = GetValue('SELECT * FROM CelaUsuario WHERE id = ' . $SessionUserId . ';');

					/*Se obtienene las configuraciones para envio de correo*/
					$QueryConf = 'SELECT Valor, Code FROM CelaConfiguraci_on;';
					$ResultConf = $Connection->query($QueryConf);
					$Conf = array();
					while($RecordConf = $ResultConf->fetch_assoc()){
						$Conf[$RecordConf['Code']] = $RecordConf['Valor'];
					}

					/*Se obtiene la configuración de los mensajes*/
					$DataAut['User'] = $Conf['uece'];
					$DataAut['Password'] = $Conf['cece'];
					$DataAut['Host'] = $Conf['hece'];
					$DataAut['Puerto'] = $Conf['pece'];

					$GlobalConfig = $Conf;

					/*Se notifica a la persona*/
					$To = array(
						'NombreCompleto' => $Persona['NombreCompleto'],
						'CorreoElectr_onico' => $Persona['CorreoElectr_onico']
					);
					$From = array(
						'NombreCompleto' => $Conf['uece'],
						'CorreoElectr_onico' => $Conf['uece']
					);

					/*Se genera el enlace para reestablecer la contraseña*/
					$RutaReestablece = HTTP_SERVER . 'PassReset?' . Encrypt('User=' . $SessionUserId . '&Action=ConfirmReset');

					/*Se notifica al usuario que se agrego un nuevo documento para firma*/
					$MessageResponse = UseMail(
						$To,
						$From,
						'prc',
						array(
							'<!--#NOMBRE_TO#-->',
							'<!--#ENLACE_RESET#-->'
						),
						array(
							$To['CorreoElectr_onico'],
							$RutaReestablece
						),
						$DataAut
					);

					if($MessageResponse['Status'] == 'OK'){
						$ArgsCelaActionMessage = array(
							'StatusMessage' => 'success',
							'IconMessage'   => 'fa-check',
							'TitleMessage'  => 'Enlace generado correctamente!',
							'TextMessage'   => 'Se gener&oacute; el enlace para reestablecimiento de contrase&ntilde;a, revisa tu correo electr&oacute;nico para completar el proceso.'
						);
						$Content .= LoadContentPage(BASE_DIR . 'CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
					}else{
						$ArgsCelaActionMessage = array(
							'StatusMessage' => 'danger',
							'IconMessage'   => 'fa-times',
							'TitleMessage'  => 'Ocurrio un error enviando el mensaje!',
							'TextMessage'   => $MessageResponse['Error']
						);
						$Content .= LoadContentPage(BASE_DIR . 'CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
					}
				}else{
					$ArgsCelaActionMessage = array(
						'StatusMessage' => 'danger',
						'IconMessage'   => 'fa-times',
						'TitleMessage'  => 'Ocurrio un error reestableciendo la contrase&ntilde;a!',
						'TextMessage'   => 'El correo elect&oacute;nico no existe, por favor asegurate de haberlo escrito de manera correcta y vulve a intentarolo'
					);
					$Content .= LoadContentPage(BASE_DIR . 'CelaTemplate/CelaActionMessage.php', $ArgsCelaActionMessage);
				}
				break;
		}
	}else{
		$Content .= LoadContentPage(BASE_DIR . 'CelaUsuario/PassResetVistaReset.php');
	}

	/*Se carga la vista del registro*/
	$Pagina = LoadContentPage(BASE_DIR . 'CelaUsuario/PassResetVista.php', $ArgsJoin);
	$Pagina = str_replace('<!--#Content#-->', $Content, $Pagina);

	print $Pagina;
?>