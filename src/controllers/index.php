<?php /*include 'utils.php';*/
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>xNotes</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<!--script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.js"></script-->
<script type="text/javascript" src="js/jquery-ui-1.8.21/js/jquery-ui-1.8.21.custom.min.js"></script>

<script type="text/javascript" src="js/jquery-ui-1.9/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9/jquery.ui.position.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9/jquery.ui.tooltip.js"></script>
<script type="text/javascript" src="js/jquery.class.js"></script>

<script src="js/jquery.ui.touch-punch.min.js"></script>

<!--
<script type="text/javascript" src="js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="js/CLEditor/jquery.cleditor.min.js"></script>
-->

<script type="text/javascript" src="js/jquery.custoVScroll.js"></script>
<script type="text/javascript" src="js/xnotes.js"></script>

<link rel="stylesheet" href="js/jquery-ui-1.9/themes/base/jquery.ui.all.css"/>
<!--link rel="stylesheet" href="js/CLEditor/jquery.cleditor.css"/-->
<link rel="stylesheet" type="text/css" href="css/vScroll.css"/>
<link rel="stylesheet" type="text/css" href="css/xnotes.css"/>
<!--[if lte IE 8]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]--> 
<link rel="apple-touch-icon-precomposed" href="images/icon_57.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/icon_72.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/icon_114.png" />
<meta name="apple-mobile-web-app-title" content="xNotes" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
</head>

<body>
<header>
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
							<a href="index.php?action=logout" class="noAjax"><?php MessageCenter::printText("_LOGOUT")?></a>
						</li>
						<li>
							<a href="#__formPassword"><?php MessageCenter::printText("_CHANGE_PASSWORD")?></a>
						</li>
						<li>
							<a href="#__formUser"><?php MessageCenter::printText("_CHANGE_USER")?></a>
						</li>
					</ul>
				</li>
				<?/*
				<li id="importMenu">
					<span class="picto"></span>	
					Importer
					<ul>
						<li>
							<a href="index.php?action=formImportAtom">Importer depuis Atom/Google Notebook</a>
						</li>
					</ul>
				</li>
				 */?>
				<li>
				</li>
			</ul>
		
		<?php
		if(UserManager::isUserAdmin())
			require "views/adminMenu.php";
		
		?>
	</div>
	<h1 class="siteTitle">xNotes</h1>
	
	<script type="text/javascript">
	$('#toolsLinks LI A[class!="noAjax"], #userMenu LI A[class!="noAjax"]').click(function(ev){
		url = $(this).attr("href");
		if($(this).attr("href").substr(0,3) == "#__")
			url = "index.php?action="+$(this).attr("href").substr(3);
		else
			ev.preventDefault();
		$.ajax({
			url: url,
			success: function(data){$("#mainContent").html(data);}
			});	
	})
	</script>
	<br/>
</header>
<div id="col1">
	<div id="notebooksSection">
		<ul id="nbList">
			<?php include 'controllers/nbList.php'; ?>
		</ul>
		<ul id="notebooksTools">
			<li class="linkNewNB"><a href="javascript:void()"><?php MessageCenter::printText("_NEW_NOTEBOOK")?></a></li>
		</ul>
	</div>
	
	<ul id="toolsLinks">
		<li class="trash" title="Drop a note or a section here to delete it">
			<div>
			</div>
		</li>		
		<li class="listSwitcher">
			<div>
				
			</div>
		</li>
	</ul>
</div>

<div id="mainContentWrapper">
	<div id="mainContent"></div>
</div>
<div id="infoMessage"></div>
<div data-role="page"></div>
<script type="text/javascript" charset="utf-8">
	onBodyLoad();	
</script>
<footer>
	
</footer>

</body>
</html>