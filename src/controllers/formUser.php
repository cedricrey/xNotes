<?php

			$action = "modifUser";
		
?>
	<form action="." id="userForm" method="post">
		<input type="hidden" name="action" id="formAction" value="<?php echo $action?>"/>
		<label>Login : </label><?php echo $user->getLogin()?> <br />
		<input type="hidden" name="login" id="login" value="<?php echo $user->getLogin()?>"/>
		<label for="firstname">Prénom : </label><input type="text" name="firstname" id="firstname" value="<?php echo $user->getFirstname()?>"/><br />
		<label for="lastname">Nom : </label><input type="text" name="lastname" id="lastname" value="<?php echo $user->getLastname()?>"/><br />
		<label for="role">Role : </label><input type="text" name="role" id="role" value="<?php echo $user->getRole()?>"/><br />
		<label for="lang">Langue : </label>
		<select name="lang" id="lang">
			<option value="">Default</option>
			<?
			foreach (ConfigManager::getAvailableLanguages() as $key => $lang) {
				if($user->getLang() == $lang)
					echo "<option value='".$lang."' selected='selected'>" . $lang . "</option>";
				else
					echo "<option value='".$lang."'>" . $lang . "</option>";
			}
			?>
		</select>
		<?/*
		<input type="text" name="lang" id="lang" value="<?php echo $user->getLang()?>"/>
		 */?>
		<br />		
		<input type="submit" value="Envoyer"/>
	</form>
	<script>
	/*
		$('#userForm').submit(function(ev){
			ev.preventDefault();
			$.ajax({
		   			url: "index.php",
			   		data : $('#userForm').serialize(),
		   			success: function(data) {
					    $('#infoMessage').html(data);
					  }
					});	
		})
	*/
		
	</script>