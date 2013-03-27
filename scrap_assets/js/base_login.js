$(document).ready(function(){
	
// ------------------------------------------------------------------------------------------------ STARTUP

	// ---------- CURRENT PAGE
	var $crt_page 		= $('#scrappy').attr('class');
	
	// ---------- AJAX PATH
	var $base_path			= $('#hdPath').val();
	var $ajax_base_path 	= $base_path + 'ajax_handler/';
	var $ajax_html_path 	= $ajax_base_path + 'html_view/';


// ------------------------------------------------------------------------------------------------ EXECUTE	
	
	// ---------- LOGIN CODE
	if($crt_page == 'pageLogin')
	{			
		$.EnterIt('input[name="loginUsername"], input[name="loginPassword"]', function()
		{
			$fc_login();
		});
		
		$('.middle .loginContainer .btnLogin').click(function()
		{
			$fc_login();
		});
	}
		
		
// -------------------------------------------------------------------------------------------------- FUNCTIONS
	
	// ---------- LOGIN FUNCTION
	function $fc_login()
	{						
		$inp_username	= $('input[name="loginUsername"]').val();
		$inp_password	= $('input[name="loginPassword"]').val();		
		
		if(($inp_username != '') && ($inp_password != ''))
		{
			// Get the loader
			$.scrap_note_loader('Checking your login details');
		
			// Send details to try and log user in
			$.post($ajax_base_path + 'log_me_in',
			{
				inp_username	: $inp_username,
				inp_password	: $inp_password,
				encrypt			: 'yes'
			},
			function($data)
			{	
				$data		= jQuery.trim($data);
				console.log($data);
				
				if($data == 'userloginsuccess')
				{
					// Reset the loader
					$.scrap_note_loader('Yup you check out.  Going into FastSell.');
					$('#frmLogin').submit();
				}
				else
				{
					if($data == 'error1')
					{
						$.scrap_note_time('Your login details are incorrect', 4000, 'cross');
					}
					else if($data == 'error2')
					{
						$.scrap_note_time('Your account is no longer active', 4000, 'cross');
					}
				}
			});
		}
		else
		{
			$.scrap_note_time('Please fill in all login fields', 4000);
		}
	}
	
});