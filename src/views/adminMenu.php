<ul id="adminMenu">
	<li><a href="" class="adminLink"><?php MessageCenter::printText("_USERS")?></a></li>
</ul>

<script type="text/javascript" charset="utf-8">
$('.adminLink').click(function(ev){
	ev.preventDefault();
	$.ajax({
   			url: "index.php",
	   		data : {action:"admin_users"},
   			success: function(data) {
			    $('#mainContent').html(data);
			  }
			});	
})
</script>