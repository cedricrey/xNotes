<div class="messages">
	<?php if(MessageCenter::hasAdmin()){
		?>
		<div class="admin"><?php
			foreach (MessageCenter::getAdmin() as $key => $value) {
				echo $value . "<br/>";
			}
			?>
		</div>
		<?php
	}?>
	
	<?php if(MessageCenter::hasError()){
		?>
		<div class="error"><?php
			foreach (MessageCenter::getError() as $key => $value) {
				echo $value . "<br/>";
			}
			?></div>
		<?php
	}?>
	<?php if(MessageCenter::hasWarning()){
		?>
		<div class="warning"><?php
			foreach (MessageCenter::getWarning() as $key => $value) {
				echo $value . "<br/>";
			}
			?></div>
		<?php
	}?>
	<?php if(MessageCenter::hasInfo()){
		?>
		<div class="info"><?php
			foreach (MessageCenter::getInfo() as $key => $value) {
				echo $value . "<br/>";
			}
			?></div>
		<?php
	}?>
	
</div>


