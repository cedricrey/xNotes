<?php 
function chargerClasseNB ($classe)
	{
	    require $classe . '.class.php'; 
	}
	
	spl_autoload_register ('chargerClasseNB');

$allBooks = XNManager::getAllNoteBook();
?>
<?php 
foreach ($allBooks as $key => $value) {
	//echo $key."<br/>"; 
	echo "<li><a href='loadNoteBook.php?file=".$value->getFile()."'>". 
		htmlentities($value->getTitle(), null, "UTF-8")."</a></li>";
}
?>	