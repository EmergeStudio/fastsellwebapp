<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Open middle div
echo open_div('middle').open_div('whiteBack coolScreen');

	// Control bar
	echo open_div('controlBar plain');

		// Go back button
		echo anchor('fastsells', '<span class="icon-arrow-left"></span>Back To FastSells', 'class="goBackButton"');

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

			echo full_div('Add Products', 'shifterBlock shifterEnd');

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

			// FastSell image
			echo open_div('blockFastSellImage').form_open_multipart('ajax_handler_fastsells/add_temp_event_image', 'class="frmFastSellImage"');

				echo full_div('', 'icon-camera fastSellImage');

				echo form_label('FastSell Image:');
				echo full_div('Allowed Formats: .jpg / .png / .gif<br>Max Filesize: 2MB', 'imageInfo');
				$inp_data		= array
				(
					'name'		=> 'uploadedFileFastsellImage',
					'class'		=> 'uploadedFileFastsellImage',
					'accept'    => 'image/*'
				);
				echo form_upload($inp_data);
				echo clear_float();

			echo form_close().close_div();

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

			echo form_label('Terms and Conditions:');
			$inp_data		= array
			(
				'name'		=> 'inpTermsAndConditions',
				'class'		=> 'inpTermsAndConditions'
			);
			echo form_textarea($inp_data);

		echo close_div();

		// Clear float
		echo clear_float();

		// Fastsell categories
		echo open_div('inset fastsellCategories');

			// Heading
			echo full_div('FastSell Categories', 'mainHeading');

			// Text
			echo '<p>Start typing in the textbox below and find your desired category.</p>';

			$inp_data		= array
			(
				'name'		=> 'inpCategorySearch',
				'class'		=> 'inpCategorySearch inpBigText'
			);
			echo form_input($inp_data);

			// The categories to search for
			echo hidden_div('Food, Beverages & Tobacco][Food Items][Dairy Products][Cheese', 'hdFastSellCategories');

			// Ajax container
			echo open_div('ajax_fastSellCategories').close_div();

		echo close_div();

	echo close_div();

	// Shifter pane 2
	echo open_div('shifterPane shifterPane_2');

		// Upload master file
		echo open_div('customerMasterFileUpload floatRight');

			echo make_button('Add Single Customers', 'btnAddCustomersPopup btnAdd blueButton', '', 'left');
			echo make_button('Add By Group', 'btnAdd btnAddByGroupPopup blueButton', '', 'left');
			echo make_button('Add New Customer', 'btnAdd btnAddNewCustomerPopup blueButton', '', 'left');
			echo make_button('Upload Master Data File', 'btnAdd btnUploadCustomers blueButton', '', 'left');

		echo close_div();

		// Heading
		echo full_div('', 'icon-users headingIcon lightGrey');
		echo heading('Who Do You Want To Notify?', 2);

		// Sub heading
		echo '<p>If you make this FastSell public anyone with a Fast Sell account will be able to see the event. Alternatively you can select a list of customers or just notify a select few of your contacts who will have exclusive viewing rights to the event. You can decide by choosing from the drop down below.</p>';
		echo div_height(35);

		// Active users
		echo open_div('inset floatRight chosenUsers');

			// Heading
			echo heading('Customers Who Will Be Notified', 3);
			echo '<p>These selected customers will be notified of this FastSell and be able to purchase any one of your selected products.</p>';

			// Chose users
			echo open_div('chosenUsersList');

				echo div_height(15);

				// Rows
//				$checkbox_remove_all_customers  = array
//				(
//					'name'                      => 'checkRemoveAllCustomers',
//					'class'                     => 'checkRemoveAllCustomers tooltip',
//					'checked'                   => TRUE,
//					'title'                     => 'Tick to remove all these customers from the FastSell'
//				);
//
//				// Heading
//				$this->table->set_heading(form_checkbox($checkbox_remove_all_customers), '', array('data' => 'Customer Name', 'class' => 'fullCell'), 'Customer Number');
				$this->table->set_heading('', '', array('data' => 'Customer Name', 'class' => 'fullCell'), 'Customer Number');

				// Generate table
				echo $this->table->generate();

			echo close_div();

		echo close_div();

		// User list
