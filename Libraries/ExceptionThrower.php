<?php
	class ExceptionThrower{
		static $IGNORE_DEPRECATED = true;

		static function Start($level = null){
			if ($level == null){
				if (defined('E_DEPRECATED')){
					$level = E_ALL & ~E_DEPRECATED ;
				}else{
					$level = E_ALL;
					self::$IGNORE_DEPRECATED = true;
				}
			}
			set_error_handler(array('ExceptionThrower', 'HandleError'), $level);
		}


		static function Stop(){
			restore_error_handler();
		}

		static function HandleError($code, $string, $file, $line, $context){
			// ignore supressed errors
			if (error_reporting() == 0) return;
			//if (self::$IGNORE_DEPRECATED && strpos($string, 'deprecated') === true) return true;
			return ;

			throw new Exception($string, $code);
		}
	}
?>