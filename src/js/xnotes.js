/**
 * @author crey
 * Dependencies : jquery-1.7.2, jquery-ui-1.8, jquery.ui.tooltip/position/widget, jquery.class,
 	jquery.custoVScroll
 */
htmlLineBreakerExp = new RegExp("(<br\/>|<br>|<p|<\/p|<div|<\/div|<h[0-9]|<\/h[0-9]>)","g");
	function clickNBLink(ev){
		/*
			ev.preventDefault();
			$.ajax({
	   			url: this.href,
	   			success: onNBLoaded
				});
		*/
		//console.log($(this).attr("href").substr(1));
		datas = {
			action : "loadNoteBook",
			file : $(this).attr("href").substr(1)
			};
		shuttle = new xShuttle({datas:datas, onReturn : onNBLoaded});
			/*
		$.ajax({
   			url: "index.php",
   			data : datas,
   			success: onNBLoaded
			});
			*/
				/*
				$(document.body).bind("click.note",function(ev){
					console.log($(ev.target).parent(".note"))
					if(!$(ev.target).hasClass("noteResume") && !$(ev.target).parent(".note"))
				    	{
				    	$('#mainContent .note:visible').slideToggle(500);
				    	$('#mainContent .noteResume').show();
				    	}
						
				})
				*/
		}
	
	function onNBLoaded(data){
	    $('#mainContent').html(data);
	    $('#mainContent .note a').attr("target","_blank");
	    $.each($('#mainContent .noteContent'),function(index,value){
	    	resumeNode = $(this).clone();
	    	resumeNode.html(resumeNode.html().replace(htmlLineBreakerExp,"____LINEBREAKER____$&"));	    	
	    	resumeText = resumeNode.text().replace(/____LINEBREAKER____/g,"<br\/>").replace(/((<br\/>|<br>)[\s]?)+/g,"<br\/>");
	    	if(resumeText.indexOf("<br/>") == 0)
	    		resumeText = resumeText.substr(5);
	    	maxLength = resumeText.length < 100 ? resumeText.length : 100;
	    	maxLength = resumeText.indexOf(" ",maxLength) > 0 ? resumeText.indexOf(" ",maxLength) : maxLength;
	    	endResumeText = resumeText.length > maxLength ? "..." : ""
	    	resumeText = resumeText.substr(0,maxLength) + endResumeText;
	    	
	    	//console.log(this);
	    	//console.log(resumeText);
	    	/*$(this).before($("<div class='noteViewSwitch'>").html("&nbsp;"));*/
	    	$(this).before($("<div class='noteResume'>").html(resumeText));
	    	if($(this).parent().find('.noteViewSwitch.open').length == 0)
	    		$(this).parent().find('.noteContent').hide();
	    	else
	    		$(this).parent().find('.noteResume').hide();
	    });
	    
	    //$('#mainContent .noteContent').hide();
	    $('#mainContent .noteResume, #mainContent .noteViewSwitch').click(manageNoteViewState);

	    //$("#noteBook .section").addClass('ui-state-default');
	    activateNBPannelSortable();
	    activateNotesListSortable();

	    
	    $('.addNote').bind("click",function(){
	    	//txtArea = $('<textarea/>').css("width","100%");
	    	editArea = $('<div class="editArea"/>').css({minHeight:"200px",backgroundColor:"#FFF"});
	    	btSubmit = $('<input type="button" value="enregistrer">');
	    	$(this).append(editArea).append(btSubmit).css({height:"auto"});
	    	//txtArea.focus().tinymize();
			editArea.editablize().css("min-height","200px").focus();
	    	$(this).unbind("click");
	    	btSubmit.click(function(){
	    		position = null;
	    		parent = $(this).parent();
	    		if(parent.find("*[name=notePosition]").length > 0)
	    			position = parent.find("*[name=notePosition]").val();
	    		numSection = null;
	    		if($(this).parents(".section").length > 0)
	    			numSection = $(this).parents(".section").find(".sectionPosition").val();
	    		
				/*
				newVal = txtArea.val();
				txtArea.tinymce().execCommand('mceRemoveControl');
				txtArea.remove();
				*/

				newVal = $(".editArea",parent).html();
				console.log($(".editArea",parent))
				$(".editArea",parent).uneditablize();
	    		btSubmit.remove();
	    		createNote(newVal,position,numSection);
	    	});
	    });
		$(".noteBookTitle").bind("click",nbTitleClick);
		$(".sectionTitle").bind("click",sectionTitleClick); 
	   	$('.noteContent').bind("click",noteClick);
		$('#noteBook').scroll(onNoteBookScroll);
	   	windowResized();
	   	if(localStorage.getItem( "position_top_" + $('#currentNBFile').val()) )
   		{
   			$('#noteBook').scrollTop( localStorage.getItem( "position_top_" + $('#currentNBFile').val()) );
   			//console.log(localStorage.getItem( "position_top_" + $('#currentNBFile').val()));
   		}
	   	else if(window.location.hash != "" && window.location.hash.split('/').length > 1)
		{
			ancor = window.location.hash.split('/')[1];
			$('#noteBook').scrollTop($("#" + ancor ).offset().top - $('#noteBook').offset().top);
		}
	   	$("#noteBook, #nbList").custoVScroll({autoHeight:true,step:10,inertie:20});
	}
	function manageNoteViewState(evt){
	    	note = $(this).parents(".note")
	    	switcher = note.find(".noteViewSwitch");
	    	if(!switcher.hasClass("open"))
	    		{
	    		openNoteView(note);
	    		}
	    	else
	    		{
	    		closeNoteView(note);
	    		}
	    	//To avoid IE 1second freeze, we trigger the resize later
			setTimeout(function(){
				$('#noteBook').trigger("hResize").trigger("mouseout").trigger("mouseover");
			},10);

	}
	
	function openNoteView(note){
		switcher = note.find(".noteViewSwitch");
		switcher.addClass("open");
		$('.noteContent', note).show();
		$('.noteResume', note).hide();
	}
	function closeNoteView(note){
		switcher = note.find(".noteViewSwitch");
		switcher.removeClass("open");
		$('.noteContent', note).hide();
		$('.noteResume', note).show();
	}
	//Function to start observing Helper position when dragging an element
	function observeHelperPosition(event, ui){
	    		//The element
	    		dragItem = ui.helper;
	    		//$(".noteContent:visible", ui.helper).parents(".note").find(".noteViewSwitch").click();
	    		//The sortable area
	    		dragParent = ui.item.parents("#noteBook");
	    		//The sortable elements
	    		sortableElement = $(this);
	    		//Let's observe the helper position every 200ms, and scroll if on top/bottom
	    		dragInterval = setInterval(function(){
	    			itTop = dragItem.position().top;
	    			paTop = dragParent.position().top;
	    			//Helper on top of area : scroll top
		    		if(itTop < paTop)
		    			{
		    				diff = paTop - itTop;
		    				dragParent.scrollTop(dragParent.scrollTop() - diff );
		    				//Need to refresh all items position on sortable list items when scrolling
		    				sortableElement.sortable("refresh")
		    			}
		    		//helper on bottom of area : scroll bottom
		    		else if(itTop + dragItem.height() > paTop + dragParent.height())
		    			{
		    				diff = itTop + dragItem.height() - (paTop + dragParent.height());
		    				dragParent.scrollTop(dragParent.scrollTop() + diff);
		    				//Need to refresh all items position on sortable list items when scrolling
		    				sortableElement.sortable("refresh")
		    			}
	    		},200);		
	}
	function moveSection(event,ui){
	    		clearInterval(dragInterval);
	    		localStorage.setItem( "position_top_" + $('#currentNBFile').val() , $('#noteBook').scrollTop() );
	    		sectionNum = $('.sectionPosition', ui.item).val();
	    		position = ui.item.parent().children().index(ui.item);	
	    		datas = {action : "moveSection", 
	   					sectionNum : sectionNum , 
	   					position : position ,
						file : $('#currentNBFile').val()}    
	    		if(ui.item.parents(".trash").length >0 )
					{
						datas.action = "removeSection";
						ui.item.css("z-index",1000).hide("explode", {pieces : 50},1000);
						ui.item.remove();
						datas.position = null;
					}	
				//If position is not the same
	    		if(datas.sectionNum != datas.position)
					new xShuttle({datas:datas});
				/*
				 	$.ajax({
				 		url : 'index.php',
			   			data : datas,
			   			success: function(data) {
			   				inform(data);
						  }
						});	
				*/
				
	    	}
	function moveNote(event,ui){
	    		clearInterval(dragInterval);
	    		localStorage.setItem( "position_top_" + $('#currentNBFile').val() , $('#noteBook').scrollTop() );	    		
	    		noteNum = $('.notePosition', ui.item).val();
	    		position = ui.item.parent().children().index(ui.item);
	    		datas = {action : "moveNote", 
		   					noteNum : noteNum ,   
		   					position : position ,
							file : $('#currentNBFile').val()}
				if($('.sectionNum', ui.item).length > 0)
					datas.sectionNum = $('.sectionNum', ui.item).val();
				if(ui.item.parents(".section"))
					datas.sectionNumTo = ui.item.parents(".section").find(".sectionPosition").val();
	    		if(ui.item.parents(".trash").length >0 )
					{
						datas.action = "removeNote";
						ui.item.css("z-index",1000).hide("explode", {pieces : 50},1000);
						ui.item.remove();
						datas.sectionNumTo = null;
					}
				//If position is not the same
	    		if(noteNum != position || datas.sectionNum != datas.sectionNumTo)		    		
		    		/*
		    		$.ajax({
				 		url : 'index.php',
			   			data : datas,
			   			success: function(data) {
							inform(data);
						  }
						});
					*/
					new xShuttle({datas:datas});
	    		}	
