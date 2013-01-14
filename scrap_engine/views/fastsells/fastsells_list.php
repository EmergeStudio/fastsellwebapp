<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Check that we have fastsells
if($fastsells['error'] == FALSE)
{
	// Heading
	echo heading('<span class="icon-ticket yellow"></span>FastSells Coming Up', 3);

	// Get the result
	$json_fastsells			    = $fastsells['result'];

  	// Display the fastsell
	foreach($json_fastsells->fastsell_events as $fastsell)
	{
		// An item container
		echo '<a href="fastsell/event/'.$fastsell->id.'" class="itemContainer" >';

			// Table
			echo '<table><tr>';

				// Banner image
				echo '<td>';

					$img_properties         = array
					(
						'src'               => 'scrap_assets/images/universal/default_event_image.png',
						'width'             => '275px'
					);

					echo full_div(img($img_properties), 'inset');

				echo '</td>';

				// FastSell details
				echo '<td>';

					echo heading($fastsell->name, 4);
					echo full_div('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam mi libero, lacinia quis accumsan vitae, hendrerit tristique nunc. Maecenas in libero sit amet nisi pulvinar sagittis in eget ligula. Praesent a augue urna. Suspendisse ante.', 'greyTxt');

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
?>