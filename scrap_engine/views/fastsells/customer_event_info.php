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

					echo '<td class="icon">'.full_div('', 'icon-clipboard').'</td>';

					echo '<td>';

						echo div_height(5);
						echo full_div($fastsell_info->description);

					echo '</td>';

				echo '</tr>';

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
			echo full_div('', 'icon-fire headingIcon yellow');
			echo heading('What\'s Currently Hot', 2);
			echo div_height(8);

			// Order list - quick
			$this->load->view('products/products_list_buy');

		echo close_div();

		// Sales at a glance
//		echo open_div('whiteBack');
//
//			// Heading
//			echo div_height(6);
//			echo full_div('', 'icon-stats headingIcon blue');
//			echo heading('What Is Still Available', 2);
//
//			// Load the char
//			$dt_settings['chart_width']		= 980;
//			$dt_settings['chart_height']	= 350;
//			$this->load->view('dashboard/js/spending_over_time', $dt_settings);
//
//		echo close_div();

	// End of large left column
	echo close_div();

	// Clear float
	echo clear_float();

// End of middle div
echo close_div();
?>