<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Check that we have customers
if($customers['error'] == FALSE)
{
	// Get the customers result
	$json_customers			= $customers['result'];
	$loop_cnt               = 0;

	// Open table
	echo '<table id="flex1">';

		// Loop through and display customer
		foreach($json_customers->customer_to_show_hosts as $customer_to_show_host)
		{
			// Some variables
			$customer_details       = $customer_to_show_host->customer_organization;
			$id                     = $customer_details->id;
			$ctsh_id                = $customer_to_show_host->id;
			$loop_cnt++;

			$url_cust_users		    = 'customerusers/.jsons?customerid='.$id;
			$call_cust_users	    = $this->scrap_web->webserv_call($url_cust_users, FALSE, 'get', FALSE, FALSE);
			$cust_user_id           = 0;
			$cust_user_fn           = 'First Name';
			$cust_user_ln           = 'Last Name';
			$cust_user_email        = 'Email Address';

			if($call_cust_users['error'] == FALSE)
			{
				$json_cust_users    = $call_cust_users['result'];

				foreach($json_cust_users->customer_users as $customer_users)
				{
					$customer_user  = $customer_users->user;
				}
			}

			// HTML
			echo '<tr>';

				// Customer ID
				echo '<td>'.$id.'</td>';

				// Customer name
				echo '<td id="'.$ctsh_id.'_customerName" class="editIt">'.$customer_to_show_host->customer_name.'</td>';

				// Customer number
				echo '<td id="'.$ctsh_id.'_customerNumber" class="editIt">'.$customer_to_show_host->customer_number.'</td>';

				// Customer state
				if($loop_cnt != 2)
				{
					echo '<td id="'.$ctsh_id.'_customerState">'.full_span('Accepted and Active', 'greenTxt').'</td>';
				}
				else
				{
					echo '<td id="'.$ctsh_id.'_customerState">The user has not accepted yet<br>'.make_link('Re-send the invitation', 'btnResend Invite').'</td>';
				}

				// Customer first name
				echo '<td id="'.$cust_user_id.'_customerFirstName" class="editIt">'.$cust_user_fn.'</td>';

				// Customer lastname
				echo '<td id="'.$cust_user_id.'_customerLastName" class="editIt">'.$cust_user_ln.'</td>';

				// Customer email address
				echo '<td id="'.$cust_user_id.'_customerEmail" class="editIt">'.$cust_user_email.'</td>';

				// Customer group
				echo '<td id="'.$ctsh_id.'_customerGroup" class="editIt"></td>';

				// View orders
				echo '<td>'.anchor('customers/orders/'.$customer_details->id, 'View Orders').'</td>';

			echo '</tr>';
		}

	echo '</table>';
}
?>