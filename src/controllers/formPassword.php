<?php

if(UserManager::isUserAdmin())
	{	
		$user = UserManager::getUser();		
?>
	<form action="." id="userForm">
		<input type="hidden" name="action" id="formAction" value="modifPassword"/>
		<label for="oldPassword">Ancien mot de passe : </label><input type="password" name="oldPassword" id="oldPassword" /><br />
		<label for="password">Nouveau mot de passe : </label><input type="password" name="password" id="password" /><br />
		<input type="submit" value="<?php MessageCenter::printText("_MODIFY")?>"/>
	</form>
	<script>
		$('#userForm').submit(function(ev){
			ev.preventDefault();
			/*$.ajax({
		   			url: "index.php",
			   		data : $('#userForm').serialize(),
		   			success: function(data) {
					    $('#infoMessage').html(data).show();
					  }
					});	
					*/
			datas = $('#userForm').serialize();
			shuttle = new xShuttle({datas:datas, avoidOpenNotesRefresh : true});
		})
		
	</script>
<?php } ?>