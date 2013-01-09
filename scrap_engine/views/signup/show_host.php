<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Open middle div
echo open_div('middle');

	// Sign up heading
	echo heading('Show Host Sign Up', 2);

	// Sign up container
	echo open_div('signupContain inset');
		
		// Account name
		echo form_label('Account Name');
		$inp_acc_name		= array
		(
			'name'			=> 'inpAccName'
		);
		echo form_input($inp_acc_name);
		
		// Address
		echo form_label('Address');
		$txt_address		= array
		(
			'name'			=> 'txtAddress'
		);
		echo form_textarea($txt_address);
		
		// User text
		echo div_height(10);
		echo '<p>This user will be the owner and will have access to all features.</p>';
		
		// First name
		echo form_label('First Name');
		$inp_first_name		= array
		(
			'name'			=> 'inpFirstName'
		);
		echo form_input($inp_first_name);
		
		// Surname
		echo form_label('Surname');
		$inp_surname		= array
		(
			'name'			=> 'inpSurname'
		);
		echo form_input($inp_surname);
		
		// Username
		echo form_label('Username');
		$inp_username		= array
		(
			'name'			=> 'inpUsername'
		);
		echo form_input($inp_username);
		
		// Password
		echo form_label('Password');
		$inp_password		= array
		(
			'name'			=> 'inpPassword'
		);
		echo form_password($inp_password);

		// Email address
		echo form_label('Email Address');
		$inp_email_address	= array
		(
			'name'			=> 'inpEmailAddress'
		);
		echo form_input($inp_email_address);
		
		// Sign up button
		echo make_button('Sign Me Up', 'btnSignUp');
	
	// Close sign up container
	echo close_div();
			
	// Login link
	echo anchor('login', 'I Want To Login', 'class="loginLink"');

// End of middle div
echo close_div();
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

<!--Base URL-->
<input type="hidden" id="hdPath" name="hdPath" value="<?php echo base_url(); ?>" />