<?php
	if(file_exists('../Libraries/ExceptionThrower.php'))
		require_once('../Libraries/ExceptionThrower.php');
	function GetSQLValueString($Value, $Type, $DefinedValue = '', $NotDefinedValue = ''){
		$Value = ((!get_magic_quotes_gpc()) ? addslashes($Value):$Value);
		switch($Type){
			case 'text':
			case 'mediumtext':
			case 'binary':
			case 'varbinary':
			case 'varchar':
				$Value = ($Value != '') ? "'" . $Value . "'" : 'NULL';
				break;
			case 'date':
				$Value = ($Value != '') ? "'" . (date('Y-m-d', strtotime(str_replace('/', '-', $Value)))) . "'" : 'NULL';
				break;
			case 'timestamp' :
			case 'datetime':
				$Value = ($Value != '') ? "'" . (date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $Value)))) . "'" : 'NULL';
				break;
			case 'time':
				$Value = ($Value != '') ? "'" . (date('H:i:s', strtotime($Value))) . "'" : 'NULL';
				break;
			case 'long':
			case 'bit':
			case 'bool':
			case 'tinyint':
			case 'smallint':
			case 'mediumint':
			case 'longint':
			case 'int':
				$Value = ($Value != '') ? intval($Value) : 'NULL';
				break;
			case 'long unsigned':
			case 'bit unsigned':
			case 'bool unsigned':
			case 'tinyint unsigned':
			case 'smallint unsigned':
			case 'mediumint unsigned':
			case 'longint unsigned':
			case 'int unsigned':
				$Value = (($Value != '') ? abs(intval($Value)) : 'NULL');
				break;
			case 'double':
			case 'float':
			case 'decimal':
				$Value = ($Value != '') ? "'" . doubleval(str_replace(',', '', $Value)) . "'" : 'NULL';
				break;
			case 'decimal unsigned':
			case 'double unsigned':
			case 'float unsigned':
				$Value = (($Value != '') ? "'" . abs(doubleval(str_replace(',', '', $Value))) . "'" : 'NULL');
				break;
			case 'SQL':
				$Value = ($Value != '') ? '`' . str_replace(array('\'', '\\', ' '), '', $Value) . '`':'NULL';
				break;
			case 'defined':
				$Value = ($Value != '') ? $DefinedValue : $NotDefinedValue;
				break;
				break;
			default:
				$Value = ($Value != '') ? $DefinedValue : $NotDefinedValue;
				break;
		}
		return $Value;
	}

	function EncodeThis($String){
		if(is_array($String)){
			extract($String, EXTR_OVERWRITE);
		}
		$String     = utf8_encode($String);
		$Control    = '/*/' . $_COOKIE['CelaRandom'] . '/*/'; // genero un llave aleatoria para codificar por sesion...
		$String     = $Control . $String . $Control; //concateno la llave para encriptar la cadena
		$String     = Encrypt($String, 'b5s1i4t5a1316');
		$String     = base64_encode($String);//codifico la cadena
		return($String);
	}

	function EncodeThis2($String, $Random){
		if(is_array($String)){
			extract($String, EXTR_OVERWRITE);
		}
		$String     = utf8_encode($String);
		$Control    = '/*/' . $Random . '/*/'; // genero un llave aleatoria para codificar por sesion...
		$String     = $Control . $String . $Control; //concateno la llave para encriptar la cadena
		$String     = Encrypt($String, 'b5s1i4t5a1316');
		$String     = base64_encode($String);//codifico la cadena
		return($String);
	}

	function DecodeGet($String){
		if(is_array($String)){
			extract($String, EXTR_OVERWRITE);
		}
		$_GET       = NULL;
		$String     = substr(strrchr($String, '?'), 1); //Obtener la url desde el ?
		$String     = base64_decode($String); //decodifico la cadena
		$String     = Decrypt($String, 'b5s1i4t5a1316');
		$Control    = '/*/'. $_COOKIE['CelaRandom'] . '/*/'; //defino la llave con la que fue encriptada la cadena,

		if(substr_count($String, $Control) == 0){
			include '../CelaTemplate/CelaAccesoProhibido.php';
			exit();
		}

		$String = str_replace($Control, '', $String); //quito la llave de la cadena

		//procedo a dejar cada variable en el $_GET
		$GET = preg_split ('[&]', $String); //separo la url por &
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
	}

	function FillSelect($SQL, $Options = '', $Empty = 0){
		global $Connection;

		if(is_array($SQL)){
			extract($SQL, EXTR_OVERWRITE);
		}

		$Select = '<select name="' . $Options['Name'] . '" id="' . str_replace(array('[]', '[', ']'), '', $Options['Name']) . '" class="' . $Options['Class'] . '" ' . (isset($Options['Custom']) ? $Options['Custom']:"") . '>';
		if($Empty == 1)
			$Select .= '<option value="' . (isset($Options['EmptyValue']) ? $Options['EmptyValue']:'') . '">' . (isset($Options['EmptyMessage'])? $Options['EmptyMessage']:'SELECCIONE UNA OPCI&Oacute;N') . '</option>';

		if(is_array($SQL)){
			foreach ($SQL as $Index => $Value) {
				$Select .= '<option value="' . $Index . '">' . ($Value) . '</option>';
			}
		}else{
			if($Result = $Connection -> query($SQL)){
				while($Record = $Result -> fetch_row()){
					$Select .= '<option value="' . $Record[0] . '">' . ($Record[1]) . '</option>';
				}
			}else{
				print_r($Connection);
				print $SQL;
			}

		}
		$Select .= '</select>';

		return $Select;
	}

	function SFillSelect($SQL, $Options, $Selected, $Empty=0){
		//print $Selected;
		global $Connection;

		if(is_array($SQL)){
			extract($SQL, EXTR_OVERWRITE);
		}

		$Select = '<select name="' . $Options['Name'] . '" id="' . str_replace(array('[]', '[', ']'), '', $Options['Name']) . '" class="' . $Options['Class'] . '" ' . (isset($Options['Custom']) ? $Options['Custom']:'') . '>';

		if($Empty == 1)
			$Select .= '<option value="' . (isset($Options['EmptyValue']) ? $Options['EmptyValue']:'') . '">' . (isset($Options['EmptyMessage']) ? $Options['EmptyMessage']:'SELECCIONE UNA OPCI&Oacute;N') . '</option>';

		if(is_array($SQL)){
			foreach ($SQL as $Index => $Value) {
				if(is_array($Selected)){
					if(in_array($Index, $Selected))
						$Select .= '<option value="' . $Index . '" selected="selected">' . ($Value) . "</option>";
					else
						$Select .= '<option value="' . $Index .'">' . ($Value) . "</option>";
				}else{
					if($Index == $Selected)
						$Select .= '<option value="' . $Index . '" selected="selected">' . ($Value) . "</option>";
					else
						$Select .= '<option value="' . $Index . '">' . ($Value) . '</option>';
				}
			}
		}else{
			$Result = $Connection -> query($SQL);
			while($Record = $Result -> fetch_array()){
				if(is_array($Selected)){
					if(in_array($Record[0], $Selected))
						$Select .= '<option value="' . $Record[0] . '" selected="selected">' . ($Record[1]) . "</option>";
					else
						$Select .= '<option value="' .$Record[0] .'">' . ($Record[1]) . "</option>";
				}else{
					if($Record[0] == $Selected)
						$Select .= '<option value="' . $Record[0] . '" selected="selected">' . ($Record[1]) . "</option>";
					else
						$Select .= '<option value="' . $Record[0] . '">' .($Record[1]) . "</option>";
				}
			}
		}
		$Select .= '</select>';

		return $Select;
	}

	function GetValue($SQL, $Value = ''){
		global $Connection;

		if(is_array($SQL)){
			extract($SQL, EXTR_OVERWRITE);
		}

		if($Value == ''){
			if($Result = $Connection->query($SQL)){
				if($Result->num_rows == 0){
					return array('Result' => 'NULL');
				}

				$Record = $Result->fetch_assoc();
				$Record['Result'] = 'OK';

				return $Record;
			}else{
				$Record['Result'] = 'ERROR';
				$Record['Error'] =  $Connection -> error;
			}
		}else{
			if($Result = $Connection->query($SQL)){

				if($Result->num_rows == 0){
					$Value = 'NULL';
				}else{
					$Record = $Result->fetch_assoc();
					$Value = $Record[$Value];
				}
				return $Value;
			}else{
				return $Connection -> error;
			}
		}
	}

	function UpdateValue($Table, $ArgsUpdate = null, $KeyUpdate = null, $KeyOperator = 'AND'){
		global $Connection;

		if(is_array($Table)){
			extract($Table, EXTR_OVERWRITE);
		}

		$FieldList = '';
		$WhereList = '';
		foreach ($ArgsUpdate as $Field => $Value) {
			$FieldList .=   sprintf(' %s = %s, ',
								GetSQLValueString($Field, 'SQL'),
								$Value
							);
		}

		foreach ($KeyUpdate as $Field => $Value) {
			$WhereList .=   sprintf(' %s = %s ' . $KeyOperator . ' ',
								$Field,
								$Value
							);
		}

		$FieldList = substr_replace($FieldList, '', -2);
		$WhereList = substr_replace($WhereList, '', -(strlen($KeyOperator) + 2));

		$UpdateQuery =  sprintf('UPDATE %s SET %s WHERE %s;',
							GetSQLValueString($Table, 'SQL'),
							$FieldList,
							$WhereList
						);

		//print '<pre>' . $UpdateQuery . '</pre>';

		if($UpdateResult = $Connection -> query($UpdateQuery)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error'] = $Connection -> error;
		}

		return $Data;
	}

	function GetFormPrivileges($Form, $Access = '', $Source = ''){
		global $Connection;

		if(is_array($Form)){
			extract($Form, EXTR_OVERWRITE);
		}

		$Privileges         = array();
		$Found              = 0;

		$PrivilegesQuery =  sprintf('SELECT
										cp1.Nombre
									 FROM CelaPrivilegios cp
										INNER JOIN CelaFormulario cf ON (cp.Tupla=cf.id)
										INNER JOIN CelaPrivilegio cp1 ON (cp.Privilegio=cp1.id)
									 WHERE
										cp.Origen = %s AND
										cp.TuplaAcceso = %s AND
										cf.Ruta = %s;',
								GetSQLValueString($Source, 'int'),
								GetSQLValueString($Access, 'int'),
								GetSQLValueString($Form, 'varchar')
							);

		//print $PrivilegesQuery;
		$PrivilegesResult = $Connection -> query($PrivilegesQuery);
		while($PrivilegesRecord = $PrivilegesResult -> fetch_array()){
			$PrivilegesFound[$PrivilegesRecord[0]] = 1;
			$Found = 1;
		}

		if($Found == 0){
			$Admin =    strtoupper(
							GetValue(
								sprintf('SELECT Nombre FROM CelaRol WHERE `id` = %s;',
									GetSQLValueString($Access, 'varchar')
								),
								'Nombre'
							)
						);

			if($Admin == 'DEVELOPER' || $Admin == 'DESARROLLADOR'){
				$PrivilegesQuery        = 'SELECT Nombre FROM CelaPrivilegio;';
				$PrivilegesResult       = $Connection -> query($PrivilegesQuery);
				while($PrivilegesRecord = $PrivilegesResult -> fetch_array()){
					$Privileges[$PrivilegesRecord[0]] = 1;
				}
			}
		}else{
			$PrivilegesQuery        = 'SELECT Nombre FROM CelaPrivilegio;';
			$PrivilegesResult       = $Connection -> query($PrivilegesQuery);
			while($PrivilegesRecord = $PrivilegesResult -> fetch_array()){
				if(isset($PrivilegesFound[$PrivilegesRecord[0]]) && $PrivilegesFound[$PrivilegesRecord[0]] == 1){
					$Privileges[$PrivilegesRecord[0]] = 1;
				}
				//else{
				//	$Privileges[$PrivilegesRecord[0]] = 0;
				//}
			}
		}

		return $Privileges;
	}

	function CleanerString($String){
		if(is_array($String)){
			extract($String, EXTR_OVERWRITE);
		}

		$String = trim($String);
		$String = strtr($String, '‚àö√Ñ‚àö√Ö‚àö√á‚àö√â‚àö√ë‚àö√ñ‚àö‚Ä†‚àö¬∞‚àö¬¢‚àö¬£‚àö¬ß‚àö‚Ä¢‚àö√≠‚àö√¨‚àö√Æ‚àö√Ø‚àö√±‚àö√≤‚àö‚â§‚àö‚â•‚àö¬•‚àö¬µ‚ àö‚àÇ‚àö‚àè‚àö√†‚àö√¢‚àö√§‚àö√£‚àö¬Æ‚àö¬©‚àö‚Ñ¢‚àö¬¥‚àö√°‚àö√ü‚àö√•‚àö√ß‚àö√©‚àö√®‚àö¬®‚àö‚â†‚àö√Ü‚àö√ò‚àö√¥‚àö√∂‚àö√µ‚àö√∫‚àöœÄ‚ àö‚à´‚àö¬™‚àö¬∫‚àö√∏‚àö√´‚àö¬±","aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn');
		$String = strtr($String, "ABCDEFGHIJKLMNOPQRSTUVWXYZ","abcdefghijklmnopqrstuvwxyz");
		$String = preg_replace('#([^.a-z0-9]+)#i', '_', $String);
		$String = preg_replace('#-{2,}#', '_', $String);
		$String = preg_replace('#-$#', '', $String);
		$String = preg_replace('#^-#', '', $String);
		return $String;
	}

	function DecodeString($String){
		if(is_array($String)){
			extract($String, EXTR_OVERWRITE);
		}
		$Letra['n'] = "&ntilde;";
		$Letra['N'] = "&Ntilde;";
		$Letra['a'] = "&aacute;";
		$Letra['e'] = "&eacute;";
		$Letra['i'] = "&iacute;";
		$Letra['o'] = "&oacute;";
		$Letra['u'] = "&uacute;";
		$Letra['A'] = "&Aacute;";
		$Letra['E'] = "&Eacute;";
		$Letra['I'] = "&Iacute;";
		$Letra['O'] = "&Oacute;";
		$Letra['U'] = "&Uacute;";
		for($i = 0; $i < strlen($String); $i++){
			if($String[$i] == '_'){
				$String = str_replace($String[$i] . $String[$i+1], $Letra[$String[$i+1]], $String);
			}
			if(ctype_upper($String[$i]) && ctype_lower($String[$i > 0 ? $i-1:$i])){
				$String = str_replace($String[$i], ' '.$String[$i], $String);
			}
		}

		$Search     = ' De '; // Reemplazar De
		$Replace    = ' de ';
		$String     = str_replace($Search, $Replace, $String);

		$Search     = ' Un '; // Reemplazar Un
		$Replace    = ' un ';
		$String     = str_replace($Search, $Replace, $String);

		$Search     = ' Del '; // Reemplazar De
		$Replace    = ' del ';
		$String     = str_replace($Search, $Replace, $String);

		return $String;
	}

	function CreateFile($SourceFile, $FileName = '', $Uploaded = true, $EndFolder = '', $DelOld = true, $BaseDir = ''){
		if(is_array($SourceFile)){
			extract($SourceFile, EXTR_OVERWRITE);
		}
		//Vemos si la hubicacion del archivo existe.
		if ( $SourceFile != 'none' ){
			$ExtensionArray = explode('.', $FileName);
			$Extension      = end($ExtensionArray);

			//Ponemos el nombre de timestamp al archivo.
			$FalseName  = date('ymdHis') . substr((string) microtime(), 1, 8);
			$FalseName  = Encrypt($FalseName, 'b5s1i4t5a1316');

			//Comprobamos y creamos el arbol de directorios para los archivos.
			$Year       = date('Y');
			$Month      = date('F');

			$Source     = $BaseDir . 'repositorio/' . $Year . '/' . $Month . $EndFolder;

			//nos aseguramos que exista la ruta del archivo
			if(!file_exists($Source)){
				mkdir($BaseDir . 'repositorio/' . $Year . '/' . $Month . $EndFolder, 0755, true);
			}

			if(!file_exists($Source.'/index.php')){
				CreateIndexFile($Source . '/');
			}

			$Source = $BaseDir . 'repositorio/' . $Year . '/' . $Month  . $EndFolder . '/' . str_replace(array('/', '=', '.', '+'), '', $FalseName) . '.' . str_replace(array('/', '=', '.', '+'), '', $Extension);

			if($Uploaded === true){
				/*Se crea un archivo desde archivos temporales de subida*/
				move_uploaded_file($SourceFile, $Source);
			}else{
//				/*Se crea un archivo desde la ruta especificada*/
//				print 'Origen: ' . $Origin     = realpath($SourceFile);
//				print 'Destino: ' . $Destiny    = realpath($Source);
//
//				rename($Origin, $Destiny);

				/*Se crea un archivo desde la ruta especificada*/
				$Origin     = ($SourceFile);
				$Destiny    = ($Source);

				$Copy = copy($Origin, $Destiny);

				if($Copy !== false && $DelOld === true){
					unlink($Origin);
				}
			}
			
			if(!file_exists($Source)){
				$Source = false;
			}

			return str_replace($BaseDir, '', $Source);
		}
	}

	function RecordLog($Table, $Record = null, $Action = null, $UserId = null, $LogData = array('Data' => 'No data')){
		global $Connection;

		if(is_array($Table)){
			/*Se extraen las variables que bienen en table*/
			extract($Table, EXTR_OVERWRITE);
		}

		$QueryLog = sprintf('INSERT INTO
								CelaAcceso
								( `id`, `Fecha`, `Usuario`, `Origen`, `Tupla`, `Acci_on`, `Datos`)
							 VALUES ( %s, %s, %s, %s, %s, %s, %s );',
						GetSQLValueString(NULL, "int"),
						GetSQLValueString(date('Y-m-d H:i:s'), 'varchar'),
						GetSQLValueString($UserId, 'int'),
						GetSQLValueString($Table, 'varchar'),
						GetSQLValueString($Record, 'varchar'),
						GetSQLValueString($Action, 'int'),
						GetSQLValueString(json_encode($LogData), 'varchar')
					);

		/**
		print $QueryLog;
		/**/

		if($ResultadoLog = $Connection -> query($QueryLog)){
			$Data['Status'] = 'OK';
		}else{
			$Data['Status'] = 'ERROR';
			$Data['Error']  = $Connection -> error;
		}

		return $Data;
	}

	function Decrypt($String, $Key = 'b5s1i4t5a1316') {
		if(is_array($String)){
			extract($String, EXTR_OVERWRITE);
		}

		$Result         = '';
		$String         = base64_decode($String);
		for($i = 0; $i < strlen($String); $i++) {
			$Char       = substr($String, $i, 1);
			$KeyChar    = substr($Key, ($i % strlen($Key)) - 1, 1);
			$Char       = chr(ord($Char) - ord($KeyChar));
			$Result     .= $Char;
		}
		return $Result;
	}

	function Encrypt($String, $Key = 'b5s1i4t5a1316') {
		if(is_array($String)){
			extract($String, EXTR_OVERWRITE);
		}

		$Result = '';
		for($i=0; $i < strlen($String); $i++) {
			$Char     = substr($String, $i, 1);
			$KeyChar  = substr($Key, ($i % strlen($Key)) - 1, 1);
			$Char     = chr(ord($Char) + ord($KeyChar));
			$Result   .= $Char;
		}
		return base64_encode($Result);
	}

	function MysqlClear($String){
		if(is_array($String)){
			extract($String, EXTR_OVERWRITE);
		}

		$String = trim($String);

		$String = str_replace(
	        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä','Ã', 'Ã¡', '&Aacute;', '&aacute;'),
	        '%',
			$String
	    );

		$String = str_replace(
	        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë', 'Ã‰', 'Ã©', '&Eacute;', '&eacute;'),
	        '%',
			$String
	    );

		$String = str_replace(
	        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î', 'Ã', 'Ã­', '&Iacute;', '&iacute;'),
	        '%',
			$String
	    );

		$String = str_replace(
	        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô', 'Ã“', 'Ã³', '&Oacute;', '&oacute;'),
	       '%',
			$String
	    );

	    $string = str_replace(
	        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü', 'Ãš', 'Ãº', '&Uacute;', '&uacute;'),
	        '%',
		    $String
	    );

		$String = str_replace(
	        array('ñ', 'Ñ', 'ç', 'Ç', '&Ntilde;', '&ntilde;'),
	        '%',
			$String
	    );

	    //Esta parte se encarga de eliminar cualquier caracter extraño
		$String = str_replace(
	        array("\\", "¨", "º", "~",
	             "#", "@", "|", "!",
	             "·", "$", "&", "/",
	             "(", ")", "?", "¡",
	             "¿", "[", "^", "`", "]",
	             "+", "}", "{", "¨", "´",
	             ">", "<", ";", ",", ":"),
	        '%',
			$String
	    );

	    return $String;
	}

	function CreateIndexFile($Ruta){
		if(is_array($Ruta)){
			extract($Ruta, EXTR_OVERWRITE);
		}

		$Contenido =    '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
    <title>403 &mdash; Forbidden</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="description" content="You do not have permission to view this"/>
    <style type="text/css">
        body{
            font-size:14px;
            color:#777777;
            font-family:arial;
            text-align:center;
        }
        h1{
            font-size:180px;
            color:#99A7AF;
            margin: 70px 0 0 0;
        }
        h2{
            color: #DE6C5D;
            font-family: arial;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: -1px;
            margin: -3px 0 39px;
        }
        p{
            width:320px;
            text-align:center;
            margin-left:auto;
            margin-right:auto;
            margin-top: 30px
        }
        div{
            width:400px;
            text-align:center;
            margin-left:auto;
            margin-right:auto;
        }
        a:link{
            color: #34536A;
        }
        a:visited{
            color: #34536A;
        }
        a:active{
            color: #34536A;
        }
        a:hover{
            color: #34536A;
        }
    </style>
</head>

<body>
    <h1>403</h1>
    <h2>Prohibido</h2>
    <h2>Desafortunadamente, no tienes permiso para ver esta parte del sitio</h2>
    <div>
        Puedes intentar ir al <a href="Escritorio" >Escritorio</a>.
    </div>
</body>

</html>';

		$RutaActual     = $Ruta;
		for($i = 0; $i < substr_count($Ruta, '/') ; $i++){
			$RutaActual = substr($RutaActual, 0, strrpos($RutaActual,'/'));
			if(!file_exists($RutaActual . '/index.php')){
				//Escribimos el archivo.
				$NewFile = fopen($RutaActual . '/index.php', 'wb');
				fwrite($NewFile, $Contenido);
				fclose($NewFile);
			}
		}
	}

	function Rrmdir($Dir){
		if(is_array($Dir)){
			extract($Dir, EXTR_OVERWRITE);
		}

		if($Dir != ''){
			if(is_dir($Dir)) {
				$Object = scandir($Dir);
				foreach ($Object as $Object) {
					if($Object != '.' && $Object != '..') {
						if(filetype($Dir . '/' . $Object) == 'dir')
							Rrmdir($Dir . '/' . $Object); else
							unlink($Dir . '/' . $Object);
					}
				}
				reset($Object);
				rmdir($Dir);
			}
		}
	}

	function TimeStart() {
		global $StartTime;
		$MTime      = microtime();
		$MTime      = explode(' ', $MTime);
		$MTime      = $MTime[1] + $MTime[0];
		$StartTime  = $MTime;
	}

	function TimeEnd() {
		global $StartTime;
		$MTime = microtime();
		$MTime = explode(' ', $MTime);
		$MTime = $MTime[1] + $MTime[0];

		return ($MTime - $StartTime);
	}

	function LoadTemplatePage($Page){
		if(is_array($Page)){
			extract($Page, EXTR_OVERWRITE);
		}

		return file_get_contents($Page);
	}

	function LoadContentPage($File, $Args = array('NoValue' => ''), $Privileges = false){
		ExceptionThrower::Start();
		try{
			global $Connection;
			global $GlobalConfig;
			global $SessionUserId;
			if(is_array($File)){
				extract($File, EXTR_OVERWRITE);
			}else{
				extract($Args, EXTR_OVERWRITE);
			}


			if($Privileges === false)
				global $Privileges;

			if($File == '' || !file_exists($File))
				throw new ErrorException('El nombre del archivo a cargar no es correcto o no existe, error cargando ' . $File, 0);

			ob_start();
			include $File;
			return ob_get_clean();
		} catch (Exception $e) {
			$Error = $e -> getMessage() . ' in line ' . $e -> getLine() . ';<br />' . $e -> getTraceAsString() . '.<br /> for more details see error_log.log';

//			$Debug =    GetValue(
//							sprintf('SELECT Valor FROM CelaConfiguraci_on WHERE Nombre = %s;',
//								GetSQLValueString('DebugMode', 'varchar')),
//							'Valor'
//						);
			$Debug =   $GlobalConfig['DebugMode'];

			/*Se guarda el error en el log del sistema.*/
			if(!file_exists('../document_errors')){
				mkdir('../document_errors', 0755, true);
			}

			$File   = fopen('../document_errors/error_log.log', 'a+');
			fwrite($File,  '[' . date('Y-m-d H:i:s') . ']' . PHP_EOL . print_r($e, TRUE) . PHP_EOL);

			fclose($File);

			if($Debug == 1){
				$ArgsCelaActionsMessage = array(
					'StatusMessage' => 'danger',
					'IconMessage'   => 'fa-times',
					'TitleMessage'  => 'Oops!... Ocurrio un error cargando la pagina',
					'TextMessage'   => $Error
				);

				$Message    = LoadContentPage('../CelaTemplate/CelaActionMessage.php', $ArgsCelaActionsMessage);

				return $Message;
			}
		}
		ExceptionThrower::Stop();
	}

	function DestroySession(){
		/*if(!isset($_SESSION)){
			session_start();
		}*/

		setcookie('idUsuario', '', time() - 3600, '/');
		setcookie('CelaRandom', '', time() - 3600, '/');

		/*session_destroy();
		$CookiesParams  = session_get_cookie_params();
		setcookie(session_name(), 0, 1, $CookiesParams['path']);*/
	}

	function ReplaceContentPage($Etiqueta, $Salida = false, $Pagina = false) {
		if(is_array($Etiqueta)){
			extract($Etiqueta, EXTR_OVERWRITE);
		}

		return str_replace($Etiqueta, $Salida, $Pagina);
	}

	function ViewPage($HTML) {
		if(is_array($HTML)){
			extract($HTML, EXTR_OVERWRITE);
		}

		echo $HTML;
	}

	function RealToString($num, $fem = true, $dec = true) {
		if(is_array($num)){
			extract($num, EXTR_OVERWRITE);
		}

		$matuni[2]  = "dos";
		$matuni[3]  = "tres";
		$matuni[4]  = "cuatro";
		$matuni[5]  = "cinco";
		$matuni[6]  = "seis";
		$matuni[7]  = "siete";
		$matuni[8]  = "ocho";
		$matuni[9]  = "nueve";
		$matuni[10] = "diez";
		$matuni[11] = "once";
		$matuni[12] = "doce";
		$matuni[13] = "trece";
		$matuni[14] = "catorce";
		$matuni[15] = "quince";
		$matuni[16] = "dieciseis";
		$matuni[17] = "diecisiete";
		$matuni[18] = "dieciocho";
		$matuni[19] = "diecinueve";
		$matuni[20] = "veinte";
		$matunisub[2] = "dos";
		$matunisub[3] = "tres";
		$matunisub[4] = "cuatro";
		$matunisub[5] = "quin";
		$matunisub[6] = "seis";
		$matunisub[7] = "sete";
		$matunisub[8] = "ocho";
		$matunisub[9] = "nove";

		$matdec[2] = "veint";
		$matdec[3] = "treinta";
		$matdec[4] = "cuarenta";
		$matdec[5] = "cincuenta";
		$matdec[6] = "sesenta";
		$matdec[7] = "setenta";
		$matdec[8] = "ochenta";
		$matdec[9] = "noventa";
		$matsub[3]  = 'mill';
		$matsub[5]  = 'bill';
		$matsub[7]  = 'mill';
		$matsub[9]  = 'trill';
		$matsub[11] = 'mill';
		$matsub[13] = 'bill';
		$matsub[15] = 'mill';
		$matmil[4]  = 'millones';
		$matmil[6]  = 'billones';
		$matmil[7]  = 'de billones';
		$matmil[8]  = 'millones de billones';
		$matmil[10] = 'trillones';
		$matmil[11] = 'de trillones';
		$matmil[12] = 'millones de trillones';
		$matmil[13] = 'de trillones';
		$matmil[14] = 'billones de trillones';
		$matmil[15] = 'de billones de trillones';
		$matmil[16] = 'millones de billones de trillones';

		$num = trim((string)@$num);
		if ($num[0] == '-') {
			$neg = 'menos ';
			$num = substr($num, 1);
		}else
			$neg = '';
		while ($num[0] == '0') $num = substr($num, 1);
		if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
		$zeros = true;
		$punt = false;
		$ent = '';
		$fra = '';
		for ($c = 0; $c < strlen($num); $c++) {
			$n = $num[$c];
			if (! (strpos(".,'''", $n) === false)) {
				if ($punt) break;
				else{
					$punt = true;
					continue;
				}

			}elseif (! (strpos('0123456789', $n) === false)) {
				if ($punt) {
					if ($n != '0') $zeros = false;
					$fra .= $n;
				}else

					$ent .= $n;
			}else
				break;
		}
		$ent = '     ' . $ent;
		if ($dec and $fra and ! $zeros) {
			$fin = ' punto';
			for ($n = 0; $n < strlen($fra); $n++) {
				if (($s = $fra[$n]) == '0')
					$fin .= ' cero';
				elseif ($s == '1')
					$fin .= $fem ? ' una' : ' un';
				else
					$fin .= ' ' . $matuni[$s];
			}
		}else
			$fin = '';
		if ((int)$ent === 0) return 'Cero ' . $fin;
		$tex = '';
		$sub = 0;
		$mils = 0;
		$neutro = false;
		while ( ($num = substr($ent, -3)) != '   ') {
			$ent = substr($ent, 0, -3);
			if (++$sub < 3 and $fem) {
				$matuni[1] = 'una';
				$subcent = 'as';
			}else{
				$matuni[1] = $neutro ? 'un' : 'uno';
				$subcent = 'os';
			}
			$t = '';
			$n2 = substr($num, 1);
			if ($n2 == '00') {
			}elseif ($n2 < 21)
				$t = ' ' . $matuni[(int)$n2];
			elseif ($n2 < 30) {
				$n3 = $num[2];
				if ($n3 != 0) $t = 'i' . $matuni[$n3];
				$n2 = $num[1];
				$t = ' ' . $matdec[$n2] . $t;
			}else{
				$n3 = $num[2];
				if ($n3 != 0) $t = ' y ' . $matuni[$n3];
				$n2 = $num[1];
				$t = ' ' . $matdec[$n2] . $t;
			}
			$n = $num[0];
			if ($n == 1) {
				$t = ' ciento' . $t;
			}elseif ($n == 5){
				$t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
			}elseif ($n != 0){
				$t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
			}
			if ($sub == 1) {
			}elseif (! isset($matsub[$sub])) {

				if ($num == 1) {
					$t = ' mil';
				}elseif ($num > 1){
					$t .= ' mil';
				}
			}elseif ($num == 1) {
				$t .= ' ' . $matsub[$sub] . 'on';
			}elseif ($num > 1){
				$t .= ' ' . $matsub[$sub] . 'ones';
			}
			if ($num == '000') $mils ++;
			elseif ($mils != 0) {
				if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
				$mils = 0;
			}
			$neutro = true;
			$tex = $t . $tex;
		}
		$tex = $neg . substr($tex, 1) . $fin;
		return ucfirst($tex);
	}

	function get_browser_(){
		$browser=array("IE","OPERA","MOZILLA","NETSCAPE","FIREFOX","SAFARI","CHROME");
		$os=array("WIN","MAC","LINUX");

		// definimos unos valores por defecto para el navegador y el sistema operativo
		$info['browser'] = "OTHER";
		$info['os'] = "OTHER";

		// buscamos el navegador con su sistema operativo
		foreach($browser as $parent)
		{
			$s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
			$f = $s + strlen($parent);
			$version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
			$version = preg_replace('/[^0-9,.]/','',$version);
			if ($s)
			{
				$info['browser'] = $parent;
				$info['version'] = $version;
			}
		}

		// obtenemos el sistema operativo
		foreach($os as $val)
		{
			if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']),$val)!==false)
				$info['os'] = $val;
		}

		// devolvemos el array de valores
		return $info['browser'];
	}

	function UseMail($To, $From, $Template, $Tags, $Replace){
		global $GlobalConfig;
		$mail = new PHPMailer\PHPMailer\PHPMailer();

		/*Seleccionamos el metodo SMTP*/
		$mail->isSMTP();

		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => false
			)
		);

		/*
		 * Enable SMTP debugging
		 * 0 = off (for production use)
		 * 1 = client messages
		 * 2 = client and server messages
		 */
		$mail->SMTPDebug = 0;

		/*Se obtiene la configuración de los mensajes*/
		$DataAut = array(
			'User' => '',
			'Password' => '',
			'Host' => '',
			'Puerto' => ''
		);
