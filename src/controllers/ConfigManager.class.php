<?php
   class ConfigManager{
		static public $DEFAULT_CONFIG_FILE = "config.ini" ;
		static public $DEMO_COUNT = false;
		static public $LOCALE = "en_EN";
		static public function loadConfig(){
		    $ini_array = parse_ini_file(self::$DEFAULT_CONFIG_FILE);
			if(isset($ini_array["date_time_zone"]))
				date_default_timezone_set ( $ini_array["date_time_zone"] );
			if(isset($ini_array["demo_count"]))
				self::$DEMO_COUNT = $ini_array["demo_count"];
			//Definition des tableaux globaux de messages

			self::loadLocale();
			//echo self::$LOCALE;
		}


		static public function loadLocale(){
			self::$LOCALE = Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
			if(file_exists("messages/messages_" . self::$LOCALE . ".php"))
				{
					require "messages/messages_" . self::$LOCALE . ".php";
				}
			MessageCenter::initLang();
		}

	}
?>