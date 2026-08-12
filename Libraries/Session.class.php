<?php
	/**
	 * @package   CelaSession
	 * Clase para el manejo de sesiones en los sistemas con la Estrucutra de tabajo CELA.
	 * @author    Eumir Esteban Salgado Lampart.
	 * @copyright EEE de México SA de CV
	 * @version   1.0.0
	 * User: Eumir
	 * Date: 21/09/15
	 * Time: 11:23 PM
	 */

	/**
	 * Estructura de la tabla para el manejo de la clase en MySQL
	 * CREATE TABLE SeguroPopular.CelaSession (
	 * id                   int UNSIGNED NOT NULL  AUTO_INCREMENT,
	 * Usuario            int  NOT NULL  ,
	 * Nombre               varchar(64)  NOT NULL DEFAULT '' ,
	 * Valor                varchar(64)  NOT NULL DEFAULT '' ,
	 * Tipo                 varchar(32)  NOT NULL DEFAULT 'int' ,
	 * CONSTRAINT pk_CelaSession PRIMARY KEY ( id )
	 * ) COMMENT = 'Variables de Sesion';
	 *Los tipos de datos son los formatos en que se van a convertir los valores almacenados en la variable.

	 */

	// Formatear todas las consultas con sprintf

	/**
	 * The Definition of undefined data type.
	 * @var $TYPE_UNDEFINED
	 */
	if(!defined('TYPE_UNDEFINED'))
		define('TYPE_UNDEFINED', 'UNDEFINED');

	/**
	 * The Definition of undefined data type.
	 * @var $DB_UNDEFINED
	 */
	if(!defined('DB_UNDEFINED'))
		define('DB_UNDEFINED', 'UNDEFINED');

	/**
	 * Clase para le manejo de variables de sesión en la base de datos.
	 * @package
	 * @subpackage classes
	 */
	class CelaSession{
		/**
		 * The Id User value
		 * @var $User
		 */
		var $User;

		/**
		 * The Cookie Id value
		 * @var $User
		 */
		var $Cookie;

		/**
		 * The Connection database intance.
		 * @var $Connection
		 */
		var $Connection;

		/**
		 * The cempleted variables of the class.
		 * @var $Connection
		 */
		var $Ready;

		/**
		 * Class constructor.<br />
		 * Because the class uses sessions, this will attempt to start a session object with NULL values.<br />
		 * <code>
		 *   $SystemSession = new CelaSession();
		 * </code>
		 *
		 * @param User
		 * @param Cookie
		 */
		function CelaSession($User = NULL, $Cookie = NULL, $Connection = NULL){
			$this -> User         = $User;
			$this -> Cookie       = $Cookie;
			$this -> Connection   = $Connection;
			$this -> Ready        = false;
			$this -> SetReady();
		}

		/**
		 * Asigna el usuario de trabajo a la sesión.
		 * @method     void SetUser(int);
		 * @uses       CelaSession  ->  SetUser(int $User);
		 */
		function SetUser($User){
			$this -> User = $User;
			$this -> SetReady();
		}

		/**
		 * Asigna el usuario de trabajo a la sesión.
		 * @method     void SetUser(int);
		 * @uses       CelaSession  ->  SetUser(int $User);
		 */
		function SetCookie($Cookie){
			$this -> Cookie = $Cookie;
			$this -> SetReady();
		}

		/**
		 * Asigna el usuario de trabajo a la sesión.
		 *
		 * @param      Connection
		 *
		 * @uses       CelaSession  ->  Setconnection($Connection);
		 */
		function SetConnection($Connection = DB_UNDEFINED){
			$this -> Connection = $Connection;
			$this -> SetReady();
		}

		/**
		 * Asigna el usuario de trabajo a la sesión.
		 * @method     void SetUser(int);
		 * @uses       CelaSession  ->  SetUser(int $User);
		 * @return     boolean Indica si se realizó la eliminación de la variable de sesión.
		 */
		function SetReady(){
			if($this -> User && $this -> Cookie && $this -> Connection)
				$this -> Ready = true;
		}

		/**
		 * Asigna el usuario de trabajo a la sesión.
		 * @method     void SetUser(int);
		 * @uses       CelaSession  ->  SetUser(int $User);
		 * @return     boolean Indica si se realizó la eliminación de la variable de sesión.
		 */
		function IsReady(){
			return $this -> Ready;
		}

		/**
		 * Inicia la sesión para le usuario activo.
		 * @method     void Iniciar();
		 * @uses       CelaSession  ->  Start( );
		 * @uses       iniciar las variables de sesión en la base de datos..
		 *             Se definen como variables básicas del control de la sesión las siguientes:
		 *             Inicial. Indica en formato fecha Hora el inicio de la sesión para el usuario actual
		 *             Actual. Indica el tiempo mas reciente de refresco de la sesión actual.
		 *             Caducidad. Indica el tiempo en que será caducada la sesión del usuario actual.
		 *             Bloqueo. Indica el tiempo a partir del cuel el sistema muestra una pantalla de bloquero.
		 */
		function Start(){
			if($this -> IsReady()){
				$this -> Add('Start', date('Y-m-d H:i:s')); // Tiempo inicial del registro de la sesion
			}
		}

		/**
		 * Crear variables de sesión en la base de datos.		 *
		 * @param Name
		 * @param Value
		 * @param Type
		 *
		 * @uses CelaSession  ->  Crear('Home','Desktop.php', 'varchar' );
		 * @return boolean Indicate if the insertion has done.
		 */
		function Add($Name, $Value, $Type = TYPE_UNDEFINED){
			if($this -> IsReady() === true){
				if($this -> Exist($Name) === true){
					/*Verificar si la variable ya existe en la base.  en ese caso se actualiza*/
					$this -> Update($Name, $Value);
					return true;
				}else{
					/*se almacena en la base*/
					$SessionQuery = sprintf('INSERT INTO
												 CelaSession ( id, Usuario, Cookie, Nombre, Valor, Tipo)
											 VALUES ( %s, %s, %s, %s, %s, %s );',
										GetSQLValueString(NULL, 'int'),
										GetSQLValueString($this -> User, 'int'),
										GetSQLValueString($this -> Cookie, 'varchar'),
										GetSQLValueString($Name, 'varchar'),
										GetSQLValueString($Value, 'varchar'),
										GetSQLValueString($this -> GetType($Value), 'varchar')
									);

					if($SessionResult = $this -> Connection -> query($SessionQuery)){
						return true;
					}else{
						//print $this -> Connection -> error;
						return false;
					}
				}
			}
		}

		/**
		 * Actualiza las variables de sesión en la base de datos.
		 *
		 * @param Name
		 * @param Value
		 *
		 * @uses CelaSession  ->  Update('Principal','Escritorio.php', 'varchar' );
		 * @return boolean Indica si se realizó la inserción de la variable de sesión.
		 */
		function Update($Name, $Value){
			if($this -> IsReady() === true){
				$SessionQuery =  sprintf('UPDATE CelaSession
										 SET Valor = %s
										 WHERE
											 Usuario = %s AND
											 Nombre = %s;',
									GetSQLValueString($Value, 'varchar'),
									GetSQLValueString($this -> User, 'varchar'),
									GetSQLValueString($Name, 'varchar')
								);

				if($SessionResult = $this -> Connection -> query($SessionQuery)){
					return true;
				}else{
					//print $this -> Connection -> error;
					return false;
				}
			}
		}

		/**
		 * Eliminar variables de sesión en la base de datos.
		 * @method     boolean Eliminar(string);
		 * @uses       CelaSession  ->  Eliminar('Variable');
		 * @return     boolean Indica si se realizó la eliminación de la variable de sesión.
		 */
		function Remove($Nombre){
			$SessionQuery =  sprintf('DELETE FROM CelaSession
									 WHERE
							            Nombre = %s AND
                                        Usuario = %s AND
                                        Cookie = %s;',
								GetSQLValueString($Nombre, 'varchar'),
								GetSQLValueString($this -> User, 'varchar'),
								GetSQLValueString($this -> Cookie, 'varchar')
							);

			if($this -> Connection -> query($SessionQuery)){
				return true;
			}else{
				//print $this -> Connection -> error;
				return false;
			}
		}

		/**
		 * Eliminar variables de sesión en la base de datos.
		 * @method     boolean Destroy();
		 * @uses               CelaSession  ->  Destroy();
		 * @return     boolean Indica si se destruyeron todas la varibles de sesion.
		 *                     // Eliminar inclusive star y lastaccesstime
		 */
		function Destroy(){
			$SessionQuery =  sprintf('DELETE FROM CelaSession
									  WHERE
									     Usuario = %s AND
									     Cookie = %s;',
								GetSQLValueString($this -> User, 'varchar'),
								GetSQLValueString($this -> Cookie, 'varchar')
							);

			if($this -> Connection -> query($SessionQuery)){
				return true;
			}else{
				//print $this -> Connection -> error;
				return false;
			}
		}

		/**
		 * Leer Devuelve el valor de la variable de sesion.
		 * @method     string  Leer(string);
		 * @uses       CelaSession  ->  Leer('Variable');
		 * @return     string Devuelve el valor de la variable de sesión o false si no existe la variable.
		 */
		function Value($Name){
			$SessionQuery = sprintf('SELECT Valor
									 FROM CelaSession
									 WHERE
									    Nombre = %s AND
									    Usuario = %s AND
									    Cookie = %s;',
								GetSQLValueString($Name, 'varchar'),
								GetSQLValueString($this -> User, 'varchar'),
								GetSQLValueString($this -> Cookie, 'varchar')
							);

			$SessionResult = $this -> Connection -> query($SessionQuery);
			if($SessionResult -> num_rows){
				$Row = $SessionResult -> fetch_object();
				return $Row -> Valor;
			}else{
				//print $this -> Connection -> error;
				return false;
			}
		}

		/**
		 * Existe Devuelve si existe una variable de sesión en la base.
		 * @method     bool Existe();
		 *
		 * @param      Name
		 *
		 * @uses       CelaSession  ->  Existe( );
		 * @return     bool Devuelve true si existe la variable o false en caso contrario.
		 */
		function Exist($Name){
			$ConsultaSesion =   sprintf('SELECT Valor
										 FROM CelaSession
								         WHERE
								            Nombre = %s AND
								            Usuario = %s AND
								            Cookie = %s;',
									GetSQLValueString($Name, 'varchar'),
									GetSQLValueString($this -> User, 'varchar'),
									GetSQLValueString($this -> Cookie, 'varchar')
								);

			$ResultadoSesion = $this -> Connection -> query($ConsultaSesion);
			if($ResultadoSesion -> num_rows != 0){
				return true;
			}else{
				//print $this -> Connection -> error;
				return false;
			}
		}

		/**
		 * ExisteUsuario Devuelve si existe una variable de sesión en la base.
		 * @method     bool Existe();
		 *
		 * @param      Nombre
		 *
		 * @uses       CelaSession  ->  Existe( );
		 * @return     bool Devuelve true si existe la variable o false en caso contrario.
		 */
		function CheckUser(){
			$ConsultaSesion =   sprintf('SELECT Valor
										 FROM CelaSession
										 WHERE
											Usuario = %s;',
									GetSQLValueString( $this -> User, 'int')
								);

			if($ResultadoSesion = $this -> Connection -> query($ConsultaSesion)){
				return true;
			}else{
				//print $this -> Connection -> error;
				return false;
			}
		}

		/**
		 * Devuelve las variables de sesion en formato JSON.
		 * @method     JSON Dump();
		 * @uses       CelaSession  ->  Dump(Type );
		 *
		 * @param      Type
		 *
		 * @return     Json string con el contenido de todas las veriables de sesion
		 *                  verificador del JSON en : http://jsonviewer.stack.hu/
		 */
		function Dump($Type = false){
			$SessionQuery = sprintf('SELECT Usuario, Cookie, Nombre, Valor, Tipo
									 FROM CelaSession
									 WHERE
									     Usuario = %s AND
									     Cookie = %s;',
								GetSQLValueString( $this -> User, 'int'),
								GetSQLValueString($this -> Cookie, 'varchar')
							);

			$SessionResult = $this -> Connection -> query($SessionQuery);

			$Data = array();
			while($SessionRecord = $SessionResult -> fetch_assoc()){
				$Data[$SessionRecord['Nombre']] =  $SessionRecord;
			}

			if($Type === false){
				return $Data;
			}else{
				return json_encode($Data);
			}

		}

		/**
		 * Funcion utilitaria que verifica si el dato recibido es tipo fechahora.
		 *
		 * @param $Date
		 * @param $Format
		 *
		 * @return Boolean
		 */
		function is_datetime($Date, $Format = 'Y-m-d H:i:s'){
			$DateAux = DateTime::createFromFormat($Format, $Date);

			return $DateAux && $DateAux -> format($Format) == $Date;
		}

		/**
		 * Funcion utilitaria que devuelve el tipo de dato de la variable recibida.
		 *
		 * @param mixed $var Variable
		 *
		 * @return string Type of variable
		 */
		function GetType($Variable){
			if(is_array($Variable))
				return 'array';
			if(is_float($Variable))
				return 'float';
			if(is_int($Variable))
				return 'integer';
			if(is_null($Variable))
				return 'NULL';
			if(is_numeric($Variable))
				return 'numeric';
			if(is_object($Variable))
				return 'object';
			if(is_resource($Variable))
				return 'resource';
			if($this -> is_datetime($Variable))
				return 'datetime';
			if(filter_var($Variable, FILTER_VALIDATE_URL) === true)
				return 'url';
			if(filter_var($Variable, FILTER_VALIDATE_EMAIL))
				return 'email';
			if(filter_var($Variable, FILTER_VALIDATE_IP))
				return 'IP';
			if(is_string($Variable))
				return 'string';
			if(is_bool($Variable))
				return 'boolean';

			return TYPE_UNDEFINED;
		}
	}// End Class
?>
