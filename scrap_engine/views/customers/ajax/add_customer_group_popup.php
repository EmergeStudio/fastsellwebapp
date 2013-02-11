<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Data

// HTML
echo form_open('customers/add_group', 'class="frmAddGroup"');

	// Group name
	echo open_div('inset');

	    echo form_label('Group Name:');

		// Customer number
		$inp_customer_group     = array
		(
			'name'			    => 'inpCustomerGroup'
		);
		echo form_input($inp_customer_group);

	echo close_div();

	// Some height
	echo div_height(25);

	// Customer list
	echo open_div('inset');

		if($customers['error'] == FALSE)
		{
			// Label
			echo form_label('Tick Off The Customers You Want In This Group?').div_height(5);

			// Tick boxes
			$json_customers     = $customers['result'];

			foreach($json_customers->customer_to_show_hosts as $customer)
			{
				echo open_div('customerCheckContain');

					$chkbx_customer             = array
					(
						'name'                  => 'checkCustomer[]',
						'value'                 => $customer->customer_organization->id,
						'class'                 => 'checkCustomer tooltip',
						'checked'               => FALSE,
						'title'                 => 'Tick to add this customer to the group'
					);
					echo form_checkbox($chkbx_customer);

					echo full_div($customer->customer_organization->name, 'customerName');

				echo close_div();
			}

			echo clear_float();
		}
		else
		{
			echo 'There are currently no customers to add to this group';
		}

	echo close_div();

echo form_close();
?>