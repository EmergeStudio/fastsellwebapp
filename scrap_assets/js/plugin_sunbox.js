/*
jQuery Sun Box
Created By: Chris Humboldt (www.chrismodem.com)
*/

;(function($) {
		   
//----------------------------------------------------------------------------- INITIAL SETUP
	var $window_w		= $(window).width();		// Window Width
	var $window_h		= $(window).height();	// Window height
	var $this			= '';
	var $target_path	= '';
	var $pop_height		= '';
//----------------------------------------------------------------------------- INITIAL SETUP



//----------------------------------------------------------------------------- FUNCTIONS
	
	// ---------- CREATE SUNBOX
	$.fn.sunBox = function(settings) 
	{
		// Settings select and user adjustments
		$settings = $.extend({}, $.fn.sunBox.defaults, settings);	
		
		// Initilize on click
		$(this).click(function()
		{	
			$this 			= $(this);
			$target_path	= $this.attr('href');
			$title			= $this.attr('title').toUpperCase();
			$img_check		= $this.find('img').is('img');
			
			// Kill the link
			$this.removeAttr('href');
			
			// Attach the loader
			if($settings.show_overlay == true)
			{
				$.fn.sunBox.overlay($settings);
			}
			$.fn.sunBox.full_loader($settings);
			
			// Attach the sunbox
			$.fn.sunBox.attach_html($title, 'sunBoxModal');
			
			// Attach content
			if($img_check == true)
			{
				$('.sunBoxModal .contentCenter').html('<img src="'+ $target_path +'" />');
				
				//$('.contentCenter img').load(function()
				//{
					$.fn.sunBox.adjust($img_check);
				//});
			}
			else
			{
				$('.sunBoxModal .contentCenter').html('<div class="iframeLoader"></div><iframe class="sunFrame" onload="$.fn.sunBox.showIframe()" width="100%" height="100%" src="'+ $target_path +'" ></iframe>');
				$.fn.sunBox.adjust($img_check);
			}
			
			
			// Load the close function
			$('.sunBoxModal.sunBox .modalClose').click(function()
			{
				$('.sunBoxModal.sunBox').remove();
				$.fn.sunBox.remove_overlay();
				$this.attr('href', $target_path);
			});
		});
	};
	
	
	// ---------- SUNBOX HTML
	$.fn.sunBox.attach_html = function($title, $class) 
	{		
		if($class != null)
		{
			$class_name	= $class;
		}
		else
		{
			$class_name	= $settings.class_name;
		}
		
		$('body').append('<div class="sunBox '+ $class_name +'"><div class="titleContain"><div class="titleTopCenter"><div class="modalClose returnFalse" onclick=""></div><div class="titleText">'+ $title +'</div></div></div><div class="contentContain"><div class="contentCenter"></div></div></div>');
		
		if($.browser.msie && parseInt($.browser.version) <= 6)
		{
			$('.sunBox').css({ position: 'absolute' });
		}
	};
	
	
	// ---------- SUNBOX ADJUSTMENTS
	$.fn.sunBox.adjust = function($img_check)
	{	
		// Adjustments
		if($img_check == true)
		{
			$edit_width		= $('.contentCenter img').width();
			$edit_height	= $('.contentCenter img').height();
		}
		else
		{
			$edit_width		= $settings.width;
			$edit_height	= $settings.height;
		}
		
		if($edit_height > ($window_h - 80))
		{
			$edit_height = $window_h - 80;
		}
		
		if($edit_width > ($window_w - 50))
		{
			$edit_width = $window_w - 50;
		}
		
		if($img_check == 'full screen')
		{
			$edit_height 	= $window_h - 80;
			$edit_width 	= $window_w - 50;
		}
		
		$('.sunBox.sunBoxModal').hide('fast', function()
		{
			$('.sunBoxModal .contentCenter').css({ height: $edit_height });
			$('.sunBoxModal .titleTopCenter, .sunBoxModal .contentCenter').css({ width: $edit_width });
			
			$('.sunBox.sunBoxModal').css({ left: (($window_w - ($edit_width + 8)) / 2), top: 18 });
			
			// Attach loader for iframe
			if(($img_check == false) || ($img_check == 'full screen'))
			{
				$('.sunBoxModal .iframeLoader').css({ top: ($edit_height - 32) / 2, left: ($edit_width - 32) / 2 });
			}
			
			// Remove the loader
			$.fn.sunBox.remove_loader();
			// Show the sun box
			$('.sunBoxModal.sunBox').fadeIn($settings.speed);
			// The title must be draggable - this is needs to be removed when a public plugin
			$('.sunBoxModal.sunBox').draggable({ handle : '.titleTopCenter' });
		});
	};
	
	
	// ---------- SUNBOX ADJUSTMENTS
	$.fn.sunBox.showIframe = function ()
	{	
		$('.iframeLoader').hide();
		$('.sunBoxModal .sunFrame').fadeIn('fast');
	};


	// ---------- MESSAGE
	$.fn.sunBox.message = function(settings) 
	{		
		// Settings select and user adjustments
		$settings = $.extend({}, $.fn.sunBox.defaults, settings);
		
		// Some variables
		$choice_w		= 0;
		
		// Attach overlays and stuff
		$.fn.sunBox.overlay($settings);
		$.fn.sunBox.remove_loader();
		$('.sunBox.popupMain').css({ zIndex : 99 });
		
		// Content
		$settings.class_name	= 'sunMessage';
		$.fn.sunBox.attach_html($settings.message_title);
		$('.sunBox.sunMessage .contentCenter').html('<div class="message"><div class="text">' + $settings.content + '</div></div>').width($settings.message_width);
		$('.sunBox.sunMessage .titleTopCenter').width($settings.message_width);
		
		// Adjustments
		$message_h	= $('.sunBox.sunMessage .contentCenter').height();
		$message_w	= $('.sunBox.sunMessage .contentCenter').width();
		
		// Add in the buttons		
		if(($settings.btn_true != '') || ($settings.btn_false != ''))
		{
			$('.sunBox.sunMessage .message').after('<div class="choiceContain"></div>');
			$message_h	= $message_h + 57;
		}
		
		if($settings.btn_true != '')
		{
			$('.sunBox.sunMessage .choiceContain').append('<div class="scrapButton blueButton returnTrue">'+ $settings.btn_true +'</div>');
			$choice_w		= $choice_w + $('.choiceContain .scrapButton.returnTrue').width();
			$('.sunBox.sunMessage .returnTrue').click(function()
			{
				$.fn.sunBox.close_message();
				$settings.callback(true);
			});
		}
		if($settings.btn_false != '')
		{
			$('.sunBox.sunMessage .choiceContain').append('<div class="scrapButton returnFalse" onclick="">'+ $settings.btn_false +'</div>');
			$choice_w		= $choice_w + $('.choiceContain .scrapButton.returnFalse').width();
			$('.sunBox.sunMessage .returnFalse').click(function()
			{
				$.fn.sunBox.close_message();
				$settings.callback(false);
				$('.sunBox.popupMain').css({ zIndex : 300 });
			});
			$('.sunBox.sunMessage .returnTrue').click(function()
			{
				$('.sunBox.popupMain').css({ zIndex : 300 });
			});
		}
		
		// Close
//		$('.sunBox.sunMessage .modalClose').click(function()
//		{
//			$.fn.sunBox.close_message();
//			$('.sunBox.popupMain').css({ zIndex : 300 });
//			$settings.callback(false);
//		});
		
		// CSS
		$('.sunBox.sunMessage').hide();
		if(($settings.btn_true != '') || ($settings.btn_false != ''))
		{
			$('.choiceContain').width($choice_w + 65);
		}
		$('.sunBox.sunMessage .contentCenter').css({ height: $message_h });
		$('.sunBox.sunMessage').css({ left: (($window_w - ($message_w + 8)) / 2), top: 70 });
		$('.sunBox.sunMessage').fadeIn();
		
		// Window resize
		$.fn.sunBox.window_resize($settings);
	};
	
	
	// ---------- CLOSE MESSAGE
	$.fn.sunBox.close_message = function()
	{
		$count	= 0;
		
		$('.sunBox:visible').each(function()
		{
			$count++;
			
			if($count > 1)
			{
				return false;
			}
		});
		
		if($count < 2)
		{
			$.fn.sunBox.remove_overlay($settings);
			$.fn.sunBox.remove_loader($settings);
			$('.sunBox.sunMessage').remove();
		}
		else
		{
			$('.sunBox.sunMessage').remove();
		}
	};


	// ---------- POPUP
	$.fn.sunBox.popup = function($heading, $class, settings)
	{
		// Settings select and user adjustments
		$settings = $.extend({}, $.fn.sunBox.defaults, settings);
		
		// Get the data
		$.post($settings.ajax_path,
		{
			ajax_call	: 'yupthisisanajaxcall'
		},
		function($data)
		{	
			if($data == 9876)
			{
				// Logout
				$.scrap_logout;
			}
			else
			{
				$.fn.sunBox.attach_html('', 'popupMain ' + $class);
				$('.' + $class + ' .titleText').text($heading);
				
				$('.' + $class + ' .contentCenter').html('<div class="popup">' + $data + '</div><div class="botBlock" style="padding-left:'+ (($settings.width / 2) - 55) +'px;"><div class="scrapButton btnSave blueButton returnTrue" onclick="">Save</div><div class="scrapButton btnCancel returnFalse" onclick="">Close</div></div>').width($settings.width);
				$('.' + $class + ' .titleTopCenter').width($settings.width);
				
				// Set the dimensions
				$content_h	= $('.' + $class + ' .contentCenter').height();
				$message_h	= $content_h;
				$message_w	= $('.' + $class + ' .contentCenter').width();
				
				// CSS
				$('.' + $class +' .contentCenter').css({ height: $message_h });
				
				// Return
				$('.returnTrue').live('click', function()
				{
					settings.callback(true);
				});
				
				// Uniform the popup
				$('.' + $class + ' select, .' + $class + ' input:checkbox, .' + $class + ' input:radio').uniform();
				
				
				// Adjust the height
				$.fn.sunBox.adjust_popup($class);
				
				// Window resize
				$.fn.sunBox.window_resize();
				
				// Close popup
				$('.' + $class + ' .modalClose, .' + $class + ' .returnFalse').live('click', function()
				{
					settings.callback(false);
					$.fn.sunBox.close_popup($class);
				});
			}
		});
	};
	
	
	// ---------- CLOSE POPUP
	$.fn.sunBox.close_popup = function($class)
	{
		//$('.popupMain').hide();
		$('body').css({ overflow : 'visible' });
		$('.' + $class + '.popupMain .greyBlock input, .' + $class + '.popupMain .greyBlock2 input').val('');
		$('.' + $class + '.popupMain .greyBlock textarea, .' + $class + '.popupMain .greyBlock2 textarea').val('');
		//$('.popupMain select').val(0)
		$('.' + $class + ' .inpFile, .' + $class + ' .attachFiles br').remove();
		$('.' + $class).animate({ top: -($('.' + $class).height() + 100) }, 350);
		$('.newOverlay').fadeOut(500, function()
		{
			$('.newOverlay').remove();
		});
	};
	
	
	// ---------- SHOW POPUP
	$.fn.sunBox.show_popup = function($class)
	{
		$('body').css({ overflow : 'hidden' });
		$.fn.sunBox.overlay();
		$.fn.sunBox.adjust_popup_height($class);
		$('.' + $class).show().animate({ top : 30 }, 350, 'easeOutCubic');
	};
	
	
	// ---------- SUNBOX POPUP ADJUSTMENT
	$.fn.sunBox.adjust_popup = function($class)
	{	
		$window_h	= $(window).height();
		$content_h	= $('.' + $class + ' .popup').height();
		
		if(($content_h + 124) > $window_h)
		{
			$('.' + $class + ' .contentContain').height($window_h - 124);
			$('.' + $class + ' .popup').height($window_h - 236);
		}
		
		$('.' + $class).hide();
		$('.' + $class).css({ left: (($window_w - ($message_w + 8)) / 2), top: -($('.' + $class).height() + 100) });
	};
	
	
	// ---------- SUNBOX POPUP ADJUSTMENT
	$.fn.sunBox.adjust_popup_height = function($class)
	{	
		$window_h	= $(window).height();
		$('.sunBox.' + $class + ' .contentCenter').css({ height : '' });
		$('.sunBox.' + $class + ' .popup').css({ height : '' });
		$popup_h		= $('.sunBox.' + $class + ' .contentCenter').height();
		$('.sunBox.' + $class + ' .contentContain').height($popup_h);
		
		if(($popup_h + 124) > $window_h)
		{
			$('.sunBox.' + $class + ' .contentContain').height($window_h - 124);
			$('.sunBox.' + $class + ' .popup').height($window_h - 236);
		}
	};


	// ---------- CREATE AN OVERLAY
	$.fn.sunBox.overlay = function(settings) 
	{	
		if($('.newOverlay').length == 0)
		{
			// Settings select and user adjustments
			$settings = $.extend({}, $.fn.sunBox.defaults, settings);
			
			// Append the elements
			$($settings.overlay_pos).prepend('<div class="newOverlay"></div>');
			$('.newOverlay').css({ opacity: 0, width: $window_w, height: $window_h });
			$('.newOverlay').animate({ opacity: $settings.overlay_opacity }, 350);
			
			// Ie6 and lower browser fix
			if($.browser.msie && parseInt($.browser.version) <= 6)
			{
				$('.middle select').hide();
			}
		}
	};


	// ---------- REMOVE THE OVERLAY
	$.fn.sunBox.remove_overlay = function() 
	{
		if($.browser.msie && parseInt($.browser.version) <= 6)
		{
			$('.newOverlay').hide();
			$('.newOverlay').remove();
		}
		else
		{
			$('.newOverlay').remove();
		}
	};


	// ---------- ATTACH THE LOADER
	$.fn.sunBox.loader = function(settings) 
	{
		if($('.loadingBG').length == 0)
		{
			// Settings select and user adjustments
			$settings = $.extend({}, $.fn.sunBox.defaults, settings);
		
			$('body').prepend('<div class="loadingBG"><div class="newLoader"></div></div>');
			if($.browser.msie && parseInt($.browser.version) <= 6){}else
			{
				$('.loadingBG').css({ position: 'fixed' });
			}
			
			$loader_L	= ($window_w - 51) / 2;
			$loader_T	= ($window_h -51) / 2;
			$('.loadingBG').css({ left: $loader_L, top: $loader_T });
		}
	};


	// ---------- REMOVE THE LOADER
	$.fn.sunBox.remove_loader = function() 
	{
		if($.browser.msie && parseInt($.browser.version) <= 6)
		{
			$('.loadingBG').hide();
			$('.loadingBG').remove();
		}
		else
		{
			$('.loadingBG').remove();
		}
	};


	// ---------- FULL LOADER
	$.fn.sunBox.full_loader = function(settings) 
	{
		// Settings select and user adjustments
		$settings = $.extend({}, $.fn.sunBox.defaults, settings);
		
		$.fn.sunBox.overlay($settings);
		$.fn.sunBox.loader($settings);
		$.fn.sunBox.window_resize($settings);
	};


	// ---------- LOADER MESSAGE
	$.fn.sunBox.loader_message = function($message, settings) 
	{
		// Settings select and user adjustments
		$settings = $.extend({}, $.fn.sunBox.defaults, settings);
		
		$.fn.sunBox.overlay($settings);
		$.fn.sunBox.loader($settings);
		$.fn.sunBox.window_resize($settings);
		$('.newOverlay').after('<div class="loaderMessage">' + $message + '</div>');
			
		$loader_w		= $('.loaderMessage').width() + 16;
		$loader_m_L		= ($window_w - $loader_w) / 2;
		$loader_m_T		= ($window_h + 65) / 2;
		$('.loaderMessage').css({ left: $loader_m_L, top: $loader_m_T });
		$('.loaderMessage').css({ opacity: 0.6 });
	};


	// ---------- WINDOW RESIZE
	$.fn.sunBox.window_resize = function(settings) 
	{
		$(window).resize(function()
		{
			// Settings select and user adjustments
			$settings = $.extend({}, $.fn.sunBox.defaults, settings);
			
			$window_w		= $(window).width();
			$window_h		= $(window).height();
			
			// Adjust the overlay and loaders
			$loader_L	= ($window_w - 51) / 2;
			$loader_T	= ($window_h -51) / 2;
			$('.loadingBG').css({ left: $loader_L, top: $loader_T });
			$('.newOverlay').css({ opacity: $settings.overlay_opacity, width: $window_w, height: $window_h });
			
			$loader_w		= $('.loaderMessage').width() + 16;
			$loader_m_L		= ($window_w - $loader_w) / 2;
			$loader_m_T		= ($window_h + 65) / 2;
			$('.loaderMessage').css({ left: $loader_m_L, top: $loader_m_T });
			
			// Adjust the messages
			$message_h	= $('.sunBox.sunMessage .contentCenter').height();
			$message_w	= $('.sunBox.sunMessage .contentCenter').width();
			$('.sunBox.sunMessage .titleTopCenter').css({ width: ($message_w - 0) });
			$('.sunBox.sunMessage .contentLeft').css({ height: $message_h });
			$('.sunBox.sunMessage .contentRight').css({ height: $message_h });
			$('.sunBox.sunMessage .contentCenter').css({ height: $message_h });
			$('.sunBox.sunMessage .bottomCenter').css({ width: $message_w });
			$('.sunBox.sunMessage').css({ left: (($window_w - ($message_w -7)) / 2) });
			
			// Popups
			$popup_w		= $('.sunBox.popupMain .contentCenter:visible').width();
			$('.sunBox.popupMain .contentCenter:visible, .sunBox.popupMain .popup:visible').height('');
			$popup_h		= $('.sunBox.popupMain .contentCenter:visible').height();
		
			if(($popup_h + 124) > $window_h)
			{
				$('.sunBox.popupMain .contentContain').height($window_h - 124);
				$('.sunBox.popupMain .contentLeft').height($window_h - 124);
				$('.sunBox.popupMain .contentRight').height($window_h - 124);
				$('.sunBox.popupMain .popup').height($window_h - 236);
			}
			else
			{
				$('.sunBox.popupMain .contentContain').height($popup_h);
				$('.sunBox.popupMain .contentLeft').height($popup_h);
				$('.sunBox.popupMain .contentRight').height($popup_h);
				$('.sunBox.popupMain .popup').height($popup_h - 112);
			}
			$('.sunBox.popupMain:visible').css({ left: (($window_w - ($popup_w + 8)) / 2) });
			$('#ui-datepicker-div').fadeOut();
		});
	};
	
//----------------------------------------------------------------------------- END OF FUNCTIONS



//----------------------------------------------------------------------------- SETTINGS
	$.fn.sunBox.defaults = 
	{
		show_overlay		: true,
		overlay_pos			: 'body',
		speed				: 'normal',
		overlay_opacity		: 0.6,
		width				: 600,
		message_width		: 400,
		content				: '',
		height				: 400,
		draggable			: true,
		btn_true			: '',
		btn_false			: '',
		ajax_path			: '',
		class_name			: 'noClass',
		callback			: null,
		message_title		: 'Message',
		bottom_bar			: true
	};
//----------------------------------------------------------------------------- END OF SETTINGS

})(jQuery);