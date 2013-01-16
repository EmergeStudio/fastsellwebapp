<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Data
$address_1                  = '';
$city                       = '';
$state                      = '';
$postal_code                = '';
if($address['error'] == FALSE)
{
	$json_address           = $address['result'];
	$address_1              = $json_address->addresses[0]->address_one;
	$city                   = $json_address->addresses[0]->city;
	$state                  = $json_address->addresses[0]->state_province;
	$postal_code            = $json_address->addresses[0]->postal_code;
}

// Open middle div
echo open_div('middle');

	// Small right column
	echo open_div('rightColSmall');

		// Delivery address
		echo open_div('whiteBack addressForm');

			// Form
			echo form_open('customers/save_address', 'class="frmSaveAddress"');

				// Heading
				echo div_height(6);
				echo full_div('', 'icon-map-pin-fill headingIcon blue');
				echo heading('Delivery Address', 2);
				echo div_height(8);

				echo '<p>Please make sure that you have a delivery address as seen below.</p>';

				// Address 1
				$ar_text            = array
				(
					'name'          => 'address1',
					'value'         => $address_1
				);
				echo form_label('Address');
				echo form_textarea($ar_text);

				// City
				$ar_inp             = array
				(
					'name'          => 'city',
					'value'         => $city
				);
				echo div_height(15);
				echo form_label('City');
				echo form_input($ar_inp);

				// State
				$ar_inp             = array
				(
					'name'          => 'state',
					'value'         => $state
				);
				echo div_height(15);
				echo form_label('State');
				echo form_input($ar_inp);

				// Postal code
				$ar_inp             = array
				(
					'name'          => 'postalCode',
					'value'         => $postal_code
				);
				echo div_height(15);
				echo form_label('Postal Code');
				echo form_input($ar_inp);

				// Save
				echo div_height(25);
				echo make_button('Save Address', 'btnSaveAddress blueButton');

				// Hidden data
				echo form_hidden('hdReturnUrl', current_url());

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

			// Heading
			echo div_height(6);
			echo full_div('', 'icon-shopping-cart headingIcon blue');
			echo heading('My Recent Orders', 2);

			// Order list - quick
			$this->load->view('orders/orders_list');

			// Link button
			echo div_height(30);
			echo make_button('View All Orders', '', 'orders');

		echo close_div();

	// End of large left column
	echo close_div();

	// Clear float
	echo clear_float();

// End of middle div
echo close_div();
?>