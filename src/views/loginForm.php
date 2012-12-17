<h1 class="siteTitle">xNotes</h1>
<form action="index.php" method="post" id="loginForm">
	<div class="formEntry">
		<label for="user"><?php MessageCenter::printText("_YOUR_LOGIN")?></label><input type="text" name="user" id="user"/>
	</div>
	<div class="formEntry">
		<label for="password"><?php MessageCenter::printText("_YOUR_PASSWORD")?></label><input type="password" name="password" id="password"/>
	</div>
	<div class="formEntry">
		<input type="submit" value="<?php MessageCenter::printText("_LOGIN")?>" class="loginButton"/>
	</div>
</form>