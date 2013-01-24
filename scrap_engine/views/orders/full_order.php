<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($order != FALSE)
{
	// Open middle div
	echo open_div('middle');

		// White back
		echo open_div('whiteBack coolScreen singleColumn');

			// Control bar
			echo open_div('controlBar');

				// Icon
				echo full_div('', 'icon-shopping-cart bigIcon yellow floatLeft');

				// Order details
				echo open_div('orderDetails floatLeft');

					echo full_div('<b>Order Number:</b>'.nbs(1).$crt_order->order_number).div_height(2);
					echo full_div('<b>Date Created:</b>'.nbs(1).$this->scrap_string->make_long_date($crt_order->date_created)).div_height(2);
					echo full_div('<b>FastSell Event:</b>'.nbs(1).$crt_order->fastsell_event->name);

				echo close_div();

				// Order details
				echo open_div('orderDeliveryAddress floatLeft');

					echo '<table>';

						echo '<tr>';

							echo '<td class="deliveryAddress"><b>Delivery Address:</b></td>';

							echo '<td>';

								echo div_height(2);
								echo $crt_order->billing_address->address_one.', ';
								echo $crt_order->billing_address->city.', ';
								echo $crt_order->billing_address->state_province.', ';
								echo $crt_order->billing_address->postal_code;

							echo '</td>';

						echo '</tr>';

						echo '<tr>';

							echo '<td></td>';

							echo '<td>';

								echo div_height(6);
								echo full_div('(All deliveries times are distributor centric)', 'greyTxt');

							echo '</td>';
						echo '</tr>';

					echo '</table>';

				echo close_div();

				// Clear float
				echo clear_float();

			echo close_div();

			// Order items
			echo open_div('listContain');

				if($this->session->userdata('sv_acc_type') == 'show_host')
				{
					$this->load->view('orders/products_list_show_host');
				}
				else
				{
					$this->load->view('orders/products_list_my_order');

					echo div_height(20);
					echo make_button('Complete Checkout', 'btnCheckout blueButton', 'fastsells/checkout/'.$crt_order->id, 'right');
					echo clear_float();
				}

			echo close_div();

		// End of white back
		echo close_div();

	// End of middle div
	echo close_div();
}
else
{
	// Open middle div
	echo open_div('middle');

		// White back
		echo open_div('whiteBack coolScreen singleColumn');

			echo open_div('listContain');

				echo anchor('fastsells/buy', 'Buy Product', 'class="messageBuyProduct"');

			echo close_div();

		// End of white back
		echo close_div();

	// End of middle div
	echo close_div();
}
?>