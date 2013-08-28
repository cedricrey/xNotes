<?php
	$datas = array();
	if (isset($_REQUEST['login']))
		$datas['login'] = $_REQUEST['login'];
	if (isset($_REQUEST['lastname']))
		$datas['lastname'] = $_REQUEST['lastname'];
	if (isset($_REQUEST['firstname']))
		$datas['firstname'] = $_REQUEST['firstname'];
	if (isset($_REQUEST['password']))
		$datas['password'] = $_REQUEST['password'];
	if (isset($_REQUEST['role']))
		$datas['role'] = $_REQUEST['role'];
	$result = UserManager::createUser($datas);	
	
if($result)
	MessageCenter::appendAdmin("_USER_CREATED_OK");
else
	MessageCenter::appendAdmin("_USER_CREATED_ERROR");
	
?>