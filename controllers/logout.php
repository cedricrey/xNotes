<?php
if(UserManager::isUserLogged())
{
	UserManager::logout();	
}	
	require 'views/loginPage.php';

?>