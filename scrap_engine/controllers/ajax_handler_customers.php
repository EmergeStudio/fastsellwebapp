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
			// Load the view
			$this->load->view('customers/ajax/add_customer_popup');
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
	| ADD CUSTOMER
	|--------------------------------------------------------------------------
	*/
	function add_customer()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$user_id                = $this->session->userdata('sv_user_id');
			$customer_name          = $this->input->post('inpCustomerName');
			$customer_number        = $this->input->post('inpCustomerNumber');
			$first_name             = $this->input->post('inpName');
			$surname                = $this->input->post('inpSurname');
			$email                  = $this->input->post('inpEmail');
			$username               = $first_name.$surname.$this->scrap_string->random_string(5);
			$password               = $this->scrap_string->random_string();

			// Scrappy web call
			$url_sample				= 'customers/sample.json';
			$call_sample			= $this->scrap_web->webserv_call($url_sample);

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

				$customer_to_show_host                              = $json_sample->customer_to_show_host;
				$customer_to_show_host->show_host_organization      = $show_host_id;
				$customer_to_show_host->customer_number             = $customer_number;

				// Customer user
				$json_sample->customer_owner->user->user_emails	    = array($ar_emails);
				$json_sample->customer_owner->user->firstname		= $first_name;
				$json_sample->customer_owner->user->lastname		= $surname;
				$json_sample->customer_owner->user->username		= $username;
				$json_sample->customer_owner->user->password		= sha1($password);
				$json_sample->time_zone->id                         = 15;

				// Recode
				$new_json				= json_encode($json_sample);
				//echo $new_json;

				// Submit the new customer
				$new_customer			= $this->scrap_web->webserv_call('customers/.json', $new_json, 'put');

				// Validate the result
				if($new_customer['error'] == FALSE)
				{
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
	| DELETE CUSTOMER
	|--------------------------------------------------------------------------
	*/
	function delete_customer()
	{
		if($this->scrap_wall->login_check_ajax() == TRUE)
		{
			// Some variables
			$customer_to_show_host_id   = $this->input->post('customer_to_show_host_id');

			// Delete the customer
			$url_customer               = 'customertoshowhosts/.json?id='.$customer_to_show_host_id;
			$delete_customer            = $this->scrap_web->webserv_call($url_customer, FALSE, 'delete');

			// Load the content view
			if($delete_customer['error'] == FALSE)
			{
				echo 'okitsdone';
			}
			else
			{
				// Return the error message
				$json				= $delete_customer['result'];
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
			$ex_rows                        = explode('][', $rows);

			// Top level JSON
			$ar_json                        = array('id' => null, 'message' => null, 'worksheets' => '', 'error_code' => null, 'error_description' => null, 'type_name' => 'workbook');

			// Worksheet JSON
			$ar_worksheets                  = array();
			$ar_worksheet                   = array('id' => null, 'message' => null, 'headers' => '', 'rows' => '', 'error_code' => null, 'error_description' => null, 'type_name' => 'sheet', 'sheet_name' => 'Customer Create Master Data');

			// Header JSON
			$ar_header                      = array('email', 'firstname', 'lastname', 'phone number', 'address one', 'address two', 'address three', 'city', 'state / province', 'zip / postal code', 'customer name', 'customer number');

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
	
}
/* End of file ajax_handler_customers.php */
/* Location: ./scrap_application/engine/controllers/ajax_handler_customers.php */