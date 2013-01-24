<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
echo open_div('middle');

	// White back
	echo open_div('whiteBack coolScreen singleColumn');

		// Control bar
		echo open_div('controlBar');

			echo open_div('floatLeft');

				echo heading('A List Of Orders', 3);

			echo close_div();

			// Clear float
			echo clear_float();

		// End of control bar
		echo close_div();

		// Product definitions list
		echo open_div('listContain');

			// Order list - quick
			$this->load->view('orders/orders_list');

		echo close_div();

	// End of white back
	echo close_div();

echo close_div();
?>