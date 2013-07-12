
<?php include 'utils.php';
function chargerClasse ($classe)
	{
	    require $classe . '.class.php'; 
	}
	
	spl_autoload_register ('chargerClasse');

if(isset($_REQUEST["file"]))
	{
		$noteBook = XNManager::loadNoteBook($_REQUEST["file"]);
		//$noteBook = $noteBooks;
	}
$section = new Section();
if(isset($_REQUEST["title"]))
	$section->setTitle($_REQUEST["title"]);


if(isset($_REQUEST["position"]))
	XNManager::addSection($noteBook,$section,$_REQUEST["position"]);
else	
	XNManager::addSection($noteBook,$section);

$result = XNManager::saveNoteBook($noteBook);
	
?>
<div class="response">
	<?php 
	if($result)
		echo 'Votre bloc section a bien été créee.';
	else
		echo 'Erreur dans la création de la section.';		
	?>	
</div>
	<?php 
	if($result){
	?>
	<script type="text/javascript" charset="utf-8">
		$.ajax({
	   			url: "loadNoteBook.php?file="+$('#currentNBFile').val(),
	   			success: function(data) {
				    $('#mainContent').html(data);
				    $.each($('#mainContent .note'),function(index,value){
				    	resumeText = $(this).text().substr(0,100);
				    	console.log(this);
				    	console.log(resumeText);
				    	$(this).before($("<div class='noteResume'>").html(resumeText));
				    });
				    
				    $('#mainContent .note').hide();
				    $('#mainContent .noteResume').click(function(){
				    	//$('#mainContent .note:visible').slideToggle(500);
				    	//$(this).next().slideToggle(500);
				    	//$('#mainContent .noteResume').show();
				    	$(this).next().show();
				    	$(this).hide();
				    	});
				  }
				});
	</script>
	<?php
	}	
	?>	