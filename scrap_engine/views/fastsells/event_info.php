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
			echo form_open_multipart('fastsells/save_event_changes', 'class="frmSaveEventChanges"');

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

					echo open_div('blockFastSellImage');

						echo open_div('fastSellImage');

							// Get the image
							$src                    = 'scrap_assets/images/universal/default_event_image.png';
							$remove_image           = FALSE;
							$url_fastsell_image     = 'serverlocalfiles/.jsons?path=scrap_shows%2F'.$fastsell_info->id.'%2Fbanner';
							$call_fastsell_image    = $this->scrap_web->webserv_call($url_fastsell_image, FALSE, 'get', FALSE, FALSE);
							if($call_fastsell_image['error'] == FALSE)
							{
								$json_fastsell_image        = $call_fastsell_image['result'];
								if($json_fastsell_image->is_empty == FALSE)
								{
									$image_path             = $json_fastsell_image->server_local_files[0]->path;
									$src                    = $this->scrap_web->image_call('serverlocalfiles/file?path='.$image_path);
									$remove_image           = TRUE;
								}
							}

							$img_properties         = array
							(
								'src'               => $src,
								'width'             => '250px'
							);

							echo full_div(img($img_properties));

							if($remove_image == TRUE)
							{
								echo anchor('fastsells/remove_event_image', 'Remove Image', 'class="btnRemoveImage"');
							}

						echo close_div();

						echo form_label('FastSell Image:');
						echo full_div('Allowed Formats: .jpg / .png / .gif<br>Max Filesize: 2MB', 'imageInfo');
						$inp_data		= array
						(
							'name'		=> 'uploadedFileFastsellImage',
							'class'		=> 'uploadedFileFastsellImage'
						);
						echo form_upload($inp_data);
						echo clear_float();

					echo close_div();

					// Start now
					if($started == FALSE)
					{
						echo div_height(30);
						echo make_button('<span class="icon-dial"></span>Start Now', 'btnStartNow blueButton', 'fastsells/start_now');
					}

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

					echo form_label('Terms and Conditions:');
					$inp_data		= array
					(
						'name'		=> 'inpTermsAndConditions',
						'class'		=> 'inpTermsAndConditions',
						'value'     => $fastsell_info->terms_and_conditions
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
		echo open_div('ajaxProductsInFastSell');
			$this->load->view('products/fastsell_products_list_small');
		echo close_div();

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
		echo open_div('ajaxCustomersInFastSell');
			$this->load->view('customers/fastsell_customers_list');
		echo close_div();

		echo div_height(30);
		echo make_button('View More', '', 'fastsells/customers');

	echo close_div();

	// Clear float
	echo clear_float();

// End of middle div
echo close_div();

// Some hidden data
echo hidden_div($this->session->userdata('sv_show_set'), 'hdEventId');
?>