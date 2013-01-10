<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
echo open_div('middle');

	// White back
	echo open_div('whiteBack coolScreen');

		// Right content
		echo open_div('rightContent');

			// Control bar
			echo open_div('controlBar');

				//echo heading('Item Definitions', 3);
				echo make_button('Edit', 'btnEditItem btnEdit', '', 'left');
				echo make_button('Delete', 'btnDeleteItem btnCross', '', 'left');
				echo clear_float();

			// End of control bar
			echo close_div();

			// Content
			echo open_div('content');

				// Nothing selected
				echo open_div('nothingSelected');

					echo 'Select a product from the left to display more information.';

				echo close_div();

				// Item information
				echo open_div('itemInformation');

				echo close_div();

			// End of content
			echo close_div();

		// End of right content
		echo close_div();

		// Right content
		echo open_div('leftContent');

			// Control bar
			echo open_div('controlBar');

				// Add definition
				echo make_button('Add Product', 'btnAddProduct btnAdd greenButton', '', 'right');

				echo open_div('floatLeft');

					// Basic search
					echo open_div('basicSearch floatLeft');

						echo form_input('inpProductsSearch', '', 'class="floatLeft"');
						echo make_button('Search', 'btnProductsSearch', '', 'left');
						echo make_button('Reset', '', 'products', 'left');
						echo clear_float();

					// End of basic search
					echo close_div();

				echo close_div();

				// Clear float
				echo clear_float();

			// End of control bar
			echo close_div();

			// Product definitions list
			echo open_div('listContain');

				$this->load->view('products/products_list');

			echo close_div();

		echo close_div();

		// Clear float
		echo clear_float();

	// End of white back
	echo close_div();

echo close_div();
?>