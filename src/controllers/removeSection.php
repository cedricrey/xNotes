<?php

$path = "";
if(isset($_REQUEST["file"]))
	{
	
	$path .= $_REQUEST["file"] . "/";
	if(isset($_REQUEST["sectionNum"]))
		{
		$path .= "S[" . $_REQUEST["sectionNum"] . "]/";
		}	
	
	$result = XNManager::removeSection($path);
	
	if($result)
		MessageCenter::appendInfo('Votre section a bien été supprimée.');
	else
		MessageCenter::appendError('Erreur dans la suppression de la section.');
	
	}

?>


<script type="text/javascript" charset="utf-8">
	$.ajax({
   			url: "index.php?action=loadNoteBook&file="+$('#currentNBFile').val(),
   			success: onNBLoaded
			});
</script>