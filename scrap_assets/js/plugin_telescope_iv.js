/*
jQuery Compass Image Viewer plugin - TELESCOPE
Created By: Chris Humboldt (www.chrismodem.com)
*/	

$(document).ready(function(){	
		
	// ----- INITIAL SETUP
	var $ajax_path 				= $('#hdPath').val();
	var $ajax_base_path 		= $ajax_path + 'ajax_handler_documents/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';
	var	$info_disp				= $('.viewerBack input[name="hdInfoDisplay"]').val();
	var $window_h 				= $(window).height();   	
	var $window_hc				= $(window).height() / 2;
	var $window_w				= $(window).width(); 	
	var $window_wc				= $(window).width() / 2;
	var $tbl_view_l				= 64; 		// Table view left value
	var $tbl_view_t				= 10;		// Table view top value
	var $old_w					= 0;  		// Old image width before zoom change
	var $old_h					= 0;  		// Old image height before zoom change
	var $zoom					= 100;		// Starting zoom
	var $page_total				= 0;		// The total number of pages loaded into the viewer
	var $page_pos				= 1;		// The page position based on which image is showing
	var $resize_h				= 0;		// Display image height after first resize
	var $resize_w				= 0;		// Display image width after first resize
	var $thumb_loop				= 0;
	var $zoom_val				= 100;
	var	$rotation				= 0;
	// ----- END OF INITIAL SETUP !!
		
		
	// Control the image movement based on keystrokes
	$fc_image_move_by_key();
		
	// Image paging and thumbnail selection
	$fc_image_change();
	
	// Toggle information
	$fc_toggle_information();
	
	// Indexing changes
	$fc_indexing_changes();
	
	// Close it
	$fc_close_it('.viewerClose');
	
	// Adjust the zoom
	$fc_adjust_zoom();
	
	// Rotate image
	$fc_image_rotate();


	// ----- CLICK ACTIVATION
	$('.scrap_telescopeView').live('click', function()
	{
		// Image path
		$this				= $(this);
		$parent				= $this.parents('.contain_compassDocument');
		$doc_id				= $parent.find('input[name="hdDocId"]').val();
		$attach_id			= $parent.find('input[name="hdAttachId"]').val();
		$attach_filename	= $parent.find('input[name="hdAttachFilename"]').val();
		
		// Get the loader
		//$.scrap_overlay();
		$.scrap_note_loader('Getting the document');
		
		// See what to do
		if($.scrap_is_image($attach_filename) == true)
		{
			// Load the viewer
			$fc_load_viewer($attach_id, $attach_filename, $doc_id);
		}
		else if(($this.hasClass('docIcon_pdf') == true) || ($this.hasClass('docIcon_txt') == true) || ($this.hasClass('docIcon_csv') == true))
		{
			$fc_download_document($attach_id, $attach_filename, '_blank');
		}
		else
		{
			$fc_download_document($attach_id, $attach_filename, '_self');
		}
	});
	// ----- END OF CLICK ACTIVATION !!
	
	
	// Download a document
	function $fc_download_document($attach_id, $attach_filename, $where)
	{
		// Post to the controller
		$.post($ajax_path + 'telescope/download_document',
		{
			attach_id			: $attach_id,
			attach_filename		: $attach_filename
		},
		function($data)
		{
			// Validate
			if($data == 9876)
			{
				// Logout
				$.scrap_logout();
			}
			else if($data != 12345)
			{	
				$.scrap_remove_overlay();
				$.scrap_note_hide();
				window.open($data, $where);
			}
			else
			{
				// Error message
				$.scrap_note_time('There was a problem getting your document', 4000, 'cross');
			}
		});
	}
	
	
	// Create the viewer with images and controls
	function $fc_load_viewer($attach_id, $attach_filename, $doc_id)
	{	
		// Reset the page total
		$page_total = 0;
		
		// Post to the controller
		$.post($ajax_path + 'telescope/load_viewer',
		{
			width				: $window_w,
			height				: $window_h,
			attach_id			: $attach_id,
			attach_filename		: $attach_filename,
			doc_id				: $doc_id
		},
		function($data)
		{
			$('body').prepend($data);
			
			// Remove the scrap note
			$pop_display		= false;
			$('.popupMain').each(function()
			{
				if($(this).is(":visible"))
				{
					$pop_display	= true;
					return false;
				}
			});
			
			if($pop_display == false)
			{
				$.scrap_remove_overlay();
				$.scrap_note_hide();	
			}
			else
			{
				$.scrap_note_hide();	
			}
			
			// Make sure that there is an image to show
			if($data == 9876)
			{
				// Logout
				$.scrap_logout();
			}
			else if($data != 12345)
			{						
				// Hide the input fields in IE6 or lower
				if($.browser.msie && parseInt($.browser.version) <= 6)
				{
					$('input, select').hide();
				}
				
				// Remove loader
				$('.loadingBG:last').remove();
				
				// Remove classes on thumbnails
				$('.infoContainer .thumbsContainer .thumbsInner img').each(function()
				{
					$thumb_loop++;
					$(this).removeAttr('class');
					$(this).addClass('thumb_' + $thumb_loop);
				});
				
				// Info display
				$info_disp			= $('.viewerBack input[name="hdInfoDisplay"]').val();
				if($info_disp == 'open')
				{
					$window_w		= $window_w - 300;
					$window_wc		= $window_wc - 150;
				}
				
				// Add active class
				$('.infoContainer .thumbsContainer .thumbsInner img:first').addClass('active');
				
				// Perform these actions once the image has loaded
				$('.imgDisplay').load(function()
				{
					// Make draggable
					$('.imageContainer').draggable(
					{
						stop: function(event, ui)
						{
							$tbl_view_l	= parseInt($('.imageContainer').css("left").replace('px',''));
							$tbl_view_t	= parseInt($('.imageContainer').css("top").replace('px',''));
						}
					});
					
					// Resize to fit
					$fc_resize_to_fit('.imgDisplay');
					
					// Control functions
					$fc_menu_controls();
				});
				
				// Total amount of images
				$('.imageContainer img').each(function()
				{
					$page_total++;
				});
			}
			else
			{
				// Create the error message
				$('body').sunBox.message('There was a problem loading up your image', { message_title: 'COMPASS MESSAGE' });
			}
		});
	}
	
	
	// Menu controls
	function $fc_menu_controls()
	{				
		// Zoom slider
		$fc_zoom_slider(".zoomSlider");
	}
	
	
	// Close the viewer
	function $fc_close_it($selector)
	{
		$($selector).live('click', function()
		{
			// Reset page postion
			$page_pos			= 1;
			$zoom_val			= 100;
			if($info_disp == 'open')
			{
				$window_w		= $window_w + 300;
				$window_wc		= $window_wc + 150;
			}
			$thumb_loop			= 0;
			
			// Post to the controller the delete command
			$.post($ajax_path + 'telescope/delete_temp_files',
			{
				check	: 'yupdeleteitbuddy'
			});
			
			$('.viewerBack').remove();
			//$('body').sunBox.remove_overlay();
			$('body').sunBox.remove_loader();
		});
	}
	
	
	// Image paging and thumbnail selection
	function $fc_image_change()
	{		
		// Next image
		$('.viewerNext').live('click', function()
		{
			//console.log('next' + ' -- ' + $page_pos +' -- '+ $page_total);
			// Make sure that there is a next image
			if($page_pos < $page_total)
			{
				// Hide the current image
				$('.imageContainer').hide();
				$zoom_val			= 100;
				
				// Display the loader
				$('body').sunBox.loader();
				if($info_disp == 'open')
				{
					$loader_L		= ($window_w - 51) / 2;
					$('.loadingBG').css({ left: $loader_L });
				}
				
				// Increase page position
				$page_pos++;
				
				// Change the page count
				$('.viewControls .viewerPageCount').text($page_pos + ' / ' + $page_total);
				
				// Edit the DOM
				$('.thumbsContainer img.active').removeClass('active');
				$('.thumbsContainer img.thumb_' + $page_pos).addClass('active');
		
				window.setTimeout($fc_change_display, 100);
			}
		});
		
		// Previous image
		$('.viewerPrev').live('click', function()
		{
			//console.log('prev' + ' -- ' + $page_pos +' -- '+ $page_total);
			// Make sure that there is a next image
			if($page_pos > 1)
			{
				// Hide the current image
				$('.imageContainer').hide();
				$zoom_val			= 100;
				
				// Display the loader
				$('body').sunBox.loader();
				if($info_disp == 'open')
				{
					$loader_L	= ($window_w - 51) / 2;
					$('.loadingBG').css({ left: $loader_L });
				}
				
				// Increase page position
				$page_pos--;
				
				// Change the page count
				$('.viewControls .viewerPageCount').text($page_pos + ' / ' + $page_total);
				
				// Edit the DOM
				$('.thumbsContainer img.active').removeClass('active');
				$('.thumbsContainer img.thumb_' + $page_pos).addClass('active');
				
				window.setTimeout($fc_change_display, 100);
			}
		});	
		
		// Thumbnail change
		$('.thumbsContainer img').live('click', function()
		{
			//console.log('next' + ' -- ' + $page_pos +' -- '+ $page_total);
			
			// Validate
			if($(this).hasClass('active') == false)
			{
				// Some variables
				$page				= $(this).attr('class').substr(6);
				$zoom_val			= 100;
				
				// Hide the current image
				$('.imageContainer').hide();
	
				// Display the loader
				$('body').sunBox.loader();
				if($info_disp == 'open')
				{
					$loader_L	= ($window_w - 51) / 2;
					$('.loadingBG').css({ left: $loader_L });
				}
	
				// Increase page position
				$page_pos			= $page;
	
				// Change the page count
				$('.viewControls .viewerPageCount').text($page_pos + ' / ' + $page_total);
				
				// Edit the DOM
				$('.thumbsContainer img.active').removeClass('active');
				$('.thumbsContainer img.thumb_' + $page_pos).addClass('active');
	
				window.setTimeout($fc_change_display, 100);
			}
		});
	}
	
	
	// Change the display only after the delay
	function $fc_change_display()
	{	
		// Show the next image
		$('.imgDisplay').removeClass('imgDisplay').hide();
		$('.viewerBack .imageContainer img:eq(' + ($page_pos - 1) + ')').addClass('imgDisplay').removeClass('scrapImage').removeClass('displayNone').show("fast", function(){ $fc_resize_to_fit('.imgDisplay'); });
	}
	
	
	// Control the image movement based on keystrokes
	function $fc_image_move_by_key()
	{		
		$(document).keydown(function(event)
		{
			switch(event.keyCode)
			{
				case $.ui.keyCode.HOME:
					break;
					
				case $.ui.keyCode.END:
					break;
					
				case $.ui.keyCode.UP:
					if($.browser.msie && parseInt($.browser.version) <= 6)
					{
						window.scrollBy(0,-5000);
					}
					$tbl_view_t = $tbl_view_t + 150
					$('.imageContainer').animate({ top: $tbl_view_t }, 200)
					break;
					
//				case $.ui.keyCode.RIGHT:
//					$tbl_view_l = $tbl_view_l - 150
//					$('.imageContainer').animate({ left: $tbl_view_l })
//					break;
					
				case $.ui.keyCode.DOWN:
					if($.browser.msie && parseInt($.browser.version) <= 6)
					{
						window.scrollBy(0,5000);
					}
					$tbl_view_t = $tbl_view_t - 150
					$('.imageContainer').animate({ top: $tbl_view_t }, 200)
					break;
					
//				case $.ui.keyCode.LEFT:
//					$tbl_view_l = $tbl_view_l + 150
//					$('.imageContainer').animate({ left: $tbl_view_l })
//					break;
			}
		});
	}
	
	
	// Zoom slider
	function $fc_zoom_slider($selector)
	{		
		// Slider code
		$($selector).slider(
		{
			orientation		: "horizontal",
			range			: "min",
			min				: 50,
			max				: 300,
			value			: $zoom,
			start			: function(event, ui) 
			{
				// Set the old dimensions
				$old_w	= $('.imgDisplay').width();
				$old_h	= $('.imgDisplay').height();
				
				// Hide the table viewer
				$('.imageContainer').hide();
				
				// Attach the loader
				$('body').sunBox.loader();
			},
			stop		: function(event, ui) 
			{
				// Image zoom
				$zoom_val	= ui.value;
				$image_zoom	= (ui.value) / 100;
				
				// Determine percentage of image that extends left from page center
				if($tbl_view_l < $window_wc)
				{
					$img_over_c		= $window_wc - $tbl_view_l;
					$pos_fcl_ratio	= Math.round(($img_over_c / ($old_w + 28) * 1000) * 10) / 100;
				}
				else
				{
					$img_exposed		= $window_w - $tbl_view_l;
					$pos_fcl_ratio	= Math.round(($img_exposed / ($old_w + 28) * 1000) * 10) / 100;
				}
				
				// Determine percentage of image that extends top from page center
				if($tbl_view_t < $window_hc)
				{
					$img_top_c		= $window_hc - $tbl_view_t;
					$pos_fct_ratio	= Math.round(($img_top_c / ($old_h + 29) * 1000) * 10) / 100;
				}
				else
				{
					$img_top_c		= $tbl_view_t - $window_hc;
					$pos_fct_ratio	= Math.round(($img_top_c / ($old_h + 29) * 1000) * 10) / 100;
				}
				
				// New dimension
				$new_w	= $resize_w * $image_zoom;
				$new_h	= $resize_h * $image_zoom;
				
				// Adjust dimensions
				$('.imgDisplay').height($new_h);
				$('.imgDisplay').width($new_w);
				
				// Adjust image position
				if($new_w > $old_w)
				{
					// Difference in size
					$diff_ratio_w	= $new_w - $old_w;
					$diff_ratio_h	= $new_h - $old_h;
					
					// Move the image left accordingly
					if($tbl_view_l < $window_wc)
					{
						$new_l	= $tbl_view_l - (($diff_ratio_w) * ($pos_fcl_ratio / 100));
					}
					else
					{
						$new_l	= $tbl_view_l + (($diff_ratio_w) * ($pos_fcl_ratio / 100));
					}
					
					// Move the image top accordingly
					if($tbl_view_t < $window_hc)
					{
						$new_t	= $tbl_view_t - (($diff_ratio_h) * ($pos_fct_ratio / 100));
					}
					else
					{
						$new_t	= $tbl_view_t + (($diff_ratio_h) * ($pos_fct_ratio / 100));
					}
				}
				else if($old_w > $new_w)
				{
					// Difference in size
					$diff_ratio_w	= $old_w - $new_w;
					$diff_ratio_h	= $old_h - $new_h;
					
					// Move the image left accordingly
					if($tbl_view_l < $window_wc)
					{
						$new_l	= $tbl_view_l + (($diff_ratio_w) * ($pos_fcl_ratio / 100));
					}
					else
					{
						$new_l	= $tbl_view_l - (($diff_ratio_w) * ($pos_fcl_ratio / 100));
					}
					
					// Move the image top accordingly
					if($tbl_view_t < $window_hc)
					{
						$new_t	= $tbl_view_t + (($diff_ratio_h) * ($pos_fct_ratio / 100));
					}
					else
					{
						$new_t	= $tbl_view_t - (($diff_ratio_h) * ($pos_fct_ratio / 100));
					}
				}
				
				// Actual Adjustment
				$tbl_view_l = $new_l;
				$tbl_view_t = $new_t;
				$('.imageContainer').css({ left: $new_l , top: $new_t});
				
				// Adjust what shows and what doesnt
				$('body').sunBox.remove_loader();
				$('.imageContainer').show();
			}
		});
	}

	
	// Resize the image
	function $fc_adjust_zoom()
	{	
		// Increase zoom
		$('.viewControls .btnPlus').live('click', function()
		{
			// Some variables
			$crt_slider_pos				= $('.zoomSlider .ui-slider-handle').position();
			$zoom_percent				= ($crt_slider_pos.left / ($('.zoomSlider').width()) * 100);
			if($zoom_percent < 100)
			{
				if($zoom_percent > 90)
				{
					$new_zoom			= 100;
				}
				else
				{
					$new_zoom			= $zoom_percent + 10;
				}
				
				// Set the posityion of the slider
				$('.ui-slider-handle').css({ left: $new_zoom + '%' });
				
				// Edit the DOM
				// Set the old dimensions
				$old_w	= $('.imgDisplay').width();
				$old_h	= $('.imgDisplay').height();
				
				// Hide the table viewer
				$('.imageContainer').hide();
				
				// Attach the loader
				$('body').sunBox.loader();
						

				// Image zoom
				if($zoom_val > 275)
				{
					$zoom_val			= 300;
				}
				else
				{
					$zoom_val			= $zoom_val + 25;
				}
				$image_zoom			= $zoom_val / 100;

				// Determine percentage of image that extends left from page center
				if($tbl_view_l < $window_wc)
				{
					$img_over_c		= $window_wc - $tbl_view_l;
					$pos_fcl_ratio	= Math.round(($img_over_c / ($old_w + 28) * 1000) * 10) / 100;
				}
				else
				{
					$img_exposed		= $window_w - $tbl_view_l;
					$pos_fcl_ratio	= Math.round(($img_exposed / ($old_w + 28) * 1000) * 10) / 100;
				}

				// Determine percentage of image that extends top from page center
				if($tbl_view_t < $window_hc)
				{
					$img_top_c		= $window_hc - $tbl_view_t;
					$pos_fct_ratio	= Math.round(($img_top_c / ($old_h + 29) * 1000) * 10) / 100;
				}
				else
				{
					$img_top_c		= $tbl_view_t - $window_hc;
					$pos_fct_ratio	= Math.round(($img_top_c / ($old_h + 29) * 1000) * 10) / 100;
				}

				// New dimension
				$new_w	= $resize_w * $image_zoom;
				$new_h	= $resize_h * $image_zoom;

				// Adjust dimensions
				$('.imgDisplay').height($new_h);
				$('.imgDisplay').width($new_w);

				// Adjust image position
				if($new_w > $old_w)
				{
					// Difference in size
					$diff_ratio_w	= $new_w - $old_w;
					$diff_ratio_h	= $new_h - $old_h;

					// Move the image left accordingly
					if($tbl_view_l < $window_wc)
					{
						$new_l	= $tbl_view_l - (($diff_ratio_w) * ($pos_fcl_ratio / 100));
					}
					else
					{
						$new_l	= $tbl_view_l + (($diff_ratio_w) * ($pos_fcl_ratio / 100));
					}

					// Move the image top accordingly
					if($tbl_view_t < $window_hc)
					{
						$new_t	= $tbl_view_t - (($diff_ratio_h) * ($pos_fct_ratio / 100));
					}
					else
					{
						$new_t	= $tbl_view_t + (($diff_ratio_h) * ($pos_fct_ratio / 100));
					}
				}
				else if($old_w > $new_w)
				{
					// Difference in size
					$diff_ratio_w	= $old_w - $new_w;
					$diff_ratio_h	= $old_h - $new_h;

					// Move the image left accordingly
					if($tbl_view_l < $window_wc)
					{
						$new_l	= $tbl_view_l + (($diff_ratio_w) * ($pos_fcl_ratio / 100));
					}
					else
					{
						$new_l	= $tbl_view_l - (($diff_ratio_w) * ($pos_fcl_ratio / 100));
					}

					// Move the image top accordingly
					if($tbl_view_t < $window_hc)
					{
						$new_t	= $tbl_view_t + (($diff_ratio_h) * ($pos_fct_ratio / 100));
					}
					else
					{
						$new_t	= $tbl_view_t - (($diff_ratio_h) * ($pos_fct_ratio / 100));
					}
				}

				// Actual Adjustment
				$tbl_view_l = $new_l;
				$tbl_view_t = $new_t;
				$('.imageContainer').css({ left: $new_l , top: $new_t});

				// Adjust what shows and what doesnt
				$('body').sunBox.remove_loader();
				$('.imageContainer').show();
			}
		});
		
		// Decrease zoom
		$('.viewControls .btnMinus').live('click', function()
		{
			// Some variables
			$crt_slider_pos				= $('.zoomSlider .ui-slider-handle').position();
			$zoom_percent				= ($crt_slider_pos.left / ($('.zoomSlider').width()) * 100);
			if($zoom_percent > 0)
			{
				if($zoom_percent < 10)
				{
					$new_zoom			= 0;
				}
				else
				{
					$new_zoom			= $zoom_percent - 10;
				}
				
				// Set the posityion of the slider
				$('.ui-slider-handle').css({ left: $new_zoom + '%' });
				
				// Edit the DOM
				// Set the old dimensions
				$old_w	= $('.imgDisplay').width();
				$old_h	= $('.imgDisplay').height();
				
				// Hide the table viewer
				$('.imageContainer').hide();
				
				// Attach the loader
				$('body').sunBox.loader();
						

				// Image zoom
				if($zoom_val < 75)
				{
					$zoom_val			= 50;
				}
				else
				{
					$zoom_val			= $zoom_val - 25;
				}
				$image_zoom			= $zoom_val / 100;

				// Determine percentage of image that extends left from page center
				if($tbl_view_l < $window_wc)
				{
					$img_over_c		= $window_wc - $tbl_view_l;
					$pos_fcl_ratio	= Math.round(($img_over_c / ($old_w + 28) * 1000) * 10) / 100;
				}
				else
				{
					$img_exposed		= $window_w - $tbl_view_l;
					$pos_fcl_ratio	= Math.round(($img_exposed / ($old_w + 28) * 1000) * 10) / 100;
				}

				// Determine percentage of image that extends top from page center
				if($tbl_view_t < $window_hc)
				{
					$img_top_c		= $window_hc - $tbl_view_t;
					$pos_fct_ratio	= Math.round(($img_top_c / ($old_h + 29) * 1000) * 10) / 100;
				}
				else
				{
					$img_top_c		= $tbl_view_t - $window_hc;
					$pos_fct_ratio	= Math.round(($img_top_c / ($old_h + 29) * 1000) * 10) / 100;
				}

				// New dimension
				$new_w	= $resize_w * $image_zoom;
				$new_h	= $resize_h * $image_zoom;

				// Adjust dimensions
				$('.imgDisplay').height($new_h);
				$('.imgDisplay').width($new_w);

				// Adjust image position
				if($new_w > $old_w)
				{
					// Difference in size
					$diff_ratio_w	= $new_w - $old_w;
					$diff_ratio_h	= $new_h - $old_h;

					// Move the image left accordingly
					if($tbl_view_l < $window_wc)
					{
						$new_l	= $tbl_view_l - (($diff_ratio_w) * ($pos_fcl_ratio / 100));
					}
					else
					{
						$new_l	= $tbl_view_l + (($diff_ratio_w) * ($pos_fcl_ratio / 100));
					}

					// Move the image top accordingly
					if($tbl_view_t < $window_hc)
					{
						$new_t	= $tbl_view_t - (($diff_ratio_h) * ($pos_fct_ratio / 100));
					}
					else
					{
						$new_t	= $tbl_view_t + (($diff_ratio_h) * ($pos_fct_ratio / 100));
					}
				}
				else if($old_w > $new_w)
				{
					// Difference in size
					$diff_ratio_w	= $old_w - $new_w;
					$diff_ratio_h	= $old_h - $new_h;

					// Move the image left accordingly
					if($tbl_view_l < $window_wc)
					{
						$new_l	= $tbl_view_l + (($diff_ratio_w) * ($pos_fcl_ratio / 100));
					}
					else
					{
						$new_l	= $tbl_view_l - (($diff_ratio_w) * ($pos_fcl_ratio / 100));
					}

					// Move the image top accordingly
					if($tbl_view_t < $window_hc)
					{
						$new_t	= $tbl_view_t + (($diff_ratio_h) * ($pos_fct_ratio / 100));
					}
					else
					{
						$new_t	= $tbl_view_t - (($diff_ratio_h) * ($pos_fct_ratio / 100));
					}
				}

				// Actual Adjustment
				$tbl_view_l = $new_l;
				$tbl_view_t = $new_t;
				$('.imageContainer').css({ left: $new_l , top: $new_t});

				// Adjust what shows and what doesnt
				$('body').sunBox.remove_loader();
				$('.imageContainer').show();
			}
		});
	}
	
	
	// Resize the image to make it fit
	function $fc_resize_to_fit($selector)
	{	
		// Display the viewer again
		$('.imageContainer').show();
		$('.imgDisplay').removeAttr('style');
				
				
		// Remove the loader
		$('body').sunBox.remove_loader();
		
		// Image width
		$img_width	= $($selector).width();
		$img_height	= $($selector).height();
		$disp_width	= ($window_w - 50);
		
		// Size condition
		if($img_width > $disp_width)
		{
			// Resize ratio
			$resize_ratio	= $disp_width / $img_width;
			
			$($selector).css({ height: Math.round($img_height * $resize_ratio), width: Math.round($img_width * $resize_ratio) });
			$('.imageContainer').css({ left: 25, top: 74 });
			$tbl_view_l	= 25; 
			$tbl_view_t	= 74;
		}
		else
		{
			$new_pos_l	= (($window_w - $('.imgDisplay').width()) / 2);
			$('.imageContainer').css({ left: $new_pos_l, top: 74 });
			$tbl_view_l	= $new_pos_l; 
			$tbl_view_t	= 74;
		}
		
		
		// Set the old dimensions
		$resize_w	= $('.imgDisplay').width();
		$resize_h	= $('.imgDisplay').height();
		
		// Reset the zoom slider handle
		$('.ui-slider-handle').animate({ left: '20%' });
	}
	
	
	// Attach the loader
	function $fc_attachLoader()
	{
		$('body').prepend('<div id="loadingBG"><div id="newLoader"></div></div>');
		if($.browser.msie && parseInt($.browser.version) <= 6)
		{
			$('#loadingBG').css({ position: 'absolute' });
		}
		$('#loadingBG').css({ left: (($window_w / 2) - 27), top: (($window_h / 2) - 27) });
	}
	
	
	// Remove the loader
	function $fc_removeLoader()
	{
		$('#loadingBG').remove();
	}
	
	
	// Indexing changes
	function $fc_indexing_changes()
	{
		// Clear all fields
		$('.telescopeViewer .indexingContainer .btnClearFields').live('click', function()
		{
			$('.telescopeViewer .indexingContainer input').val('');
		});
		
		// Save changes
		$('.telescopeViewer .indexingContainer .btnSaveChanges').live('click', function()
		{
			// Some variables
			$error					= false;
			$doc_id					= $('.telescopeViewer .indexingContainer input[name="hdDocId"]').val();
			$primary_val			= $('.telescopeViewer .indexingContainer .primary').val();
			$field_name				= $('.telescopeViewer .indexingContainer .primary').attr('placeholder');
			$index_fields			= '';
			
			// Validate
			if($error == false)
			{
				if($primary_val.length < 1)
				{
					$error			= true;
					$.scrap_note_time($field_name +' is required', 4000, 'cross');
					$('.telescopeViewer .indexingContainer .primary').addClass('redBorder');
				}
			}
			
			// Submit the changes
			if($error == false)
			{
				// Get the indexing fields
				$('.telescopeViewer .indexingContainer .inpIndexField').each(function()
				{
					// Validate
					if($(this).val() != '')
					{
						$index_fields	+= '[';
						$index_fields	+= $(this).val();
						$index_fields	+= '--';
						$index_fields	+= $(this).attr('name').substring(14);
						$index_fields	+= '--';
						$index_fields	+= $(this).parent().find('input[name="hdIndexId"]').val();
						$index_fields	+= '--';
						$index_fields	+= $(this).parent().find('input[name="hdIndexArchive"]').val();
						$index_fields	+= ']';
					}
				});
				
				// Update the document
				$.scrap_note_loader('Updating the document');
				
				// Post the data
				$.post($ajax_base_path + 'edit_document',
				{
					doc_id				: $doc_id,
					index_fields		: $index_fields
				},
				function($data)
				{	
					$data	= jQuery.trim($data);
					//$('body').prepend($data);
					
					if($data == '9876')
					{
						$.scrap_logout();
					}
					else if($data == 'wassuccessfullyedited')
					{
						// Post the data
						$.post($ajax_base_path + 'get_document_line',
						{
							doc_id				: $doc_id
						},
						function($data)
						{	
							$data	= jQuery.trim($data);
							
							if($data == '9876')
							{
								$.scrap_logout();
							}
							else
							{
								$('.middle .compassDocument_' + $doc_id).html($data);
								$.scrap_note_time('The document changes have been saved', 4000, 'tick');
								$('.middle .compassDocument_'+ $doc_id +' select, .middle .compassDocument_'+ $doc_id +' input:checkbox, .middle .compassDocument_'+ $doc_id +' input:radio').uniform();
							}
						});
					}
					else
					{
						$.scrap_note_time($data, 4000, 'cross');
					}
				});
			}
		});
	}
	
	
	// Toggle information
	function $fc_toggle_information()
	{
		$('.telescopeViewer .viewControls .viewerInfo').live('click', function()
		{
			$zoom_val				= 100;
			if($info_disp == 'open')
			{
				// Change information view setting
				$info_disp			= 'closed';
				$window_w			= $window_w + 300;
				$window_wc			= $window_wc + 150;
				
				// Edit the DOM
				$('.telescopeViewer .infoContainer').fadeOut('fast');
			}
			else
			{
				// Change information view setting
				$info_disp			= 'open';
				$window_w			= $window_w - 300;
				$window_wc			= $window_wc - 150;
				
				// Edit the DOM
				$('.telescopeViewer .infoContainer').fadeIn('fast');
			}
			
			// Resize to fit
			$fc_resize_to_fit('.imgDisplay');
		});
	}
	
	
	// Rotate the image
	function $fc_image_rotate()
	{
		// Rotate right
		$('.telescopeViewer .viewerRotateRight').live('click', function()
		{
			$('.imgDisplay').rotate($rotation + 90);
			$rotation				= $rotation + 90;
		});
		
		// Rotate left
		$('.telescopeViewer .viewerRotateLeft').live('click', function()
		{
			$('.imgDisplay').rotate($rotation - 90);
			$rotation				= $rotation - 90;
		});
	}
	// ----- END OF FUNCTIONS !!
});

