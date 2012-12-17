<?php
   class MessageCenter{
   	public static $messageAdmin = array();
   	public static $messageErreur = array();
   	public static $messageWarning = array();
   	public static $messageInfo = array();
	
	public static $ADMIN_MESSAGES = array(
	"_DOM_ERROR" => "Error occurs while validating DOM"
	);
	public static $ERROR_MESSAGES = array(
	"_INEXISTANT_USER" => "The user doesn't exist.",
	"_WRONG_PASSWORD" => "The password is wrong",
	"_ERROR_LOGIN" => "User doesn't exist or the password is wrong",
	"_USER_ALREADY_EXISTS" => "The user name is already used",
	"_NOTE_MOVED_ERROR" => "An error has been encountered while moving note",
	"_NOTE_MOVED_ERROR" => "An error has been encountered while moving section",
	"_NOTE_MODIFIED_ERROR" => "An error has been encountered while modifying note",
	"_SECTION_MODIFIED_ERROR" => "An error has been encountered while modifying section",
	"_NOTE_CREATED_ERROR" => "An error has been encountered while creating note",
	"_SECTION_CREATED_ERROR" => "An error has been encountered while creating section",
	"_NOTEBOOK_CREATED_ERROR" => "An error has been encountered while creating notebook",
	"_PASSWORD_CHANGED_ERROR" => "An error has been encountered while changing password",
	);
	public static $WARNING_MESSAGES = array(
	"" => ""
	);
	public static $INFO_MESSAGES = array(
	"_LOGOUT" => "You have been logged out.",
	"_IMPORT_ATOM_OK" => "The import finished successfully.",
	"_IMPORT_ATOM_ERROR" => "A problem occurs while importing datas.",
	"_NOTE_MOVED_OK" => "The note has been moved",
	"_NOTE_MODIFIED_OK" => "The note has been modified",
	"_NOTE_CREATED_OK" => "The note has been created",
	"_SECTION_MOVED_OK" => "The section has been moved",
	"_SECTION_MODIFIED_OK" => "The section has been modified",
	"_SECTION_CREATED_OK" => "The section has been created",
	"_NOTEBOOK_CREATED_OK" => "The notebook has been created",
	"_PASSWORD_CHANGED_OK" => "The password has been changed",
	"_PASSWORD_CHANGED_OK" => "The password has been changed",
	"_INSTALL_USER_CREATED" => "The administrator user has been created",
	);
	
	public static $COMMON_TEXTS = array(
	"_YOUR_LOGIN" => "Your Login",
	"_YOUR_PASSWORD" => "Your Password",
	"_LOGIN" => "Login",
	"_INSTALL_ADMIN_LOGIN" => "Administrator login",
	"_INSTALL_ADMIN_PASSWORD" => "Administrator password"
	);
	
	
	public static function appendAdmin($admin){
		if(UserManager::isUserAdmin())
		{
			if(isset(self::$ADMIN_MESSAGES[$admin]))
				$admin = self::$ADMIN_MESSAGES[$admin];
			self::$messageAdmin[] = $admin; 
		}		
	}	
	public static function getAdmin(){
		if(UserManager::isUserAdmin())
			return self::$messageAdmin;
		else
			return "";
	}
	public static function hasAdmin(){
		return (!empty(self::$messageAdmin) && UserManager::isUserAdmin());
	}	
	
	
	public static function appendError($error){
		if(array_key_exists($error, self::$ERROR_MESSAGES))
			$error = self::$ERROR_MESSAGES[$error];
		self::$messageErreur[] =  $error ; 
	}	
	public static function getError(){
		return self::$messageErreur;
	}
	public static function hasError(){
		return !empty(self::$messageErreur);
	}


	public static function appendWarning($warning){
		if(array_key_exists($warning, self::$WARNING_MESSAGES))
			$warning = self::$WARNING_MESSAGES[$warning];
		self::$messageWarning[] = $warning; 
	}	
	public static function getWarning(){
		return self::$messageWarning;
	}
	public static function hasWarning(){
		return !empty(self::$messageWarning);
	}	
	
	
	public static function appendInfo($info){
		if(array_key_exists($info, self::$INFO_MESSAGES))
			$info = self::$INFO_MESSAGES[$info];
		self::$messageInfo[] = $info; 
	}	
	public static function getInfo(){
		return self::$messageInfo;
	}
	public static function hasInfo(){
		return !empty(self::$messageInfo);
	}	
	
	public static function displayMessages(){
		require "views/messages.php";
	}
	
	public static function initLang(){
		global $global_INFO_MESSAGES,
		$global_ADMIN_MESSAGES,
		$global_ERROR_MESSAGES,
		$global_WARNING_MESSAGES,
		$global_COMMON_TEXTS;
		if(isset($global_ADMIN_MESSAGES))
			self::$ADMIN_MESSAGES = array_merge ( self::$ADMIN_MESSAGES ,$global_ADMIN_MESSAGES );
		if(isset($global_ERROR_MESSAGES))
			self::$ERROR_MESSAGES = array_merge ( self::$ERROR_MESSAGES ,$global_ERROR_MESSAGES );
		if(isset($global_WARNING_MESSAGES))
			self::$WARNING_MESSAGES = array_merge ( self::$WARNING_MESSAGES ,$global_WARNING_MESSAGES );
		if(isset($global_INFO_MESSAGES))
			{
			self::$INFO_MESSAGES = array_merge ( self::$INFO_MESSAGES ,$global_INFO_MESSAGES );
			}
		if(isset($global_COMMON_TEXTS))
			{
			self::$COMMON_TEXTS = array_merge ( self::$COMMON_TEXTS ,$global_COMMON_TEXTS );
			}
	}
	
	public static function printText($string){
		if(isset(self::$COMMON_TEXTS[$string]))
			echo self::$COMMON_TEXTS[$string];

	}
   }
?>