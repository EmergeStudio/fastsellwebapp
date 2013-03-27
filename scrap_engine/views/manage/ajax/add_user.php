<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Profile information
echo open_div('profileInformation rightColumn');

	// Form
	echo form_open_multipart('manage/add_a_user', 'class="frmNewUser"');

		// User type
		echo open_div('profileUserType displayNone');

			// Inset
			echo open_div('inset');

				// Drop down list
				$ar_user_types	= array
				(
					2			=> 'Administrator',
					3			=> 'Normal User'
				);
				echo form_dropdown('drpdwnUserType', $ar_user_types, 1, 'class="drpdwnUserType"');

			echo close_div();

			// Some height
			echo div_height(25);

		echo close_div();


		// User information
		echo open_div('inset profileContainer');

			// First names
			echo form_label('First Name');
			$inp_data			= array
			(
				'name'			=> 'inpName',
				'class'			=> 'inpName'
			);
			echo form_input($inp_data);

			// Surname
			echo form_label('Last Name');
			$inp_data			= array
			(
				'name'			=> 'inpSurname',
				'class'			=> 'inpSurname'
			);
			echo form_input($inp_data);

			// Email
			echo form_label('Email Address');
			$inp_data			= array
			(
				'name'			=> 'inpEmail',
				'class'			=> 'inpEmail'
			);
			echo form_input($inp_data);

			// Username
			echo form_label('Username');
			$inp_data			= array
			(
				'name'			=> 'inpUsername',
				'class'			=> 'inpUsername'
			);
			echo form_input($inp_data);

			// Password
			echo open_div('passwordContain');

				echo div_height(5);

				echo form_label('Your New Password');
				$inp_data			= array
				(
					'name'			=> 'inpPassword',
					'class'			=> 'inpPassword'
				);
				echo form_password($inp_data);

				// Confirm password
				echo form_label('Confirm Password');
				$inp_data			= array
				(
					'name'			=> 'inpPasswordConfirm',
					'class'			=> 'inpPasswordConfirm last'
				);
				echo form_password($inp_data);

			// End of password container
			echo close_div();

		// Close the form
		echo form_close();

	// End of profile container
	echo close_div();


	// Upload container
	echo open_div('inset uploadContainer displayNone');

		// Form loader
		echo full_div('Uploading new profile image', 'loader');

		// Form container
		echo open_div('uploadFormContiner');

			// Upload text
			echo full_div('Please provide images in .jpg, .gif or .png formats', 'greyLabelText');

			// Upload form
			echo form_open_multipart('ajax_handler_users/upload_user_image', 'class="frmUploadUserImage"');

				// Upload imput
				$upload_inp	 	= array
				(
					'name'  	=> 'inpUploadImage',
					'class'		=> 'inpUploadImage'
				);
				echo form_upload($upload_inp);

				// Upload options
				echo div_height(15);
				echo make_button('Upload It', 'btnDoUpload', '', 'left');
				echo make_button('Never Mind', 'btnCancelUpload', '', 'left');
				echo clear_float();

			// Close upload form
			echo form_close();

		// End of upload form container
		echo close_div();

	// End of upload container
	echo close_div();

echo close_div();

// Side column
echo open_div('leftColumn');

	// Profile image
	echo open_div('inset');

		$img_properties			= array
		(
			'src'			=> $this->scrap_web->get_profile_image(0),
			'width'			=> '120',
			'class'			=> 'profileImage'
		);
		echo img($img_properties);

		// New upload button
		echo make_button('Upload', 'btnUploadNewProfileImage btnUpArrow', '', '', 'Upload a new profile image');

	echo close_div();

echo close_div();

// Clear float
echo clear_float();
?>