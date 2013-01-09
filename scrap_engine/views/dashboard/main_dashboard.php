<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Open middle div
echo open_div('middle');

	// Small right column
	echo open_div('rightColSmall');

		// Move product
		echo open_div('whiteBack');

			// Heading
			echo div_height(6);
			echo full_div('', 'icon-ticket headingIcon yellow');
			echo heading('Move Product', 2);
			echo div_height(8);

			// Content
			echo '<p>Have product you need to move quickly?</p>';
			echo '<p>Create a FastSell and let your customers know immediately about the great savings they can make by buying now.</p>';

			// Create button
			echo div_height(15);
			echo make_button('Create FastSell', 'blueButton btnAdd', 'fastsells/create_event');

		echo close_div();

	// End of small right column
	echo close_div();

	// Large left column
	echo open_div('leftColBig');

		// Current orders
		echo open_div('whiteBack');

			// Heading
			echo div_height(6);
			echo full_div('', 'icon-cart headingIcon blue');
			echo heading('My Latest Orders', 2);
			echo div_height(8);

			// Order list - quick
			$this->load->view('orders/orders_list');

			// Link button
			echo div_height(30);
			echo make_button('View All Orders', '', 'orders');

		echo close_div();

		// Sales at a glance
		echo open_div('whiteBack');

			// Heading
			echo div_height(6);
			echo full_div('', 'icon-bars headingIcon blue');
			echo heading('Sales At A Glance', 2);

			// Load the char
			$dt_settings['chart_width']		= 980;
			$dt_settings['chart_height']	= 350;
			$this->load->view('dashboard/js/spending_over_time', $dt_settings);

		echo close_div();

	// End of large left column
	echo close_div();

	// Clear float
	echo clear_float();

// End of middle div
echo close_div();
?>