<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Open middle div
echo open_div('middle');

	// White back
	echo open_div('whiteBack coolScreen');

		// Right content
		echo open_div('rightContent');

			// Control bar
			echo open_div('controlBar');

				echo make_button('My Order', '', 'fastsells/my_order', 'right');

				echo heading('Quick Order', 3);

			// End of control bar
			echo close_div();

			// Content
			echo open_div('content');

				// Quick order table
				echo open_div('quickOrder');

				echo '<table>';

					// Heading row
					echo '<tr>';

						echo '<th class="productHeading">Product</th>';
						echo '<th class="itemHeading">Quantity</th>';

					echo '</tr>';

					// Total row
					echo '<tr>';

						echo '<td class="totalText">Total: </td>';
						echo '<td class="totalAmount">0</td>';

					echo '</tr>';

				echo '</table>';

				// Hidden data
				echo hidden_div(0, 'hdQuickTotal');

			echo close_div();

			// End of white content
			echo close_div();

		// End of right content
		echo close_div();

		// Left content
		echo open_div('leftContent');

			// Control bar
			echo open_div('controlBar');

				echo open_div('floatLeft');

					// Basic search
					echo open_div('basicSearch floatLeft');

						echo form_input('inpProductsSearch', '', 'class="floatLeft"');
						echo make_button('Search', 'btnProductsSearch blueButton', '', 'left');
						echo make_button('Reset', '', 'fastsells/buy', 'left');
						echo clear_float();

					// End of basic search
					echo close_div();

				echo close_div();

				// Clear float
				echo clear_float();

			// End of control bar
			echo close_div();

			// Products list
			echo open_div('listContain');

				$this->load->view('products/products_list_buy');

			echo close_div();

		// End of left content
		echo close_div();

		// Clear float
		echo clear_float();

	// End of white back
	echo close_div();

// End of middle div
echo close_div();
?>