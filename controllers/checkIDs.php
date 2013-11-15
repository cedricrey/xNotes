<?php 

$user = UserManager::getUser();
$allBooks = XNManager::getAllNoteBook();

$result = true;
foreach ($allBooks as $key => $notebook) {
	XNManager::checkNotebookIDs($notebook);
	$result = $result && XNWriter::saveNoteBook($notebook);
}


	if($result)
		MessageCenter::appendInfo('Bloc-Notes vérifié');
	else
		MessageCenter::appendError('Erreur...');

?>