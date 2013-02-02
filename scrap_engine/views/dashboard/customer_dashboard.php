<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Data
$deliver_address_1               = '';
$deliver_city                    = '';
$deliver_state                   = '';
$deliver_postal_code             = '';
$bill_address_1                  = '';
$bill_city                       = '';
$bill_state                      = '';
$bill_postal_code                = '';

if($address['error'] == FALSE)
{
	$json_address           = $address['result'];

	foreach($json_address->addresses as $address)
	{
		if($address->address_type->id == 1)
		{
			$bill_address_1             = $address->address_one;
			$bill_city                  = $address->city;
			$bill_state                 = $address->state_province;
			$bill_postal_code           = $address->postal_code;
		}
		elseif($address->address_type->id == 2)
		{
			$deliver_address_1          = $address->address_one;
			$deliver_city               = $address->city;
			$deliver_state              = $address->state_province;
			$deliver_postal_code        = $address->postal_code;
		}
	}
}

// Open middle div
echo open_div('middle');

	// Small right column
	echo open_div('rightColSmall');

		// Delivery address
		echo open_div('whiteBack addressForm');

			// Heading
			echo make_button('Shipping Address', 'btnDeliveryAddress blueButton', '', 'left');
			echo make_button('Billing Address', 'btnBillingAddress greyButton', '', 'left');
			echo clear_float();
			echo div_height(20);

            echo open_div('billingAddress displayNone');

				// Form
				echo form_open('customers/save_address_billing', 'class="frmSaveAddress"');

					// Heading
					echo div_height(10);
					echo full_div('', 'icon-dollar headingIcon blue');
					echo heading('Billing Address', 2);
					echo div_height(4);

					echo '<p>You can fill in alternative billing address below else your delivery address will be used for all orders.</p>';

					// Address 1
					$ar_text            = array
					(
						'name'          => 'address1',
						'value'         => $bill_address_1
					);
					echo form_label('Address');
					echo form_textarea($ar_text);

					// City
					$ar_inp             = array
					(
						'name'          => 'city',
						'value'         => $bill_city
					);
					echo div_height(15);
					echo form_label('City');
					echo form_input($ar_inp);

					// State
					$ar_inp             = array
					(
						'name'          => 'state',
						'value'         => $bill_state
					);
					echo div_height(15);
					echo form_label('State');
					echo form_input($ar_inp);

					// Postal code
					$ar_inp             = array
					(
						'name'          => 'postalCode',
						'value'         => $bill_postal_code
					);
					echo div_height(15);
					echo form_label('Postal Code');
					echo form_input($ar_inp);

					// Save
					echo div_height(25);
					echo make_button('Save Address', 'btnSaveAddress2 blueButton');

					// Hidden data
					echo form_hidden('hdReturnUrl', current_url());

				// Form close
				echo form_close();

			echo close_div();

            echo open_div('deliveryAddress');

				// Form
				echo form_open('customers/save_address_delivery', 'class="frmSaveAddress"');

					// Heading
					echo div_height(10);
					echo full_div('', 'icon-map-pin-fill headingIcon blue');
					echo heading('Shipping Address', 2);
					echo div_height(4);

					echo '<p>Please make sure that you have a shipping address as seen below.</p>';

					// Address 1
					$ar_text            = array
					(
						'name'          => 'address1',
						'value'         => $deliver_address_1
					);
					echo form_label('Address');
					echo form_textarea($ar_text);

					// City
					$ar_inp             = array
					(
						'name'          => 'city',
						'value'         => $deliver_city
					);
					echo div_height(15);
					echo form_label('City');
					echo form_input($ar_inp);

					// State
					$ar_inp             = array
					(
						'name'          => 'state',
						'value'         => $deliver_state
					);
					echo div_height(15);
					echo form_label('State');
					echo form_input($ar_inp);

					// Postal code
					$ar_inp             = array
					(
						'name'          => 'postalCode',
						'value'         => $deliver_postal_code
					);
					echo div_height(15);
					echo form_label('Postal Code');
					echo form_input($ar_inp);

					// Make addresses the same
					echo div_height(20);
					$checkbox_make_same     = array
					(
						'name'              => 'checkMakeSame',
						'class'             => 'checkMakeSame'
					);
					echo form_checkbox($checkbox_make_same);
					echo full_div('Make the billing address the same as the shipping address', 'txtMakeSameAddress greyTxt');

					// Save
					echo div_height(25);
					echo make_button('Save Address', 'btnSaveAddress blueButton');

					// Hidden data
					echo form_hidden('hdReturnUrl', current_url());

				// Form close
				echo form_close();

			echo close_div();

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