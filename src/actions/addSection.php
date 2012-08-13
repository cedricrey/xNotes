<?php 
if(isset($_REQUEST["file"]))
	{
		$noteBook = XNReader::loadNoteBook($_REQUEST["file"]);
		//$noteBook = $noteBooks;
	}
$section = new Section();
if(isset($_REQUEST["title"]))
	$section->setTitle($_REQUEST["title"]);


if(isset($_REQUEST["position"]))
	XNManager::addSection($noteBook,$section,$_REQUEST["position"]);
else	
	XNManager::addSection($noteBook,$section);

$result = XNWriter::saveNoteBook($noteBook);
if($result)
	MessageCenter::appendInfo("_SECTION_CREATED_OK");
else
	MessageCenter::appendError("_SECTION_CREATED_ERROR");
	
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