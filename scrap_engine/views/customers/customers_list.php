<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Data
$loop_cnt       = 1;

// Check box all properties
$checkbox_add_all_customers   = array
(
	'name'          => 'checkAddAllCustomers',
	'class'         => 'checkAddAllCustomers tooltip',
	'checked'       => FALSE,
	'title'         => 'Tick to add all these customers to the FastSell'
);

// Table data
// Heading
$this->table->set_heading(form_checkbox($checkbox_add_all_customers), '', array('data' => 'Customer Name', 'class' => 'fullCell'), 'Customer Number');

// Rows
while($loop_cnt <= 7)
{
	// Check box properties
	$checkbox_add_customer   = array
	(
		'name'          => 'checkAddCustomer',
		'class'         => 'checkAddCustomer tooltip',
		'checked'       => FALSE,
		'title'         => 'Tick to add this customer to the FastSell'
	);

	// Profile image
	$img_properties		= array
	(
		'src'			=> $this->scrap_web->get_profile_image(100000000000000),
		'height'		=> '35',
		'class'			=> 'profileImage'
	);

	// Table row
	$this->table->add_row(form_checkbox($checkbox_add_customer), img($img_properties), array('data' => 'Customer Name '.$loop_cnt, 'class' => 'fullCell'), 'CN123456'.$loop_cnt);
	$loop_cnt++;
}

// Generate table
echo $this->table->generate();
?>