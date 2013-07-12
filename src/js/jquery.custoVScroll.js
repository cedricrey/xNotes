/**Customized Vertical Scroll
 * I'm new to jQuery, and have never developped jQuery plugin.
 * I'm regular with Prototype, so please be indulgent.
 * I know that is not at all well developped function as preconized by jQuery documentation,
 	I'm sorry. I'll try to make it better.
  You can customize the bar and the scroller with CSS using .scrollerContener and .scroller classes:
  <div class="scrollerContener> <----- fixed bar
  	<div class="scroller"></div> <--- scroller that move with content
  </div>
  The only style applied to them is an absolute position and top:0 for both,
  and display to 'none' and height to 100% for scrollerContener by default
  Also, your contener will have the overflow to 'hidden' and a 'relative' position.
  ========= > !!!! BUT you'll have to set your content height the heigh you want !!!!  <===========
  As it's not optimized, the scrollbar is a DIV.
  	You can use it on a <UL> or <P> as we do it in JS even if it's semantically incorrect.
  	
 USE : $(FILTER).custoVScroll({autoHeight:true|false,step:10})
 	the arguments array is optionnal, as each of argument.
 	autoHeight : (default=true) set a proportionnal scroller height (the more the content is high, the more the scroller will be small)
 	setp : (defaut=10) in pixels, how many pixel content is scrolled when using mousewheel 
 */

