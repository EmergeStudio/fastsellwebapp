<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Open middle div
echo open_div('middle');

	// Current orders
	echo open_div('whiteBack coolScreen singleColumn');

		// Control bar
		echo open_div('controlBar');

			// Add customer
			echo make_button('Add Customer', 'btnAddCustomer btnAdd greenButton', '', 'right');

			echo open_div('floatLeft');

				// Basic search
				echo open_div('basicSearch floatLeft');

					// Input field
					echo form_input('inpCustomerSearch', '', 'class="floatLeft"');

					// Search button
					echo make_button('Search', 'btnCustomerSearch', '', 'left');

					// Reset button
					echo make_button('Reset', '', 'customers', 'left');

					// Clear float
					echo clear_float();

				// End of basic search
				echo close_div();

			echo close_div();

			// Clear float
			echo clear_float();

		// End of control bar
		echo close_div();

		// Order list - quick
		echo open_div('listContain');

			$this->load->view('customers/customers_list_full');

		echo close_div();

	echo close_div();

// End of middle div
echo close_div();
?>