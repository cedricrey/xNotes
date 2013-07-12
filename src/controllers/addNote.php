<?php 
$result = false;

$user = UserManager::getUser();
if(isset($_REQUEST["file"]))
	{
		$note = new Note();
		$noteBook = XNReader::loadNoteBook($_REQUEST["file"]);	
		$sections = $noteBook->getSections();
		$parent = $noteBook;		
		$note->setId( "note_" . $user->newId() );
		
		if(isset($_REQUEST["sectionNum"]))
			$sectionNum = $_REQUEST["sectionNum"];
		if(isset($sectionNum) && $sectionNum < count($sections))
			$parent = $sections[$sectionNum];
		if(isset($_REQUEST["content"]))
			$note->setContent($_REQUEST["content"]);
		if(isset($_REQUEST["type"]))
			$note->setType($_REQUEST["type"]);
		$note->setViewstate("open");
		
		if(XNManager::isModuleCodeType($note))
			$note->setContent(stripslashes($note->getContent()));
		
		if(isset($_REQUEST["position"]))
			XNManager::addNote($parent,$note,$_REQUEST["position"]);
		else	
			XNManager::addNote($parent,$note);
		
		$result = XNWriter::saveNoteBook($noteBook);
		if($result)
			MessageCenter::appendInfo("_NOTE_CREATED_OK");
		else
			MessageCenter::appendError("_NOTE_CREATED_ERROR");
	}
	
?>
	<?php 
	if($result){
	?>
	<script type="text/javascript" charset="utf-8">
		$.ajax({
	   			url: "index.php?action=loadNoteBook&file="+$('#currentNBFile').val(),
	   			success: onNBLoaded
				});
	</script>
	<?php
	}	
	?>	