//		$DataAut['User'] = GetValue(
//			sprintf('SELECT Valor FROM CelaConfiguraci_on WHERE Nombre = %s;',
//				GetSQLValueString('UsuarioCorreoAlmac_en', 'varchar')
//			),
//			'Valor'
//		);
		$DataAut['User'] = $GlobalConfig['uece'];

//		$DataAut['Password'] = GetValue(
//			sprintf('SELECT Valor FROM CelaConfiguraci_on WHERE Nombre = %s;',
//				GetSQLValueString('PasswordCorreoAlmac_en', 'varchar')
//			),
//			'Valor'
//		);
		$DataAut['Password'] = $GlobalConfig['cece'];

//		$DataAut['Host'] = GetValue(
//			sprintf('SELECT Valor FROM CelaConfiguraci_on WHERE Nombre = %s;',
//				GetSQLValueString('HostCorreo', 'varchar')
//			),
//			'Valor'
//		);
		$DataAut['Host'] = $GlobalConfig['hece'];

//		$DataAut['Puerto'] = GetValue(
//			sprintf('SELECT Valor FROM CelaConfiguraci_on WHERE Nombre = %s;',
//				GetSQLValueString('PuertoCorreo', 'varchar')
//			),
//			'Valor'
//		);
		$DataAut['Puerto'] = $GlobalConfig['pece'];

		/*Seleccionamos que se autorice el SMTP*/
		$mail->SMTPAuth = true;
		/*Hostname por default*/
		$mail->Host = $DataAut['Host'];
		/*Puerto SMTP*/
		$mail->Port = $DataAut['Puerto'];
		/*Usuario SMTP*/
		$mail->Username = $DataAut['User'];
		$mail->Password = $DataAut['Password'];

		/*Seleccionamos el FROM*/
		if(!is_array($From)){
			$From = GetValue(
				sprintf('SELECT NombreCompleto, CorreoElectr_onico FROM CelaUsuario WHERE id = %s;',
					GetSQLValueString($From, 'int')
				)
			);
		}

		$mail->setFrom($DataAut['User'], $From['NombreCompleto']);
