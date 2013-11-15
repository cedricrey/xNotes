<?php

function loadClass ($class)
	{
		/*if (file_exists('controllers/' . $class . '.class.php'))
	    	require 'controllers/' . $class . '.class.php';*/
		if (file_exists('models/' . $class . '.class.php'))	    
	   		require 'models/' . $class . '.class.php';
	}
	
	spl_autoload_register ('loadClass');

session_start();
require 'init_globals.php';
ConfigManager::loadConfig();

$user = null;
if(!UserManager::isUserLogged())
{
	if (isset($_REQUEST['user']) && isset($_REQUEST['password'])) {
		{
			$user = UserManager::login($_REQUEST['user'],$_REQUEST['password']);
		}
	}
	if($user == null || $user == false)
		{
		require 'views/loginPage.php';
		MessageCenter::displayMessages();
		exit;
		}
}
else{
	$user = UserManager::getUser();
}
/*Open/Closed notes management*/
XNManager::manageOpenClosedNote();

$action = "index";
//Appel du controleur dédié
if (isset($_REQUEST['action'])) {
	if (file_exists('controllers/' . $_REQUEST['action'] . '.php'))
		$action = $_REQUEST['action'];
}
	include 'controllers/' . $action . '.php';

MessageCenter::displayMessages();
UserManager::saveUser($user);
?>