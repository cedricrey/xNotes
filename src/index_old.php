<?php /*include 'utils.php';*/
function chargerClasse ($classe)
	{
	    require $classe . '.class.php'; 
	}
	
	spl_autoload_register ('chargerClasse');

session_start();
?>
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

<body>
<?php
if(!isset($_SESSION['user']))
{
	$user = null;
	if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
		{
			$user = UserManager::login($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']);
		}
		
	if(!$user)
		{
		//	header("HTTP/1.0 403 Forbidden");
		//	exit;
		}
	else {
		$_SESSION['user'] = $user;
		}
	}
}

if(isset($_SESSION['user']))
	echo $_SESSION['user']->getLastname() . " " . $_SESSION['user']->getFirstname();

 ?>
<h1>Welcome in xNotes</h1>

Voici : <br/>
<?php
/*
$note = fileToNote("sample.xml");

foreach ($note->root  as $key => $value) {
	echo $key . " : " ."<br/>";	
}
 */
/*
foreach ($note->root->sections  as $key => $value) {
	echo "<div style='background-color:#FF8'>";
	echo "<h2>".$value->title."</h2>";
	foreach ($value->notes  as $nkey => $nvalue) {
		echo "<div style='background-color:#F88;margin-bottom:10px'>";
		echo $nvalue->content;
		echo "</div>";
	}	
	echo "</div>";
		
}
*/
/*echo $note->root->sections[0]->notes[0]->content*/

$sample = new Notebook("Ceci est un à test");

/*
echo "TEST : ".$sample->getFile();
*/
//echo "<br/>TEST : ";
//print_r(XNManager::getAllNoteBook());

?>
<div id="col1">
	<ul id="nbList">
		<?php include 'actions/nbList.php'; ?>
	</ul>
	<ul>
		<li class="linkNewNB"><a href="javascript:void()">Nouveau Bloc-Notes</a></li>
	</ul>
</div>

<div id="mainContent"></div>
<div id="infoMessage" style="display:none;position:absolute;text-align:center;width:100%;background-color:#FFF"></div>
<script type="text/javascript" charset="utf-8">
	$('#nbList li A').click(clickNBLink);
	$('.linkNewNB').click(createNB);	
</script>

<script type="text/javascript">

</script>



</body>
</html>