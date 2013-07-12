<div>
	<div class="handle">
		<div class="note" id="<?php echo $noteId?>">
			<?php if(isset($section_key)) {?>
			<input type="hidden" class="sectionNum" value="<?php echo $section_key?>"/>
			<? } ?>
			<input type="hidden" class="notePosition" value="<?php echo $noteKey?>"/>
			<input type="hidden" class="noteID" name="noteID" value="<?php echo $note->getId()?>"/>
			<input type="hidden" class="noteType" name="noteType" value="<?php echo $note->getType()?>"/>
			<?php 
			if($note->getType() == "link")
			{
				echo '<div class="noteContentLink">'
				. '<div class="noteTitle">' . $note->getTitle() . '</div>'
				. '<a href="' . $note->getContent() . '" class="noteLink">' . $note->getContent() . '</a>'
				. '</div>';
			}
			else if(XNManager::isModuleCodeType($note))
			{
				echo '<div class="noteViewSwitch';
				//if(in_array($noteId, $_REQUEST["openNotes"]))
				if($note->getViewstate() == "open")
					echo ' open';
				echo '">&nbsp;</div>';
				echo '<div class="noteContent noteContentTweet">' . $note->getContent() . '</div>';
			}
			else {
				echo '<div class="noteViewSwitch';
				//if(in_array($noteId, $_REQUEST["openNotes"]))
				if($note->getViewstate() == "open")
					echo ' open';
				echo '">&nbsp;</div>';
				
				echo '<div class="noteContent">' . $note->getContent() . '</div>';				
			}
			?>
		</div>
	</div>
	<div class="addNote">
		<input type="hidden" name="notePosition" value="<?php echo $noteKey+1?>"/>
	</div>
</div>