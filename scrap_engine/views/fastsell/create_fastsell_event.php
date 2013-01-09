<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Open middle div
echo open_div('middle').open_div('whiteBack coolScreen');

	// Control bar
	echo open_div('controlBar plain');

		// Shifter
		echo open_div('shifter');

			// Active bar
			echo open_div('activeBarContain');

				echo open_div('activeBar');

					// Active start
					echo full_div('Shifter Active Start', 'shifterBlockActive shifterStart');

					// Active end
					echo full_div('Shifter Active End', 'shifterBlockActive shifterEnd');

					// Clear float
					echo clear_float();

				echo close_div();

			echo close_div();

			echo full_div('FastSell Details', 'shifterBlock shifterStart');

			echo full_div('Who To Notify?', 'shifterBlock');

			echo full_div('Add Products', 'shifterBlock');

			echo full_div('Create FastSell Event', 'shifterBlock shifterEnd');

			// Clear float
			echo clear_float();

		echo close_div();

	// End of control bar
	echo close_div();

	// Shifter pane 1
	echo open_div('shifterPane shifterPane_1');

		// Heading
		echo full_div('', 'icon-clipboard headingIcon lightGrey');
		echo heading('FastSell Details', 2);
		echo div_height(15);

		// Show banner
		echo open_div('inset showBanner');

			echo full_div('', 'uploadedBannerContainer');

			echo open_div('uploadBannerMessage');

				echo img('scrap_assets/images/icons/upload_images_icon.png');

				echo full_div('', 'loader');

			echo close_div();

		echo close_div();

		// Upload space
		echo div_height(20);
		echo open_div('uploadBanner');

			echo open_div('uploaderContainer');

				// Upload form
				echo form_open_multipart('ajax_handler_shows/upload_temp_banner', 'class="frmUploadBanner"');

					$inp_data		= array
					(
						'name'		=> 'uploadedFile',
						'class'		=> 'uploadedFile'
					);
					echo form_upload($inp_data);
					echo clear_float();

				// Close the form
				echo form_close();

			echo close_div();

		echo close_div();

		// Some height
		echo div_height(30);

		// Show Details
		echo open_div('inset showDescription floatRight');

			echo form_label('Start Date:');
			$inp_data		= array
			(
				'name'		=> 'inpStartDate',
				'class'		=> 'inpStartDate scrap_date'
			);
			echo form_input($inp_data);
			echo clear_float();

			echo open_div('time');

				echo $this->scrap_string->hours_select('startHoursSelect');
				echo $this->scrap_string->minutes_select('startMinutesSelect');
				echo clear_float();

			echo close_div();

			echo form_label('End Date:');
			$inp_data		= array
			(
				'name'		=> 'inpEndDate',
				'class'		=> 'inpEndDate scrap_date'
			);
			echo form_input($inp_data);
			echo clear_float();

			echo open_div('time');

				echo $this->scrap_string->hours_select('endHoursSelect');
				echo $this->scrap_string->minutes_select('endMinutesSelect');
				echo clear_float();

			echo close_div();

			echo form_label('Close Date:');
			$inp_data		= array
			(
				'name'		=> 'inpCloseDate',
				'class'		=> 'inpCloseDate scrap_date'
			);
			echo form_input($inp_data);
			echo clear_float();

			echo open_div('time');

				echo $this->scrap_string->hours_select('closeHoursSelect');
				echo $this->scrap_string->minutes_select('closeMinutesSelect');
				echo clear_float();

			echo close_div();

		echo close_div();

		echo open_div('showDescription inset floatLeft');

			// Inputs
			echo form_label('FastSell Event Title:');
			$inp_data		= array
			(
				'name'		=> 'inpShowName',
				'class'		=> 'inpShowName inpBigText'
			);
			echo form_input($inp_data);

			echo form_label('A Brief Description:');
			$inp_data		= array
			(
				'name'		=> 'inpShowDescription',
				'class'		=> 'inpShowDescription'
			);

			echo form_textarea($inp_data);

		echo close_div();

		// Clear float
		echo clear_float();

	echo close_div();

	// Shifter pane 2
	echo open_div('shifterPane shifterPane_2');

		// Heading
		echo full_div('', 'icon-users headingIcon lightGrey');
		echo heading('Who Do You Want To Notify Of This FastSell?', 2);

		// Sub heading
		echo '<p>If you make this FastSell public anyone with a Fast Sell account will be able to see the event. Alternatively you can select a list of customers or just notify a select few of your contacts who will have exclusive viewing rights to the event. You can decide by choosing from the drop down below.</p>';
		echo div_height(35);

		// Active users
		echo open_div('inset floatRight chosenUsers');

			// Heading
			echo heading('Customers Who Will Be Notified', 3);
			echo '<p>These selected customers will be notified of this FastSell and be able to purchase any one of your selected products.</p>';
			echo div_height(15);

			// Message
			echo open_div('message messageSelectFromLeftOrUpload');

				$inp_data		= array
				(
					'name'		=> 'uploadedFile2',
					'class'		=> 'uploadedFile2'
				);
				echo open_div('uploaderContainer').form_upload($inp_data).close_div();
				echo clear_float();
				echo div_height(50);

			echo close_div();

		echo close_div();

		// User list
		echo open_div('inset floatLeft userList');

			// Heading
            echo heading('Search For Customer', 3);

			// Search
			echo open_div('search');

				echo make_button('Search', 'btnSearchCustomer blueButton', '', 'right');
				$inp_data			= array
				(
					'name'			=> 'inpCustomerName'
				);
				echo form_input($inp_data);

			echo close_div();
			echo div_height(10);

			$this->load->view('customers/customers_list');

		echo close_div();

		// Clear float
		echo clear_float();

	echo close_div();

	// Shifter pane 3
	echo open_div('shifterPane shifterPane_3');

		// Heading
		echo full_div('', 'icon-box headingIcon lightGrey');
		echo heading('What Products Are You Wanting To Sell?', 2);
		echo '<p>Select items in your product catalogue to add to the FastSell and set your desired price.</p>';
		echo div_height(20);

		// Upload master file
		echo open_div('productsMasterFileUpload floatRight');

			echo heading('Link Products Via Master File:'.nbs(4), 3, 'class="floatLeft"');
			$inp_data		= array
			(
				'name'		=> 'uploadedFile3',
				'class'		=> 'uploadedFile3'
			);
			echo form_upload($inp_data);

		echo close_div();

		// Search products
		$inp_data			= array
		(
			'name'			=> 'inpSearchProducts',
			'class'         => 'inpSearchProducts floatLeft'
		);
		echo form_input($inp_data);
		echo make_button('Search', 'btnSearchProducts blueButton', '', 'left');
		echo make_button('Reset', 'btnSearchReset', '', 'left');
		echo clear_float();

		// Load the products list
		echo div_height(20);
		$this->load->view('products/add_products_list');

		echo div_height(20);
		echo full_div('', 'line');
		echo div_height(40);

		// Heading
		echo full_div('', 'icon-box headingIcon lightGrey');
		echo heading('Products In Your FastSell', 2);
		echo div_height(20);

	echo close_div();

	// Shifter pane 4
	echo open_div('shifterPane shifterPane_4');

		// Heading
		echo full_div('', 'icon-checkmark headingIcon lightGrey');
		echo heading('All Done But You Can Make Changes', 2);

		// Content
		echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur et tortor quis nunc vehicula dapibus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed arcu risusipsum dolor sit amet, consectetur adipiscing elit. Curabitur et tortor quis</p>';
		echo '<p>Sit amet, consectetur adipiscing elit. Curabitur et tortor quis nunc vehicula dapibus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed arcu risusipsum dolor sit amet, consectetur adipiscing elit. Curabitur et tortor quis.</p>';

	echo close_div();

	// Shifter navigation
	echo open_div('shifterNav');

		echo make_button('Next Step', 'btnShifterNext', '', 'right');

		echo make_button('Finish FastSell Create', 'btnComplete', '', 'right', '', FALSE);

		echo make_button('Go Back', 'btnShifterBack', '', '', '', FALSE);

		// Shifter position
		echo hidden_div(1, 'hdPanePosition');

		// Clear float
		echo clear_float();

	// End of shifter navigation
	echo close_div();


// End of middle div
echo close_div().close_div();
?>