//		echo open_div('inset floatLeft userList');
//
//			// Heading
//            echo heading('Add Single Customers', 3);
//
//			// Search
//			echo open_div('search');
//
//				echo make_button('Search', 'btnSearchCustomer blueButton', '', 'right');
//				$inp_data			= array
//				(
//					'name'			=> 'inpCustomerName'
//				);
//				echo form_input($inp_data);
//
//			echo close_div();
//			echo div_height(10);
//
//			echo open_div('ajaxCustomerList');
//
//				$this->load->view('customers/customers_list');
//
//			echo close_div();
//
//		echo close_div();

		// Clear float
		echo clear_float();

	echo close_div();

	// Shifter pane 3
	echo open_div('shifterPane shifterPane_3');

		echo open_div('centerMessage');

			echo heading('What Products Are You Wanting To Sell?', 2);

			echo open_div('iconSet');

                echo full_div('', 'iconImage icon-box');
				echo full_div('', 'iconImage icon-arrow-right');
				echo full_div('', 'iconImage icon-sale');
				echo clear_float();

			echo close_div();

			echo open_div('buttonOptions');

				echo make_button('Add New Product', 'btnAdd btnAddProductAndLink blueButton', '', 'left');
				echo full_div('OR', 'orText floatLeft');
				echo make_button('Add Existing Product', 'btnAdd btnAddProductPopup blueButton', '', 'left');
				echo full_div('OR', 'orText floatLeft');
				echo make_button('Upload Master Data File', 'btnAdd btnUploadProducts blueButton', '', 'left');
				echo clear_float();

			echo close_div();

		echo close_div();

//		// Upload master file
//		echo open_div('productsMasterFileUpload floatRight');
//
//			echo make_button('Upload Master Data File', 'btnAdd btnUploadProducts blueButton');
//
//		echo close_div();
//
//		// Heading
//		echo full_div('', 'icon-box headingIcon lightGrey');
//		echo heading('What Products Are You Wanting To Sell?', 2);
//		echo '<p>Select items in your product catalogue to add to the FastSell and set your desired price.</p>';
//		echo div_height(20);

//		// Search products
//		$inp_data			= array
//		(
//			'name'			=> 'inpSearchProducts',
//			'class'         => 'inpSearchProducts floatLeft'
//		);
//		echo form_input($inp_data);
//		echo make_button('Search', 'btnSearchProducts blueButton', '', 'left');
//		echo make_button('Reset', 'btnSearchReset', '', 'left');
//		echo clear_float();
//
//		// Load the products list
//		echo div_height(20);
//		echo open_div('products');
//
//			$this->load->view('products/add_products_list');
//
//		echo close_div();
//
//		echo div_height(20);
//		echo full_div('', 'line');
//		echo div_height(40);

		// Heading
		echo full_div('', 'line');
		echo div_height(40);
		echo full_div('', 'icon-box headingIcon lightGrey');
		echo heading('Products In Your FastSell', 2);
		echo div_height(20);

		echo open_div('ajaxProductsInFastSell');

		echo close_div();

	echo close_div();

	// Shifter navigation
	echo open_div('shifterNav');

		echo make_button('Next Step', 'btnShifterNext', '', 'right');

		echo make_button('Ok All Done', 'btnComplete', 'fastsells/deploy', 'right', '', FALSE);

		echo make_button('Go Back', 'btnShifterBack', '', '', '', FALSE);

		// Shifter position
		echo hidden_div(1, 'hdPanePosition');

		// Clear float
		echo clear_float();

	// End of shifter navigation
	echo close_div();

	// Some hidden data
	echo hidden_div('no_id', 'hdEventId');
	echo hidden_div('no_banner', 'hdBannerImagePath');


// End of middle div
echo close_div().close_div();
?>