<?php

if(UserManager::isUserAdmin())
	{	

		if (isset($_REQUEST['login'])){		
			$user = UserManager::loadUser($_REQUEST['login']);
			$action = "admin_modifUser";
		}
		else {
			$user = new User();
			$action = "admin_createUser";
		}
		
?>
	<form action="." id="userForm">
		<input type="hidden" name="action" id="formAction" value="<?php echo $action?>"/>
		<?php if($action == "admin_createUser") {?>
		<label for="login">Login : </label><input type="text" name="login" id="login"/>
		<?php }else{?>
		<label>Login : </label><?php echo $user->getLogin()?>
		<input type="hidden" name="login" id="login" value="<?php echo $user->getLogin()?>"/>
		<?php }?>
		<label for="firstname">Prénom : </label><input type="text" name="firstname" id="firstname" value="<?php echo $user->getFirstname()?>"/>
		<label for="firstname">Nom : </label><input type="text" name="lastname" id="lastname" value="<?php echo $user->getLastname()?>"/>
		<label for="firstname">Role : </label><input type="text" name="role" id="role" value="<?php echo $user->getRole()?>"/>
		<label for="password">Mot de passe : </label><input type="password" name="password" id="password" value=""/>
		<input type="submit" value="Envoyer"/>
	</form>
	<script>
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
		
		
	</script>
	<a href="index.php?action=admin_users" class="adminLink">Retour</a>
	<script type="text/javascript" charset="utf-8">
	$('.adminLink').click(function(ev){
		ev.preventDefault();
		$.ajax({
	   			url: "index.php",
		   		data : {action:"admin_users"},
	   			success: function(data) {
				    $('#mainContent').html(data);
				  }
				});	
	})
	</script>
<?php } ?>