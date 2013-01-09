<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Middle
echo open_div('middle');
	
	// Success container
	echo open_div('fcSuccess inset');
	
		echo full_div('<span>YOU ARE ALMOST DONE</span>', 'note green');
		echo clear_float();
		echo div_height(10);
		echo heading('An email has been sent to your address with a link you need to follow in order to complete your password reset.', 4);
	
	// End of success container
	echo close_div();
	
	echo open_div('fcResetContainer');

		// Heading
		echo heading('Forgot Your Password?', 2);
		echo heading('Enter in your username and we will send you your new password via email', 4);
	
		// Forgot password container
		echo open_div('fpContainer');
	
			// Inset
			echo open_div('inset');
			
				// Email input
				$ar_inp_email		= array
				(
					'name'			=> 'username',
					'placeholder'	=> 'Username'
				);
				echo form_input($ar_inp_email);
					
				// Reset password button
				echo make_button('Reset My Password', 'btnResetPassword');
				
			echo close_div();
				
			// Login link
			echo anchor('login', 'I Want To Login', 'class="loginLink"');
				
			// Sign up link
			echo anchor('http://www.emergestudio.net', 'An Emerge Studio Project', 'class="emergeStudioLink"');
			
		// Close forgot password container
		echo close_div();
	
	// End of password container
	echo close_div();
	
// Close middle div
echo close_div();
?>

<!--Base URL-->
<input type="hidden" id="hdPath" name="hdPath" value="<?php echo base_url(); ?>" />