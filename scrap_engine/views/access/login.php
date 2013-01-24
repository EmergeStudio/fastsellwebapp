<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="middle">

	<div class="loginContainer">

		<?php
		echo div_height(25);

		// Inset
		echo open_div('inset');

			// Username input
			echo form_label('Username / Email Address:');
			$ar_inp_username	= array
			(
				'name'			=> 'loginUsername'
			);
			echo form_input($ar_inp_username);

			// Password input
			echo form_label('Password:');
			$ar_inp_password	= array
			(
				'name'			=> 'loginPassword'
			);
			echo form_password($ar_inp_password).'<br>';

			// Login button
			echo make_button('Login Now', 'btnLogin');

		echo close_div();

		// Forgot password link
		echo make_button('Forgot Your Password?', 'forgotPassword', 'forgot_password');

		// Sign up link
		echo make_button('I Want To Sign Up', 'signUp greenButton', 'signup');

		// Sign up link
		echo anchor('http://www.emergestudio.net', 'An Emerge Studio and Data Connect Project', 'class="emergeStudioLink"');
		?>

		<!--Bottom Strip-->
		<div id="loginBottom">
			<?php
			$attributes = array('id' => 'frmLogin');
			if($this->uri->segment(2) == 'redirect')
			{
				$new_url			= '';
				$new_url			.= $this->uri->segment(2).'/';
				$new_url			.= $this->uri->segment(3).'/';
				$new_url			.= $this->uri->segment(4).'/';
				$new_url			.= $this->uri->segment(5);

				echo form_open($new_url, $attributes);
			}
			else
			{
				echo form_open('login_redirect', $attributes);
			}
			echo form_hidden('hdLogin', 'login');
			echo form_close();
			?>
		</div>

	</div>

	<?php echo clear_float(); ?>

	<?php echo div_height(50); ?>

</div>

<!--Base URL-->
<input type="hidden" id="hdPath" name="hdPath" value="<?php echo base_url(); ?>" />