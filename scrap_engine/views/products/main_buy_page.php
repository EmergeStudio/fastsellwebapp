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
						echo '<th class="txtCenter">Quantity</th>';
						echo '<th class="itemHeading">Amount</th>';

					echo '</tr>';

					if($order == FALSE)
					{
						$crt_total              = 0.00;
					}
					else
					{
						$crt_total              = 0;
						foreach($crt_order->fastsell_order_to_items as $order_item)
						{
							$quantity           = $order_item->quantity;
							$price              = $order_item->fastsell_item->price;
							$crt_total          = $crt_total + ($quantity * $price);

							echo '<tr>';

								echo '<td class="productName">';

									echo $ar_product_names[$order_item->fastsell_item->id];

								echo '</td>';

								echo '<td class="txtCenter">';

									echo $quantity;

								echo '</td>';

								echo '<td class="productTotal">';

									echo '$'.number_format(($order_item->quantity * $order_item->fastsell_item->price), 2);

								echo '</td>';

							echo '</tr>';
						}
						$crt_total              = number_format($crt_total, 2);
					}

					// Total row
					echo '<tr>';

						echo '<td></td>';
						echo '<td class="totalText">Total: </td>';
						echo '<td class="totalAmount">$'.$crt_total.'</td>';

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

						echo form_open('fastsells/buy', 'class="frmSearch"');

							echo form_input('inpSearchText', str_replace('%20', ' ', $search_text), 'class="floatLeft"');
							echo make_button('Search', 'btnSearch blueButton', '', 'left');
							echo make_button('Reset', '', 'fastsells/buy', 'left');
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