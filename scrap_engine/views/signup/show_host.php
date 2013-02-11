<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Open middle div
echo open_div('middle');

	// Sign up container
	echo open_div('signupContain inset');
		
		// Account name
		echo form_label('Company Name');
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
		echo div_height(4);

		$ar_timezone	= array
		(
			'1' 	=> '(GMT -12:00) Eniwetok, Kwajalein',
			'2'		=> '(GMT -11:00) Midway Island, Samoa',
			'3'		=> '(GMT -10:00) Hawaii',
			'4'		=> '(GMT -9:00) Alaska',
			'5'		=> '(GMT -8:00) Pacific Time (US &amp; Canada)',
			'6'		=> '(GMT -7:00) Mountain Time (US &amp; Canada)',
			'7'		=> '(GMT -6:00) Central Time (US &amp; Canada), Mexico City',
			'8'		=> '(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima',
			'9'		=> '(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz',
			'10'	=> '(GMT -3:00) Brazil, Buenos Aires, Georgetown',
			'11'	=> '(GMT -2:00) Mid-Atlantic',
			'12'	=> '(GMT -1:00) Azores, Cape Verde Islands',
			'13'	=> '(GMT) Western Europe Time, London, Lisbon, Casablanca',
			'14'	=> '(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris',
			'15'	=> '(GMT +2:00) Kaliningrad, South Africa',
			'16'	=> '(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg',
			'17'	=> '(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi',
			'18'	=> '(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent',
			'19'	=> '(GMT +6:00) Almaty, Dhaka, Colombo',
			'20'	=> '(GMT +7:00) Bangkok, Hanoi, Jakarta',
			'21'	=> '(GMT +8:00) Beijing, Perth, Singapore, Hong Kong',
			'22'	=> '(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk',
			'23'	=> '(GMT +10:00) Eastern Australia, Guam, Vladivostok',
			'24'	=> '(GMT +11:00) Magadan, Solomon Islands, New Caledonia',
			'25'	=> '(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka'
		);

		echo form_dropdown('drpdwnTimezone', $ar_timezone, 13);
		
		// User text
		echo div_height(16);
		echo '<p>This user will be the owner and will have access to all features.</p>';
		
		// First name
		echo form_label('First Name');
		$inp_first_name		= array
		(
			'name'			=> 'inpFirstName'
		);
		echo form_input($inp_first_name);
		
		// Surname
		echo form_label('Last Name');
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

		// Confirm password
		echo form_label('Confirm Password');
		$inp_password_2		= array
		(
			'name'			=> 'inpPassword2'
		);
		echo form_password($inp_password_2);

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