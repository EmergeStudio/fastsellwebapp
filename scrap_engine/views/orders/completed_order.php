<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Data
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
	}
	$crt_total              = number_format($crt_total, 2);
}

// HTML
if($order != FALSE)
{
	// Open middle div
	echo open_div('middle');

		// White back
		echo open_div('whiteBack coolScreen singleColumn checkoutConfirmation');

			// Heading
	        echo heading('Your Order Has Been Checked Out Successfully!', 2);

			// Billing information
			echo open_div('billingInformation');

				echo '<style>';

					?>
					* {
						margin: 0;
						padding: 0;
						font-family: 'Helvetica Neue', Helvetica, Arial;
						font-size: 13px;
						line-height: 18px;
						color: #414141;
					}

					.middle .whiteBack.coolScreen {
					padding: 50px;
					}
					.middle .whiteBack.coolScreen textarea {
					width: 97%;
					height: 250px;
					padding: 15px;
					}
					.heading {
					padding: 10px;
					margin-top: 20px;
					margin-bottom: 15px;
					font-size: 30px;
					}
					.content {
					padding: 4px 10px;
					font-size: 16px;
					}
					.content b {
					font-size: 16px;
					font-weight: bold;
					}
					.coolTable {
					width:100%;
					}
					.coolTable .buttonsColumn {
					display:block;
					width:70px;
					}
					.coolTable tr.oddRow {
					background-color:#f1f1f1;
					}
					.coolTable th {
					padding:8px 6px;
					text-align:center;
					white-space:nowrap;
					}
					.coolTable td {
					padding: 16px 8px;
					text-align:center;
					vertical-align:middle;
					white-space:nowrap;
					}
					.coolTable td.rightText {
					text-align: right;
					}
					.coolTable td.fullCell, .coolTable th.fullCell {
					width:100%;
					white-space:normal;
					text-align:left;
					}
					.coolTable th.txtLeft, .coolTable td.txtLeft {
					text-align:left;
					}
					.coolTable input {
					padding:5px 0px 5px 2px;
					}
					<?php

				echo '</style>';

				echo '<table style="width:100%;">';

					echo '<tr>';

						echo '<td>'.full_div('Billed To:', 'heading').'</td>';
						echo '<td></td>';

					echo '</tr>';

					echo '<tr>';

						echo '<td>';

							echo full_div($this->session->userdata('sv_name'), 'content');
							echo full_div($user->user_emails[0]->email, 'content');

							foreach($addresses->addresses as $address)
							{
								if($address->address_type->id == 1)
								{
									echo div_height(20);
									echo full_div('<b>Billing Address</b>', 'content');

									if(!empty($address->address_one))
									{
										echo full_div($address->address_one, 'content');
									}
									if(!empty($address->address_two))
									{
										echo full_div($address->address_two, 'content');
									}
									if(!empty($address->address_three))
									{
										echo full_div($address->address_three, 'content');
									}
									if(!empty($address->city))
									{
										echo full_div($address->city, 'content');
									}
									if(!empty($address->state_province))
									{
										echo full_div($address->state_province, 'content');
									}
									if(!empty($address->postal_code))
									{
										echo full_div($address->postal_code, 'content');
									}
								}
								elseif($address->address_type->id == 2)
								{
									echo div_height(20);
									echo full_div('<b>Shipping Address</b>', 'content');

									if(!empty($address->address_one))
									{
										echo full_div($address->address_one, 'content');
									}
									if(!empty($address->address_two))
									{
										echo full_div($address->address_two, 'content');
									}
									if(!empty($address->address_three))
									{
										echo full_div($address->address_three, 'content');
									}
									if(!empty($address->city))
									{
										echo full_div($address->city, 'content');
									}
									if(!empty($address->state_province))
									{
										echo full_div($address->state_province, 'content');
									}
									if(!empty($address->postal_code))
									{
										echo full_div($address->postal_code, 'content');
									}
								}
							}

						echo '</td>';

						echo '<td>';

							echo full_div('<b>Order Number</b>', 'content');
							echo full_div($crt_order->order_number, 'content');

							echo div_height(20);
							echo full_div('<b>Date Created</b>', 'content');
							echo full_div($this->scrap_string->make_date($crt_order->date_created), 'content');

							echo div_height(20);
							echo full_div('<b>Order Total</b>', 'content');
							echo full_div('$    '.$crt_total, 'content greenTxt');

						echo '</td>';

					echo '</tr>';

				echo '</table>';


				// Load the products list view
				echo div_height(50);
				$this->load->view('orders/products_list_show_host');

			echo close_div();

			// Some buttons
			echo div_height(30);
			echo make_button('All Done', 'blueButton', 'fastsells/event/'.$this->session->userdata('sv_show_set'), 'left');
			echo make_button('Print This', 'blueButton btnPrint', '', 'left');

			echo clear_float();

		// End of white back
		echo close_div();

	// End of middle div
	echo close_div();
}
?>