function noteClick(ev){
	if(ev.target.nodeName == "A")
		return true;
	ev.preventDefault();
	currentNoteEdit = $(this);
	this.oldContent = $(this).html();

	/*MODE CONTENTEDITABLE*/
	currentNoteEdit.editablize().unbind('click');
	btSubmit = $('<input type="button" value="enregistrer">');
	$(this).parent().append(btSubmit).css({height:"auto"});
	


	/*MODE TINYMCE*/
	/*
	height = $(this).height();		   		
	txtArea = $('<textarea id="currentTxtArea" />').val($(this).html()).css({width:"100%",height:height+"px"});
	btSubmit = $('<input type="button" value="enregistrer">');
	$(this).parent().append(txtArea).append(btSubmit).css({height:"auto"});
	txtArea.focus();
	$(this).hide();
	$(this).unbind("click");
	txtArea.tinymize();
	*/

	/*MODE CLEditor*/
	/*
	height = $(this).height();		   		
	txtArea = $('<textarea id="currentTxtArea" />').val($(this).html()).css({width:"100%",height:height+"px"});
	btSubmit = $('<input type="button" value="enregistrer">');
	$(this).parent().append(txtArea).append(btSubmit).css({height:"auto"});
	txtArea.focus();
	$(this).hide();
	$(this).unbind("click");
	txtArea.cleditor();
	*/
	btSubmit.click(function(){
		txtArea = $('textarea',$(this).parent());
		position = null;
		if($(this).parents(".note").find(".notePosition").length > 0)
			position = $(this).parents(".note").find(".notePosition").val();
		numSection = null;
		if($(this).parents(".section").length > 0)
			numSection = $(this).parents(".section").find(".sectionPosition").val();
		 		
		/*MODE TINYMCE // CLEditor*/
		/*
		newVal = txtArea.val();
		txtArea.remove();
		btSubmit.remove();
		*/
		/*MODE CONTENTEDITABLE*/
		newVal = currentNoteEdit.html();

		console.log(newVal);
		if(newVal != currentNoteEdit[0].oldContent.replace(/^\s+|\s+$/g,""))
			modifNote(encodeURI(newVal),position,numSection);

		$(document.body).unbind('click.editContent');
		currentNoteEdit.uneditablize().bind("click",noteClick);
		currentNoteEdit.parents(".note").find("input[type=button]").remove();
	});
	


	$(document.body).bind('click.editContent',function(ev){	    		
		//if($(ev.target).parents(".note").find('.noteContent')[0] == currentNoteEdit[0] && $(ev.target).parents(".mceEditor").length > 0)
		if($(ev.target)[0] == currentNoteEdit[0]
			|| $(ev.target).parents(".noteContent")[0] == currentNoteEdit[0]
			|| $(ev.target).parents(".scrollerContener").length > 0
			|| $(ev.target).parents("#contentEditToolBar").length > 0)
			{return null;}
		//console.log(ev);
		position = null;
		if(currentNoteEdit.parents(".note").find(".notePosition").length > 0)
			position = currentNoteEdit.parents(".note").find(".notePosition").val();
		numSection = null;
		if(currentNoteEdit.parents(".section").length > 0)
			numSection = currentNoteEdit.parents(".section").find(".sectionPosition").val();
		
		/*MODE TINYMCE // CLEditor*/
		/*
		newVal = txtArea.val();
		txtArea.remove();
		btSubmit.remove();
		*/

		
		/*MODE CONTENTEDITABLE*/
		newVal = currentNoteEdit.html();

		
		if(newVal != currentNoteEdit[0].oldContent.replace(/^\s+|\s+$/g,""))
			modifNote(encodeURI(newVal),position,numSection);



		if(!$(ev.target).hasClass("noteViewSwitch"))
			currentNoteEdit.show();
		$(document.body).unbind('click.editContent');
		currentNoteEdit.uneditablize().bind("click",noteClick);
		currentNoteEdit.parents(".note").find("input[type=button]").remove();
	});
	ev.stopPropagation();
	$('#noteBook').trigger("hResize");
}
function nbTitleClick(ev){
	if(ev.target.nodeName == "A")
			return true;
		ev.preventDefault();
		currentTitleEdit = $(this);
		this.oldContent = $(this).html();

		/*MODE CONTENTEDITABLE*/
		currentTitleEdit.editablize().unbind('click').focus();
		
		$(document.body).bind('click.editnbTitle',function(ev){	    		
			if($(ev.target)[0] == currentTitleEdit[0])
				{return null;}
			
			/*MODE CONTENTEDITABLE*/
			newVal = currentTitleEdit.html();
			fileName = $('#currentNBFile').val();

			if(newVal != currentTitleEdit[0].oldContent.replace(/^\s+|\s+$/g,""))
				modifNBTitle(encodeURI(newVal),fileName);

			$(document.body).unbind('click.editnbTitle');
			currentTitleEdit.uneditablize().bind("click",nbTitleClick);
	});
}

