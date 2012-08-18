<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<script type="text/javascript" src="http://www.cedricrey.fr/pages/atelierWeb/js/jquery-1.4.1.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.js"></script>

<script type="text/javascript" src="js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="js/xnotes.js"></script>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css"/>
<link rel="stylesheet" type="text/css" href="css/xnotes.css"/>
</head>

<body id="loginPage">
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

</body>
</html>