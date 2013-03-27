<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * AJAX Handler Customers Controller
 *
 * This controller handles all AJAX requests.
 *
 * It also has a special means to login checks to prevent
 * situations where the session returns false and shows a
 * huge error in the message box.
 * 
 * @author	Chris Humboldt (http://www.chrismodem.com)
 * @link	http://www.chrismodem.com/
 */

class Ajax_handler_customers extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}


	/*
	|--------------------------------------------------------------------------
	| INDEX
	|--------------------------------------------------------------------------
	*/
	function index()
	{
		redirect('login');
	}


	/*
	|--------------------------------------------------------------------------
	| HTML VIEW
	|--------------------------------------------------------------------------
	*/
	function html_view()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// URI segment
			$application	= $this->uri->segment(3);
			$view_file		= $this->uri->segment(4);
			
			// Load the view
			$this->load->view($application.'/ajax/'.$view_file);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| ADD CUSTOMER POPUP
	|--------------------------------------------------------------------------
	*/
	function add_customer_popup()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id                   = $this->scrap_web->get_show_host_id();

			// Get the groups
			$url_groups                     = 'fastsellcustomergroups/.jsons?showhostid='.$show_host_id;
			$call_groups                    = $this->scrap_web->webserv_call($url_groups, FALSE, 'get', FALSE, FALSE);
			$dt_body['groups']              = $call_groups;

			// Load the view
			$this->load->view('customers/ajax/add_customer_popup', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| GET CUSTOMERS
	|--------------------------------------------------------------------------
	*/
	function get_customers()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$list_type              = $this->input->post('list_type');
			$show_host_id			= $this->scrap_web->get_show_host_id();

			// Get the customers
			$url_customers          = 'customertoshowhosts/.jsons?showhostid='.$show_host_id;
			$call_customers         = $this->scrap_web->webserv_call($url_customers);
			$dt_body['customers']   = $call_customers;

			// Load the content view
			if($list_type == 'createShowList')
			{
				$this->load->view('customers/customers_list', $dt_body);
			}
			else
			{
				$this->load->view('customers/customers_list_full', $dt_body);
			}
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| GET CUSTOMERS
	|--------------------------------------------------------------------------
	*/
	function get_groups_list()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id                   = $this->scrap_web->get_show_host_id();

			// Get the groups
			$url_groups                     = 'fastsellcustomergroups/.jsons?showhostid='.$show_host_id;
			$call_groups                    = $this->scrap_web->webserv_call($url_groups, FALSE, 'get', FALSE, FALSE);
			$dt_body['groups']              = $call_groups;

			// Load the view
			$this->load->view('customers/groups_list', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| GET GROUPS FOR AUTOCOMPLETE
	|--------------------------------------------------------------------------
	*/
	function get_groups_for_autocomplete()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id                   = $this->scrap_web->get_show_host_id();

			// Get the groups
			$url_groups                     = 'fastsellcustomergroups/.jsons?showhostid='.$show_host_id;
			$call_groups                    = $this->scrap_web->webserv_call($url_groups, FALSE, 'get', FALSE, FALSE);
			$dt_body['groups']              = $call_groups;

			// Load the view
			$this->load->view('customers/ajax/groups_for_autocomplete', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}



	/*
	|--------------------------------------------------------------------------
	| ADD CUSTOMER
	|--------------------------------------------------------------------------
	*/
	function add_customer()
	{
		error_reporting(0);

		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$user_id                = $this->session->userdata('sv_user_id');
			$customer_name          = $this->input->post('inpCustomerName');
			$customer_number        = $this->input->post('inpCustomerNumber');
			$first_name             = $this->input->post('inpName');
			$surname                = $this->input->post('inpSurname');
			$email                  = $this->input->post('inpEmail');
			$customer_group         = $this->input->post('inpCustomerGroup');
			$group_ids              = $this->input->post('inpGroupIds');
			$username               = $first_name.$surname.$this->scrap_string->random_string(5);
			$password               = $this->scrap_string->random_string();
			$show_host_id			= $this->scrap_web->get_show_host_id();

			// Scrappy web call
			$url_sample				    = 'customers/sample.json';
			$call_sample			    = $this->scrap_web->webserv_call($url_sample);

			// Validate
			if($call_sample['error'] == FALSE)
			{
				// Get show host id
				$show_host_id			= $this->scrap_web->get_show_host_id();

				// Sample
				$json_sample			= $call_sample['result'];

				// Change the data
				$ar_emails									        = array();
				$ar_emails['is_primary']              		        = TRUE;
				$ar_emails['email']							        = $email;

				$json_sample->name                                  = $customer_name;
				$json_sample->customer_to_show_host->customer_name  = $customer_name;

				$customer_to_show_host                              = $json_sample->customer_to_show_host;
				$customer_to_show_host->show_host_organization      = $show_host_id;
				$customer_to_show_host->customer_number             = $customer_number;

				// Customer user
				$json_sample->customer_owner->user->user_emails	    = array($ar_emails);
				$json_sample->customer_owner->user->firstname		= $first_name;
				$json_sample->customer_owner->user->lastname		= $surname;
				$json_sample->customer_owner->user->username		= $username;
				$json_sample->customer_owner->user->password		= sha1($password);
				$json_sample->time_zone->id                         = 6;
				$json_sample->customer_owner->user->clear_password  = $password;

				// Recode
				$new_json				= json_encode($json_sample);

				// Submit the new customer
				$new_customer			= $this->scrap_web->webserv_call('customers/.json', $new_json, 'put');

				// Validate the result
				if($new_customer['error'] == FALSE)
				{
					// Some variables
					$json_new_customer              = $new_customer['result'];

					// Existing Groups
					$ex_existing_groups             = explode(':', $group_ids);
					$ar_existing_group_ids          = array();
					$ar_existing_group_labels       = array();

					foreach($ex_existing_groups as $existing_group)
					{
						if(!empty($existing_group))
						{
							$ex_existing_group          = explode('-', $existing_group);
							if(!in_array($ex_existing_group[0], $ar_existing_group_ids))
							{
								array_push($ar_existing_group_ids, $ex_existing_group[0]);
							}
							array_push($ar_existing_group_labels, trim($ex_existing_group[1]));

							// Group info
							$url_group                      = 'fastsellcustomergroups/.json?id='.$ex_existing_group[0];
							$call_group                     = $this->scrap_web->webserv_call($url_group, FALSE, 'get', FALSE, FALSE);

							if($call_group['error'] == FALSE)
							{
								$json_group          = $call_group['result'];

								// Add the customer ids to the group
								$ar_customer_ids                = array();

								foreach($json_group->customer_organizations as $current_customer)
								{
									array_push($ar_customer_ids, array('id' => $current_customer->id));
								}
								array_push($ar_customer_ids, array('id' => $json_new_customer->id));
								$json_group->customer_organizations          = $ar_customer_ids;

								// Encode
								$json_group                         = json_encode($json_group);

								// Create the group
								$update_group                       = $this->scrap_web->webserv_call('fastsellcustomergroups/.json', $json_group, 'post');
							}
						}
					}

					// New groups
					$ex_text_groups         = explode(',', $customer_group);

					foreach($ex_text_groups as $text_group)
					{
						if(!in_array(trim($text_group), $ar_existing_group_labels))
						{
							// Sample group
							$url_sample_group               = 'fastsellcustomergroups/sample.json';
							$call_sample_group              = $this->scrap_web->webserv_call($url_sample_group);

							if($call_sample_group['error'] == FALSE)
							{
								$json_sample_group          = $call_sample_group['result'];

								$json_sample_group->name                            = $text_group;
								$json_sample_group->show_host_organization->id      = $show_host_id;
								$json_sample_group->customer_organizations          = null;

								// Add the customer ids to the group
								$ar_customer_ids                = array();
								array_push($ar_customer_ids, array('id' => $json_new_customer->id));
								$json_sample_group->customer_organizations          = $ar_customer_ids;

								// Encode
								$json_sample_group                  = json_encode($json_sample_group);

								// Create the group
								$new_group                          = $this->scrap_web->webserv_call('fastsellcustomergroups/.json', $json_sample_group, 'put');
							}
						}
					}

					echo 'okitsdone';
				}
				else
				{
					// Return the error message
					$json				= $new_customer['result'];
					echo $json->error_description;
				}
			}
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| ADD CUSTOMER 2
	|--------------------------------------------------------------------------
	*/
	function add_customer_2()
	{
		error_reporting(0);

		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$user_id                = $this->session->userdata('sv_user_id');
			$customer_name          = $this->input->post('inpCustomerName');
			$customer_number        = $this->input->post('inpCustomerNumber');
			$first_name             = $this->input->post('inpName');
			$surname                = $this->input->post('inpSurname');
			$email                  = $this->input->post('inpEmail');
			$customer_group         = $this->input->post('inpCustomerGroup');
			$group_ids              = $this->input->post('inpGroupIds');
			$username               = $first_name.$surname.$this->scrap_string->random_string(5);
			$password               = $this->scrap_string->random_string();

			// Scrappy web call
			$url_sample				    = 'customers/sample.json';
			$call_sample			    = $this->scrap_web->webserv_call($url_sample);

			// Validate
			if($call_sample['error'] == FALSE)
			{
				// Get show host id
				$show_host_id			= $this->scrap_web->get_show_host_id();

				// Sample
				$json_sample			= $call_sample['result'];

				// Change the data
				$ar_emails									        = array();
				$ar_emails['is_primary']              		        = TRUE;
				$ar_emails['email']							        = $email;

				$json_sample->name                                  = $customer_name;
				$json_sample->customer_to_show_host->customer_name  = $customer_name;

				$customer_to_show_host                              = $json_sample->customer_to_show_host;
				$customer_to_show_host->show_host_organization      = $show_host_id;
				$customer_to_show_host->customer_number             = $customer_number;

				// Customer user
				$json_sample->customer_owner->user->user_emails	    = array($ar_emails);
				$json_sample->customer_owner->user->firstname		= $first_name;
				$json_sample->customer_owner->user->lastname		= $surname;
				$json_sample->customer_owner->user->username		= $username;
				$json_sample->customer_owner->user->password		= sha1($password);
				$json_sample->time_zone->id                         = 6;
				$json_sample->customer_owner->user->clear_password  = $password;

				// Recode
				$new_json				= json_encode($json_sample);
				//echo $new_json;

				// Submit the new customer
				$new_customer			= $this->scrap_web->webserv_call('customers/.json', $new_json, 'put');

				// Validate the result
				if($new_customer['error'] == FALSE)
				{
					// Some variables
					$json_new_customer              = $new_customer['result'];

					// Existing Groups
					$ex_existing_groups             = explode(':', $group_ids);
					$ar_existing_group_ids          = array();
					$ar_existing_group_labels       = array();

					foreach($ex_existing_groups as $existing_group)
					{
						if(!empty($existing_group))
						{
							$ex_existing_group          = explode('-', $existing_group);
							array_push($ar_existing_group_ids, $ex_existing_group[0]);
							array_push($ar_existing_group_labels, trim($ex_existing_group[1]));

							// Group info
							$url_group                      = 'fastsellcustomergroups/.json?id='.$ex_existing_group[0];
							$call_group                     = $this->scrap_web->webserv_call($url_group, FALSE, 'get', FALSE, FALSE);

							if($call_group['error'] == FALSE)
							{
								$json_group          = $call_group['result'];

								// Add the customer ids to the group
								$ar_customer_ids                = array();

								foreach($json_group->customer_organizations as $current_customer)
								{
									array_push($ar_customer_ids, array('id' => $current_customer->id));
								}
								array_push($ar_customer_ids, array('id' => $json_new_customer->id));
								$json_group->customer_organizations          = $ar_customer_ids;

								// Encode
								$json_group                         = json_encode($json_group);

								// Create the group
								$update_group                       = $this->scrap_web->webserv_call('fastsellcustomergroups/.json', $json_group, 'post');
							}
						}
					}

					// New groups
					$ex_text_groups         = explode(',', $customer_group);

					foreach($ex_text_groups as $text_group)
					{
						if(!in_array(trim($text_group), $ar_existing_group_labels))
						{
							// Sample group
							$url_sample_group               = 'fastsellcustomergroups/sample.json';
							$call_sample_group              = $this->scrap_web->webserv_call($url_sample_group);

							if($call_sample_group['error'] == FALSE)
							{
								$json_sample_group          = $call_sample_group['result'];

								$json_sample_group->name                            = $text_group;
								$json_sample_group->show_host_organization->id      = $show_host_id;
								$json_sample_group->customer_organizations          = null;

								// Add the customer ids to the group
								$ar_customer_ids                = array();
								array_push($ar_customer_ids, array('id' => $json_new_customer->id));
								$json_sample_group->customer_organizations          = $ar_customer_ids;

								// Encode
								$json_sample_group                  = json_encode($json_sample_group);

								// Create the group
								$new_group                          = $this->scrap_web->webserv_call('fastsellcustomergroups/.json', $json_sample_group, 'put');
							}
						}
					}

					// Some variables
					$event_id                       = $this->session->userdata('sv_show_set');
					$json_new_customer              = $new_customer['result'];
					$customer_id                    = $json_new_customer->id;
					$call_type                      = 'put';

					// Sample code
					$json_link                      = array
					(
						'customer_organizations'    => array(array('id' => $customer_id))
					);

					// Recode
					$link_json		                = json_encode($json_link);

					// Submit the fastsell customer link
					$link_customer		            = $this->scrap_web->webserv_call('fastsellevents/customers/.jsons?fastselleventid='.$event_id, $link_json, $call_type, FALSE, FALSE);

					echo 'okitsdone';
				}
				else
				{
					// Return the error message
					$json				= $new_customer['result'];
					echo $json->error_description;
				}
			}
		}
		else
		{
			echo 9876;
		}
	}



	/*
	|--------------------------------------------------------------------------
	| REMOVE CUSTOMER
	|--------------------------------------------------------------------------
	*/
	function fastsell_remove_customer()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| CHECK CUSTOMER UPLOAD
	|--------------------------------------------------------------------------
	*/
	function check_customer_upload()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id                   = $this->scrap_web->get_show_host_id();

			if(isset($_FILES['uploadedFile']) && !empty($_FILES['uploadedFile']))
			{
				$document_file			= str_replace(' ', '%20', $_FILES['uploadedFile']);
			}
			else
			{
				$document_file			= FALSE;
			}

			// Submit the file
			$url_file_convert           = 'masterdata/multipartmasterdata.xls';
			$call_file_convert          = $this->scrap_web->webserv_call($url_file_convert, array('uploadedFile'	=> '@'.$document_file['tmp_name']), 'post', 'multipart_form');
			$json_file_convert          = $call_file_convert['result'];
			$encode_file_convert        = json_encode($json_file_convert);
			//echo $encode_file_convert;

			// Submit the JSON
			$url_file_check             = 'customertoshowhosts/masterdata.json?showhostid='.$show_host_id.'&validateinnercontentonly=true';
			$call_file_check            = $this->scrap_web->webserv_call($url_file_check, $encode_file_convert, 'put');
			$dt_body['row_check']       = $call_file_check;
			//echo json_encode($call_file_check['result']);

			// Load the view
			$this->load->view('customers/check_rows_customers', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| CUSTOMERS UPLOAD
	|--------------------------------------------------------------------------
	*/
	function upload_customers()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id                   = $this->scrap_web->get_show_host_id();
			$rows                           = $this->scrap_string->remove_flc($this->input->post('rows'));
			$headings                       = $this->scrap_string->remove_flc($this->input->post('headings'));
			$ex_rows                        = explode('][', $rows);
			$ex_headings                    = explode('][', $headings);

			// Top level JSON
			$ar_json                        = array('id' => null, 'message' => null, 'worksheets' => '', 'error_code' => null, 'error_description' => null, 'type_name' => 'workbook');

			// Worksheet JSON
			$ar_worksheets                  = array();
			$ar_worksheet                   = array('id' => null, 'message' => null, 'headers' => '', 'rows' => '', 'error_code' => null, 'error_description' => null, 'type_name' => 'sheet', 'sheet_name' => 'Customer Create Master Data');

			// Header JSON
//			$ar_header                      = array('email', 'firstname', 'lastname', 'phone number', 'address one', 'address two', 'address three', 'city', 'state / province', 'zip / postal code', 'customer name', 'customer number');
			foreach($ex_headings as $row_item)
			{
				// Some variables
				$columns                    = $this->scrap_string->remove_flc($row_item);
				$ex_columns                 = explode('}{', $columns);
				$ar_header                  = array();
				foreach($ex_columns as $column_item)
				{
					array_push($ar_header, $column_item);
				}
			}

			// Rows array
			$ar_rows                        = array();
			foreach($ex_rows as $row_item)
			{
				// Some variables
				$columns                    = $this->scrap_string->remove_flc($row_item);
				$ex_columns                 = explode('}{', $columns);
				$ar_row                     = array('id' => null, 'message' => null, 'columns' => '', 'error_code' => -1, 'error_description' => null, 'type_name' => 'row');
				$ar_columns                 = array();

				// Set the columns
				foreach($ex_columns as $column_item)
				{
					$ar_column              = array('id' => null, 'message' => null, 'value' => $column_item, 'error_code' => null, 'error_description' => null, 'type_name' => 'column');
					array_push($ar_columns, $ar_column);
				}

				// Push the columns and rows into the arrays
				$ar_row['columns']          = $ar_columns;
				array_push($ar_rows, $ar_row);
			}

			// Generate the JSON
			$ar_worksheet['rows']           = $ar_rows;
			$ar_worksheet['headers']        = $ar_header;
			array_push($ar_worksheets, $ar_worksheet);
			$ar_json['worksheets']          = $ar_worksheets;
			$json_customers                 = json_encode($ar_json);
			//echo $json_customers;

			// Submit the JSON
			$url_insert_customers           = 'customertoshowhosts/masterdata.json?showhostid='.$show_host_id.'&validateinnercontentonly=false';
			$call_insert_customers          = $this->scrap_web->webserv_call($url_insert_customers, $json_customers, 'put');
			$dt_body['row_check']           = $call_insert_customers;


			// Load the view
			$this->load->view('customers/error_rows_customers', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| ADD CUSTOMERS POPUP
	|--------------------------------------------------------------------------
	*/
	function add_customers_popup()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id                   = $this->scrap_web->get_show_host_id();

			// Get all the customers
			$url_customers                  = 'customertoshowhosts/.jsons?showhostid='.$show_host_id;
			$call_customers                 = $this->scrap_web->webserv_call($url_customers, FALSE, 'get', FALSE, FALSE);
			$dt_body['customers']           = $call_customers;

			// Get the view
			$this->load->view('customers/ajax/add_customers_popup', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| REFRESH ADDED CUSTOMERS LIST
	|--------------------------------------------------------------------------
	*/
	function get_added_customers()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$fastsell_id                = $this->input->post('event_id');
			$show_host_id               = $this->scrap_web->get_show_host_id();
			$dt_body['show_host_id']    = $show_host_id;

			// Get all the customers
			$url_customers              = 'customers/.jsons?fastselleventid='.$fastsell_id;
			$call_customers             = $this->scrap_web->webserv_call($url_customers, FALSE, 'get', FALSE, FALSE);
			$dt_body['customers']       = $call_customers;

			// Load the view
			$this->load->view('customers/fastsell_customers_list', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| ADD CUSTOMERS VIA MASTER DATA FILE POPUP
	|--------------------------------------------------------------------------
	*/
	function add_master_data_file_popup()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Get the view
			$this->load->view('customers/ajax/add_customers_via_master_data_popup');
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| ADD CUSTOMERS VIA MASTER DATA FILE POPUP
	|--------------------------------------------------------------------------
	*/
	function add_master_data_file_popup_2()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Get the view
			$this->load->view('customers/ajax/add_customers_via_master_data_popup_2');
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| DELTETE CUSTOMER
	|--------------------------------------------------------------------------
	*/
	function delete_customer()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$customer_to_show_host_id       = $this->input->post('customer_to_show_host_id');

			// Remove link
			$url_remove                     = 'customertoshowhosts/.json?id='.$customer_to_show_host_id;
			$call_remove                       = $this->scrap_web->webserv_call($url_remove, FALSE, 'delete');

			if($call_remove['error'] == FALSE)
			{
				echo 'okitsdone';
			}
			else
			{
				// Return the error message
				$json				= $call_remove['result'];
				echo $json->error_description;
			}
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| ADD CUSTOMER GROUP POPUP
	|--------------------------------------------------------------------------
	*/
	function add_customer_group_popup()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id                   = $this->scrap_web->get_show_host_id();

			// Get all the customers
			$url_customers                  = 'customertoshowhosts/.jsons?showhostid='.$show_host_id;
			$call_customers                 = $this->scrap_web->webserv_call($url_customers, FALSE, 'get', FALSE, FALSE);
			$dt_body['customers']           = $call_customers;

			// Get the view
			$this->load->view('customers/ajax/add_customer_group_popup', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| EDIT CUSTOMER GROUP POPUP
	|--------------------------------------------------------------------------
	*/
	function edit_group_popup()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Get the view
			$this->load->view('customers/ajax/edit_customer_group_popup');
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| EDIT CUSTOMER POPUP
	|--------------------------------------------------------------------------
	*/
	function edit_customer_popup()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Get the view
			$this->load->view('customers/ajax/quick_view');
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| EDIT CUSTOMER GROUP POPUP CONTENT
	|--------------------------------------------------------------------------
	*/
	function get_group_popup_content()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id                   = $this->scrap_web->get_show_host_id();
			$group_id                       = $this->input->post('group_id');

			// Get the group information
			$url_group                      = 'fastsellcustomergroups/.json?id='.$group_id;
			$call_group                     = $this->scrap_web->webserv_call($url_group, FALSE, 'get', FALSE, FALSE);
			$dt_body['group']               = $call_group;

			// Get all the customers
			$url_customers                  = 'customertoshowhosts/.jsons?showhostid='.$show_host_id;
			$call_customers                 = $this->scrap_web->webserv_call($url_customers, FALSE, 'get', FALSE, FALSE);
			$dt_body['customers']           = $call_customers;

			// Get the view
			$this->load->view('customers/ajax/edit_customer_group_popup_content', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| EDIT CUSTOMER POPUP CONTENT
	|--------------------------------------------------------------------------
	*/
	function get_customer_popup_content()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$show_host_id                           = $this->scrap_web->get_show_host_id();
			$customer_to_show_host_id               = $this->input->post('customer_to_show_host_id');
			$dt_body['customer_to_show_host_id']    = $customer_to_show_host_id;

			// Get the group information
			$url_groups                     = 'fastsellcustomergroups/.jsons?showhostid='.$show_host_id;
			$call_groups                    = $this->scrap_web->webserv_call($url_groups, FALSE, 'get', FALSE, FALSE);
			$dt_body['groups']              = $call_groups;

			// Get all the customers
			$url_customer                   = 'customertoshowhosts/.json?id='.$customer_to_show_host_id;
			$call_customer                  = $this->scrap_web->webserv_call($url_customer, FALSE, 'get', FALSE, FALSE);
			$dt_body['customer']            = $call_customer;

			// Get the view
			$this->load->view('customers/ajax/edit_customer_popup_content', $dt_body);
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| DELETE A GROUP
	|--------------------------------------------------------------------------
	*/
	function delete_group()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$group_id                       = $this->input->post('group_id');

			// Remove link
			$url_remove                     = 'fastsellcustomergroups/.json?id='.$group_id;
			$call_remove                    = $this->scrap_web->webserv_call($url_remove, FALSE, 'delete');

			if($call_remove['error'] == FALSE)
			{
				echo 'okitsdone';
			}
			else
			{
				// Return the error message
				$json				= $call_remove['result'];
				echo $json->error_description;
			}
		}
		else
		{
			echo 9876;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| SAVE CUSTOMER CHANGES
	|--------------------------------------------------------------------------
	*/
	function save_customer_changes()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$new_value                      = $this->input->post('new_value');
			$edits                          = $this->input->post('edits');
			$ex_edits                       = explode('][', $this->scrap_string->remove_flc($edits));
			$ar_ctsh_ids                    = array();
			$ar_data                        = array();

			// Update the customers to show host id array
			foreach($ex_edits as $edit_value)
			{
				$ex_edit_value              = explode('_', $edit_value);
				$ctsh_id_value              = $ex_edit_value[0];
				array_push($ar_ctsh_ids, $ctsh_id_value);
			}
			$ar_ctsh_ids                    = array_unique($ar_ctsh_ids);

			// Get and edit each customer
			foreach($ar_ctsh_ids as $ctsh_id)
			{
				// Get info
				$url_customer                   = 'customertoshowhosts/.json?id='.$ctsh_id;
				$call_customer                  = $this->scrap_web->webserv_call($url_customer);

				if($call_customer['error'] == FALSE)
				{
					$json_customer                      = $call_customer['result'];

					foreach($ex_edits as $edit_value)
					{
						$ex_edit_value              = explode('_', $edit_value);

						// Edit the data
						if($ex_edit_value[0] == $ctsh_id)
						{
							$value_name                 = $ex_edit_value[1];
							if($value_name == 'customerNumber')
							{
								$json_customer->customer_number     = $new_value;
							}
							elseif($value_name == 'customerName')
							{
								$json_customer->customer_name     = $new_value;
							}
						}
					}

					// Encode
					$json_update                        = json_encode($json_customer);

					// Do the update
					$update                             = $this->scrap_web->webserv_call('customertoshowhosts/.json', $json_update, 'post');
				}
			}
		}
		else
		{
			echo 9876;
		}
	}
	
}
/* End of file ajax_handler_customers.php */
/* Location: ./scrap_application/engine/controllers/ajax_handler_customers.php */