function sectionTitleClick(ev){
	if(ev.target.nodeName == "A")
		return true;
	ev.preventDefault();
	currentTitleEdit = $(this);
	this.oldContent = $(this).html();

	/*MODE CONTENTEDITABLE*/
	currentTitleEdit.editablize().unbind('click').focus();
	unactivateSortable($('#nbPannel'));

	$(document.body).bind('click.editSectionTitle',function(ev){	    		
		if($(ev.target)[0] == currentTitleEdit[0]
		|| $(ev.target).parents(".sectionTitle")[0] == currentTitleEdit[0]
		|| $(ev.target).parents(".scrollerContener").length > 0
		|| $(ev.target).parents("#contentEditToolBar").length > 0)
			{return null;}
		
		numSection = null;
		if(currentTitleEdit.parents(".section").length > 0)
			numSection = currentTitleEdit.parents(".section").find(".sectionPosition").val();
		/*MODE CONTENTEDITABLE*/
		newVal = currentTitleEdit.html();
		fileName = $('#currentNBFile').val();

		if(newVal != currentTitleEdit[0].oldContent.replace(/^\s+|\s+$/g,""))
			modifSectionTitle(encodeURI(newVal),fileName,numSection);

		$(document.body).unbind('click.editSectionTitle');
		currentTitleEdit.uneditablize().bind("click",sectionTitleClick);
		activateNBPannelSortable();
	});
}

