<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Open middle div
echo open_div('middle');

	// Current orders
	echo open_div('whiteBack coolScreen');

		// Right content
		echo open_div('rightContent');

			// Control bar
			echo open_div('controlBar');

				echo make_button('Add Group', 'btnAddGroup blueButton', '', 'right');

				echo heading('Customer Groups', 3);

			// End of control bar
			echo close_div();

			// Content
			echo open_div('content');

				$this->load->view('customers/groups_list');

			// End of white content
			echo close_div();

		// End of right content
		echo close_div();

		// Left content
		echo open_div('leftContent');

			// Control bar
			echo open_div('controlBar');

				// Add customer
				echo make_button('Add Customer', 'btnAddCustomer btnAdd blueButton', '', 'right');

				echo open_div('floatLeft');

					// Basic search
					echo open_div('basicSearch floatLeft');

					echo form_open('customers', 'class="frmSearch"');

						echo form_input('inpSearchText', str_replace('%20', ' ', $search_text), 'class="floatLeft"');
						echo make_button('Search', 'btnSearch blueButton', '', 'left');
						echo make_button('Reset', '', 'customers', 'left');
						echo form_hidden('hdOffset', $offset);
						echo clear_float();

					echo form_close();

					// End of basic search
					echo close_div();

				echo close_div();

				// Clear float
				echo clear_float();

			// End of control bar
			echo close_div();

			// Order list - quick
			echo open_div('listContain');

				if($customer_view == 'all')
				{
					$this->load->view('customers/customers_list_full');
				}
				else
				{
					$this->load->view('customers/customers_list_by_group');
				}

			echo close_div();

		echo close_div();

		// Clear float
		echo clear_float();

	echo close_div();

// End of middle div
echo close_div();
?>