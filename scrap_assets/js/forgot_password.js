$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler_users/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';


// ------------------------------------------------------------------------------EXECUTE
	
	$fc_reset_password();
	
	$fc_save_password();
	
	
// ------------------------------------------------------------------------------FUNCTIONS
	
	// ----- RESET PASSWORD
	function $fc_reset_password()
	{
		$('.btnResetPassword').live('click', function()
		{
			// Some variables
			$username			= $('input[name="username"]').val();
			$error				= false;
			
			// Validate
			if($error == false)
			{
				if($username.length > 5)
				{
					if($.scrap_has_white_space($username) == true)
					{
						$error	= true;
						$('.fpContainer input[name="username"]').addClass('redBorder');
						$.scrap_note_time('Your username cannot have any spaces', 4000, 'cross');
					}
				}
				else
				{
					$error		= true;
					$('.fpContainer input[name="username"]').addClass('redBorder');
					$.scrap_note_time('Your username needs to be longer then 5 characters', 4000, 'cross');
				}
			}
			
			// Submit the reset
			if($error == false)
			{
				// Edit the DOM
				$.scrap_note_loader('Resetting the password for your username');
				
				// Post the data
				$.post($ajax_base_path + 'reset_password_by_username',
				{
					username			: $username
				},
				function($data)
				{	
					$data	= jQuery.trim($data);
					
					if($data == '9876')
					{
						$.scrap_logout();
					}
					else if($data == 'okitsdone')
					{
						// Change the DOM
						$('.fcResetContainer').hide();
						$('.fcSuccess').fadeIn();
						$.scrap_note_hide();
					}
					else
					{
						$.scrap_note_time($data, 4000, 'cross');
					}
				});
			}
		});
	}
	
	// ----- SAVE PASSWORD
	function $fc_save_password()
	{
		$('.btnSaveNewPassword').live('click', function()
		{
			// Some variables
			$error				= false;
			$password			= $('.fcResetContainer input[name="password"]').val();
			$password_confirm	= $('.fcResetContainer input[name="password_confirm"]').val();
			$token				= $('.fcResetContainer input[name="hdToken"]').val();
			
			// Validate
			// Password
			if($error == false)
			{
				if(($.scrap_is_password($password) == false))
				{
					$error		= true;
					$('.fcResetContainer input[name="password"]').addClass('redBorder');
					$.scrap_note_time('Your password is not valid. It is <b>required</b>, needs to be <b>6 characters or more</b> and must be <b>alphanumeric</b>.', 4000, 'cross');
				}
			}
			
			// Password confirm
			if($error == false)
			{
				if($password != $password_confirm)
				{
					$error		= true;
					$('.fcResetContainer input[name="password"], .fcResetContainer input[name="password_confirm"]').addClass('redBorder');
					$.scrap_note_time('Your passwords do not match', 4000, 'cross');
				}
			}
			
			// Submit the reset
			if($error == false)
			{
				// Edit the DOM
				$.scrap_note_loader('Saving your new password');
				
				// Post the data
				$.post($ajax_base_path + 'reset_password_with_token',
				{
					password			: $password,
					token				: $token
				},
				function($data)
				{	
					$data	= jQuery.trim($data);
					
					if($data == '9876')
					{
						$.scrap_logout();
					}
					else if($data == 'okitsdone')
					{
						// Change the DOM
						$('.fcResetContainer').hide();
						$('.fcSuccess').fadeIn();
						$.scrap_note_hide();
					}
					else
					{
						$.scrap_note_time($data, 4000, 'cross');
					}
				});
			}
		});
	}


});