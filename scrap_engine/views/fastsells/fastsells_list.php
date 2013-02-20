<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Check that we have fastsells
if($fastsells['error'] == FALSE)
{
	// Heading
//	echo heading('<span class="icon-ticket yellow"></span>FastSells Coming Up', 3);

	// Get the result
	$json_fastsells			    = $fastsells['result'];

  	// Display the fastsell
	foreach($json_fastsells->fastsell_events as $fastsell)
	{
		// An item container
		echo '<a href="fastsells/event/'.$fastsell->id.'" class="itemContainer" >';

			// Table
			echo '<table><tr>';

				// Banner image
				echo '<td>';

					// Get the image
					$src                    = 'scrap_assets/images/universal/default_event_image.png';
					$url_fastsell_image     = 'serverlocalfiles/.jsons?path=scrap_shows%2F'.$fastsell->id.'%2Fbanner';
					$call_fastsell_image    = $this->scrap_web->webserv_call($url_fastsell_image, FALSE, 'get', FALSE, FALSE);
					if($call_fastsell_image['error'] == FALSE)
					{
						$json_fastsell_image        = $call_fastsell_image['result'];
						if($json_fastsell_image->is_empty == FALSE)
						{
							$image_path             = $json_fastsell_image->server_local_files[0]->path;
							$src                    = $this->scrap_web->image_call('serverlocalfiles/file?path='.$image_path);
						}
					}

					$img_properties         = array
					(
						'src'               => $src,
						'width'             => '275px'
					);

					echo full_div(img($img_properties), 'inset');

				echo '</td>';

				// FastSell details
				echo '<td class="fastSellDetails">';

					echo heading($fastsell->name, 4);
					echo full_div($fastsell->description, 'greyTxt');

				echo '</td>';

				// Counter
				echo '<td>';

					$current_time           = $this->scrap_web->get_current_time();
					$start_time             = $fastsell->event_start_date;
					$end_time               = $fastsell->event_end_date;
					//					$end_time               = str_replace('-', '',str_replace(' ', '', $fastsell->event_end_date));

					//					echo 'Current Time: '.$current_time.'<br>';
					//					echo 'Start Time: '.$start_time.'<br>';
					//					echo 'End Time: '.$end_time.'<br>';

					//					echo $this->scrap_string->make_db_date($fastsell->event_start_date).'<br>';
					//					echo substr($fastsell->event_start_date, 11).'<br>';

					if($current_time > $start_time)
					{
						if($fastsell->event_end_date > $this->scrap_web->get_current_time())
						{
							echo full_div('FastSell Currently Running', 'yellowBlock');

							echo full_div('Event Ending In', 'counterHeading');

							echo open_div('counterTime yellow');

								echo hidden_div($this->scrap_string->make_db_date($fastsell->event_end_date), 'hdStartDate');
								echo hidden_div(substr($fastsell->event_end_date, 11), 'hdStartTime');

							echo close_div();

							echo heading(nbs(4).'Days'.nbs(13).'Hours'.nbs(11).'Minutes'.nbs(8).'Seconds', 4, 'class="counterHeadings"');
						}
						else
						{
							echo full_div('FastSell Finished', 'blueBlock');
						}
					}
					else
					{
						echo full_div('Going To Start In', 'counterHeading');

						echo open_div('counterTime blue');

							echo hidden_div($this->scrap_string->make_db_date($fastsell->event_start_date), 'hdStartDate');
							echo hidden_div(substr($fastsell->event_start_date, 11), 'hdStartTime');

						echo close_div();

						echo heading(nbs(4).'Days'.nbs(13).'Hours'.nbs(11).'Minutes'.nbs(7).'Seconds', 4, 'class="counterHeadings"');
					}

					// Clear float
					echo clear_float();

				echo '</td>';

			echo '</tr></table>';

		// End of an item container
		echo '</a>';
	}
}
else
{
	if($this->scrap_web->get_show_host_id() != FALSE)
	{
		if($definitions['error'] == FALSE)
		{
			echo anchor('fastsells/create_event', 'Create Fastsell', 'class="messageCreateAFastSell"');
		}
		else
		{
			echo anchor('products/definitions', 'Create Product Defnition', 'class="messageNoProductDefinitions"');
		}
	}
	else
	{
		echo full_div('No Fastsells', 'messageNoFastSells');
	}
}
?>