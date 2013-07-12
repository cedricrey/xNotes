<?php
if(UserManager::isUserAdmin())
	{
	$datas = array();
	if (isset($_REQUEST['login']))
		$datas['login'] = $_REQUEST['login'];
	if (isset($_REQUEST['lastname']))
		$datas['lastname'] = $_REQUEST['lastname'];
	if (isset($_REQUEST['firstname']))
		$datas['firstname'] = $_REQUEST['firstname'];
	if (isset($_REQUEST['role']))
		$datas['role'] = $_REQUEST['role'];
	if (isset($_REQUEST['lang']))
		$datas['lang'] = $_REQUEST['lang'];
	$result = UserManager::modifUser($datas);	
	
	if($result)
		MessageCenter::appendAdmin("_USER_MODIFIED_OK");
	else
		MessageCenter::appendAdmin("USER_MODIFIED_ERROR");
	
	
	echo "MODIF USER : " . print_r(UserManager::loadUser($datas['login']),true);
	if($user->getLogin() == $datas['login'])
		{
			$user = UserManager::loadUser($datas['login']);
			$_SESSION["user"] = $user;
		}
	
	print_r($user);
	}
?>