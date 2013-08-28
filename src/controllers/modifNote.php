<?php 
$result = false;

if(isset($_REQUEST["file"]))
	{
		$note = new Note();
		$noteBook = XNReader::loadNoteBook($_REQUEST["file"]);		
		$sections = $noteBook->getSections();
		$parent = $noteBook;
		if(isset($_REQUEST["sectionNum"]))
			$sectionNum = $_REQUEST["sectionNum"];
		if(isset($sectionNum) && $sectionNum < count($sections))
			$parent = $sections[$sectionNum];
		
		$notes = $parent->getNotes();
		if(isset($_REQUEST["position"]))
			$note = $notes[$_REQUEST["position"]];
		
		if(isset($_REQUEST["content"]))
			{
				if (get_magic_quotes_gpc() === 1)
				{
					$_REQUEST = json_decode(stripslashes(preg_replace('~\\\(?:0|a|b|f|n|r|t|v)~', '\\\$0', json_encode($_REQUEST, JSON_HEX_APOS | JSON_HEX_QUOT))), true);
				}
				$content = urldecode($_REQUEST["content"]);
				$content = stripslashes($content);
				$note->setContent($content);
				$now = new DateTime("now", new DateTimeZone(date_default_timezone_get()));
				$note->setModified($now);
				$parent->setModified($now);
				$noteBook->setModified($now);
			}
		
		
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
		if(is_touch_device())
		{
			$("input[value='<?=$note->getId()?>'] ~ .noteContent").each(function(){
				$(this).html("<?=$note->getContent()?>");
			});
		}
		/*
	    $.each($('#mainContent .noteContent'),function(index,value){
		    generateNoteResume($(this));		    	
	    });
	    */
	    onNBLoaded();
	</script>
	
	<?php
	}
	else{
		?>
	<script type="text/javascript" charset="utf-8">
		$.ajax({
	   			url: "index.php?action=loadNoteBook&file="+$('#currentNBFile').val(),
	   			success: onNBLoaded
				});
	</script>
	<?
	}
	?>	