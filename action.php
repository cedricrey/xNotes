<?php
function chargerClasse ($classe)
	{
	    require $classe . '.class.php'; 
	}
	
	spl_autoload_register ('chargerClasse');

session_start();

	/*
if (isset($_REQUEST['file']))
	$file = $_REQUEST['file'];

if ($_REQUEST['sectionNum'])
	$sectionNum = $_REQUEST['sectionNum'];

if ($_REQUEST['sectionNumFrom'])
	$sectionNumFrom = $_REQUEST['sectionNumFrom'];

if (isset($_REQUEST['position']))
	$position = $_REQUEST['position'];

if (isset($_REQUEST['content']))
	$content = $_REQUEST['content'];

if (isset($_REQUEST['title']))
	$title = $_REQUEST['title'];
	 */

//Appel du controleur dédié
if (isset($_REQUEST['action'])) {
	if (file_exists('actions/' . $_REQUEST['action'] . '.php'))
		require 'actions/' . $_REQUEST['action'] . '.php';
}

?>