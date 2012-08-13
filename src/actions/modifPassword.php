<?php
	
	$result = null;
	if (isset($_REQUEST['oldPassword']) && isset($_REQUEST['password']))
		$result = UserManager::modifPassword($_REQUEST['oldPassword'], $_REQUEST['password']);
	
	if($result)
		MessageCenter::appendInfo('_PASSWORD_CHANGED_OK');
	else
		MessageCenter::appendError('_PASSWORD_CHANGED_ERROR');	
?>