function reloadNBList(){
	$.ajax({
			url: "index.php",
   		data : {action:"nbList"},
			success: function(data) {
		    $('#nbList').html(data);
		    $('#nbList li A').click(clickNBLink);
		    $('#nbList').trigger("hResize").trigger("mouseout").trigger("mouseover");
		  }
		});		
}
	
function createNB(){
	 if(title = prompt('donnez un titre à votre NB'))
	 {
		new xShuttle(
		{
			datas : {action:"createNoteBook",title:title},
			onReturn : reloadNBList
		});			 	
	 }
}

function createNote(content,position,numSection){
	url = "index.php";
	datas = {action:"addNote","content":content,"file":$('#currentNBFile').val()}
	if(position != null)
		datas.position = position;
	if(numSection != null)
		datas.sectionNum = numSection;
	new xShuttle({datas:datas});	
}
function modifNote(content,position,numSection){
	url = "index.php";
	datas = {action:"modifNote","content":content,"file":$('#currentNBFile').val()}
	if(position != null)
		datas.position = position;
	if(numSection != null)
		datas.sectionNum = numSection;
	new xShuttle({datas:datas});
}
function modifNBTitle(title,fileName){
	datas = {action:"modifNBTitle","title":title,"file":fileName}
	new xShuttle({datas:datas});
}
function modifSectionTitle(title,fileName,numSection){
	datas = {action:"modifSectionTitle","title":title,"file":fileName}
	if(numSection != null)
		datas.sectionNum = numSection;
	new xShuttle({datas:datas});
}
function onNoteBookScroll(e){
	localStorage.setItem( "position_top_" + $('#currentNBFile').val() , $('#noteBook').scrollTop() );
}