//VERSION: 2.2 LAST UPDATE: 13.03.2012
/* 
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 * 
 * Made by Wilq32, wilq32@gmail.com, Wroclaw, Poland, 01.2009
 * Website: http://code.google.com/p/jqueryrotate/ 
 */
(function(j){for(var d,k=document.getElementsByTagName("head")[0].style,h=["transformProperty","WebkitTransform","OTransform","msTransform","MozTransform"],g=0;g<h.length;g++)void 0!==k[h[g]]&&(d=h[g]);var i="v"=="\v";jQuery.fn.extend({rotate:function(a){if(!(0===this.length||"undefined"==typeof a)){"number"==typeof a&&(a={angle:a});for(var b=[],c=0,f=this.length;c<f;c++){var e=this.get(c);if(!e.Wilq32||!e.Wilq32.PhotoEffect){var d=j.extend(!0,{},a),e=(new Wilq32.PhotoEffect(e,d))._rootObj;
b.push(j(e))}else e.Wilq32.PhotoEffect._handleRotation(a)}return b}},getRotateAngle:function(){for(var a=[],b=0,c=this.length;b<c;b++){var f=this.get(b);f.Wilq32&&f.Wilq32.PhotoEffect&&(a[b]=f.Wilq32.PhotoEffect._angle)}return a},stopRotate:function(){for(var a=0,b=this.length;a<b;a++){var c=this.get(a);c.Wilq32&&c.Wilq32.PhotoEffect&&clearTimeout(c.Wilq32.PhotoEffect._timer)}}});Wilq32=window.Wilq32||{};Wilq32.PhotoEffect=function(){return d?function(a,b){a.Wilq32={PhotoEffect:this};this._img=this._rootObj=
this._eventObj=a;this._handleRotation(b)}:function(a,b){this._img=a;this._rootObj=document.createElement("span");this._rootObj.style.display="inline-block";this._rootObj.Wilq32={PhotoEffect:this};a.parentNode.insertBefore(this._rootObj,a);if(a.complete)this._Loader(b);else{var c=this;jQuery(this._img).bind("load",function(){c._Loader(b)})}}}();Wilq32.PhotoEffect.prototype={_setupParameters:function(a){this._parameters=this._parameters||{};"number"!==typeof this._angle&&(this._angle=0);"number"===
typeof a.angle&&(this._angle=a.angle);this._parameters.animateTo="number"===typeof a.animateTo?a.animateTo:this._angle;this._parameters.step=a.step||this._parameters.step||null;this._parameters.easing=a.easing||this._parameters.easing||function(a,c,f,e,d){return-e*((c=c/d-1)*c*c*c-1)+f};this._parameters.duration=a.duration||this._parameters.duration||1E3;this._parameters.callback=a.callback||this._parameters.callback||function(){};a.bind&&a.bind!=this._parameters.bind&&this._BindEvents(a.bind)},_handleRotation:function(a){this._setupParameters(a);
this._angle==this._parameters.animateTo?this._rotate(this._angle):this._animateStart()},_BindEvents:function(a){if(a&&this._eventObj){if(this._parameters.bind){var b=this._parameters.bind,c;for(c in b)b.hasOwnProperty(c)&&jQuery(this._eventObj).unbind(c,b[c])}this._parameters.bind=a;for(c in a)a.hasOwnProperty(c)&&jQuery(this._eventObj).bind(c,a[c])}},_Loader:function(){return i?function(a){var b=this._img.width,c=this._img.height;this._img.parentNode.removeChild(this._img);this._vimage=this.createVMLNode("image");
this._vimage.src=this._img.src;this._vimage.style.height=c+"px";this._vimage.style.width=b+"px";this._vimage.style.position="absolute";this._vimage.style.top="0px";this._vimage.style.left="0px";this._container=this.createVMLNode("group");this._container.style.width=b;this._container.style.height=c;this._container.style.position="absolute";this._container.setAttribute("coordsize",b-1+","+(c-1));this._container.appendChild(this._vimage);this._rootObj.appendChild(this._container);this._rootObj.style.position=
"relative";this._rootObj.style.width=b+"px";this._rootObj.style.height=c+"px";this._rootObj.setAttribute("id",this._img.getAttribute("id"));this._rootObj.className=this._img.className;this._eventObj=this._rootObj;this._handleRotation(a)}:function(a){this._rootObj.setAttribute("id",this._img.getAttribute("id"));this._rootObj.className=this._img.className;this._width=this._img.width;this._height=this._img.height;this._widthHalf=this._width/2;this._heightHalf=this._height/2;var b=Math.sqrt(this._height*
this._height+this._width*this._width);this._widthAdd=b-this._width;this._heightAdd=b-this._height;this._widthAddHalf=this._widthAdd/2;this._heightAddHalf=this._heightAdd/2;this._img.parentNode.removeChild(this._img);this._aspectW=(parseInt(this._img.style.width,10)||this._width)/this._img.width;this._aspectH=(parseInt(this._img.style.height,10)||this._height)/this._img.height;this._canvas=document.createElement("canvas");this._canvas.setAttribute("width",this._width);this._canvas.style.position="relative";
this._canvas.style.left=-this._widthAddHalf+"px";this._canvas.style.top=-this._heightAddHalf+"px";this._canvas.Wilq32=this._rootObj.Wilq32;this._rootObj.appendChild(this._canvas);this._rootObj.style.width=this._width+"px";this._rootObj.style.height=this._height+"px";this._eventObj=this._canvas;this._cnv=this._canvas.getContext("2d");this._handleRotation(a)}}(),_animateStart:function(){this._timer&&clearTimeout(this._timer);this._animateStartTime=+new Date;this._animateStartAngle=this._angle;this._animate()},
_animate:function(){var a=+new Date,b=a-this._animateStartTime>this._parameters.duration;if(b&&!this._parameters.animatedGif)clearTimeout(this._timer);else{(this._canvas||this._vimage||this._img)&&this._rotate(~~(10*this._parameters.easing(0,a-this._animateStartTime,this._animateStartAngle,this._parameters.animateTo-this._animateStartAngle,this._parameters.duration))/10);this._parameters.step&&this._parameters.step(this._angle);var c=this;this._timer=setTimeout(function(){c._animate.call(c)},10)}this._parameters.callback&&
b&&(this._angle=this._parameters.animateTo,this._rotate(this._angle),this._parameters.callback.call(this._rootObj))},_rotate:function(){var a=Math.PI/180;return i?function(a){this._angle=a;this._container.style.rotation=a%360+"deg"}:d?function(a){this._angle=a;this._img.style[d]="rotate("+a%360+"deg)"}:function(b){this._angle=b;b=b%360*a;this._canvas.width=this._width+this._widthAdd;this._canvas.height=this._height+this._heightAdd;this._cnv.translate(this._widthAddHalf,this._heightAddHalf);this._cnv.translate(this._widthHalf,
this._heightHalf);this._cnv.rotate(b);this._cnv.translate(-this._widthHalf,-this._heightHalf);this._cnv.scale(this._aspectW,this._aspectH);this._cnv.drawImage(this._img,0,0)}}()};i&&(Wilq32.PhotoEffect.prototype.createVMLNode=function(){document.createStyleSheet().addRule(".rvml","behavior:url(#default#VML)");try{return!document.namespaces.rvml&&document.namespaces.add("rvml","urn:schemas-microsoft-com:vml"),function(a){return document.createElement("<rvml:"+a+' class="rvml">')}}catch(a){return function(a){return document.createElement("<"+
a+' xmlns="urn:schemas-microsoft.com:vml" class="rvml">')}}}())})(jQuery);
