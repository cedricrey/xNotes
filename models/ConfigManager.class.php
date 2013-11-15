<?php
   class ConfigManager{
		static public $DEFAULT_CONFIG_FILE = "config.ini" ;
		static public $DEMO_COUNT = false;
		static public $LOCALE = "";
		static private $DEFAULT_LOCALE = "en-en";
		static public function loadConfig(){
		    $ini_array = parse_ini_file(self::$DEFAULT_CONFIG_FILE);
			if(isset($ini_array["date_time_zone"]))
				date_default_timezone_set ( $ini_array["date_time_zone"] );
			if(isset($ini_array["demo_count"]))
				self::$DEMO_COUNT = $ini_array["demo_count"];
			//Definition des tableaux globaux de messages
			if(UserManager::isUserLogged())
			{
				$user = UserManager::getUser();
				if($user->getLang() != "")
					self::$LOCALE = $user->getLang();
			}
			if(self::$LOCALE == "")
				self::getLocaleFromBrowser();
			self::loadLocale();
			//echo self::$LOCALE;
		}

		static public function loadLocale(){
			/*self::$LOCALE = Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);			
			self::$LOCALE = strtolower(self::$LOCALE);
			echo($_SERVER['HTTP_ACCEPT_LANGUAGE']);			
			if(file_exists("messages/messages_" . self::$LOCALE . ".php"))
				{
					require "messages/messages_" . self::$LOCALE . ".php";
				}
			*/			
			if(file_exists("messages/messages_" . self::$LOCALE . ".php"))
			{
				require "messages/messages_" . self::$LOCALE . ".php";
			}
			
			MessageCenter::initLang();
		}
		

		static public function getLocaleFromBrowser(){
			$acceptedLanguage = self::parseLocaleFromHTTPHeader($_SERVER['HTTP_ACCEPT_LANGUAGE']);
			foreach ($acceptedLanguage as $key => $currLang) {
				$currLang = strtolower($currLang);
				if(file_exists("messages/messages_" . $currLang . ".php"))
					{
						self::$LOCALE = $currLang;
						break;
					}				
			}
		}
		//To get the all the xx-XX (or xx-xx) formated values.
		//Locale::acceptFromHttp return the first found, and it could be xx instead of xx-XX.
		//We want standardize to get the good lang file.
		//Return an array
		static public function parseLocaleFromHTTPHeader($acceptLanguage){
			if(!$acceptLanguage)
				return "";
			
			preg_match_all('/([a-z]{1,8}-[a-z]{1,8})/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);
			return $lang_parse[0];
			
		}
		public static function getAvailableLanguages(){
			$dir    = 'messages';
			$messagesfiles = scandir($dir);
			$languages = array(0 => self::$DEFAULT_LOCALE);
			foreach ($messagesfiles as $key => $file) {
				if(strncmp($file, "messages_", 9) == 0)
				{
					preg_match_all('/([a-z]{1,8}-[a-z]{1,8})/i', $file, $lang);
					if(count($lang)>0 && count($lang[0])>0)
					array_push($languages, $lang[0][0]);
				}				
			}
			
			return $languages;
		}

	}
?>