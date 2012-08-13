<?php include 'utils.php';
function chargerClasse ($classe)
	{
	    require $classe . '.class.php'; 
	}
	
	spl_autoload_register ('chargerClasse');

if($_REQUEST["title"])
	{
		$result = XNManager::createNoteBook($_REQUEST["title"]);
		//$noteBook = $noteBooks;
	}
?>

<div class="response">
	<?php 
	if($result)
		echo 'Votre bloc note a bien été crée.';
	else
		echo 'Erreur dans la création du bloc note.';		
	?>	
</div>
	<?php 
	if($result){
	?>
	<script type="text/javascript" charset="utf-8">
		reloadNBList();
	</script>
	<?php
	}	
	?>	

