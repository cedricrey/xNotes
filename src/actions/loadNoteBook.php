<?php

if(isset($_REQUEST["file"]))
	{
		$noteBook = XNReader::loadNoteBook($_REQUEST["file"]);
		//$noteBook = $noteBooks;
	}
	
if (file_exists('views/loadNoteBook.php'))
	require 'views/loadNoteBook.php';
	
?>


