<?php /*include 'utils.php';*/
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.21/js/jquery-ui-1.8.21.custom.min.js"></script>

<script type="text/javascript" src="js/jquery-ui-1.9/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9/jquery.ui.position.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9/jquery.ui.tooltip.js"></script>
<script type="text/javascript" src="js/jquery.class.js"></script>

<script type="text/javascript" src="js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="js/CLEditor/jquery.cleditor.min.js"></script>

<script type="text/javascript" src="js/jquery.custoVScroll.js"></script>
<script type="text/javascript" src="js/xnotes.js"></script>

<link rel="stylesheet" href="js/jquery-ui-1.9/themes/base/jquery.ui.all.css"/>
<link rel="stylesheet" href="js/CLEditor/jquery.cleditor.css"/>
<link rel="stylesheet" type="text/css" href="css/vScroll.css"/>
<link rel="stylesheet" type="text/css" href="css/xnotes.css"/>
<!--[if lte IE 8]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]--> 
</head>

<body>
<header>
	<h1 class="siteTitle">xNotes</h1>
	<div id="topMenu">
			<ul id="userMenu">
				<li id="loginMenu">
					<span class="picto"></span>		
				 	<?php
				 	
				 	if(UserManager::isUserLogged())
				 		echo UserManager::getUser()->getLastname() . " " . UserManager::getUser()->getFirstname();
				 	?>
				 	<ul>
						<li>
							<a href="index.php?action=logout" class="noAjax">Déconnexion</a>
						</li>
						<li>
							<a href="index.php?action=formPassword">Changer de mot de passe</a>
						</li>
					</ul>
				</li>
				<li id="importMenu">
					<span class="picto"></span>	
					Importer
					<ul>
						<li>
							<a href="index.php?action=formImportAtom">Importer depuis Atom/Google Notebook</a>
						</li>
					</ul>
				</li>
				<li>
				</li>
			</ul>
		
		<?php
		if(UserManager::isUserAdmin())
			require "views/adminMenu.html";
		
		?>
	</div>
		
	<ul id="toolsLinks">
		<li>
			<div href="" class="trash" title="Déposer ici un élément pour le suprimer">
			</div>
		</li>
	</ul>
	
	
	<script type="text/javascript">
	$('#toolsLinks LI A[class!="noAjax"], #userMenu LI A[class!="noAjax"]').click(function(ev){
		ev.preventDefault();
		$.ajax({
			url: this.href,
			success: function(data){$("#mainContent").html(data);}
			});	
	})
	$( ".trash" ).sortable().tooltip();
	</script>
	<br/>
</header>
<div id="col1">
	<div id="notebooksSection">
		<ul id="nbList">
			<?php include 'actions/nbList.php'; ?>
		</ul>
		<ul id="notebooksTools">
			<li class="linkNewNB"><a href="javascript:void()">Nouveau Bloc-Notes</a></li>
		</ul>
	</div>
</div>

<div id="mainContent"></div>
<div id="infoMessage"></div>
<script type="text/javascript" charset="utf-8">
	$('#nbList li A').click(clickNBLink);
	$('.linkNewNB').click(createNB);
	if(window.location.hash != "")
		{
		noteBook = window.location.hash.split('/')[0];
		$("a[href='" + noteBook + "']").click();
		}
</script>
<footer>
	
</footer>

</body>
</html>