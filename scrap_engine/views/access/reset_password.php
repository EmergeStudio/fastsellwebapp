<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Middle
echo open_div('middle');
	
	// Success container
	echo open_div('fcSuccess inset ');
	
		echo full_div('<span>SUCCESSFULLY RESET</span>', 'note green');
		echo clear_float();
		echo div_height(10);
		echo heading('Your password has now been reset. Go to the login screen and login with your new details.', 4);
		echo div_height(5);
		echo make_button('login Now', 'btnLogin2', 'login');
		echo div_height(10);
	
	// End of success container
	echo close_div();
	
	echo open_div('fcResetContainer');

		// Heading
		echo heading('Rest Your Password?', 2);
		echo heading('Enter in your new password below and confirm it', 4);
	
		// Forgot password container
		echo open_div('fpContainer');
	
			// Inset
			echo open_div('inset');
			
				// Password input
				$ar_inp_password	= array
				(
					'name'			=> 'password',
					'placeholder'	=> 'New Password',
					'type'			=> 'password'
				);
				echo form_input($ar_inp_password);
			
				// Confirm password input
				$ar_inp_password_2	= array
				(
					'name'			=> 'password_confirm',
					'placeholder'	=> 'Confirm Password',
					'type'			=> 'password'
				);
				echo form_input($ar_inp_password_2);
				
				// Token
				echo form_hidden('hdToken', $token);
					
				// Reset password button
				echo make_button('Save New Password', 'btnSaveNewPassword');
				
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