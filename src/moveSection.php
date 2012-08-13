<?php include 'utils.php';
function chargerClasse ($classe)
	{
	    require $classe . '.class.php'; 
	}
	
	spl_autoload_register ('chargerClasse');

if($_REQUEST["file"])
	{
		$noteBook = XNManager::loadNoteBook($_REQUEST["file"]);
		//$noteBook = $noteBooks;
	}
$sections = $noteBook->getSections();
$section = new Section();
$sectionNum = 0;
if($_REQUEST["sectionNum"])
	$sectionNum = $_REQUEST["sectionNum"];
if($sectionNum < count($sections))
	$section = $sections[$sectionNum];

$position = null;
if(isset($_REQUEST["position"]))
	$position = $_REQUEST["position"];

unset($sections[$_REQUEST["sectionNum"]]);
$sections = array_values($sections);
$noteBook->setSections($sections);
XNManager::addSection($noteBook, $section, $position);

$result = XNManager::saveNoteBook($noteBook);
	
?>
<div class="response">
	<?php 
	if($result)
		echo 'Votre bloc section a bien été déplacé.';
	else
		echo 'Erreur dans le déplacement de la section.';		
	?>	
</div>
	<?php 
	if($result){
	?>
	<script type="text/javascript" charset="utf-8">
		$.ajax({
	   			url: "loadNoteBook.php?file="+$('#currentNBFile').val(),
	   			success: onNBLoaded
				});
	</script>
	<?php
	}	
	?>