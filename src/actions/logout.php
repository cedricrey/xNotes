<?php
if(UserManager::isUserLogged())
{
	UserManager::logout();	
}	
	require 'views/loginForm.html';

?>