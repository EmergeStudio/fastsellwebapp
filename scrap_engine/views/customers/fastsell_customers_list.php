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
			$url_customer               = 'customertoshowhosts/.json?showhostid='.$show_host_id.'&customerid='.$customer->id.'&includegroups=true';
			$call_customer              = $this->scrap_web->webserv_call($url_customer, FALSE, 'get', FALSE, FALSE);
			$json_customers             = $call_customer['result'];

			$url_cust_user		        = 'customerusers/.json?customerid='.$customer->id;
			$call_cust_user	            = $this->scrap_web->webserv_call($url_cust_user, FALSE, 'get', FALSE, FALSE);

			$cust_user_id               = 0;
			$cust_user_fn               = 'First Name';
			$cust_user_ln               = 'Last Name';
			$cust_user_email            = 'Email Address';

			if($call_cust_user['error'] == FALSE)
			{
				$json_cust_user         = $call_cust_user['result'];

				$cust_user_id           = $json_cust_user->user->id;
				$cust_user_fn           = $json_cust_user->user->firstname;
				$cust_user_ln           = $json_cust_user->user->lastname;
				$cust_user_email        = $json_cust_user->user->user_emails[0]->email;
			}

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

					echo $cust_user_fn;

				echo '</td>';

				echo '<td>';

					echo $cust_user_ln;

				echo '</td>';

				echo '<td>';

					echo $cust_user_email;

				echo '</td>';

				echo '<td>';

					echo 'Group';

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