jQuery.fn.custoVScroll = function (opts) {
	opts = opts || {};
	currentScrollingBar = null;
	jQuery.fn.custoVScroll.propScrollerHeight = typeof opts.autoHeight != "undefined" ? opts.autoHeight : true;
	jQuery.fn.custoVScroll.scrollStep = typeof opts.step != "undefined" ? opts.step : 10;
	jQuery.fn.custoVScroll.inertie = typeof opts.inertie != "undefined" ? opts.inertie : 0;
	
	return this.each(function(){
		/*Custo Scroll is useless for touch screen*/
		if(is_touch_device())
			{
			$(this).css("overflow","auto");
			return true; //= 'continue;'
			}
		currentScrollingOver = null;

		//To find 'this' in any condition...
		$(this).addClass('scrolledContent');
			
		this.calculRealHeight = function(){
			var initHeight = $(this).outerHeight(true);
			var initScrollTop = $(this).scrollTop();
			contentClone = $(this).clone();
			//set the normal height just a moment to get the real height (invisible for user, I swear)
			contentClone.css({height:"auto"});
			contentClone.scrollTop(0);			
			$(this).parent().append(contentClone);
			this.props = {
				totalHeight:contentClone.outerHeight(true),
				notScrollable : false
				};
			contentClone.remove();
			if(this.props.totalHeight < initHeight)
				this.props.notScrollable = true;
			
			
			//if the "autoHeight" option is true or not indicated, set a proportional height to the scroller
			if(jQuery.fn.custoVScroll.propScrollerHeight)
				$(this).find(".scroller").height((initHeight*initHeight / this.props.totalHeight)+"px");
			//Set the scroller to the new position
			if(this.setScrollerPosition)
				this.setScrollerPosition();			
		}
		
		//Initialisation of visual elements (scroller, content, scoller contener)
		$(this).css({overflow:"hidden", position:"relative"});
		var scrollerContener = $("<div>").css({position:"absolute",display:"none",top:0,height:"100%"}).addClass("scrollerContener");
		var scroller = $("<div>").css({position:"absolute",top:0}).addClass("scroller");
		scrollerContener.append(scroller);
		$(this).append(scrollerContener);
		
		//Get the content Height and set the scroller height
		this.calculRealHeight();
		
		//Update the content and scroller height when image is loaded
		$(this).find("IMG").bind("load",function(e){
			//console.log($(this).parents(".scrolledContent"));
			$(this).parents(".scrolledContent")[0].calculRealHeight()
			});
		//When the mouse is over this content,
		//displays the scrollbar and attach mousewheel event to this content
		$(this).hover(
				function(e){
					if(this.props.notScrollable)
						return false;
					//When mouse over, indicate what is content covered
					currentScrollingOver = this;
					$(this).find(".scrollerContener").fadeIn();
					//Specific mousewheel management : 1=this, 2=up function, 3=down fn, 4=do we prevent default?
					jQuery.event.mousewheel.giveFocus(this, 
								function(event, delta){
									this.stopInertie();
									//currTop = parseInt($($(this).children()[0]).css("marginTop")) || 0;
									currTop = $(this).scrollTop();
									currTop -= jQuery.fn.custoVScroll.scrollStep*delta;
									if(currTop > this.props.totalHeight - $(this).outerHeight(true))
										currTop = this.props.totalHeight - $(this).outerHeight(true);
									$(this).scrollTop(currTop);
									//$($(this).children()[0]).css({marginTop:currTop+"px"});
									//set the good scroller position
									this.setScrollerPosition();
									if(jQuery.fn.custoVScroll.inertie)
									{
										//this.currentInertOrientation = 1;
										this.currentInertSpeed = jQuery.fn.custoVScroll.scrollStep*delta;
										this.currentInertie = jQuery.fn.custoVScroll.inertie;
										this.inertie();
									}
								},
								function(event, delta){
									this.stopInertie();
									//currTop = parseInt($($(this).children()[0]).css("marginTop")) || 0;
									currTop = $(this).scrollTop();
									currTop -= jQuery.fn.custoVScroll.scrollStep*delta;
									if(currTop < 0)
										currTop = 0;
									$(this).scrollTop(currTop);									
									//$($(this).children()[0]).css({marginTop:currTop+"px"});
									//set the good scroller position
									this.setScrollerPosition();
									if(jQuery.fn.custoVScroll.inertie)
									{
										//this.currentInertOrientation = -1;
										this.currentInertSpeed = jQuery.fn.custoVScroll.scrollStep*delta;
										this.currentInertie = jQuery.fn.custoVScroll.inertie;
										this.inertie();
									}
								}, 
								true);					
				},
				function(){
					//When mouse out, indicate that no content is covered
					currentScrollingOver = null;
					//If this content scrollbar is not in move (ie: mousedown), hides it
					if(currentScrollingBar != this)
						$(this).children(".scrollerContener").fadeOut();
					//Remove the mousewheel event survey
					jQuery.event.mousewheel.removeFocus(this);
				});		
		$(this).bind("hResize",function(evt){
			this.calculRealHeight();
		});
		$(this).scroll(function(evt){
			this.setScrollerPosition();
		})


		//function to set the good scroller position when moving with wheel/scrolling
		this.setScrollerPosition = function(){
			scrollerTop = ($(this).find(".scrollerContener").outerHeight(true) - $(this).find(".scroller").outerHeight(true)) * -$(this).scrollTop() / ($(this).outerHeight(true) - this.props.totalHeight);
			$(this).find(".scroller").css({top:scrollerTop+"px"});
			$(this).find(".scrollerContener").css("top",$(this).scrollTop()+"px");
		}
		
		this.disableTextSelection = function(){
			if($.browser.mozilla){//Firefox
                $(this).css('MozUserSelect','none');
            }else if($.browser.msie){//IE
                $(this).bind('selectstart',function(){return false;});
            }else{//Opera, etc.
                $(this).bind('mousedown',function(){return false;});
            }
		};
		this.enableTextSelection = function(){
			if($.browser.mozilla){//Firefox
                $(this).css('MozUserSelect','');
            }else if($.browser.msie){//IE
                $(this).unbind('selectstart',function(){return false;});
            }else{//Opera, etc.
                $(this).unbind('mousedown',function(){return false;});
            }
		};
		//function called when mouse down over a scroller to move content+scroller
		this.onScrollerMove = function(e, element){
			this.stopInertie();
			currScroller = $(element).find(".scroller");
			currScrollerContener = $(element).find(".scrollerContener");
			diff = element.currYPosition - e.pageY;
			scrollerTop = parseInt(currScroller.css("top"));
			scrollerTop -= diff;
			
			if(scrollerTop < 0)
				scrollerTop = 0;			
			else if(scrollerTop > currScrollerContener.outerHeight(true) - currScroller.outerHeight(true))
				scrollerTop = currScrollerContener.outerHeight(true) - currScroller.outerHeight(true);
			else			
				element.currYPosition = e.pageY;
			currScroller.css({top:scrollerTop+"px"});
			
			contentTop = ($(element).outerHeight(true) - element.props.totalHeight) * -scrollerTop / (currScrollerContener.outerHeight(true) - currScroller.outerHeight(true));
			currScrollerContener.css("top",contentTop+"px");
			$(element).scrollTop(contentTop);
			
			if(jQuery.fn.custoVScroll.inertie)
			{
				this.currentInertSpeed = diff;
			}
			
		};
				//function to set the good scroller position when moving with wheel/scrolling
		this.inertie = function(){
			currTop = $(this).scrollTop();
			currTop -= this.currentInertSpeed * this.currentInertie/jQuery.fn.custoVScroll.inertie;
			if(currTop < 0)
				currTop = 0;
			else if(currTop > this.props.totalHeight - $(this).outerHeight(true))
				currTop = this.props.totalHeight - $(this).outerHeight(true);
			$(this).scrollTop(currTop);
			this.setScrollerPosition();
			
			this.currentInertie--;
			if(this.currentInertie > 0)
				this.currentInertTimeOut = setTimeout($.proxy(this.inertie,this),20);
		};
		this.stopInertie = function(){
			clearTimeout(this.currentInertTimeOut);			
		};
		//When clicking on scroller
		$(this).find(".scroller").mousedown(function(e){
			currentScrollingBar = $(this).parents(".scrolledContent")[0];
			currentScrollingBar.currYPosition = e.pageY;
			//document is binded in order to move scroller even when mouse is out of content
			$(document).bind("mousemove.scroller",function(event) {
				currentScrollingBar.onScrollerMove(event, currentScrollingBar);
			});
			currentScrollingBar.disableTextSelection();
		});
		//When leave the click on scroller
		$(document).mouseup(function(e){
			if(currentScrollingBar)
				{
				currentScrollingBar.enableTextSelection();
				if(jQuery.fn.custoVScroll.inertie)
				{
					currentScrollingBar.currentInertie = jQuery.fn.custoVScroll.inertie;
					currentScrollingBar.inertie();
				}
				}
			$(document).unbind("mousemove.scroller");
			currentScrollingBar = null;
			if(currentScrollingOver == null)
				$(".scrollerContener").hide();
		});
	});
	
};


