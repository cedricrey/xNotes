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

/*Destination*/
$parentTo = $parent;
if (isset($_REQUEST['sectionNumTo']))
	{
	$sectionNumTo = $_REQUEST['sectionNumTo'];
	if($sectionNumTo < count($sections))
		$parentTo = $sections[$sectionNumTo];
	}

$position = null;
if(isset($_REQUEST["position"]))
	$position = $_REQUEST["position"];

unset($notes[$_REQUEST["noteNum"]]);
$notes = array_values($notes);
$parent->setNotes($notes);
XNManager::addNote($parentTo, $note, $position);

$result = XNWriter::saveNoteBook($noteBook);

if($result)
	MessageCenter::appendInfo('Votre note a bien été déplacée.');
else
	MessageCenter::appendError('Erreur dans le déplacement de la note.');
?>
<script type="text/javascript" charset="utf-8">
	$.ajax({
   			url: "index.php?action=loadNoteBook&file="+$('#currentNBFile').val(),
   			success: onNBLoaded
			});
</script>