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
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<script type="text/javascript" src="http://www.cedricrey.fr/pages/atelierWeb/js/jquery-1.4.1.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.js"></script>

<script type="text/javascript" src="js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="js/xnotes.js"></script>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css"/>
<link rel="stylesheet" type="text/css" href="css/xnotes.css"/>
</head>

<body id="installPage">
	<?php
	$user = null;
	if (isset($_REQUEST['login']) && isset($_REQUEST['password'])) {
		$user = UserManager::createUser(array("login" => $_REQUEST['login'], "role" => "admin", "password" => $_REQUEST['password'], "lastname" => " ", "firstname" => " "));

	}
	if($user)
		{
		MessageCenter::appendInfo("_INSTALL_USER_CREATED");
		MessageCenter::appendInfo("Login : " . $user->getLogin());
		
		}
	else {
			?>
		<form action="install.php" method="post">
			<label for="login"><?php MessageCenter::printText("_INSTALL_ADMIN_LOGIN")?></label><input type="text" name="login"/>
			<label for="password"><?php MessageCenter::printText("_INSTALL_ADMIN_PASSWORD")?></label><input type="password" name="password"/>
			<input type="submit" value="Install"/>
		</form>	
			
			<?		
		}
  
MessageCenter::displayMessages();
?>
</body>
</html>