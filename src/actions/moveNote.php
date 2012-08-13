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
	if(isset($_REQUEST["noteNum"]))
		{
		$pathFrom .= "N[" . $_REQUEST["noteNum"] . "]/";
		}
	
	
	/*Destination*/
	$pathTo .= $_REQUEST["file"] . "/";
	$ancor = "#" . $_REQUEST["file"] . "/";
	if(isset($_REQUEST["sectionNumTo"]))
		{
		$pathTo .= "S[" . $_REQUEST["sectionNumTo"] . "]/";
		$ancor .= "S" . $_REQUEST["sectionNumTo"];
		}
	if(isset($_REQUEST["position"]))
		{
		$pathTo .= "N[" . $_REQUEST["position"] . "]/";
		$ancor .= "N" . $_REQUEST["position"];
		}	
	$result = XNManager::moveNote($pathFrom, $pathTo);
	
	if($result)
		MessageCenter::appendInfo('_NOTE_MOVED_OK');
	else
		MessageCenter::appendError('_NOTE_MOVED_ERROR');
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