//		$mail->setFrom('contacto@ambet.com.mx', 'demo');

		/*Seleccionamos el reply*/
		$mail->addReplyTo($From['CorreoElectr_onico'], $From['NombreCompleto']);

		/*Seleccionamos el destinatario*/
		if(!is_array($To)){
			$To = GetValue(
				sprintf('SELECT NombreCompleto, CorreoElectr_onico FROM CelaUsuario WHERE id = %s;',
					GetSQLValueString($To, 'int')
				)
			);
		}

		if((!isset($To['CorreoElectr_onico'])) || (isset($To['CorreoElectr_onico']) && (empty($To['CorreoElectr_onico']) || $To['CorreoElectr_onico'] == '')) || (isset($To['Result']) && $To['Result'] == 'NULL')){
			return array(
				'Status' => 'Error',
				'Error' => 'No se pud&oacute; notificar al usuario que gener&oacute; la Solicitud porque el correo no existe.'
			);
		}

//		print_r($To);

		$mail->addAddress($To['CorreoElectr_onico'], $To['NombreCompleto']);
//		$mail->addAddress('vzarmij@gmail.com', 'Mijail Vazquez Arellanes');

		/*Se obtienen los datos de la plantilla*/
		if(!is_array($Template)){
//			$Template = GetValue(
//				sprintf('SELECT Valor FROM CelaConfiguraci_on WHERE Nombre = %s;',
//					GetSQLValueString($Template ,'varchar')
//				), 'Valor'
//			);

			$Template = $GlobalConfig[$Template];

			/*Se obtiene la plantilla*/
			$TemplateMail = GetValue(
				sprintf('SELECT * FROM Plantilla  WHERE id = %s;',
					GetSQLValueString($Template, 'int')
				)
			);
		}

		/*Se genera el mensajes segun la plantilla*/
		$mail->Subject = $TemplateMail['Nombre'];
		$Mensaje = ReplaceContentPage(
			$Tags,
			$Replace,
			$TemplateMail['Plantilla']
		);
		$mail->msgHTML($Mensaje);
		$mail->AltBody = 'This is a html-text message body';

		$mail->ContentType = 'text/html';
		$mail->CharSet = 'UTF-8';

		/*S envia el mensaje*/
		if (!$mail->send()) {
			$Data = array(
				'Status' => 'Error',
				'Error' => $mail -> ErrorInfo
			);
		} else {
			$Data = array(
				'Status' => 'OK'
			);
		}

		return $Data;
	}

	function ValidPassword($Value){
		if(is_array($Value)){
			extract($Value, EXTR_OVERWRITE);
		}

		global $SessionUserId;

		$Response = GetValue(sprintf('SELECT 1 as response FROM CelaHistoriaContrase_na WHERE Contrase_na = %s AND Usuario = %s;', GetSQLValueString($Password, 'varchar'), GetSQLValueString($SessionUserId, 'int')), 'response');

		if($Response == 'NULL'){
			/*Se compara la contraseña actual*/
			$Response = GetValue(sprintf('SELECT id FROM CelaUsuario WHERE id = %s AND Contrase_na = %s;', GetSQLValueString($SessionUserId, 'int'), GetSQLValueString($Password, 'varchar')), 'id');
		}

		return $Response == 'NULL' ? 'true':'false';
	}
	
	function GetQueryValues($Query){
        global $Connection;
        
        if(is_array($Key)){
            /*Se extraen las variables si vienen en un arreglo*/
            extract($Key, EXTR_OVERWRITE);
        }
        
        $ProcesoQuery = $Query;
        
        if($ProcesoResult = $Connection -> query($ProcesoQuery)){
            $Procesos = array();
            while($ProcesoRecord = $ProcesoResult -> fetch_assoc()){
                $Procesos[] = $ProcesoRecord;
            }
            
            $Data = array(
                'Status' => 'OK',
                'Data' => $Procesos
            );
        }else{
            $Data = array(
                'Status'    => 'ERROR',
                'Error'     => $Connection -> error
            );
        }
        
        return $Data;
    }

	function LoadEnv($archivo) {
		if (!file_exists($archivo)) {
			throw new Exception("No se encontró el archivo .env");
		}

		$variables = parse_ini_file($archivo);

		if ($variables === false) {
			throw new Exception("No se pudo leer el archivo .env");
		}

		return $variables;
	}
?>
