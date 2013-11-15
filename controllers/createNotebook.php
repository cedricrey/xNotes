<?php
if(isset($_REQUEST['title']))
	{
		$result = XNManager::createNoteBook($_REQUEST['title']);
		//$noteBook = $noteBooks;
	}
	if($result)
		MessageCenter::appendInfo("_NOTEBOOK_CREATED_OK");
	else
		MessageCenter::appendError("_NOTEBOOK_CREATED_ERROR");
?>
	<?php 
	if($result){
	?>
	<script type="text/javascript" charset="utf-8">
		reloadNBList();
	</script>
	<?php
	}	
	?>	

