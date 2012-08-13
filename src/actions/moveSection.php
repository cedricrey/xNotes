<?php


$pathFrom = "";
$pathTo = "";
if(isset($_REQUEST["file"]))
	{
	/*Origine*/
	$pathFrom .= $_REQUEST["file"] . "/";
	if(isset($_REQUEST["sectionNum"]))
		{
		$pathFrom .= "S[" . $_REQUEST["sectionNum"] . "]/";
		}
	
	
	/*Destination*/
	$pathTo .= $_REQUEST["file"] . "/";
	$ancor = "#" . $_REQUEST["file"] . "/";
	if(isset($_REQUEST["position"]))
		{
		$pathTo .= "S[" . $_REQUEST["position"] . "]/";
		$ancor .= "S" . $_REQUEST["position"];
		}	
	$result = XNManager::moveSection($pathFrom, $pathTo);
	
	if($result)
		MessageCenter::appendInfo('Votre section a bien été déplacée.');
	else
		MessageCenter::appendError('Erreur dans le déplacement de la section.');
	}
?>
<script type="text/javascript" charset="utf-8">
	window.location.hash = "<?php echo $ancor?>";
	/*
	$.ajax({
		url: "index.php?action=loadNoteBook&file="+$('#currentNBFile').val(),
		success: onNBLoaded
		});
	*/
	shuttle = new xShuttle({
		datas : {
			action : "loadNoteBook", 
			file : $('#currentNBFile').val()
		},
		avoidOpenNotesRefresh : true,
		onReturn : onNBLoaded
	});
</script>