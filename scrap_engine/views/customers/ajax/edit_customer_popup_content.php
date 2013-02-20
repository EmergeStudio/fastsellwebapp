<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Data
$json_customer              = $customer['result'];

// HTML
echo form_open('customers/edit_customer', 'class="frmEditCustomer"');

	echo open_div('inset');

		// Customer name
	    echo form_label('Company Name:');
		$inp_customer_name      = array
		(
			'name'			    => 'inpCustomerName',
			'value'             => $json_customer->customer_name
		);
		echo form_input($inp_customer_name);

		// Some height
		echo div_height(8);

		// Customer number
	    echo form_label('Customer Number:');
		$inp_customer_number    = array
		(
			'name'			    => 'inpCustomerNumber',
			'value'             => $json_customer->customer_number
		);
		echo form_input($inp_customer_number);

	echo close_div();

	// Some hidden information
	echo form_hidden('hdCustomerToShowHostId', $customer_to_show_host_id);
	echo form_hidden('hdReturnUrl', 'customers');

echo form_close();
?>