<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
// Open middle div
echo open_div('middle');

	// Right content
	echo open_div('rightContentLarge');

		// Basic search
		echo open_div('basicSearch floatRight');

			echo form_open(current_url(), 'class="frmSearch"');

				if($customers['error'] == FALSE)
				{
					// Data
					$json_customers         = $customers['result'];

					$crt_page               = ($offset / $limit) + 1;
					$max_page               = floor($json_customers->no_limit_count / $limit) + 1;
				}
				else
				{
					$max_page               = 1;
				}

				echo form_input('inpSearchText', str_replace('%20', ' ', $search_text), 'class="floatLeft" placeholder="search for a customer"');
				echo make_button('Reset', '', current_url(), 'left');
				echo form_hidden('hdOffset', $offset);
				echo form_hidden('hdLimit', $limit);
				echo form_hidden('scrap_pageNo', $crt_page);
				echo form_hidden('scrap_pageMax', $max_page);
				echo clear_float();

			echo form_close();

		// End of basic search
		echo close_div();

		// Main heading
		echo heading('My Customers', 2, 'class="headingTitle"');

		// Add group
		echo make_button('Add Customer', 'btnAddCustomer btnAdd blueButton btnHeading', '', 'left');

		// Edit toggle
		echo open_div('editToggle');

			echo form_checkbox('chkboxEdit', 'editStuff', FALSE, 'class="chkboxEdit switch"');

		echo close_div();

		// Clear float
		echo clear_float();

		echo open_div('whiteBack noPadding');

			if($customer_view == 'all')
			{
				$this->load->view('customers/customers_list_full_flexigrid');
			}
			else
			{
				$this->load->view('customers/customers_list_by_group_flexigrid');
			}

		echo close_div();

	// End of right content
	echo close_div();

	// Left content
	echo open_div('leftContentSmall');

		// Main heading
		echo heading('Groups', 2, 'class="headingTitle"');

		// Add group
		echo make_button('Add', 'btnAddGroup btnAdd blueButton btnHeading');

		// Clear float
		echo clear_float();

		echo open_div('whiteBack noPadding');

			if($this->uri->segment(2) != 'by_group')
			{
				echo anchor('customers', 'All Customers', 'class="firstLink active"');
			}
			else
			{
				echo anchor('customers', 'All Customers', 'class="firstLink"');
			}

			if($groups['error'] == FALSE)
			{
				$json_groups                = $groups['result'];
				foreach($json_groups->fastsell_customer_groups as $group)
				{
					if(($this->uri->segment(2) == 'by_group') && ($this->uri->segment(3) == $group->id))
					{
						$active                 = ' active';
					}
					else
					{
						$active                 = '';
					}

					echo open_div('groupContainer outerContainer'.$active);

						echo full_div('', 'icon-cross btnDeleteGroup');
						echo full_div('', 'icon-pencil btnEditGroup');
						echo anchor('customers/by_group/'.$group->id, $group->name);
						echo hidden_div($group->id, 'hdGroupId');

					echo close_div();
				}
			}

		echo close_div();

	// End of left content
	echo close_div();

	// Clear float
	echo clear_float();

// End of middle div
echo close_div();
?>