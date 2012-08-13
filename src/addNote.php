<?php include 'utils.php';
function chargerClasse ($classe)
	{
	    require $classe . '.class.php'; 
	}
	
	spl_autoload_register ('chargerClasse');
$result = false;

if($_REQUEST["file"])
	{
		$note = new Note();
		$noteBook = XNManager::loadNoteBook($_REQUEST["file"]);		
		$sections = $noteBook->getSections();
		$parent = $noteBook;
		if(isset($_REQUEST["sectionNum"]))
			$sectionNum = $_REQUEST["sectionNum"];
		if(isset($sectionNum) && $sectionNum < count($sections))
			$parent = $sections[$sectionNum];
		if(isset($_REQUEST["content"]))
			$note->setContent($_REQUEST["content"]);
		
		
		if(isset($_REQUEST["position"]))
			XNManager::addNote($parent,$note,$_REQUEST["position"]);
		else	
			XNManager::addNote($parent,$note);
		
		$result = XNManager::saveNoteBook($noteBook);
	}
	
?>
<div class="response">
	<?php 
	if($result)
		echo 'Votre note a bien été créee.';
	else
		echo 'Erreur dans la création de la note.';		
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