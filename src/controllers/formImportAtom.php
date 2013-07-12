<?php

if(UserManager::isUserAdmin())
	{	
		$user = UserManager::getUser();		
?>
	<form action="." id="userForm" enctype="multipart/form-data" target="upload_target" method="POST">
		<input type="hidden" name="action" id="formAction" value="importAtom"/>
		<input type="file" value="Parcourir" name="file"/>
		<input type="submit" value="Modifier"/>
	</form>
	<iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
	<script>
	/*
		$('#userForm').submit(function(ev){
			ev.preventDefault();
			$.ajax({
		   			url: "index.php",
			   		data : $('#userForm').serialize(),
		   			success: function(data) {
					    $('#infoMessage').html(data).show();
					  }
					});	
		})
		*/
		function uploadFinished(data){
			console.log("uploadFinished :" + data);
			inform(data);
		}
	</script>
<?php } ?>