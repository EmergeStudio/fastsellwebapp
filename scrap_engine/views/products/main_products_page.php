<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
echo open_div('middle');

	// White back
	echo open_div('whiteBack coolScreen');

		// Right content
		echo open_div('rightContent');

			// Control bar
			echo open_div('controlBar');

				echo heading('Product Information', 3);

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

				// Add product
				if($definitions['error'] == FALSE)
				{
					echo make_button('Add Product', 'btnAddProduct btnAdd blueButton', '', 'right');
				}
				else
				{
					echo make_button('Go To Product Groups', 'blueButton', 'products/definitions', 'right');
				}

				echo open_div('floatLeft');

					// Basic search
					echo open_div('basicSearch floatLeft');

						echo form_open('products', 'class="frmSearch"');

							echo form_input('inpSearchText', str_replace('%20', ' ', $search_text), 'class="floatLeft"');
							echo make_button('Search', 'btnSearch blueButton', '', 'left');
							echo make_button('Reset', '', 'products', 'left');
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

			// Product definitions list
			echo open_div('listContain').open_div('ajaxProductsList');

				$this->load->view('products/products_list');

			echo close_div().close_div();

		echo close_div();

		// Clear float
		echo clear_float();

	// End of white back
	echo close_div();

echo close_div();
?>