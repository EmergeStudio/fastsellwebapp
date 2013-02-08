<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Data
$json_group                     = $group['result'];

// HTML
echo form_open('customers/edit_group', 'class="frmEditGroup"');

	// Group name
	echo open_div('inset');

	    echo form_label('Group Name:');

		// Customer number
		$inp_customer_group     = array
		(
			'name'			    => 'inpCustomerGroup',
			'value'             => $json_group->name
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

					$state                      = FALSE;
					foreach($json_group->customer_organizations as $customer_organization)
					{
						if($customer_organization->id == $customer->id)
						{
							$state              = TRUE;
							break;
						}
					}

					$chkbx_customer             = array
					(
						'name'                  => 'checkCustomer[]',
						'value'                 => $customer->id,
						'class'                 => 'checkCustomer tooltip',
						'checked'               => $state,
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

	// Some hidden information
	echo form_hidden('hdGroupId', $json_group->id);

echo form_close();
?>