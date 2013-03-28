<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Check that we have customers
if($customers['error'] == FALSE)
{
	// Get the customers result
	$json_customers			    = $customers['result'];

	// Open table
	echo '<table id="flex1">';

		// Loop through and display customer
		foreach($json_customers->customer_organizations as $customer)
		{
			// Get the customer number
			$url_customer               = 'customertoshowhosts/.json?showhostid='.$show_host_id.'&customerid='.$customer->id;
			$call_customer              = $this->scrap_web->webserv_call($url_customer, FALSE, 'get', FALSE, FALSE);
			$json_customers             = $call_customer['result'];

			echo '<tr>';

				echo '<td>';

					echo $customer->id;

				echo '</td>';

				echo '<td>';

					echo $customer->name;

				echo '</td>';

				echo '<td>';

					echo $json_customers->customer_number;

				echo '</td>';

				echo '<td>';

					echo full_div(full_div('', 'btnRemoveCustomer icon-cross', 'Remove this customer from the FastSell').hidden_div($customer->id, 'hdCustomerId'), 'extraOptions');

				echo '</td>';

			echo '</tr>';
		}

	// Close table
	echo '</table>';
}
?>