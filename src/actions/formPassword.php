<?php

if(UserManager::isUserAdmin())
	{	
		$user = UserManager::getUser();		
?>
	<form action="." id="userForm">
		<input type="hidden" name="action" id="formAction" value="modifPassword"/>
		<label for="oldPassword">Ancien mot de passe : </label><input type="password" name="oldPassword" id="oldPassword" />
		<label for="password">Nouveau mot de passe : </label><input type="password" name="password" id="password" />
		<input type="submit" value="Modifier"/>
	</form>
	<script>
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
		
	</script>
<?php } ?>