function activateNBPannelSortable(){
	$('#nbPannel').sortable('enable').sortable({
		placeholder: "emptyPlaceHighlight",
    	cancel : ".note",
		connectWith: ".trash",
		tolerance : "pointer",
		distance: 5,
		appendTo: 'body' ,
		//appendTo: '#mainContent' ,
		helper: 'clone',
    	scroll : false,
    	stop: moveSection,
    	start : observeHelperPosition
    });
}
function activateNotesListSortable(){
	$( ".notesList" ).sortable('enable').sortable({
    	//cancel : ".noteViewSwitch, .noteContent, .addingNoteSection, .noteResume",
		placeholder: "emptyPlaceHighlight",
    	handle : ".handle",
    	cancel : ".handle *",
		connectWith: ".trash, .notesList",
		tolerance : "pointer",
		//distance: 5,
		//appendTo: '#mainContent' ,
		appendTo: 'body' ,
		//revert: true ,
		helper: 'clone',
    	scroll : false,
    	stop: moveNote,
    	//In order to scroll when helper out of notebook area 
    	//(beacause helper is not constrained in this area -> trash etc.)
    	// we watch the position every 200ms
    	start : observeHelperPosition
    });
}
function unactivateSortable(element){
	element.sortable('disable');
}

function inform(data){		
    $('#infoMessage').show().stop(true).css("opacity",1).html(data).delay(1000).fadeOut(1500);
    $('#infoMessage').hover(
    	function(){$(this).stop(true).css("opacity",1)},
    	function(){$(this).delay(1000).fadeOut(1500)}
    	);
}

(function( $ ){
//Unused now, deprecated (because of bug with multiple textarea and ajax)...
/*
$.getScript('js/tiny_mce/jquery.tinymce.js');
  $.fn.tinymize = function() {
    return $(this).tinymce({
                        script_url : 'js/tiny_mce/tiny_mce.js',
                        mode : "none",
                       	theme : "advanced",
                        plugins : "",

                       	theme_advanced_toolbar_location : "top",
                        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull",
                        theme_advanced_buttons2 : "",
                        theme_advanced_buttons3 : "",

                        forced_root_block : false,
						force_br_newlines : true,
						force_p_newlines : false
                });
         
  };
*/

//Allows to an Element become editable and display edit buttons. Really great because keep exacly the same display as it was !
  $.fn.editablize = function() {
	function createEditToolsBar(){
		//Edit buttons bar. Each button must have "contentEditControl" class, and the action name on a class named "actionName", like "actionBold" for "bold" action
		EditToolBar = $('<ul id="contentEditToolBar">'
			+'<li class="contentEditControl actionBold">B</li>'
			+'<li class="contentEditControl actionItalic">I</li>'
			+'<li class="contentEditControl actionUnderline">U</li>'
			+'<li class="contentEditControl actionJustifyLeft">U</li>'
			+'<li class="contentEditControl actionJustifyRight">U</li>'
			+'<li class="contentEditControl actionJustifyCenter">U</li>'
			+'<li class="contentEditControl actionJustifyFull">U</li>'
			+'</ul>');
		$("#mainContent").append(EditToolBar);
		$('#contentEditToolBar .contentEditControl').mousedown(editAction);
	}
	function editAction(ev){
		ev.preventDefault();
		ev.stopPropagation();
		target = ev.target;
		action = 'bold';
		classNames = $(target).attr("class");
		pattern = /action([a-z]*)/i;
		//With a regexp, retrieves the action
		action = classNames.match(pattern)[1];
		action = action.toLowerCase();
		document.execCommand(action,false,null);
		return false;
	}
	if($('#contentEditToolBar').length == 0)
		createEditToolsBar();
	/*
	$('#contentEditToolBar').css({
			top: ( $(this).offset().top - $('#contentEditToolBar').height() ) + "px",
			left: $(this).offset().left + "px"})
		.fadeIn();
	*/
	//$(this).parent().append($('#contentEditToolBar'));
	$('#contentEditToolBar').fadeIn();

	return $(this).attr("contenteditable","");
  }
//disable the edit capability of an element and hide edit buttons 
$.fn.uneditablize = function() {
	$('#contentEditToolBar').fadeOut();
	return $(this).attr("contenteditable",null);
  }
})( jQuery );


