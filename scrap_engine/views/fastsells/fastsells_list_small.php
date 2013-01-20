<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Check that we have fastsells
if($fastsells['error'] == FALSE)
{
	// Get the result
	$json_fastsells			    = $fastsells['result'];

  	// Display the fastsell
	foreach($json_fastsells->fastsell_events as $fastsell)
	{
		// An item container
		echo '<a href="fastsells/event/'.$fastsell->id.'" class="itemContainer small" >';

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
						'width'             => '175px'
					);

					echo full_div(img($img_properties), 'inset');

				echo '</td>';

				// FastSell details
				echo '<td>';

					echo heading($fastsell->name, 4);
					echo full_div($fastsell->description, 'greyTxt');

				echo '</td>';

				// Counter
				echo '<td>';

					echo open_div('counterTime');

						echo open_div('counterText');

							echo full_div('DAYS', 'counterDays');
							echo full_div('HOURS', 'counterHours');
							echo full_div('MINUTES', 'counterMinutes');
							echo full_div('SECONDS', 'counterSeconds');

						echo close_div();

						echo hidden_div($this->scrap_string->make_db_date($fastsell->event_start_date), 'hdStartDate');
						echo hidden_div(substr($fastsell->event_start_date, 11), 'hdStartTime');

					echo close_div();

					echo heading(nbs(6).'Days'.nbs(14).'Hours'.nbs(12).'Minutes'.nbs(10).'Seconds', 4, 'class="counterHeadings"');

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