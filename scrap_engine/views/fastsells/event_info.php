<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Show Details
echo form_open_multipart('fastsells/save_event_changes', 'class="frmSaveEventChanges"');

	// Open middle div
	echo open_div('middle');

		// Small right column
		echo open_div('rightColSmall showDescription');

			// White back
			echo open_div('whiteBack');

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
							echo div_height(20);
						}

					echo close_div();

					echo div_height(15);
					$inp_data		= array
					(
						'name'		=> 'uploadedFileFastsellImage',
						'class'		=> 'uploadedFileFastsellImage'
					);
					echo form_upload($inp_data);
					echo clear_float();
					echo div_height(10);
					echo full_div('Allowed Formats: .jpg / .png / .gif<br>Max Filesize: 2MB', 'imageInfo');
					echo div_height(10);

				echo close_div();

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

					echo $this->scrap_string->hours_select_short('startHoursSelect', $this->scrap_string->make_hours_ampm($fastsell_info->event_start_date));
					echo $this->scrap_string->minutes_select('startMinutesSelect', $this->scrap_string->make_minutes($fastsell_info->event_start_date));
					echo $this->scrap_string->ampm_select('startAMPM', $this->scrap_string->make_ampm($fastsell_info->event_start_date));
					echo clear_float();

				echo close_div();

				echo div_height(20);

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

					echo $this->scrap_string->hours_select_short('endHoursSelect', $this->scrap_string->make_hours_ampm($fastsell_info->event_end_date));
					echo $this->scrap_string->minutes_select('endMinutesSelect', $this->scrap_string->make_minutes($fastsell_info->event_end_date));
					echo $this->scrap_string->ampm_select('endAMPM', $this->scrap_string->make_ampm($fastsell_info->event_end_date));
					echo clear_float();

				echo close_div();

				echo div_height(30);
				echo make_button('Save Changes', 'btnSaveChanges blueButton');

			echo close_div();

		// End of small right column
		echo close_div();

		// Large left column
		echo open_div('leftColBig');

			// Current orders
			echo open_div('whiteBack');

				echo '<table class="tblFastSellInfo">';

					echo '<tr>';

						echo '<td>';

							echo full_div('', 'icon-info headingIcon blue');

						echo '</td>';
						echo '<td>';

							// Start now
							echo make_button('<span class="icon-megaphone"></span>Notify Customers', 'btnNotifyCustomers blueButton', 'fastsells/notify_customers', 'right');
							if($started == FALSE)
							{
								echo make_button('<span class="icon-dial"></span>Start Now', 'btnStartNow blueButton', 'fastsells/start_now', 'right');
							}

							echo heading('FastSell Information', 2);

						echo '</td>';

					echo '</tr>';

					echo '<tr>';

						echo '<td class="title">Event Title</td>';
						echo '<td class="content">';

							$inp_data		= array
							(
								'name'		=> 'inpShowName',
								'class'		=> 'inpShowName inpBigText',
								'value'     => $fastsell_info->name
							);
							echo form_input($inp_data);

						echo '</td>';

					echo '</tr>';

					echo '<tr>';

						echo '<td class="title">A Brief Description</td>';
						echo '<td class="content">';

							$inp_data		= array
							(
								'name'		=> 'inpShowDescription',
								'class'		=> 'inpShowDescription',
								'value'     => $fastsell_info->description
							);
							echo form_textarea($inp_data);

						echo '</td>';

					echo '</tr>';

					echo '<tr>';

						echo '<td class="title">Description</td>';
						echo '<td class="content">';

							$inp_data		= array
							(
								'name'		=> 'inpTermsAndConditions',
								'class'		=> 'inpTermsAndConditions',
								'value'     => $fastsell_info->terms_and_conditions
							);
							echo form_textarea($inp_data);

						echo '</td>';

					echo '</tr>';

					echo '<tr>';

						echo '<td class="title">FastSell Categories</td>';
						echo '<td class="content">';

							// FastSell categories
							echo open_div('fastsellCategories');

								$inp_data		= array
								(
									'name'		=> 'inpCategorySearch',
									'class'		=> 'inpCategorySearch inpBigText'
								);
								echo form_input($inp_data);

								// Text
								echo '<p>Start typing in the textbox above and find your desired category.</p>';

								// Ajax container
								echo open_div('ajax_fastSellCategories');

									if($fastsell_info->fastsell_item_categories != null)
									{
										foreach($fastsell_info->fastsell_item_categories as $item_category)
										{
											// Get the category
											$url_fs_cat                 = 'fastsellitemcategories/.jsons?categorytext='.urlencode($item_category->category).'&includerelationships=true';
											$call_fs_cat                = $this->scrap_web->webserv_call($url_fs_cat, FALSE, 'get', FALSE, FALSE);
											$dt_inner_body['category']  = $call_fs_cat;

											echo open_div('catBack blue');

											$this->load->view('fastsells/category_breadcrumbs', $dt_inner_body);

											echo close_div();
										}
									}

									// Hidden data
									echo form_hidden('hdFastSellCategories', '');

								echo close_div();

							echo close_div();

							echo div_height(20);

						echo '</td>';


					echo '<tr>';

						echo '<td class="title"></td>';
						echo '<td class="content">';

							echo make_button('Save Changes', 'btnSaveChanges blueButton');

						echo '</td>';

					echo '</tr>';

				echo '</table>';

				// Some hidden data
				echo form_hidden('hdEventId', $this->uri->segment(3));
				echo form_hidden('hdReturnUrl', current_url());

			echo close_div();

			// Clear float
			echo clear_float();

		// End of large column
		echo close_div();

		echo clear_float();

	// End of middle div
	echo close_div();

echo form_close();

// Some hidden data
echo hidden_div($this->session->userdata('sv_show_set'), 'hdEventId');
if($this->uri->segment(4) == 'notificationsuccess')
{
	echo hidden_div('Your customers have been notified', 'hdNotification');
}
elseif($this->uri->segment(4) == 'fastsellstarted')
{
	echo hidden_div('Your FastSell has now started', 'hdNotification');
}
?>