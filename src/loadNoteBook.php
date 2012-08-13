<?php include 'utils.php';
function chargerClasse ($classe)
	{
	    require $classe . '.class.php'; 
	}
	
	spl_autoload_register ('chargerClasse');

if($_REQUEST["file"])
	{
		$noteBook = XNManager::loadNoteBook($_REQUEST["file"]);
		//$noteBook = $noteBooks;
	}
?>

<div id="noteBook">
	
	<h2><?php echo $noteBook->getTitle()?></h2>
	<ul>
		<li><a href="javascript:void" class="linkAddSection">Ajouter une section</a></li>
	</ul>
	
	
	<div id="nbPannel">
		<?php
		foreach ($noteBook->getSections() as $key => $section) {
			?>
			<div class="section">
				<input type="hidden" class="sectionPosition" value="<?php echo $key?>"/>
				<h3><?php echo $section->getTitle()?></h3>
					<div class="addingNoteBloc">
						<input type="hidden" name="notePosition" value="0"/>
					</div>
					<div class="notesList">
						<?php foreach ($section->getNotes() as $noteKey => $notes) {
							?>
							<div class="note">
								<input type="hidden" class="sectionNum" value="<?php echo $key?>"/>
								<input type="hidden" class="notePosition" value="<?php echo $noteKey?>"/>
								<div class="noteContent">
									<?php echo $notes->getContent()?>
								</div>	
								<div class="addingNoteBloc">
									<input type="hidden" name="notePosition" value="<?php echo $noteKey+1?>"/>
								</div>
							</div>
							<?php
						}?>
					</div>
			</div>		
			<?php
		
		}
		?>
	</div>
	
<input type="hidden" id="currentNBFile" value="<?php echo $_REQUEST["file"]?>"/>
</div>
<script type="text/javascript" charset="utf-8">
	$('.linkAddSection').click(function(){
		 if(sectionTitle = prompt('donnez un titre à votre Section'))
		 {
		 	$.ajax({
	   			url: "addSection.php?title="+sectionTitle+"&file="+$('#currentNBFile').val(),
	   			success: function(data) {	   				
				    $('#infoMessage').show().html(data);
				    $('#infoMessage').click(function(){$('#infoMessage').hide()});
				  }
				});		 	
		 }
	})
</script>