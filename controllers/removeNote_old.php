<?php
$noteBook = new NoteBook();
if(isset($_REQUEST["file"]))
	{
		$noteBook = XNReader::loadNoteBook($_REQUEST["file"]);
		//$noteBook = $noteBooks;
	}
/*Origine*/
$sections = $noteBook->getSections();
$parent = $noteBook;
$sectionNum = 0;
if(isset($_REQUEST["sectionNum"]))
	{
	$sectionNum = $_REQUEST["sectionNum"];
	if($sectionNum < count($sections))
		$parent = $sections[$sectionNum];
	}

$notes = $parent->getNotes();
$note = new Note();
$noteNum = 0;
if(isset($_REQUEST["noteNum"]))
	$noteNum = $_REQUEST["noteNum"];
if($noteNum < count($notes))
	$note = $notes[$noteNum];

unset($notes[$_REQUEST["noteNum"]]);
$parent->setNotes($notes);
$result = XNWriter::saveNoteBook($noteBook);
	

if($result)
	MessageCenter::appendInfo('Votre note a bien été supprimée.');
else
	MessageCenter::appendError('Erreur dans la suppression de la note.');

?>


<script type="text/javascript" charset="utf-8">
alert("test");
	$.ajax({
   			url: "index.php?action=loadNoteBook&file="+$('#currentNBFile').val(),
   			success: onNBLoaded
			});
</script>