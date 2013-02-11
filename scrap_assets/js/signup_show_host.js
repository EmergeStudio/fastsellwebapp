$(document).ready(function(){
	
// ------------------------------------------------------------------------------STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 				= $('#scrappy').attr('class');
	
	// ---------- AJAX PATHS
	var $base_path				= $('#hdPath').val();
	var $ajax_base_path 		= $base_path + 'ajax_handler/';
	var $ajax_html_path 		= $ajax_base_path + 'html_view/';


// ------------------------------------------------------------------------------EXECUTE
	
	$fc_sign_up()
	
	
// ------------------------------------------------------------------------------FUNCTIONS
	
	// ----- SIGN UP
	function $fc_sign_up()
	{
		$('.btnSignUp').live('click', function()
		{
			// Some variables
			$error				= false;
			$acc_name			= $('.signupContain input[name="inpAccName"]').val();
			$address			= $('.signupContain textarea[name="txtAddress"]').val();
			$timezone			= $('.signupContain select[name="drpdwnTimezone"]').val();
			$first_name			= $('.signupContain input[name="inpFirstName"]').val();
			$surname			= $('.signupContain input[name="inpSurname"]').val();
			$username			= $('.signupContain input[name="inpUsername"]').val();
			$password			= $('.signupContain input[name="inpPassword"]').val();
			$email_address		= $('.signupContain input[name="inpEmailAddress"]').val();
			
			// Validate
			// Account name
			if($error == false)
			{
				if($acc_name.length < 3)
				{
					$error		= true;
					$('.signupContain input[name="inpAccName"]').addClass('redBorder');
					$.scrap_note_time('Please provide an account name', 4000, 'cross');
				}
			}
			
			// First name
			if($error == false)
			{
				if($first_name.length < 3)
				{
					$error		= true;
					$('.signupContain input[name="inpFirstName"]').addClass('redBorder');
					$.scrap_note_time('Please provide a first name', 4000, 'cross');
				}
			}
			
			// Surname
			if($error == false)
			{
				if($surname.length < 3)
				{
					$error		= true;
					$('.signupContain input[name="inpSurname"]').addClass('redBorder');
					$.scrap_note_time('Please provide a surname', 4000, 'cross');
				}
			}
			
			// Username
			if($error == false)
			{
				if($username.length > 5)
				{
					if($.scrap_has_white_space($username) == false)
					{
					}
					else	
					{
						$error	= true;
						$('.signupContain input[name="inpUsername"]').addClass('redBorder');
						$.scrap_note_time('Your username cannot have any spaces', 4000, 'cross');
					}
				}
				else
				{
					$error		= true;
					$('.signupContain input[name="inpUsername"]').addClass('redBorder');
					$.scrap_note_time('Your username needs to be longer then 5 characters', 4000, 'cross');
				}
			}
			
			// Email
			if($error == false)
			{
				if($.scrap_is_email($email_address) == false)
				{
					$error		= true;
					$('.signupContain input[name="inpEmailAddress"]').addClass('redBorder');
					$.scrap_note_time('Your email address does not check out', 4000, 'cross');
				}
			}
			
			// Perform the sign up
			if($error == false)
			{
                $.scrap_note_loader('Signing you up');

				$.post($ajax_base_path + 'show_host_signup',
				{
					acc_name		: $acc_name,
					address			: $address,
                    timezone        : $timezone,
					first_name		: $first_name,
					surname			: $surname,
					username		: $username,
					password		: $password,
					email_address	: $email_address
				},
				function($data)
				{	
					$data	= jQuery.trim($data);
					
					if($data == 'userloginsuccess')
					{
						// Reset the loader
						//$.scrap_note_loader('You are signed up!  You\'re now going into FastSell.');
						$('#frmLogin').submit();
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