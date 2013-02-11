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
		$dt_body['customer_view']       = 'all';

		// Get all the groups
		$url_groups                     = 'fastsellcustomergroups/.jsons?showhostid='.$show_host_id;
		$call_groups                    = $this->scrap_web->webserv_call($url_groups, FALSE, 'get', FALSE, FALSE);
		$dt_body['groups']              = $call_groups;

		// Load the view
		$this->load->view('customers/main_customers_page', $dt_body);
		
		
		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}


	/*
	|--------------------------------------------------------------------------
	| DISPLAY CUSTOMERS BY GROUP
	|--------------------------------------------------------------------------
	*/
	function by_group()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- SOME VARIABLES ---------------------------------
		$show_host_id                   = $this->scrap_web->get_show_host_id();
		$group_id                       = $this->uri->segment(3);


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
		$url_customers                  = 'fastsellcustomergroups/.json?id='.$group_id;
		$call_customers                 = $this->scrap_web->webserv_call($url_customers, FALSE, 'get', FALSE, FALSE);
		$dt_body['customers']           = $call_customers;
		$dt_body['customer_view']       = 'by_group';

		// Get all the groups
		$url_groups                     = 'fastsellcustomergroups/.jsons?showhostid='.$show_host_id;
		$call_groups                    = $this->scrap_web->webserv_call($url_groups, FALSE, 'get', FALSE, FALSE);
		$dt_body['groups']              = $call_groups;

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
	| SAVE ADDRESS - DELIVERY
	|--------------------------------------------------------------------------
	*/
	function save_address_delivery()
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
		$same_address               = $this->input->post('checkMakeSame');

		// Get current address
		$url_address                = 'addresses/.jsons?customerid='.$customer_org_id;
		$call_address               = $this->scrap_web->webserv_call($url_address, FALSE, 'get', FALSE, FALSE);

		// Validate
		if($call_address['error'] == FALSE)
		{
			// Get customer organization details
			$url_customer                           = 'customers/.json?id='.$customer_org_id;
			$call_customer                          = $this->scrap_web->webserv_call($url_customer, FALSE, 'get', FALSE, FALSE);
			$json_customer                          = $call_customer['result'];
			$exists                                 = FALSE;

			// Edit the address
			foreach($json_customer->addresses as $address)
			{
				if($address->address_type->id == 2)
				{
					$exists                         = TRUE;
					$address->city                  = $city;
					$address->address_one           = $address_1;
					$address->postal_code           = $postal_code;
					$address->state_province        = $state;
					$address->address_type->id      = 2;
				}
			}

			if($exists == FALSE)
			{
				$ar_addresses                       = array();
				foreach($json_customer->addresses as $address)
				{
					$ar_address             = array
					(
						'id'                => $address->id,
						'city'              => $address->city,
						'type_name'         => $address->type_name,
						'address_one'       => $address->address_one,
						'address_two'       => $address->address_two,
						'address_three'     => $address->address_three,
						'postal_code'       => $address->postal_code,
						'state_province'    => $address->state_province,
						'address_type'      => array
						(
							'id'            => $address->address_type->id,
							'description'   => $address->address_type->description,
							'type_name'     => $address->address_type->type_name
						)
					);
					array_push($ar_addresses, $ar_address);
				}

				// Add new address
				$ar_address             = array
				(
					'city'              => $city,
					'address_one'       => $address_1,
					'address_two'       => '',
					'address_three'     => '',
					'postal_code'       => $postal_code,
					'state_province'    => $state,
					'address_type'      => array
					(
						'id'            => 2,
					)
				);
				array_push($ar_addresses, $ar_address);

				$json_customer->addresses       = $ar_addresses;
			}

			// Encode JSON
			$update_json                        = json_encode($json_customer);

			// Update the address
			$update_address                     = $this->scrap_web->webserv_call('customers/.json', $update_json, 'post');
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
				$json_sample->address_type->id  = 2;

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

		// Save the billing address as well
		if(isset($same_address))
		{
			// Get current address
			$url_address                = 'addresses/.jsons?customerid='.$customer_org_id;
			$call_address               = $this->scrap_web->webserv_call($url_address, FALSE, 'get', FALSE, FALSE);

			// Validate
			if($call_address['error'] == FALSE)
			{
				// Get customer organization details
				$url_customer                           = 'customers/.json?id='.$customer_org_id;
				$call_customer                          = $this->scrap_web->webserv_call($url_customer, FALSE, 'get', FALSE, FALSE);
				$json_customer                          = $call_customer['result'];
				$exists                                 = FALSE;

				// Edit the address
				foreach($json_customer->addresses as $address)
				{
					if($address->address_type->id == 1)
					{
						$exists                         = TRUE;
						$address->city                  = $city;
						$address->address_one           = $address_1;
						$address->postal_code           = $postal_code;
						$address->state_province        = $state;
						$address->address_type->id      = 1;
					}
				}

				if($exists == FALSE)
				{
					$ar_addresses                       = array();
					foreach($json_customer->addresses as $address)
					{
						$ar_address             = array
						(
							'id'                => $address->id,
							'city'              => $address->city,
							'type_name'         => $address->type_name,
							'address_one'       => $address->address_one,
							'address_two'       => $address->address_two,
							'address_three'     => $address->address_three,
							'postal_code'       => $address->postal_code,
							'state_province'    => $address->state_province,
							'address_type'      => array
							(
								'id'            => $address->address_type->id,
								'description'   => $address->address_type->description,
								'type_name'     => $address->address_type->type_name
							)
						);
						array_push($ar_addresses, $ar_address);
					}

					// Add new address
					$ar_address             = array
					(
						'city'              => $city,
						'address_one'       => $address_1,
						'address_two'       => '',
						'address_three'     => '',
						'postal_code'       => $postal_code,
						'state_province'    => $state,
						'address_type'      => array
						(
							'id'            => 1,
						)
					);
					array_push($ar_addresses, $ar_address);

					$json_customer->addresses       = $ar_addresses;
				}

				// Encode JSON
				$update_json                        = json_encode($json_customer);

				// Update the address
				$update_address                     = $this->scrap_web->webserv_call('customers/.json', $update_json, 'post');
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
					$json_sample->address_type->id  = 2;

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

		// Redirect
		redirect($return_url);
	}


	/*
	|--------------------------------------------------------------------------
	| SAVE ADDRESS - BILLING
	|--------------------------------------------------------------------------
	*/
	function save_address_billing()
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
			$url_customer                           = 'customers/.json?id='.$customer_org_id;
			$call_customer                          = $this->scrap_web->webserv_call($url_customer, FALSE, 'get', FALSE, FALSE);
			$json_customer                          = $call_customer['result'];
			$exists                                 = FALSE;

			// Edit the address
			foreach($json_customer->addresses as $address)
			{
				if($address->address_type->id == 1)
				{
					$exists                         = TRUE;
					$address->city                  = $city;
					$address->address_one           = $address_1;
					$address->postal_code           = $postal_code;
					$address->state_province        = $state;
					$address->address_type->id      = 1;
				}
			}

			if($exists == FALSE)
			{
				$ar_addresses                       = array();
				foreach($json_customer->addresses as $address)
				{
					$ar_address             = array
					(
						'id'                => $address->id,
						'city'              => $address->city,
						'type_name'         => $address->type_name,
						'address_one'       => $address->address_one,
						'address_two'       => $address->address_two,
						'address_three'     => $address->address_three,
						'postal_code'       => $address->postal_code,
						'state_province'    => $address->state_province,
						'address_type'      => array
						(
							'id'            => $address->address_type->id,
							'description'   => $address->address_type->description,
							'type_name'     => $address->address_type->type_name
						)
					);
					array_push($ar_addresses, $ar_address);
				}

				// Add new address
				$ar_address             = array
				(
					'city'              => $city,
					'address_one'       => $address_1,
					'address_two'       => '',
					'address_three'     => '',
					'postal_code'       => $postal_code,
					'state_province'    => $state,
					'address_type'      => array
					(
						'id'            => 1,
					)
				);
				array_push($ar_addresses, $ar_address);

				$json_customer->addresses       = $ar_addresses;
			}

			// Encode JSON
			$update_json                        = json_encode($json_customer);

			// Update the address
			$update_address                     = $this->scrap_web->webserv_call('customers/.json', $update_json, 'post');

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
				$json_sample->address_type->id  = 2;

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


	/*
	|--------------------------------------------------------------------------
	| ADD A CUSTOMER GROUP
	|--------------------------------------------------------------------------
	*/
	function add_group()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);

		// Some variables
		$show_host_id                   = $this->scrap_web->get_show_host_id();
		$group_name                     = $this->input->post('inpCustomerGroup');

		// Sample group
		$url_sample_group               = 'fastsellcustomergroups/sample.json';
		$call_sample_group              = $this->scrap_web->webserv_call($url_sample_group);

		if($call_sample_group['error'] == FALSE)
		{
			$json_sample_group          = $call_sample_group['result'];

			$json_sample_group->name                            = $group_name;
			$json_sample_group->show_host_organization->id      = $show_host_id;
			$json_sample_group->customer_organizations          = null;

			// Add the customer ids to the group
			if($this->input->post('checkCustomer'))
			{
				// Some variables
				$ar_pv_customer_ids             = $this->input->post('checkCustomer');
				$ar_customer_ids                = array();

				foreach($ar_pv_customer_ids as $customer_id)
				{
					array_push($ar_customer_ids, array('id' => $customer_id));
				}
				$json_sample_group->customer_organizations          = $ar_customer_ids;
			}

			// Encode
			$json_sample_group                  = json_encode($json_sample_group);

			// Create the group
			$new_group                          = $this->scrap_web->webserv_call('fastsellcustomergroups/.json', $json_sample_group, 'put');

			// Redirect
			redirect('customers');
		}
	}


	/*
	|--------------------------------------------------------------------------
	| EDIT A CUSTOMER GROUP
	|--------------------------------------------------------------------------
	*/
	function edit_group()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(TRUE);

		// Some variables
		$show_host_id                   = $this->scrap_web->get_show_host_id();
		$group_name                     = $this->input->post('inpCustomerGroup');
		$group_id                       = $this->input->post('hdGroupId');
		$return_url                     = $this->input->post('hdReturnUrl');

		// Group info
		$url_group                      = 'fastsellcustomergroups/.json?id='.$group_id;
		$call_group                     = $this->scrap_web->webserv_call($url_group);

		if($call_group['error'] == FALSE)
		{
			$json_group          = $call_group['result'];

			$json_group->name                            = $group_name;
			$json_group->show_host_organization->id      = $show_host_id;
			$json_group->customer_organizations          = null;

			// Add the customer ids to the group
			if($this->input->post('checkCustomer'))
			{
				// Some variables
				$ar_pv_customer_ids             = $this->input->post('checkCustomer');
				$ar_customer_ids                = array();

				foreach($ar_pv_customer_ids as $customer_id)
				{
					array_push($ar_customer_ids, array('id' => $customer_id));
				}
				$json_group->customer_organizations          = $ar_customer_ids;
			}

			// Encode
			$json_group                         = json_encode($json_group);

			// Create the group
			$update_group                       = $this->scrap_web->webserv_call('fastsellcustomergroups/.json', $json_group, 'post');

			// Redirect
			redirect($return_url);
		}
	}
}

/* End of file customers.php */
/* Location: scrap_engine/controllers/customers.php */