//Class to send and recieve datas to/from server
var xShuttle = Class.extend({
	url : "index.php",
	datas : {},
  init: function(args){
  	//we get all arguments
	$.extend(this,args);
	//We send the open note state to the server (stand with same state) if avoidOpenNotesRefresh is not true
	if(typeof this.avoidOpenNotesRefresh == "undefined" || !this.avoidOpenNotesRefresh )
	{
		this.datas.openNotes = this.getOpenNotes();
		//If the last open note have been closed, we have to say it to the server, so we send "0", or server will note update it
		if(this.datas.openNotes.length == 0)
			this.datas.openNotes.push("0");
		this.datas.currentfile = $('#currentNBFile').val();
	}
	/*
	3
	2
	1...
	*/
	this.takeOff();
  },
  //Send it to the moon !
  takeOff : function(){
		$.ajax({
   			url: this.url,
   			data : this.datas,
   			type : "POST",
   			success: $.proxy(this.backHome,this)
			});
  },
  backHome : function(data){
  	//if special function defined to call on server response, else standard inform function
  	if(this.onReturn)
  		this.onReturn(data);
  	else
  		this.inform(data)
  },
  //displays different kind of returned server messages (info, warning, error)
  inform : function(data){
	    $('#infoMessage').show().stop(true).css("opacity",1).html(data).delay(1000).fadeOut(1500);
	    
	    $('#infoMessage').hover(
	    	function(){$(this).stop(true).css("opacity",1)},
	    	function(){$(this).delay(1000).fadeOut(1500)}
	    	);
	    
	},
	//return all open notes (that means notes with "open" class)
	getOpenNotes : function(){
		openNotes = new Array();
		$.each($('.noteViewSwitch.open'),function(key,val){
			position = $(this).parents('.note').attr('id');
			openNotes.push(position);
		});
		return openNotes;
	}
});

$(window).resize(onWindowResized);
//triggered when window is resized
function onWindowResized(e){	
	wH = $(window).height();
	wW = $(window).width();
	//as IE triggered more than necessary this event, we do nothing if window size didn't change
	if(typeof window.oldWH != "undefined" && typeof window.oldWW != "undefined" && wH == window.oldWH && wW == window.oldWW)
		return false;
	//keep the size on memory
	window.oldWH = wH;
	window.oldWW = wW;
	//really does the needed adjustments
	windowResized(e);
}
//function that really does something if window/elements size has changed
function windowResized(e){
	wH = $(window).height();
	wW = $(window).width();
	nbHei = wH - $("header").height() - $(".noteBookTitle").height() - 50;
	nbWid = wW - $("#col1").width() - 80;
	$('#noteBook').css({width: nbWid + "px" , height : nbHei + "px"});
	nblistHei = (wH - $("header").height()) / 2 - 30;
	$('#nbList').css({height : nblistHei + "px"});
	$('#noteBook, #nbList').trigger("hResize");
}
windowResized();
