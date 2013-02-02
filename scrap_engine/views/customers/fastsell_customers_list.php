<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Check that we have customers
if($customers['error'] == FALSE)
{
	// Get the customers result
	$json_customers			    = $customers['result'];

	// Table heading
	$this->table->set_heading('', array('data' => 'Company Name', 'class' => 'fullCell'), 'Customer Number', '');

	// Loop through and display customer
	foreach($json_customers->customer_organizations as $customer)
	{
		// Get the customer number
		$url_customer               = 'customertoshowhosts/.json?showhostid='.$show_host_id.'&customerid='.$customer->id;
		$call_customer              = $this->scrap_web->webserv_call($url_customer, FALSE, 'get', FALSE, FALSE);
		$json_customers             = $call_customer['result'];

		// Table data
		$ar_fields              = array();

		// Profile image
		$img_properties		    = array
		(
			'src'			    => $this->scrap_web->get_profile_image(100000000000000),
			'height'		    => '35',
			'class'			    => 'profileImage'
		);
		array_push($ar_fields, img($img_properties));

		// Customer name
		array_push($ar_fields, array('data' => $customer->name, 'class' => 'fullCell'));

		// Customer number
		array_push($ar_fields, $json_customers->customer_number);

		// View orders
		//array_push($ar_fields, anchor('fastsells/view_customer_order/'.$customer->id, 'View Orders'));

		// Buttons
		$html   = '';
		$html   .= open_div('extraOptions');

			$html   .= full_div('', 'btnRemoveCustomer icon-cross', 'Remove this customer from the FastSell');

			// Hidden data
			$html   .= hidden_div($customer->id, 'hdCustomerId');

		$html   .= close_div();
		array_push($ar_fields, $html);

		// Table row
		$this->table->add_row($ar_fields);
	}

	// Generate table
	echo $this->table->generate();
}
?>