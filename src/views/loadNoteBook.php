<h2 class="noteBookTitle"><?php echo $noteBook->getTitle()?></h2>
<div id="noteBook">
	<div class="notesList">
		<div class="addNote">
			<input type="hidden" name="notePosition" value="0"/>
		</div>
		<?php foreach ($noteBook->getNotes() as $noteKey => $note) {
			$noteId = "N" . $noteKey;
			include "loadNote.php";
		}?>
	</div>
	
	
	<ul>
		<li><a href="javascript:void" class="linkAddSection"><?php MessageCenter::printText("_ADD_SECTION")?></a></li>
	</ul>
	
	
	<div id="nbPannel">
		<?php
		foreach ($noteBook->getSections() as $section_key => $section) {
			?>
			<div class="section">
				<input type="hidden" class="sectionPosition" value="<?php echo $section_key?>"/>
				<input type="hidden" class="sectionID" value="<?php echo $section->getId()?>"/>
				<h3 class="sectionTitle"><?php echo $section->getTitle()?></h3>
					<div class="addNote">
						<input type="hidden" name="notePosition" value="0"/>
					</div>
					<div class="notesList">
						<?php foreach ($section->getNotes() as $noteKey => $note) {
							
							$noteId = "S" . $section_key . "N" . $noteKey;
							include "loadNote.php";							
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
	   			url: "index.php?action=addSection&title="+sectionTitle+"&file="+$('#currentNBFile').val(),
	   			success: function(data) {	   				
				    $('#infoMessage').show().html(data);
				    $('#infoMessage').click(function(){$('#infoMessage').hide()});
				  }
				});		 	
		 }

	})
</script>