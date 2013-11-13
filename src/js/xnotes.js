/**
 * @author crey
 * Dependencies : jquery-1.7.2, jquery-ui-1.8, jquery.ui.tooltip/position/widget, jquery.class,
 	jquery.custoVScroll
 */
htmlLineBreakerExp = new RegExp("(<br\/>|<br>|<p|<\/p|<div|<\/div|<h[0-9]|<\/h[0-9]>)","g");
smallScreenSize = 700;
moduleCodeTypes = ["tweet","soundcloud"];
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
	    //Summaries creation
	    $.each($('#mainContent .noteContent'),function(index,value){
		    	generateNoteResume($(this));		    	
	    });
	    
	    //$('#mainContent .noteContent').hide();
	    $('#mainContent .noteResume, #mainContent .noteViewSwitch').unbind('click').click(manageNoteViewState);

	    //$("#noteBook .section").addClass('ui-state-default');
	    if(!is_touch_device())
		    {
		    activateNBPannelSortable();
		    activateNotesListSortable();
		    }

	    
	    $('.addNote').unbind('click').bind("click",addNote);
		$(".noteBookTitle").unbind('click').bind("click",nbTitleClick);
		$(".sectionTitle").unbind('click').bind("click",sectionTitleClick); 
	   	$('.noteContent').unbind('click').bind("click",noteClick);
		$('#noteBook').unbind('scroll').scroll(onNoteBookScroll);
		//Custo Scroll
		if(!is_touch_device())
	   		$("#noteBook, #nbList").custoVScroll({autoHeight:true,step:10,inertie:0});   	
	   	
	   	//NBList entry activation
		$('#nbList li').removeClass('active');
		$("#nbList li A[href='#" + $('#currentNBFile').val() +"']").parent().addClass('active');
		
		//In touch device case		
		if(is_touch_device())
			onNBLoaded_Touch();
			
		//Height and Width calculation
	   	windowResized();
	   	
	   	if(localStorage.getItem( "position_top_" + $('#currentNBFile').val()) )
   		{
   			$('#noteBook').scrollTop( localStorage.getItem( "position_top_" + $('#currentNBFile').val()) );
   			//console.log(localStorage.getItem( "position_top_" + $('#currentNBFile').val()));
   		}
	   	else if(window.location.hash != "" && window.location.hash.split('/').length > 1)
		{
			ancor = window.location.hash.split('/')[1];
			if($("#" + ancor ).length > 0)
				$('#noteBook').scrollTop($("#" + ancor ).offset().top - $('#noteBook').offset().top);
		}		
	}
	
	function onNBLoaded_Touch(){
		$('.sectionTitle, .noteContent, .noteResume').unbind("click");
		$('#mainContent').removeClass("view1").removeClass("view2");
		$('.sectionTitle, .noteResume').bind("click",switchElement_touch);
		
		if($('#touchView1').length == 0)
			{
			view1 = $("<div id='touchView1'></div>");
			cpView1 = $("<div id='cpView1'><a href='#' class='back'>&#8629;</a><a href='#' class='addNote'>+</a></div>");
			$('#mainContent').append(cpView1).append(view1);			
			//$('#cpView1 a').bind('click',backTouch);
		
			view2 = $("<div id='touchView2'></div>");
			cpView2 = $("<div id='cpView2'><a href='#' class='back'>&#8629;</a></div>");
			$('#mainContent').append(cpView2).append(view2);
			$('#cpView1 .back, #cpView2 .back').bind('click',backTouch);
			$('#mainContent').append($("<a href='#' class='btAddNote'>+</a>"));
			$('#cpView1 .addNote, #mainContent .btAddNote').bind('click',addNote_touch);
			}
		
		
		
		//if hash
		hash = document.location.hash;
		path = (/\.xml\/(.*)/gi).exec(hash);
		if(path && path.length > 1)
			{
				path = path[1];
				view1 = path.substr(1,1);
				$('#mainContent').addClass('noTransition');
				if(path.substr(0,1) == "S")
					$(".sectionPosition[value='" + view1 + "'] ~ .sectionTitle").click();
				else if(path.substr(0,1) == "N")
					$(":not(.section) .notePosition[value='" + view1 + "'] ~ .noteResume").click();
				
				view2 = path.substr(3,1);
				if(path.substr(2,1) == "N")
					$("#touchView1 .notePosition[value='" + view2 + "'] ~ .noteResume").click();
				setTimeout(function(){$('#mainContent').removeClass('noTransition')},10);
				
			}
			//mobile_FullScreenOn();
	}
	
	function switchElement_touch(){
		/*
		$('.section, .handle').removeClass("active");
		$(this).parents(".section, .handle").addClass("active");
		*/
		
		content = $(this).parent().clone(true);
		//content.find('.sectionTitle').unbind('click').bind("click",sectionTitleClick).find('span').remove();		
		//content.find('.noteContent').unbind('click').bind("click",noteClick);
		content.find('.sectionTitle').bind("click",sectionTitleClick).find('span').remove();		
		content.find('.noteContent').bind("click",noteClick);
		if($(this).parent().find('.originalContent').length > 0)
			content = $('<div class="' + $(this).parent().find('.noteContent').attr('class') + '">' + $(this).parent().find('.originalContent').text().replace(/--lt/g,"<") + '</div>');
		if($('#mainContent').hasClass("view1"))
			{
			$('#touchView2').empty().append(content);
			$('#mainContent').addClass("view2");
			}
		else
			{
			$('#touchView1').empty().append(content);
			$('#mainContent').addClass("view1");
			if(content.hasClass('section'))
				{
				$('#cpView1').addClass('addNotePermitted');
				if($(this).parent().index() % 2 == 1)
					content.addClass('pair');
				}
			else
				$('#cpView1').removeClass('addNotePermitted');
			}
			
		updateHash_touch(content);
		/*Close menu if opened*/
		$("#col1").removeClass("active");
		
	}
	function updateHash_touch(view){
		content = view;
		//Hash update
		s = "", n = "";
		if(content.hasClass('section'))
			s = "S" + content.find('.sectionPosition').val();
		else if(content.hasClass('note'))
			{
				if(content.find('.sectionNum').length > 0)
					s = "S" + content.find('.sectionNum').val();
				n = "N" + content.find('.notePosition').val();
			}
		/*
		if($content.find('.sectionPosition, .sectionNum').length > 0)
			s = "S" + $content.find('.sectionPosition, .sectionNum').val();
		if($content.find('.notePosition').length > 0)
			n = "N" + $content.find('.notePosition').val();
		*/
		
		hash = document.location.hash;
		hash = hash.split("/");
		document.location.hash = hash[0] + "/" + s + n;
	}
	function backTouch(ev){
		if($('#mainContent').hasClass("view2"))
			{
			$('#mainContent').addClass("view1").removeClass("view2");
			updateHash_touch($('#touchView1  :first-child'));
			//$('.active .section, .active .handle').removeClass("active");
			}
		else
			{
			$('#mainContent').removeClass("view1");
			updateHash_touch($('#mainContener'));
			//$('.section, .handle').removeClass("active");
			}
			
		$('body').trigger("click");
		//ev.preventDefault();
		return false;
	}
	
	function addNote_touch(){
		//parentContener = $('#mainContent .notesList:first-of-type')
		//updateHash_touch($('#mainContener'));
		parentContener = $('#touchView1');
		//updateHash_touch($('#touchView1'));
		updateHash_touch($('#mainContener'));
		
		contener = $('<div class="note"></div>');
		$('#mainContent').css({
			WebkitTransition : 'none',
			MozTransition : 'none',
			MsTransition : 'none',
			transition : 'none'
		});
		if($('#mainContent').hasClass('view1'))
			{
				//parentContener = $('#touchView1 .section .notesList');
				//updateHash_touch($('#touchView1  :first-child'));
				parentContener = $('<div class="section"></div>');
				$('#touchView2').empty().append(parentContener);
				contener.append($('#touchView1 .section .sectionPosition').clone());
				//updateHash_touch($('#touchView2'));
				updateHash_touch($('#touchView1  :first-child'));
				
				//contener = $('<div class="section"><div class="notesList"></div></div>');
				
				$('#mainContent').addClass('view2');
			}
			
		$('#mainContent').addClass('view1');
		parentContener.empty().append(contener);
		
		currentNoteEdit = contener;
		txtArea = $('<textarea/>').css("width","100%");
		editArea = $('<div class="editArea"/>').css({minHeight:"200px",backgroundColor:"#FFF"});
		btSubmit = $('<input type="button" value="save">');
		typeList = $('<select name="type"><option value="html">Texte</option><option value="tweet">Tweet</option><option value="soundcloud">SoundCloud</option></select>').change(changeNoteType);
		contener.append(typeList).append(btSubmit).append(editArea).append(txtArea);//.css({height:"auto"});
		triggerContentHeight();
		//txtArea.focus().tinymize();
		txtArea.hide();
		editArea.editablize();
		editArea.css("min-height","200px");
		editArea.focus();
		contener.unbind("click");
		btSubmit.click(submitNewNote);
		$(document.body).bind('click.editContent',$.proxy(submitNewNote, btSubmit) );
		//$("#noteBook").animate({scrollTop: contener.offset().top + $("#noteBook").scrollTop() - 100}, 1000,'easeInOutCubic');
	
		setTimeout(function(){			
			$('#mainContent').css({
				WebkitTransition : '',
				MozTransition : '',
				MsTransition : '',
				transition : ''
			});
		},10);
	
		return false;
	}
	function generateNoteResume(note){
		//Summary creation (node)
		note = $(note);
    	summaryNode = note.clone();
    	//To keep line breaks
    	summaryNode.html(summaryNode.html().replace(htmlLineBreakerExp,"____LINEBREAKER____$&"));
    	//Get only text part (without html formatting), retrieve the line breaks, remove double line breaks    	
    	summaryText = summaryNode.text().replace(/____LINEBREAKER____/g,"<br\/>").replace(/((<br\/>|<br>)[\s]?)+/g,"<br\/>");
    	//If the summary start with line break
    	while(summaryText.indexOf("<br/>") == 0)
    		summaryText = summaryText.substr(5);
    	//Get the max summary length : 100 or less if so.
    	maxLength = summaryText.length < 100 ? summaryText.length : 100;
    	//If more than 5 breakline
    	countBr=0;
    	for(i=0;i<maxLength && summaryText.indexOf("<br/>",i) != -1;i=summaryText.indexOf("<br/>",i)+1)
    	{
    		countBr++
    		if(countBr >= 5)
    		{
    			maxLength=i;
    			break;
    		}
    	}
    	//To avoid word cut, from the max length (100 or less), we take the position of the next space
    	nextword = Math.min(summaryText.indexOf(" ",maxLength), summaryText.indexOf("<br/>",maxLength));
    	maxLength = nextword > 0 ? nextword : maxLength;
    	//If the complete text is longer than max length, we add "..." to the end
    	endSummaryText = summaryText.length > maxLength ? "..." : ""
    	// aaaand... CUT ! we cut the whole text to get the summary
    	summaryText = summaryText.substr(0,maxLength) + endSummaryText;
    	
    	note.parent().find(".noteResume").remove();
    	note.before($("<div class='noteResume'>").html(summaryText));
    	
    	if(note.parent().find('.noteViewSwitch.open').length == 0)
    		note.parent().find('.noteContent').hide();
    	else
    		note.parent().find('.noteResume').hide();
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
	    	triggerContentHeight();

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
	if(typeof currentNoteEdit != "undefined" && currentNoteEdit)
		$(document.body).trigger('click.editContent');
	currentNoteEdit = $(this);
	if(moduleCodeTypes.indexOf($(this).parent(".note").find(".noteType").val()) != -1)
		return true;
	this.oldContent = $(this).html();

	/*MODE CONTENTEDITABLE*/
	currentNoteEdit.editablize().unbind('click');
	btSubmit = $('<input type="button" value="save">');
	$(this).parent().append(btSubmit);//.css({height:"auto"});
	


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
		else if($(this).parents(".note").find(".sectionNum").length > 0)	
			numSection = $(this).parents(".note").find(".sectionNum").val();	
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
		else if(currentNoteEdit.parents(".note").find(".sectionNum").length > 0)	
			numSection = currentNoteEdit.parents(".note").find(".sectionNum").val();
		
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
		note = currentNoteEdit.parents(".note");
		note.find("input[type=button]").remove();
		currentNoteEdit = null;
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
		currentTitleEdit.editablize({toolBar:false}).unbind('click').focus();
		
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
	$(this).find("span").remove();
	this.oldContent = $(this).html();

	/*MODE CONTENTEDITABLE*/
	currentTitleEdit.editablize({toolBar:false}).unbind('click').focus();
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
		
	    if(!is_touch_device())
		    {
			activateNBPannelSortable();
			}
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
	 if(title = prompt('Please give a Title to your notebook'))
	 {
		new xShuttle(
		{
			datas : {action:"createNotebook",title:title},
			onReturn : reloadNBList
		});			 	
	 }
}
function addNote(ev){
	if(typeof currentNoteEdit != "undefined" && currentNoteEdit)
		$(document.body).trigger('click.editContent');
		
	currentNoteEdit = $(this);
	
	txtArea = $('<textarea/>').css("width","100%");
	editArea = $('<div class="editArea"/>').css({minHeight:"200px",backgroundColor:"#FFF"});
	btSubmit = $('<input type="button" value="save" class="saveButton">');
	typeList = $('<select name="type"><option value="html">Text</option><option value="tweet">Tweet</option><option value="soundcloud">SoundCloud</option></select>').change(changeNoteType);
	$(this).append(typeList).append(editArea).append(txtArea).append(btSubmit).addClass('editing');//.css({height:"auto"});
	if(!$('body').hasClass('touchable'))
		$(this).css({height:"auto"});
	triggerContentHeight();
	//txtArea.focus().tinymize();
	txtArea.hide();
	editArea.editablize().css("min-height","200px").focus();
	$(this).unbind("click");
	btSubmit.click(submitNewNote);
	$(document.body).bind('click.editContent',$.proxy(submitNewNote, btSubmit) );
	$("#noteBook").animate({scrollTop: $(this).offset().top + $("#noteBook").scrollTop() - 100}, 1000,'easeInOutCubic');
	ev.stopPropagation();
}
function changeNoteType(){
	type = $(this).val();
	parent = $(this).parent(".note, .addNote");
	switch(type){
		case "tweet" : 
			parent.find(".editArea").uneditablize().hide(); parent.find("textarea").show().focus();
		break;
		case "soundcloud" : 
			parent.find(".editArea").uneditablize().hide(); parent.find("textarea").show().focus();
		break;
		default : 
			parent.find(".editArea").editablize().show().get(0); parent.find("textarea").hide();
		
		break;
	}
}

