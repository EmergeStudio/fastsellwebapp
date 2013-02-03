<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Open middle div
echo open_div('middle');

	// Small right column
	echo open_div('rightColSmall');

		// Move product
		echo open_div('whiteBack');

			// Table
			echo '<table class="infoTable">';

				echo '<tr>';

					echo '<td class="icon">'.full_div('', 'icon-calendar').'</td>';

					echo '<td>';

						echo '<b>Starting Time</b>';
						echo full_div($this->scrap_string->make_long_date($fastsell_info->event_start_date));

					echo '</td>';

				echo '</tr>';

				echo '<tr>';

					echo '<td class="icon">'.full_div('', 'icon-calendar').'</td>';

					echo '<td>';

						echo '<b>Ending Time</b>';
						echo full_div($this->scrap_string->make_long_date($fastsell_info->event_end_date));

					echo '</td>';

				echo '</tr>';

			echo '</table>';

		echo close_div();

	// End of small right column
	echo close_div();

	// Large left column
	echo open_div('leftColBig');

		// Current orders
		echo open_div('whiteBack');

			// Heading
			echo div_height(6);
//
//			// Get the image
//			$src                    = 'scrap_assets/images/universal/default_event_image.png';
//			$url_fastsell_image     = 'serverlocalfiles/.jsons?path=scrap_shows%2F'.$fastsell_info->id.'%2Fbanner';
//			$call_fastsell_image    = $this->scrap_web->webserv_call($url_fastsell_image, FALSE, 'get', FALSE, FALSE);
//			if($call_fastsell_image['error'] == FALSE)
//			{
//				$json_fastsell_image        = $call_fastsell_image['result'];
//				if($json_fastsell_image->is_empty == FALSE)
//				{
//					$image_path             = $json_fastsell_image->server_local_files[0]->path;
//					$src                    = $this->scrap_web->image_call('serverlocalfiles/file?path='.$image_path);
//				}
//			}
//
//			$img_properties         = array
//			(
//				'src'               => $src,
//				'width'             => '200px',
//				'style'             => 'margin-bottom:20px;',
//				'class'             => 'eventBannerImage'
//			);
//
//			echo img($img_properties);

			echo '<table class="tblFastSellInfo">';

				echo '<tr>';

					echo '<td>';

						echo full_div('', 'icon-info headingIcon blue');

					echo '</td>';
					echo '<td>';

						echo anchor('fastsells/buy', '<span class="icon-dollar"></span>Buy Now', 'class="bigBuyButton yellowBlock"');
						echo heading('FastSell Information', 2);

					echo '</td>';

				echo '</tr>';

				echo '<tr>';

					echo '<td class="title">Event Title</td>';
					echo '<td class="content">'.$fastsell_info->name.'</td>';

				echo '</tr>';

				echo '<tr>';

					echo '<td class="title">Organizer</td>';
					echo '<td class="content">'.$fastsell_info->show_host_organization->name.'</td>';

				echo '</tr>';

				echo '<tr>';

					echo '<td class="title">Description</td>';
					echo '<td class="content">'.$fastsell_info->description.'</td>';

				echo '</tr>';

				echo '<tr>';

					echo '<td class="title">Terms And Conditions</td>';
					echo '<td class="content">'.$fastsell_info->terms_and_conditions.'</td>';

				echo '</tr>';

			echo '</table>';

		echo close_div();

	// End of large left column
	echo close_div();

	// Clear float
	echo clear_float();

// End of middle div
echo close_div();
?>