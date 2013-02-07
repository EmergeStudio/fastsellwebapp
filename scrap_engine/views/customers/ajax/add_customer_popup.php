<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Data

// HTML
echo open_div('inset');

	// Table heading
	$this->table->set_heading('Company Name', 'Customer Number', 'User First Name', 'User Last Name', 'User Email Address', '');

	// Table data
	$ar_fields              = array();

	// Customer name
	$inp_customer_name	    = array
	(
		'name'			    => 'inpCustomerName'
	);
	array_push($ar_fields, form_input($inp_customer_name));

	// Customer number
	$inp_customer_number    = array
	(
		'name'			    => 'inpCustomerNumber'
	);
	array_push($ar_fields, form_input($inp_customer_number));

	// First name
	$inp_first_name	        = array
	(
		'name'			    => 'inpFirstName'
	);
	array_push($ar_fields, form_input($inp_first_name));

	// Surname
	$inp_surname	        = array
	(
		'name'			    => 'inpSurname'
	);
	array_push($ar_fields, form_input($inp_surname));

	// Email address
	$inp_email_address	    = array
	(
		'name'			    => 'inpEmailAddress'
	);
	array_push($ar_fields, form_input($inp_email_address));

	// Customer group
	$inp_customer_group	    = array
	(
		'name'			    => 'inpCustomerGroup'
	);
	array_push($ar_fields, form_input($inp_customer_group));

	// Buttons
	array_push($ar_fields, make_button('Add', 'btnAddCustomerNow blueButton'));

	// Table row
	$this->table->add_row($ar_fields);

	// Generate table
	echo $this->table->generate();

echo close_div();
?>