<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if($customers['error'] == FALSE)
{
	// Data
	$json_customers             = $customers['result'];

	// Table data
	// Heading
	$this->table->set_heading('', '', array('data' => 'Company Name', 'class' => 'fullCell'), full_div('Customer Number'));

	// Rows
	foreach($json_customers->customer_to_show_hosts as $customer_to_show_host)
	{
		// Some variables
		$customer_details       =   $customer_to_show_host->customer_organization;

		// Check box properties
		$checkbox_add_customer   = array
		(
			'name'              => 'checkAddCustomer',
			'class'             => 'checkAddCustomer tooltip',
			'checked'           => FALSE,
			'title'             => 'Tick to add this customer to the FastSell',
			'value'             => $customer_details->id
		);

		// Profile image
		$img_properties		    = array
		(
			'src'			    => $this->scrap_web->get_profile_image(100000000000000),
			'height'		    => '35',
			'class'			    => 'profileImage'
		);

		// Table row
		$this->table->add_row(form_checkbox($checkbox_add_customer), img($img_properties), array('data' => $customer_to_show_host->customer_name, 'class' => 'fullCell'), $customer_to_show_host->customer_number);
	}

	// Generate table
	echo $this->table->generate();
}
?>