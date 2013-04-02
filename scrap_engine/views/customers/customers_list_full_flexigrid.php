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

			$url_cust_user		    = 'customerusers/.json?customerid='.$id;
			$call_cust_user	        = $this->scrap_web->webserv_call($url_cust_user, FALSE, 'get', FALSE, FALSE);
			if($customer_details->customer_logged_in_after_link == FALSE)
			{
				$active_class           = 'editIt';
			}
			else
			{
				$active_class           = '';
			}
			$cust_user_id           = 0;
			$cust_user_fn           = 'First Name';
			$cust_user_ln           = 'Last Name';
			$cust_user_email        = 'Email Address';

			if($call_cust_user['error'] == FALSE)
			{
				$json_cust_user     = $call_cust_user['result'];

				$cust_user_id       = $json_cust_user->user->id;
				$cust_user_fn       = $json_cust_user->user->firstname;
				$cust_user_ln       = $json_cust_user->user->lastname;
				$cust_user_email    = $json_cust_user->user->user_emails[0]->email;
			}

			// HTML
			echo '<tr>';

				// Customer ID
				echo '<td>'.$id.'</td>';

				// Customer name
				echo '<td id="'.$ctsh_id.'_customerName_customer" class="editIt">'.$customer_to_show_host->customer_name.'</td>';

				// Customer number
				echo '<td id="'.$ctsh_id.'_customerNumber_customer" class="editIt">'.$customer_to_show_host->customer_number.'</td>';

				// Customer state
				if($active_class == '')
				{
					echo '<td id="'.$ctsh_id.'_customerState_customer">'.full_span('Accepted and Active', 'greenTxt').'</td>';
				}
				else
				{
					echo '<td id="'.$ctsh_id.'_customerState_customer">The user has not accepted yet<br>'.make_link('Re-send the invitation', 'btnResend Invite').'</td>';
				}

				// Customer first name
				echo '<td id="'.$cust_user_id.'_customerFirstName_user" class="'.$active_class.'">'.$cust_user_fn.'</td>';

				// Customer lastname
				echo '<td id="'.$cust_user_id.'_customerLastName_user" class="'.$active_class.'">'.$cust_user_ln.'</td>';

				// Customer email address
				echo '<td id="'.$cust_user_id.'_customerEmail_user" class="'.$active_class.'">'.$cust_user_email.'</td>';

				// Customer group
				echo '<td id="'.$ctsh_id.'_customerGroup_groups" class="editIt">';

					$groups                 = '';

					if($customer_details->fastsell_customer_groups != NULL)
					{
						foreach($customer_details->fastsell_customer_groups as $fs_group)
						{
							$groups             .= $fs_group->name.',';
						}
					}
					echo $groups;

				echo '</td>';

				// View orders
				echo '<td>'.anchor('customers/orders/'.$customer_details->id, 'View Orders').'</td>';

			echo '</tr>';
		}

	echo '</table>';
}
?>