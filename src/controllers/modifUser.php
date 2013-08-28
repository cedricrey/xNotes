<?php
	$datas = array();
	
	$datas['login'] = $user->getLogin();
	if (isset($_REQUEST['lastname']))
		$datas['lastname'] = $_REQUEST['lastname'];
	if (isset($_REQUEST['firstname']))
		$datas['firstname'] = $_REQUEST['firstname'];
	if (isset($_REQUEST['lang']))
		$datas['lang'] = $_REQUEST['lang'];
	$result = UserManager::modifUser($datas);	
	
	if($result)
		{
			MessageCenter::appendInfo("_USER_MODIFIED_OK");
			$_SESSION['user'] = UserManager::loadUser($datas['login']);
		}
	else
		MessageCenter::appendError("USER_MODIFIED_ERROR");
	
	header('Location: #__formUser'); 
				
?>