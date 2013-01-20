<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Customers extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		
		// ----- LOGIN CHECK ----------------------------------------
		$this->scrap_wall->login_check('check');
	}


	/*
	|--------------------------------------------------------------------------
	| MAIN INDEX FUNCTION
	|--------------------------------------------------------------------------
	*/
	function index()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- SOME VARIABLES ---------------------------------
		$show_host_id                   = $this->scrap_web->get_show_host_id();
		
		
		// ----- HEADER ------------------------------------		
		// Some variables
		$dt_header['title'] 	        = 'FastSell Customers';
		$dt_header['crt_page']	        = 'pageCustomers';
		$dt_header['extra_js']          = array('customers');
		$dt_header['extra_css']         = array('customers');
		
		// Load header
		$this->load->view('universal/header', $dt_header);
		
		
		// ----- CONTENT ------------------------------------
		// Navigation view
		$dt_nav['app_page']	            = 'pageCustomers';
		$this->load->view('universal/navigation', $dt_nav);

		// Get all the customers
		$url_customers                  = 'customertoshowhosts/.jsons?showhostid='.$show_host_id;
		$call_customers                 = $this->scrap_web->webserv_call($url_customers, FALSE, 'get', FALSE, FALSE);
		$dt_body['customers']           = $call_customers;

		// Load the view
		$this->load->view('customers/main_customers_page', $dt_body);
		
		
		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}


	/*
	|--------------------------------------------------------------------------
	| UPLOAD VIA MASTER FILE
	|--------------------------------------------------------------------------
	*/
	function upload_master_file()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FAlSE);


		// Some variables
		$user_id					= $this->session->userdata('sv_user_id');


		// ----- HEADER ------------------------------------
		// Some variables
		$dt_header['title'] 		= 'Customers';
		$dt_header['crt_page']		= 'pageCustomers';
		$dt_header['extra_js']		= array('customers_upload_shifter');
		$dt_header['extra_css']		= array('customers', 'scrap_shifter');

		// Load header
		$this->load->view('universal/header', $dt_header);


		// ----- CONTENT ------------------------------------
		// Navigation view
		$dt_nav['app_page']		    = 'pageCustomers';
		$this->load->view('universal/navigation', $dt_nav);

		// Content view
		$this->load->view('customers/customer_master_file_upload');


		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}


	/*
	|--------------------------------------------------------------------------
	| SAVE ADDRESS
	|--------------------------------------------------------------------------
	*/
	function save_ADDRESS()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(TRUE);

		// Some variables
		$customer_org_id            = $this->scrap_web->get_customer_org_id();
		$address_1                  = $this->input->post('address1');
		$city                       = $this->input->post('city');
		$state                      = $this->input->post('state');
		$postal_code                = $this->input->post('postalCode');
		$return_url                 = $this->input->post('hdReturnUrl');

		// Get current address
		$url_address                = 'addresses/.jsons?customerid='.$customer_org_id;
		$call_address               = $this->scrap_web->webserv_call($url_address, FALSE, 'get', FALSE, FALSE);

		// Validate
		if($call_address['error'] == FALSE)
		{
			// Get customer organization details
			$url_customer                       = 'customers/.json?id='.$customer_org_id;
			$call_customer                      = $this->scrap_web->webserv_call($url_customer, FALSE, 'get', FALSE, FALSE);
			$json_customer                      = $call_customer['result'];

			// Edit the address
			$json_customer->addresses[0]->city                 = $city;
			$json_customer->addresses[0]->address_one          = $address_1;
			$json_customer->addresses[0]->postal_code          = $postal_code;
			$json_customer->addresses[0]->state_province       = $state;
			$json_customer->addresses[0]->address_type->id     = 1;

			// Encode JSON
			$update_json                        = json_encode($json_customer);

			// Update the customer
			$update_customer                    = $this->scrap_web->webserv_call('customers/.json', $update_json, 'post');

			// Redirect
			redirect($return_url);
		}
		else
		{
			$call_error                         = $call_address['result'];
			if($call_error->error_code == 10005)
			{
				// Get sample
				$url_sample                     = 'addresses/sample.json';
				$call_sample                    = $this->scrap_web->webserv_call($url_sample);
				$json_sample                    = $call_sample['result'];

				// Edit the sample
				$json_sample->city              = $city;
				$json_sample->address_one       = $address_1;
				$json_sample->postal_code       = $postal_code;
				$json_sample->state_province    = $state;
				$json_sample->address_type->id  = 1;

				// Get customer organization details
				$url_customer                   = 'customers/.json?id='.$customer_org_id;
				$call_customer                  = $this->scrap_web->webserv_call($url_customer, FALSE, 'get', FALSE, FALSE);
				$json_customer                  = $call_customer['result'];

				// Edit customer
				$json_customer->addresses       = array($json_sample);

				// Encode JSON
				$update_json                    = json_encode($json_customer);

				// Update the customer
				$update_customer                = $this->scrap_web->webserv_call('customers/.json', $update_json, 'post');

				// Redirect
				redirect($return_url);
			}
			else
			{
				redirect('fastsells');
			}
		}
	}


	/*
	|--------------------------------------------------------------------------
	| CUSTOMER ORDERS
	|--------------------------------------------------------------------------
	*/
	function orders()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- SOME VARIABLES ---------------------------------
		$customer_user_id               = $this->uri->segment(3);


		// ----- HEADER ------------------------------------
		// Some variables
		$dt_header['title'] 	        = 'FastSell Customers';
		$dt_header['crt_page']	        = 'pageCustomers';
		$dt_header['extra_js']          = array('customers');
		$dt_header['extra_css']         = array('customers', 'fastsells');

		// Load header
		$this->load->view('universal/header', $dt_header);


		// ----- CONTENT ------------------------------------
		// Navigation view
		$dt_nav['app_page']	            = 'pageCustomers';
		$this->load->view('universal/navigation', $dt_nav);

		// Orders
		$url_orders                     = 'fastsellorders/.jsons?customeruserid='.$customer_user_id;
		$call_orders                    = $this->scrap_web->webserv_call($url_orders, FALSE, 'get', FALSE, FALSE);
		$dt_body['orders']              = $call_orders;

		// Load the view
		$this->load->view('orders/main_orders_page_show_host', $dt_body);


		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}


	/*
	|--------------------------------------------------------------------------
	| CUSTOMER ORDER
	|--------------------------------------------------------------------------
	*/
	function order()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- SOME VARIABLES ---------------------------------
		$order_id                       = $this->uri->segment(3);


		// ----- HEADER ------------------------------------
		// Some variables
		$dt_header['title'] 	        = 'FastSell Customers';
		$dt_header['crt_page']	        = 'pageCustomers';
		$dt_header['extra_js']          = array('customers');
		$dt_header['extra_css']         = array('customers', 'fastsell_my_order');

		// Load header
		$this->load->view('universal/header', $dt_header);


		// ----- CONTENT ------------------------------------
		// Navigation view
		$dt_nav['app_page']	            = 'pageCustomers';
		$this->load->view('universal/navigation', $dt_nav);

		// Current order
		$url_crt_order                  = 'fastsellorders/.json?id='.$order_id;
		$call_crt_order                 = $this->scrap_web->webserv_call($url_crt_order, FALSE, 'get', FALSE, FALSE);
		$json_order                     = $call_crt_order['result'];

		// Parse variables
		$dt_body['order']               = TRUE;
		$dt_body['crt_order']           = $call_crt_order['result'];

		// Load the view
		$this->load->view('orders/full_order', $dt_body);


		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}
}

/* End of file customers.php */
/* Location: scrap_engine/controllers/customers.php */