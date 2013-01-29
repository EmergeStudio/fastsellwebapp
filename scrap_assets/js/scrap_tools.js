// ---------- SCRAP TOOLS EXTENDER
// Created by:		Chris Humboldt
// Website:			www.chrismodem.com

// ----- LOCK A FORM FROM SUBMITTING ON ENTER
jQuery.scrap_lock_submit = function($element)
{
	$($element).live('keypress', function($e)
	{
		if($e.keyCode == 13)
		{
			return false;
		}
	});
}

// ----- CHECK THAT SOMETHING EXISTS
jQuery.scrap_exists = function($element)
{
	if($($element).length > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

// ----- CURRENT DB DATE
jQuery.scrap_crt_db_date = function()
{
	$current_time 			= new Date();
	$year						= $current_time.getFullYear();
	$month					= $current_time.getMonth() + 1;
	if($month < 10)
	{
		$month				= '0' + $month;
	}
	$day						= $current_time.getDate();
	if($day < 10)
	{
		$day					= '0' + $day;
	}
	return $year + '-' + $month + '-' + $day;
}

// ----- CHECK THAT SOMETHING IS IN A DATABAE DATE FORMAT (yyyy-mm-dd)
jQuery.scrap_check_date = function($date)
{
	if(($date.substr(4, 1) == '-') && ($date.substr(7, 1) == '-') && ($.scrap_is_integer($date.substr(0, 4)) == true) && ($.scrap_is_integer($date.substr(5, 2)) == true) && ($.scrap_is_integer($date.substr(8, 2)) == true) && ($date.length == 10))
	{
		return true;
	}
	else
	{
		return false;
	}
}

// ----- REMOVE LAST CHARACTER
jQuery.scrap_remove_lc = function($date)
{
    return $date.substring(0, $date.length - 1);
}

// ----- CHECK THAT A STRING IS A TIME FORMAT
jQuery.scrap_is_time = function($int)
{
	if($int != '')
	{
		var $valid_chars		= '0123456789.:';
		var $is_number			= true;
		var $char;
		
		for($i = 0; $i < $int.length && $is_number == true; $i++) 
		{ 
			$char = $int.charAt($i); 
			
			if($valid_chars.indexOf($char) == -1) 
			{
				$is_number = false;
			}
		}
		return $is_number;
	}
	else
	{
		return false;
	}
}

// ----- CHECK THAT A STRING IS ONLY INTEGERS - INLCUDING FRACTION
jQuery.scrap_is_integer = function($int)
{
	if($int != '')
	{
		var $valid_chars		= '0123456789.';
		var $is_number			= true;
		var $char;
		
		for($i = 0; $i < $int.length && $is_number == true; $i++) 
		{ 
			$char = $int.charAt($i); 
			
			if($valid_chars.indexOf($char) == -1) 
			{
				$is_number = false;
			}
		}
		return $is_number;
	}
	else
	{
		return false;
	}
}

// ----- CHECK THAT A STRING IS ONLY INTEGERS - NOT INLCUDING FRACTION
jQuery.scrap_is_full_integer = function($int)
{
	if($int != '')
	{
		var $valid_chars		= '0123456789';
		var $is_number			= true;
		var $char;
		
		for($i = 0; $i < $int.length && $is_number == true; $i++) 
		{ 
			$char = $int.charAt($i); 
			
			if($valid_chars.indexOf($char) == -1) 
			{
				$is_number = false;
			}
		}
		return $is_number;
	}
	else
	{
		return false;
	}
}

// ----- CHECK FOR WHITE SPACE
jQuery.scrap_has_white_space = function($check)
{
	if($check.indexOf(' ') != -1)
	{
		return true;
	}
	else
	{
		return false;
	}
}

// ----- CHECK THAT THE FILE IS AN ALLOWED TYPE
jQuery.scrap_allowed_doc = function($file, $ar_allowed_types)
{
	if($ar_allowed_types == null)
	{
		$ar_allowed_types	= ['png', 'jpg', 'jpeg', 'gif', 'tif', 'tiff', 'bmp', 'doc', 'docx', 'xls', 'xlsx', 'pdf', 'txt', 'csv'];
	}
	
	$file_ext				= $file.split('.').pop().toLowerCase();
	
	if(jQuery.inArray($file_ext, $ar_allowed_types) == -1)
	{
		return false;
	}
	else
	{
		return true;
	}
}

// ---------- INPUT MIRRORS
jQuery.scrap_input_mirror = function($input, $output)
{
	$($selector).keyup(function()
	{
		$ref_input	= $(this).val();
		$ref_value	= $ref_input.replace(/ /g,"_").toLowerCase();
		
		// Output the mirror
		$($output).text($ref_value);
	});
}

// ----- CHECK THAT A STRING IS AN EMAIL
jQuery.scrap_is_email = function($email)
{
	if(($email.indexOf('@') != -1) && ($email.indexOf('.') != -1))
	{
		return true;
	}
	else
	{
		return false;
	}
}

// ----- CHECK THAT A STRING IS A VALID PASSWORD
jQuery.scrap_is_password = function($password)
{
	if($password.length > 5)
	{
		$num_check 		= /^[0-9]+$/;
		$letter_check	= /^[a-zA-Z-]+$/;
		
		$error			= false;
		
		if($password.match($num_check))
		{
			$error		= true;
		}
		
		if($password.match($letter_check))
		{
			$error		= true;
		}
		
		if($error == true)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	else
	{
		return false;
	}
}

// ----- GET FILE EXTENSION
jQuery.scrap_get_extension = function($file)
{
    return $file.split('.').pop().toLowerCase();
}

// ----- CHECK THAT THE FILE IS AN IMAGE
jQuery.scrap_is_image = function($file, $ar_allowed_types)
{
	if($ar_allowed_types == null)
	{
		$ar_allowed_types	= ['jpg', 'jpeg', 'gif', 'tif', 'tiff', 'bmp', 'png'];
	}
	
	$file_ext				= $file.split('.').pop().toLowerCase();
	
	if(jQuery.inArray($file_ext, $ar_allowed_types) == -1)
	{
		return false;
	}
	else
	{
		return true;
	}
}

// ----- CHECK THAT INPUT IS A HEX CODE
jQuery.scrap_is_color = function($color)
{
	if($color.length == 7)
	{
		if($color.substr(0, 1) != '#')
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

// ----- LOAD QUICK VIEW
jQuery.scrap_quick_view = function($ajax_html_path)
{
	$('body').sunBox.popup('QUICK VIEW', 'popQuickView',
	{ 
		ajax_path		: $ajax_html_path + 'universal/quick_view',
		callback 		: function($return){}
	});
}

// ----- DISPLAY MESSAGE
jQuery.scrap_message = function($text)
{
	$('body').sunBox.message(
	{ 
		content		: $text,
		callback		: function($return){}
	});
}

// ----- LOGOUT ON ERROR
jQuery.scrap_logout = function()
{
	$('body').sunBox.message(
	{ 
		content			: 'Your session has expired, please logon again',
		message_title	: 'Just To Let You Know',
		btn_true		: 'Ahh Man...Ok',
		callback		: function($return)
		{
			if(($return == true) || ($return == false))
			{
				$('#frmLogout').submit();
			}
		}
	});
}

// ----- PAGE NAVIGATION
jQuery.scrap_page_nav = function()
{
	// Set main variable
	$crt_page_no		= parseInt($('input[name="page_no"]').val());
	
	$('.btnCrtPage').click(function()
	{
		$('.numList').toggle();
	});
	
	// Previous page
	$('.btnPrevPage').click(function()
	{
		if($crt_page_no > 1)
		{
			$new_page = $crt_page_no - 1;
			$('input[name="page_no"]').val($page);
			$('#frmPage').submit();
		}
	});
		
	// Next page
	$('.btnNextPage').click(function()
	{;
		if($crt_page_no < $page_max)
		{
			$new_page = $crt_page_no + 1;
			$('input[name="page_no"]').val($page);
			$('#frmPage').submit();
		}
	});
		
	// Remove limit
	$('.btnRemoveLimit').click(function()
	{
		$crtLimit = $('.btnRemoveLimit').html();
		
		if($crtLimit == 'REMOVE LIMIT')
		{
			$('#filtFileLimit').val('APPLY LIMIT');
			$('input[name="page_no"]').val(1);
			$('#frmPage').submit();
		}
		else
		{
			$('#filtFileLimit').val('REMOVE LIMIT');
			$('input[name="page_no"]').val(1);
			$('#frmPage').submit();
		}
	});
	
	// Number list selection
	$('.listPageNum').click(function()
	{
		$list_num	= $(this).text();
		$('input[name="page_no"]').val($page);
		$('#frmPage').submit();
	})
}

// ----- VIEW ATTACHED ITEMS
jQuery.scrap_random_string = function($string_length)
{
	$chars 				= "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	if($string_length == null)
	{
		$string_length	= 10;
	}
	$random_string 		= '';
	
	for(var i = 0; i < $string_length; i++) 
	{
		$r_num 				= Math.floor(Math.random() * $chars.length);
		$random_string 	+= $chars.substring($r_num, $r_num+1);
	}
	
	return $random_string;
}

// ----- FULL LOADER
jQuery.scrap_full_loader = function()
{
	$('body').sunBox.full_loader();
}

// ----- LOADER MESSAGE
jQuery.scrap_loader_message = function($text)
{
	$('body').sunBox.loader_message($text);
}

// ----- ADD OVERLAY
jQuery.scrap_overlay = function()
{
	$('body').sunBox.overlay();
}

// ----- REMOVE OVERLAY
jQuery.scrap_remove_overlay = function()
{
	$('body').sunBox.remove_overlay();
}

// ----- SCRAP NOTE
jQuery.scrap_note = function($text, $extra_class)
{
	// Hide the loader
	$('.scrapNote .mainLoader').hide();
	
	// Edit the text
	if($extra_class == null)
	{
		$('.scrapNote .mainNote').html($text).show();
	}
	else
	{
		$('.scrapNote .mainNote').html('<div class="' + $extra_class + ' icon">Tick</div>' + $text).show();
	}
	
	// Show the note
	$('.scrapNote').fadeIn('fast');
}

// ----- SCRAP NOTE WITH LOADER
jQuery.scrap_note_loader = function($text, $extra_class)
{	
	// Show the loader
	$('.scrapNote .mainLoader').show();
	
	// Edit the text
	if($extra_class == null)
	{
		$('.scrapNote .mainNote').html($text).show();
	}
	else
	{
		$('.scrapNote .mainNote').html('<div class="' + $extra_class + ' icon">Tick</div>' + $text).show();
	}
	
	// Show the note
	$('.scrapNote').fadeIn('fast');
}

// ----- SCRAP NOTE HIDE
jQuery.scrap_note_hide = function()
{
	// Show the loader
	$('.scrapNote, .scrapNote .mainNote, .scrapNote .mainLoader').fadeOut('fast');
}

// ----- SCRAP NOTE WITH TIMER
jQuery.scrap_note_time = function($text, $time, $extra_class)
{
	// Show note
	if($extra_class == null)
	{
		$.scrap_note($text);
	}
	else
	{
		$.scrap_note($text, $extra_class);
	}
	
	// Reset the time
	clearTimeout($.scrap_note_hide_time);
	
	// Close on time
	setTimeout($.scrap_note_hide, $time);
}

// ----- SCRAP NOTE HIDE WITH TIMER
jQuery.scrap_note_hide_time = function($time)
{
	setTimeout($.scrap_note_hide(), $time);
}

// ----- UNIFORM FORM ELEMENTS
jQuery.scrap_uniform_all = function($time)
{
	var $is_iPad 			= navigator.userAgent.match(/iPad/i) != null;	// iPad check
	var $is_iPhone 		= navigator.userAgent.match(/iPhone/i) != null; // iPhone check
	
	if(($is_iPad == true) || ($is_iPhone == true))
	{
		$('select, input:checkbox, input:radio').uniform();
	}
	else
	{
		$('select, input:checkbox, input:radio, input:file').uniform();
	}
}

// ----- UPDATE UNIFORM FORM ELEMENTS
jQuery.scrap_uniform_update = function($elements)
{
	var $is_iPad 			= navigator.userAgent.match(/iPad/i) != null;	// iPad check
	var $is_iPhone 		= navigator.userAgent.match(/iPhone/i) != null; // iPhone check
	
	if(($is_iPad == true) || ($is_iPhone == true))
	{
		$($elements).uniform();
	}
	else
	{
		$($elements).uniform();
	}
}