jQuery.event.mousewheel = {
	giveFocus: function(el, up, down, preventDefault) {
		if (el._handleMousewheel) jQuery(el).unmousewheel();
		
		if (preventDefault == window.undefined && down && down.constructor != Function) {
			preventDefault = down;
			down = null;
		}
		
		el._handleMousewheel = function(event) {
			if (!event) event = window.event;
			if (preventDefault)
				if (event.preventDefault) event.preventDefault();
				else event.returnValue = false;
			var delta = 0;
			if (event.wheelDelta) {
				delta = event.wheelDelta/120;
				if (window.opera) delta = -delta;
			} else if (event.detail) {
				delta = -event.detail/3;
			}
			if (up && (delta > 0 || !down))
				up.apply(el, [event, delta]);
			else if (down && delta < 0)
				down.apply(el, [event, delta]);
		};
		
		if (window.addEventListener)
			window.addEventListener('DOMMouseScroll', el._handleMousewheel, false);
		window.onmousewheel = document.onmousewheel = el._handleMousewheel;
	},
	
	removeFocus: function(el) {
		if (!el._handleMousewheel) return;
		
		if (window.removeEventListener)
			window.removeEventListener('DOMMouseScroll', el._handleMousewheel, false);
		window.onmousewheel = document.onmousewheel = null;
		el._handleMousewheel = null;
	}
};

function is_touch_device() {
  return !!('ontouchstart' in window) // works on most browsers 
      || !!('onmsgesturechange' in window); // works on ie10
};
