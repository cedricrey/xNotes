<?php

$path = "";
if(isset($_REQUEST["file"]))
	{
	
	$path .= $_REQUEST["file"] . "/";
	if(isset($_REQUEST["sectionNum"]))
		{
		$path .= "S[" . $_REQUEST["sectionNum"] . "]/";
		}
	if(isset($_REQUEST["noteNum"]))
		{
		$path .= "N[" . $_REQUEST["noteNum"] . "]/";
		}		
	
	$result = XNManager::removeNote($path);
	
	if($result)
		MessageCenter::appendInfo('Votre note a bien été supprimée.');
	else
		MessageCenter::appendError('Erreur dans la suppression de la note.');
	
	}

?>


<script type="text/javascript" charset="utf-8">
	$.ajax({
   			url: "index.php?action=loadNoteBook&file="+$('#currentNBFile').val(),
   			success: onNBLoaded
			});
</script>