//Fired when submiting new note
function submitNewNote(ev){
	if( !$(ev.target).hasClass("saveButton") && 
		(
			$(ev.target)[0] == currentNoteEdit[0]
			|| $(ev.target).parents(".noteContent, .addNote")[0] == currentNoteEdit[0]
			|| $(ev.target).parents(".scrollerContener").length > 0
			|| $(ev.target).parents("#contentEditToolBar").length > 0
			|| $(ev.target).attr("name") == "type"
		)
	)
			{return null;}
	position = null;
	options = {};
	parent = $(this).parent();
	if(parent.find("*[name=notePosition]").length > 0)
		position = parent.find("*[name=notePosition]").val();
	numSection = null;
	if($(this).parents(".section").length > 0)
		numSection = $(this).parents(".section").find(".sectionPosition").val();
	if($(this).parents(".note, .addNote").find("select[name='type']").length > 0)
		options.type = $(this).parents(".note, .addNote").find("select[name='type']").val();

	/*
	newVal = txtArea.val();
	txtArea.tinymce().execCommand('mceRemoveControl');
	txtArea.remove();
	*/

	newVal = $(".editArea",parent).html();
	if(moduleCodeTypes.indexOf(options.type) != -1)
		newVal = parent.find("textarea").val();
	//console.log($(".editArea",parent))
	$(".editArea",parent).uneditablize();
	btSubmit.remove();
	$(document.body).unbind('click.editContent');
	if(newVal != "")
		createNote(newVal,position,numSection,options);
	else
		{
			$(this).empty().css({height:""}).removeClass('editing').bind("click",addNote);
		}
	
}
function createNote(content,position,numSection,options){
	url = "index.php";
	options = $.extend({},options);
	datas = {action:"addNote","content":content,"file":$('#currentNBFile').val()}
	if(position != null)
		datas.position = position;
	if(numSection != null)
		datas.sectionNum = numSection;
	if(options.type)
		datas.type = options.type;
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
/*
function cancelEditable(){	
	currentNoteEdit.uneditablize().bind("click",noteClick);
	currentNoteEdit.parents(".note").find("input[type=button]").remove();
}
*/

function onNoteBookScroll(e){
	localStorage.setItem( "position_top_" + $('#currentNBFile').val() , $('#noteBook').scrollTop() );
}

function activateNBPannelSortable(){
	$('#nbPannel').sortable('enable').sortable({
		placeholder: "emptyPlaceHighlight",
    	cancel : ".note, select, textarea, .editArea",
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
    	cancel : ".handle *, select, textarea, .editArea",
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
function triggerContentHeight(){
	//To avoid IE 1second freeze, we trigger the resize later
	setTimeout(function(){
		$('#noteBook').trigger("hResize").trigger("mouseout").trigger("mouseover");
	},10);
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
  $.fn.editablize = function(options) {
  	options = options || {};
	function createEditToolsBar(){
		//Edit buttons bar. Each button must have "contentEditControl" class, and the action name on a class named "actionName", like "actionBold" for "bold" action
		EditToolBar = $('<ul id="contentEditToolBar">'
			+'<li class="contentEditControl actionIncreaseFontSize">+</li>'
			+'<li class="contentEditControl actionDecreaseFontSize">-</li>'
			+'<li class="contentEditControl actionBold">B</li>'
			+'<li class="contentEditControl actionItalic">I</li>'
			+'<li class="contentEditControl actionUnderline">U</li>'
			+'<li class="contentEditControl actionJustifyLeft">&nbsp;</li>'
			+'<li class="contentEditControl actionJustifyRight">&nbsp;</li>'
			+'<li class="contentEditControl actionJustifyCenter">&nbsp;</li>'
			+'<li class="contentEditControl actionJustifyFull">&nbsp;</li>'
			+'</ul>');
		//$("#mainContent").append(EditToolBar);
		$("body").append(EditToolBar);
		$('#contentEditToolBar .contentEditControl').mousedown(editAction);
		EditToolBar.hide();
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
		param = null;
		if(action == "increasefontsize")
			{
				fontSize = document.queryCommandValue("fontSize")=="" ? 3 : parseInt(document.queryCommandValue("fontSize"));
				param = Math.min( fontSize + 1 , 7);
				action = "fontSize";
			}
		if(action == "decreasefontsize")
			{
				fontSize = document.queryCommandValue("fontSize")=="" ? 3 : parseInt(document.queryCommandValue("fontSize"));
				param = Math.max( fontSize - 1, 0);
				action = "fontSize";
			}
		
		document.execCommand(action,false,param);
		return false;
	}
	function updateToolBarPosition(ev){
		range = getRange();
		tbTop = $('#contentEditToolBar').offset().top;
		parent = $('#contentEditToolBar').parent()
		parentTop = parent.offset().top - $('body').scrollTop();
		if(range && range.getClientRects && range.getClientRects().length > 0)
			//tbTop = range.getClientRects()[0].bottom + 10;
			tbTop = range.getClientRects()[0].bottom - parentTop + 10;
		$('#contentEditToolBar').css('top', tbTop + "px");
		$('#contentEditToolBar').css('left', "0");
		//console.log(top);
	}
	
	function getRange() {
    var sel, range;
    if (window.getSelection) {
        sel = window.getSelection();
        if (sel.rangeCount) {
            range = sel.getRangeAt(0);            
        }
    } else if (document.selection && document.selection.createRange) {
        range = document.selection.createRange();
    }
    return range;
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

	displayToolBar = typeof options.toolBar == "undefined" ? true : options.toolBar;
	$('body').addClass("editing");
	if(displayToolBar && !is_small_screen())
		{
			$('#contentEditToolBar').fadeIn();
			$(this).bind("keyup.editToolsBar",updateToolBarPosition);
			$('html').bind("touchEnd.editToolsBar click.editToolsBar mousedown.editToolsBar",updateToolBarPosition);
			$(this).parents(".note").append($('#contentEditToolBar'));
		}
	
	//setTimeout($.proxy(function(){$(this).attr("contenteditable","").focus()}, this) , 500);
	mobile_FullScreenOff();
	$(this).attr("contenteditable","").focus()
	updateToolBarPosition()
	return $(this);
  }
//disable the edit capability of an element and hide edit buttons 
$.fn.uneditablize = function() {
	$('#contentEditToolBar').fadeOut();
	$('body').removeClass("editing");
	$(this).unbind("keyup.editToolsBar");
	$('html').unbind("touchEnd.editToolsBar click.editToolsBar mousedown.editToolsBar");
	mobile_FullScreenOn();
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
			
	//If menu opened on smallScreen, close it
	$("#col1, #col1 .active").removeClass("active");
	this.startWaiting();
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
	this.stopWaiting();
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
	    	function(){
	    		if(!is_touch_device())
	    			$(this).stop(true).css("opacity",1)
	    		},
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
	},
	startWaiting : function(){
		if($("#shuttleWaiting").length > 0)
			$("#shuttleWaiting").show();
		else
		{
			shuttleWaiting = $('<div id="shuttleWaiting"><div class="vAligner"></div><div id="shuttleWaitIndicator"></div></div>');
			$('body').append(shuttleWaiting);
		}
	},
	stopWaiting : function(){
		$("#shuttleWaiting").hide();		
	}
});

//triggered when window is resized
function onWindowResized(e){
	var wH = window.innerHeight;
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
	//var wH = $(window).height();
	var wH = window.innerHeight;
	//if($('body').hasClass('isIphone'))
		//wH = Math.max($(window).height(),$('html').height());
	var wW = $(window).width();
	if(!is_small_screen())
		{
		var nbHei = wH - $("header").height() - $(".noteBookTitle").height() - 50;
		if(is_touch_device())
			{
			$('#mainContent').css({height : (wH - $("header").height() - 50) + "px"});
			nbHei -= $(".btAddNote").outerHeight();
			}
		nbWid = wW - $("#col1").width() - 40;
		$('#noteBook').css({width: nbWid + "px" , height : nbHei + "px"});
		nblistHei = (wH - $("header").outerHeight()) / 2 - 30;
		$('#nbList').css({height : nblistHei + "px"});
		$('#noteBook, #nbList').trigger("hResize");
		window.scrollTo(0, 0);
		}
	else
		{
		//nbMain = wH - $(".btAddNote").outerHeight();
		$('#mainContent').css({ height : wH + "px"});		
		nbHei = wH - $(".noteBookTitle").outerHeight() - $("#toolsLinks").outerHeight() - $('header').outerHeight(true);
		$('#noteBook').css({width: "100%" , height : nbHei + "px"});
		nblistHei = wH - $('#notebooksTools').outerHeight(true);
		$('#nbList').css({height : nblistHei + "px"});
		$('#noteBook, #nbList').trigger("hResize");
		
		$('#col1').css("top", $('header').outerHeight(true) + "px");
		mobile_FullScreenOn();
		}
}
$(window).resize(onWindowResized);
windowResized();

//Mobile & Touch functions

/**IPHONE SPECIFIC***/
//If iphone browser, hide the bar
if((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i))) {
	var app = document.URL.indexOf( 'http://' ) === -1 && document.URL.indexOf( 'https://' ) === -1;
	//console.log(window);
	//alert("document.URL" + document.URL);
	if ( !app ) {
	// web browser, not application
	//alert("WEB BROWSER");
	window.addEventListener("load",function() {
	// Set a timeout...
	setTimeout(function(){
		$('body').addClass('isIphone');
		//calculateHTMLHeight();
		mobile_FullScreenOn();
		//window.scrollTo(0, 1);
		}, 0);
	});
	} else {
	    // Web page
	}
}
/*DEPRECATED : WHEN mobile_FullScreenOn didn't exist, IN ORDER TO HIDE =< IOS6 BAR*/
/*
function calculateHTMLHeight(){
	// Hide the address bar!
	//alert($('html').height());
	$('html').css("height","auto");
	newHeight = $('html').height() + 60;
	$('html').css("height",newHeight + "px");
	windowResized();
	//window.onscroll = scrollHasMoved;
	//setInterval(window.scrollTo, 3000, 0, 1);
}
*/
/**END IPHONE SPECIFIC***/

function mobile_FullScreenOn(){
	headHeight = $('header').outerHeight(true);
	//if($('body').hasClass('isIphone'))
		$('#mainContent').bind("click.mobileScroll", function(){window.scrollTo(0, headHeight);});
	window.scrollTo(0, headHeight);
}
function mobile_FullScreenOff(){
	$('#mainContent').unbind("click.mobileScroll");
}
if(typeof window.scrollTo == "undefined")
	window.scrollTo = function(leftVal,topVal){
		$(window).scrollTop(topVal).scrollLeft(leftVal);
	}

function is_touch_device() {
  return !!('ontouchstart' in window) // works on most browsers 
      || !!('onmsgesturechange' in window); // works on ie10
};
function is_small_screen() {
	wW = $(window).width();
	if(wW < smallScreenSize)
		return true;
	return false;
};

function activeTouchSort()
    {
    $('#noteBook').addClass("touchSortable");
    activateNBPannelSortable();
    activateNotesListSortable();
    }
function unactivateTouchSort()
    {
    $('#noteBook').removeClass("touchSortable");
    unactivateSortable($( ".notesList, #nbPannel" ))
    }

function onBodyLoad(){
	//touch screen
	if(is_touch_device())	
	{
		$('body').addClass("touchable");
		$('#loginMenu').click(function(evt){$(this).toggleClass('active');});
		$('body').click(function(evt){
			if($(evt.target).attr('id') != 'loginMenu')
				$('#loginMenu').removeClass('active');
			});		
	}
	//small screen
	$(".listSwitcher").bind('click',function(){
		$("#col1").toggleClass("active");
	});

	/*
	$(window).bind('onorientationchange', function(){
		calculateHTMLHeight();
		//onWindowResized();
		});
*/

	$( ".trash" ).sortable().tooltip();
	$('#nbList li A').click(clickNBLink);
	$('.linkNewNB').click(createNB);
	if(window.location.hash != "")
		{
		if(window.location.hash.substr(0,3) == "#__")
			{
			datas = {
				action : window.location.hash.substr(3)
				};
			shuttle = new xShuttle({datas:datas, onReturn : function(datas){$("#mainContent").html(datas);}});				
			}	
		else
			{	
			noteBook = window.location.hash.split('/')[0];
			if($("a[href='" + noteBook + "']").length > 0)
				$("a[href='" + noteBook + "']").click();
			else
				$('#nbList li:first-child a').click();
			}
		
		}
	else
		{
			$($('#nbList a')[0]).click();
		}		
}
//fucking ios7 safari viewport...
isIOS7 = false;
if(navigator.userAgent.match(/(iPad|iPhone);.*CPU.*OS 7_\d/i))
	isIOS7 = true;
if(isIOS7)
{
	oldWindowHei = window.innerHeight;
	function resizeIOS7(){
		newHei = window.innerHeight;
		if(oldWindowHei != newHei)
			$(window).trigger('resize');
		oldWindowHei = newHei;
	}
	window.setInterval(resizeIOS7, 1000);
}
