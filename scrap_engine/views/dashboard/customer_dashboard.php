<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Data
$same_address                   = FALSE;

$deliver_address_1              = '';
$deliver_address_2              = '';
$deliver_address_3              = '';
$deliver_city                   = '';
$deliver_state                  = '';
$deliver_postal_code            = '';

$bill_address_1                 = '';
$bill_address_2                 = '';
$bill_address_3                 = '';
$bill_city                      = '';
$bill_state                     = '';
$bill_postal_code               = '';

if($address['error'] == FALSE)
{
	$json_address           = $address['result'];

	foreach($json_address->addresses as $address)
	{
		if($address->address_type->id == 1)
		{
			$bill_address_1             = $address->address_one;
			$bill_address_2             = $address->address_two;
			$bill_address_3             = $address->address_three;
			$bill_city                  = $address->city;
			$bill_state                 = $address->state_province;
			$bill_postal_code           = $address->postal_code;
		}
		elseif($address->address_type->id == 2)
		{
			$deliver_address_1          = $address->address_one;
			$deliver_address_2          = $address->address_two;
			$deliver_address_3          = $address->address_three;
			$deliver_city               = $address->city;
			$deliver_state              = $address->state_province;
			$deliver_postal_code        = $address->postal_code;
		}
	}
}

if(($deliver_address_1 == $bill_address_1) && ($deliver_address_2 == $bill_address_2) && ($deliver_address_3 == $bill_address_3) && ($deliver_city == $bill_city) && ($deliver_postal_code == $bill_postal_code) && ($deliver_state == $bill_state))
{
	$same_address               = TRUE;
}

// Open middle div
echo open_div('middle');

	// Small right column
	echo open_div('rightColSmall');

		// Delivery address
		echo open_div('whiteBack addressForm');

			// Heading
			echo make_button('Billing Address', 'btnBillingAddress blueButton', '', 'left');
			echo make_button('Shipping Address', 'btnDeliveryAddress greyButton', '', 'left');
			echo clear_float();
			echo div_height(20);

			// Form
			echo form_open('customers/save_addresses', 'class="frmSaveAddresses"');

                echo open_div('billingAddress');

					// Heading
					echo div_height(10);
					echo full_div('', 'icon-dollar headingIcon blue');
					echo heading('Billing Address', 2);
					echo div_height(4);

					echo '<p>You can fill in your billing address below.</p>';

					// Address 1
					$ar_text            = array
					(
						'name'          => 'billAddress1',
						'value'         => $bill_address_1
					);
					echo form_label('Address 1');
					echo form_textarea($ar_text);

					// Address 2
					$ar_text            = array
					(
						'name'          => 'billAddress2',
						'value'         => $bill_address_2
					);
					echo div_height(15);
					echo form_label('Address 2');
					echo form_textarea($ar_text);

					// Address 3
					$ar_text            = array
					(
						'name'          => 'billAddress3',
						'value'         => $bill_address_2
					);
					echo div_height(15);
					echo form_label('Address 3');
					echo form_textarea($ar_text);

					// City
					$ar_inp             = array
					(
						'name'          => 'billCity',
						'value'         => $bill_city
					);
					echo div_height(15);
					echo form_label('City');
					echo form_input($ar_inp);

					// State
					$ar_inp             = array
					(
						'name'          => 'billState',
						'value'         => $bill_state
					);
					echo div_height(15);
					echo form_label('State');
					echo form_input($ar_inp);

					// Postal code
					$ar_inp             = array
					(
						'name'          => 'billPostalCode',
						'value'         => $bill_postal_code
					);
					echo div_height(15);
					echo form_label('Postal Code');
					echo form_input($ar_inp);

				echo close_div();

                echo open_div('deliveryAddress displayNone');

					// Heading
					echo div_height(10);
					echo full_div('', 'icon-map-pin-fill headingIcon blue');
					echo heading('Shipping Address', 2);
					echo div_height(4);

					echo '<p>Please make sure that you have a shipping address as seen below.</p>';

					// Address 1
					$ar_text            = array
					(
						'name'          => 'shipAddress1',
						'value'         => $deliver_address_1
					);
					echo form_label('Address 1');
					echo form_textarea($ar_text);

					// Address 2
					$ar_text            = array
					(
						'name'          => 'shipAddress2',
						'value'         => $deliver_address_2
					);
					echo div_height(15);
					echo form_label('Address 2');
					echo form_textarea($ar_text);

					// Address 3
					$ar_text            = array
					(
						'name'          => 'shipAddress3',
						'value'         => $deliver_address_3
					);
					echo div_height(15);
					echo form_label('Address 3');
					echo form_textarea($ar_text);

					// City
					$ar_inp             = array
					(
						'name'          => 'shipCity',
						'value'         => $deliver_city
					);
					echo div_height(15);
					echo form_label('City');
					echo form_input($ar_inp);

					// State
					$ar_inp             = array
					(
						'name'          => 'shipState',
						'value'         => $deliver_state
					);
					echo div_height(15);
					echo form_label('State');
					echo form_input($ar_inp);

					// Postal code
					$ar_inp             = array
					(
						'name'          => 'shipPostalCode',
						'value'         => $deliver_postal_code
					);
					echo div_height(15);
					echo form_label('Postal Code');
					echo form_input($ar_inp);

				echo close_div();

				// Make addresses the same
				echo div_height(20);
				$checkbox_make_same     = array
				(
					'name'              => 'checkMakeSame',
					'class'             => 'checkMakeSame',
					'checked'           => $same_address,
					'value'             => 'yesno'
				);
				echo form_checkbox($checkbox_make_same);
				echo full_div('Make your billing and shipping addresses the same', 'txtMakeSameAddress greyTxt');

				// Hidden data
				echo form_hidden('hdReturnUrl', current_url());

				// Save
				echo div_height(25);
				echo make_button('Save Address Details', 'btnSaveAddresses blueButton');

			// Form close
			echo form_close();

		echo close_div();

	// End of small right column
	echo close_div();

	// Large left column
	echo open_div('leftColBig');

		// Fastsells
		echo open_div('whiteBack');

			// FastSells list
			$this->load->view('fastsells/fastsells_list_small');

		echo close_div();

		// Current orders
		echo open_div('whiteBack');

			if($orders['error'] == FALSE)
			{
				// Heading
				echo div_height(6);
				echo full_div('', 'icon-shopping-cart headingIcon blue');
				echo heading('My Recent Orders', 2);

				// Order list - quick
				$this->load->view('orders/orders_list');

				// Link button
				echo div_height(30);
				echo make_button('View All Orders', '', 'my_orders');
			}
			else
			{
				echo full_div('No Orders', 'messageNoOrders1');
			}

		echo close_div();

	// End of large left column
	echo close_div();

	// Clear float
	echo clear_float();

// End of middle div
echo close_div();
?>