<?php

function loadClass ($class)
	{
		if (file_exists('controllers/' . $class . '.class.php'))
	    	require 'controllers/' . $class . '.class.php';
		else if (file_exists('models/' . $class . '.class.php'))	    
	   		require 'models/' . $class . '.class.php';
	}
	
	spl_autoload_register ('loadClass');

session_start();
require 'init_globals.php';
ConfigManager::loadConfig();

if(!UserManager::isUserLogged())
{
	$user = null;
	if (isset($_REQUEST['user']) && isset($_REQUEST['password'])) {
		{
			$user = UserManager::login($_REQUEST['user'],$_REQUEST['password']);
		}
	}
		
	if($user == null || $user == false)
		{
		require 'views/loginForm.php';
		exit;
		}
}
/*Open/Closed notes management*/
XNManager::manageOpenClosedNote();

$action = "index";
//Appel du controleur dédié
if (isset($_REQUEST['action'])) {
	if (file_exists('actions/' . $_REQUEST['action'] . '.php'))
		$action = $_REQUEST['action'];
}
	include 'actions/' . $action . '.php';

MessageCenter::displayMessages();

?>