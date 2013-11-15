<?php
if(UserManager::isUserAdmin())
	{		
		echo '<div id="adminUser">';
		$allUsers = UserManager::getAllUsers();
		//print_r($allUsers);
		echo "<ul>";
		foreach ($allUsers as $key => $user) {
			echo '<li><a href="./?action=admin_formUser&login=' . $user->getLogin() . '" class="adminLink">' . $user->getLogin() . " ( ". $user->getFirstname() . " " . $user->getLastname() ." )" . '</a></li>';
		}
		echo "</ul>";
	?>		
		<ul><li><a href="./?action=admin_formUser" class="adminLink">créer un utilisateur</a></li></ul>
		
		<script type="text/javascript">
		/*AJAX LOAD INSTEAD OF REFRESH PAGE*/
		$('#adminUser a').click(function(ev){
			ev.preventDefault();
			$.ajax({
	   			url: this.href,
	   			success: function(data){$('#mainContent').html(data)}
				});
		});
		</script>
		</div>
<?php
	}
?>