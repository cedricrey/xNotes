<?php 
$result = false;

if(isset($_REQUEST["file"]))
	{
		$note = new Note();
		$noteBook = XNReader::loadNoteBook($_REQUEST["file"]);		
		

		$title = urldecode($_REQUEST["title"]);
		$noteBook->setTitle($title);
		$now = new DateTime("now", new DateTimeZone(date_default_timezone_get()));
		$noteBook->setModified($now);
		
		//$notes[$_REQUEST["content"]] = $note;
		/*
		if(isset($_REQUEST["position"]))
			XNManager::addNote($parent,$note,$_REQUEST["position"]);
		else	
			XNManager::addNote($parent,$note);
		*/
		$result = XNWriter::saveNoteBook($noteBook);
	if($result)
		MessageCenter::appendInfo('_NOTE_MODIFIED_OK');
	else
		MessageCenter::appendError('_NOTE_MODIFIED_ERROR');
	}
	
?>
<div class="response">
</div>
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