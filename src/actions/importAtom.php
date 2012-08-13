<?php
	
	$result = null;
	$importDir = "import/" . UserManager::getUser()->getLogin() . "/";
	if ($_FILES["file"]["error"] == 0 && 
		!file_exists($importDir . $_FILES["file"]["name"]))
		{
		if(!file_exists ( $importDir ))
			mkdir($importDir);
      	move_uploaded_file($_FILES["file"]["tmp_name"], $importDir . $_FILES["file"]["name"]);
		$result = XNManager::importNBFromAtom( $importDir . $_FILES["file"]["name"]);
		unlink($importDir . $_FILES["file"]["name"]);
		}		
	if($result)
	{
		?>
		<script type="text/javascript">
			window.top.window.reloadNBList();
		</script>
	<?php }
?>
<script type="text/javascript">
	window.onload = function() {
		window.top.window.uploadFinished(document.body.innerHTML);
	};
</script>