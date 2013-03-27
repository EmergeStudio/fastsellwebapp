<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($customers['error'] == FALSE)
{
	// Data
	$json_customers             = $customers['result'];

	$this->table->set_heading(make_button('Add All', 'btnAddAllCustomers blueButton'), '', array('data' => 'Company Name', 'class' => 'fullCell'), 'Customer Number');

	// Rows
	foreach($json_customers->customer_to_show_hosts as $customer_to_show_host)
	{
		// Some variables
		$customer_details       =   $customer_to_show_host->customer_organization;

		// Profile image
		$img_properties		    = array
		(
			'src'			    => $this->scrap_web->get_profile_image(100000000000000),
			'height'		    => '35',
			'class'			    => 'profileImage'
		);

		// Table row
		$this->table->add_row(make_button('Add', 'btnAddCustomerOne blueButton').hidden_div($customer_details->id, 'hdCustomerIdOne'), img($img_properties), array('data' => $customer_details->name, 'class' => 'fullCell'), $customer_to_show_host->customer_number);
	}

	// Generate table
	echo $this->table->generate();
}
?>