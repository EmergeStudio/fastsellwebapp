<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Open middle div
echo open_div('middle');

	// Large column
	echo open_div();

		// Current orders
		echo open_div('whiteBack');

			// Heading
			echo div_height(6);
			echo full_div('', 'icon-ticket headingIcon yellow');
			echo heading('FastSell Information', 2);
			echo div_height(8);

			// Show Details
			echo form_open('fastsells/save_event_changes', 'class="frmSaveEventChanges"');

				echo open_div('inset showDescription floatRight');

					echo form_label('Start Date:');
					$inp_data		= array
					(
						'name'		=> 'inpStartDate',
						'class'		=> 'inpStartDate scrap_date',
						'value'     => $this->scrap_string->make_db_date($fastsell_info->event_start_date)
					);
					echo form_input($inp_data);
					echo clear_float();

					echo open_div('time');

						echo $this->scrap_string->hours_select('startHoursSelect', $this->scrap_string->make_hours($fastsell_info->event_start_date));
						echo $this->scrap_string->minutes_select('startMinutesSelect', $this->scrap_string->make_minutes($fastsell_info->event_start_date));
						echo clear_float();

					echo close_div();

					echo form_label('End Date:');
					$inp_data		= array
					(
						'name'		=> 'inpEndDate',
						'class'		=> 'inpEndDate scrap_date',
						'value'     => $this->scrap_string->make_db_date($fastsell_info->event_end_date)
					);
					echo form_input($inp_data);
					echo clear_float();

					echo open_div('time');

						echo $this->scrap_string->hours_select('endHoursSelect', $this->scrap_string->make_hours($fastsell_info->event_end_date));
						echo $this->scrap_string->minutes_select('endMinutesSelect', $this->scrap_string->make_minutes($fastsell_info->event_end_date));
						echo clear_float();

					echo close_div();

				echo close_div();

				echo open_div('showDescription inset floatLeft');

					// Inputs
					echo form_label('FastSell Event Title:');
					$inp_data		= array
					(
						'name'		=> 'inpShowName',
						'class'		=> 'inpShowName inpBigText',
						'value'     => $fastsell_info->name
					);
					echo form_input($inp_data);

					echo form_label('A Brief Description:');
					$inp_data		= array
					(
						'name'		=> 'inpShowDescription',
						'class'		=> 'inpShowDescription',
						'value'     => $fastsell_info->description
					);

					echo form_textarea($inp_data);

				echo close_div();

				// Clear float
				echo clear_float();

				// Save details
				echo div_height(30);
				echo make_button('Save Changes', 'btnSaveChanges blueButton');

				// Some hidden data
				echo form_hidden('hdEventId', $this->uri->segment(3));
				echo form_hidden('hdReturnUrl', current_url());

			echo form_close();

		echo close_div();

	// End of large column
	echo close_div();

	// Right column
	echo open_div('whiteBack rightColEven');

		// Heading
		echo div_height(6);
		echo full_div('', 'icon-box headingIcon blue');
		echo heading('Recent Products', 2);
		echo div_height(8);

		// Load the view
		$this->load->view('products/fastsell_products_list_small');

		echo div_height(30);
		echo make_button('View More', '', 'fastsells/products');

	echo close_div();

	// Left column
	echo open_div('whiteBack leftColEven');

		// Heading
		echo div_height(6);
		echo full_div('', 'icon-users headingIcon blue');
		echo heading('Recent Customers', 2);
		echo div_height(8);

		// Load the view
		$this->load->view('customers/fastsell_customers_list');

		echo div_height(30);
		echo make_button('View More', '', 'fastsells/customers');

	echo close_div();

	// Clear float
	echo clear_float();

// End of middle div
echo close_div();
?>