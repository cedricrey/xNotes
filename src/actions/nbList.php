<?php 
$allBooks = XNManager::getAllNoteBook();
?>
<?php 
foreach ($allBooks as $key => $value) {
	//echo $key."<br/>"; 
	/*echo "<li><a href='index.php?action=loadNoteBook&file=".$value->getFile()."'>". 
		htmlentities($value->getTitle(), null, "UTF-8")."</a></li>";*/
	echo "<li><a href='#".$value->getFile()."'>". 
		htmlentities($value->getTitle(), null, "UTF-8")."</a></li>";
}
?>	