<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Fastsells extends CI_Controller
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


		// ----- SOME VARiABLES ------------------------------
		$acc_type                       = $this->session->userdata('sv_acc_type');
		if($acc_type == 'show_host')
		{
			$show_host_id               = $this->scrap_web->get_show_host_id();
		}
		elseif($acc_type == 'customer')
		{
			$customer_id                = $this->scrap_web->get_customer_org_id();
		}
		
		
		// ----- HEADER ------------------------------------		
		// Some variables
		$dt_header['title'] 	        = 'FastSells';
		$dt_header['crt_page']	        = 'pageFastSells';
		$dt_header['extra_css']         = array('fastsells');
		$dt_header['extra_js']          = array('plugin_countdown', 'fastsells');
		
		// Load header
		$this->load->view('universal/header', $dt_header);
		
		
		// ----- CONTENT ------------------------------------
		if($acc_type == 'show_host')
		{
			// Navigation view
			$dt_nav['app_page']	            = 'pageFastSells';
			$this->load->view('universal/navigation', $dt_nav);

			// Get the fastsells
			$url_fastsells                  = 'fastsellevents/.jsons?showhostid='.$show_host_id;
			$call_fastsells                 = $this->scrap_web->webserv_call($url_fastsells);
			$dt_body['fastsells']           = $call_fastsells;
		}
		else
		{
			// Navigation view
			$dt_nav['app_page']	            = 'pageFastSells';
			$this->load->view('universal/customer_navigation', $dt_nav);

			// Get the fastsells
			$url_fastsells                  = 'fastsellevents/.jsons?customerid='.$customer_id;
			$call_fastsells                 = $this->scrap_web->webserv_call($url_fastsells, FALSE, 'get', FALSE, FALSE);
			$dt_body['fastsells']           = $call_fastsells;
		}

		// Load the view
		$this->load->view('fastsells/main_fastsells_page', $dt_body);
		
		
		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}


	/*
	|--------------------------------------------------------------------------
	| CREATE FASTSELL EVENTS
	|--------------------------------------------------------------------------
	*/
	function create_event()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- SOME VARIABLES ------------------------------
		$show_host_id                   = $this->scrap_web->get_show_host_id();


		// ----- HEADER ------------------------------------
		// Some variables
		$dt_header['title'] 	        = 'Create New FastSell';
		$dt_header['crt_page']	        = 'pageFastSells';
		$dt_header['extra_css']         = array('scrap_shifter', 'create_fastsell');
		$dt_header['extra_js']          = array('shifter_create_fastsell_event');

		// Load header
		$this->load->view('universal/header', $dt_header);


		// ----- CONTENT ------------------------------------
		// Navigation view
		$dt_nav['app_page']	            = 'pageFastSells';
		$this->load->view('universal/navigation', $dt_nav);

		// Get all the customers
		$url_customers                  = 'customertoshowhosts/.jsons?showhostid='.$show_host_id;
		$call_customers                 = $this->scrap_web->webserv_call($url_customers, FALSE, 'get', FALSE, FALSE);
		$dt_body['customers']           = $call_customers;

		// Get the products
		$url_products                   = 'catalogitems/.jsons?showhostid='.$show_host_id;
		$call_products                  = $this->scrap_web->webserv_call($url_products, FALSE, 'get', FALSE, FALSE);
		$dt_body['products']            = $call_products;

		// Load the view
		$this->load->view('fastsells/create_fastsell_event', $dt_body);


		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}


	/*
	|--------------------------------------------------------------------------
	| FASTSELL EVENT
	|--------------------------------------------------------------------------
	*/
	function event()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- SOME VARIABLES ------------------------------
		$acc_type                           = $this->session->userdata('sv_acc_type');
		$fastsell_id                        = $this->uri->segment(3);


		// Get the event information
		$url_fastsell                       = 'fastsellevents/.json?id='.$fastsell_id;
		$call_fastsell                      = $this->scrap_web->webserv_call($url_fastsell, FALSE, 'get', FALSE, FALSE);


		if($call_fastsell['error'] == FALSE)
		{
			// Set the session variables
			$this->session->set_userdata('sv_show_set', $fastsell_id);

			// FastSell information
			$fastsell_info                  = $call_fastsell['result'];
			$started                        = FALSE;
			if(date('Y-m-d His') >= $fastsell_info->event_start_date)
			{
				$started                    = TRUE;
			}

			// ----- HEADER ------------------------------------
			// Some variables
			$dt_header['title'] 	        = 'FastSell';
			$dt_header['crt_page']	        = 'pageFastSellInfo';
			$dt_header['extra_css']         = array('fastsells', 'fastsell_event', 'fastsell_buy');
			$dt_header['extra_js']          = array('plugin_countdown', 'fastsell_event', 'fastsell_buy');

			// Load header
			$this->load->view('universal/header', $dt_header);


			// ----- CONTENT ------------------------------------
			if($acc_type == 'show_host')
			{
				// Some variables
				$show_host_id               = $this->scrap_web->get_show_host_id();

				// Navigation view
				$dt_nav['app_page']	        = 'pageFastSellInfo';
				$dt_nav['fastsell_info']    = $fastsell_info;
				$dt_nav['started']          = $started;
				$this->load->view('fastsells/event_navigation', $dt_nav);

				// Content
				$dt_body['fastsell_info']   = $fastsell_info;

				// Get the products
				$url_products               = 'fastsellitems/.jsons?fastselleventid='.$fastsell_id.'&includevalues=true&includecatalogvalues=true&offset=0&limit=5';
				$call_products              = $this->scrap_web->webserv_call($url_products, FALSE, 'get', FALSE, FALSE);
				$dt_body['products']        = $call_products;

				// Get all the customers
				$url_customers              = 'customertoshowhosts/.jsons?showhostid='.$show_host_id;
				$call_customers             = $this->scrap_web->webserv_call($url_customers, FALSE, 'get', FALSE, FALSE);
				$dt_body['customers']       = $call_customers;

				// Load the view
				$this->load->view('fastsells/event_info', $dt_body);
			}
			elseif($acc_type == 'customer')
			{
				// Get customer user id
				$cust_user_id               = $this->scrap_web->get_customer_user_id();

				// See if any orders exists
				$url_orders                 = 'fastsellorders/.jsons?fastselleventid='.$fastsell_id.'&customeruserid='.$cust_user_id;
				$call_orders                = $this->scrap_web->webserv_call($url_orders, FALSE, 'get', FALSE, FALSE);
				if($call_orders['error'] == TRUE)
				{
					$dt_body['order']       = FALSE;
					$dt_nav['order']        = FALSE;
				}
				else
				{
					// Orders
					$json_orders            = $call_orders['result'];
					$order_id               = $json_orders->fastsell_orders[0]->id;

					// Current order
					$url_crt_order          = 'fastsellorders/.json?id='.$order_id;
					$call_crt_order         = $this->scrap_web->webserv_call($url_crt_order, FALSE, 'get', FALSE, FALSE);

					$dt_body['order']       = TRUE;
					$dt_body['crt_order']   = $call_crt_order['result'];
					$dt_nav['order']        = TRUE;
					$dt_nav['crt_order']    = $call_crt_order['result'];
				}

				// Navigation view
				$dt_nav['app_page']	        = 'pageFastSellInfo';
				$dt_nav['fastsell_info']    = $fastsell_info;
				$dt_nav['started']          = $started;
				$this->load->view('fastsells/customer_event_navigation', $dt_nav);

				// Get the products
				$url_products               = 'fastsellitems/.jsons?fastselleventid='.$fastsell_id.'&includevalues=true&includecatalogvalues=true&offset=0&limit=5';
				$call_products              = $this->scrap_web->webserv_call($url_products, FALSE, 'get', FALSE, FALSE);
				$dt_body['products']        = $call_products;

				// Load the view
				$this->load->view('fastsells/customer_event_info', $dt_body);
			}


			// ----- FOOTER ------------------------------------
			$this->load->view('universal/footer');
		}
		else
		{
			redirect('fastsells');
		}
	}


	/*
	|--------------------------------------------------------------------------
	| FASTSELL BUY
	|--------------------------------------------------------------------------
	*/
	function buy()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- SOME VARIABLES ------------------------------
		$acc_type                           = $this->session->userdata('sv_acc_type');
		$fastsell_id                        = $this->session->userdata('sv_show_set');
		$customer_user_id                   = $this->scrap_web->get_customer_user_id();

		// Get the event information
		$url_fastsell                       = 'fastsellevents/.json?id='.$fastsell_id;
		$call_fastsell                      = $this->scrap_web->webserv_call($url_fastsell, FALSE, 'get', FALSE, FALSE);


		if($call_fastsell['error'] == FALSE)
		{
			// FastSell information
			$fastsell_info                  = $call_fastsell['result'];
			$started                        = FALSE;
			if(date('Y-m-d His') >= $fastsell_info->event_start_date)
			{
				$started                    = TRUE;
			}

			// ----- HEADER ------------------------------------
			// Some variables
			$dt_header['title'] 	            = 'Buy Product';
			$dt_header['crt_page']	            = 'pageFastSellBuy';
			$dt_header['extra_css']             = array('fastsells', 'fastsell_buy');
			$dt_header['extra_js']              = array('plugin_countdown', 'fastsell_buy');

			// Load header
			$this->load->view('universal/header', $dt_header);


			// ----- CONTENT ------------------------------------
			if($acc_type == 'customer')
			{
				// See if any orders exists
				$url_orders                         = 'fastsellorders/.jsons?fastselleventid='.$fastsell_id.'&customeruserid='.$customer_user_id;
				$call_orders                        = $this->scrap_web->webserv_call($url_orders, FALSE, 'get', FALSE, FALSE);
				if($call_orders['error'] == TRUE)
				{
					$dt_body['order']               = FALSE;
					$dt_nav['order']                = FALSE;
				}
				else
				{
					// Orders
					$json_orders                    = $call_orders['result'];
					$order_id                       = $json_orders->fastsell_orders[0]->id;

					// Current order
					$url_crt_order                  = 'fastsellorders/.json?id='.$order_id;
					$call_crt_order                 = $this->scrap_web->webserv_call($url_crt_order, FALSE, 'get', FALSE, FALSE);

					// Get the product names
					$ar_product_names               = array();
					foreach($call_crt_order['result']->fastsell_order_to_items as $order_item)
					{
						// Get the product
						$url_product                = 'fastsellitems/.json?id='.$order_item->fastsell_item->id;
						$call_product               = $this->scrap_web->webserv_call($url_product, FALSE, 'get', FALSE, FALSE);
						$json_product               = $call_product['result'];

						$ar_product_names[$order_item->fastsell_item->id]  = $json_product->catalog_item->catalog_item_field_values[0]->value;
					}

					$dt_body['ar_product_names']    = $ar_product_names;
					$dt_body['order']               = TRUE;
					$dt_body['crt_order']           = $call_crt_order['result'];
					$dt_nav['order']                = TRUE;
					$dt_nav['crt_order']            = $call_crt_order['result'];
				}

				// Navigation view
				$dt_nav['app_page']	                = 'pageFastSellBuy';
				$dt_nav['fastsell_info']            = $fastsell_info;
				$dt_nav['started']                  = $started;
				$this->load->view('fastsells/customer_event_navigation', $dt_nav);

				// Get the products
				$url_products                       = 'fastsellitems/.jsons?fastselleventid='.$fastsell_id.'&includevalues=true&includecatalogvalues=true&offset=0&limit=20';
				$call_products                      = $this->scrap_web->webserv_call($url_products, FALSE, 'get', FALSE, FALSE);
				$dt_body['products']                = $call_products;

				// Load the view
				$this->load->view('products/main_buy_page', $dt_body);
			}


			// ----- FOOTER ------------------------------------
			$this->load->view('universal/footer');
		}
		else
		{
			redirect('fastsells');
		}
	}


	/*
	|--------------------------------------------------------------------------
	| FASTSELL BUY PRODUCT
	|--------------------------------------------------------------------------
	*/
	function buy_product()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(TRUE);

		// Some variables
		$quantity                           = $this->input->post('inpQuantity');
		$product_id                         = $this->input->post('hdProductId');
		$return_url                         = $this->input->post('hdReturnURL');
		$fastsell_id                        = $this->session->userdata('sv_show_set');
		$customer_ord_id                    = $this->scrap_web->get_customer_org_id();
		$customer_user_id                   = $this->scrap_web->get_customer_user_id();

		// Check if the order exists
		if(!empty($customer_user_id) && !empty($fastsell_id))
		{
			$url_orders                     = 'fastsellorders/.jsons?fastselleventid='.$fastsell_id.'&customeruserid='.$customer_user_id;
			$call_orders                    = $this->scrap_web->webserv_call($url_orders, FALSE, 'get', FALSE, FALSE);

			if($call_orders['error'] == FALSE)
			{
				$json_orders                = $call_orders['result'];
				$order_id                   = $json_orders->fastsell_orders[0]->id;

				// Current order
				$url_crt_order              = 'fastsellorders/.json?id='.$order_id;
				$call_crt_order             = $this->scrap_web->webserv_call($url_crt_order, FALSE, 'get', FALSE, FALSE);
				$json_crt_order             = $call_crt_order['result'];

				// Edit order
				$ar_items                   = array();

				// Keep existing items
				foreach($json_crt_order->fastsell_order_to_items as $order_item)
				{
					$ar_item                = array
					(
						'id'                => $order_item->id,
						'quantity'          => $order_item->quantity,
						'fastsell_order'    => array('id' => $order_item->fastsell_order->id),
						'fastsell_item'     => array('id' => $order_item->fastsell_item->id)
					);
					array_push($ar_items, $ar_item);
				}

				// Add new item
				$ar_item                    = array
				(
					'quantity'              => $quantity,
					'fastsell_order'        => array('id' => $order_id),
					'fastsell_item'         => array('id' => $product_id)
				);
				array_push($ar_items, $ar_item);


				$json_crt_order->fastsell_order_to_items    = $ar_items;

				// Encode
				$update_json                = json_encode($json_crt_order);

				// Create new order
				$update_order               = $this->scrap_web->webserv_call('fastsellorders/.json', $update_json, 'post');

				// Redirect
				redirect($return_url);
			}
			else
			{
				// New order

				// Get current address
				$url_address                = 'addresses/.jsons?customerid='.$customer_ord_id;
				$call_address               = $this->scrap_web->webserv_call($url_address, FALSE, 'get', FALSE, FALSE);
				$json_address               = $call_address['result'];
				$address_id                 = $json_address->addresses[0]->id;

				$call_error                 = $call_orders['result'];
				if($call_error->error_code == 10005)
				{
					// Get sample
					$url_sample             = 'fastsellorders/sample.json';
					$call_sample            = $this->scrap_web->webserv_call($url_sample, FALSE, 'get', FALSE, FALSE);
					$json_sample            = $call_sample['result'];

					// Edit the sample
					$json_sample->location->id              = $address_id;
					$json_sample->customer_user->id         = $customer_user_id;
					$json_sample->fastsell_event->id        = $fastsell_id;
					$json_sample->fastsell_order_to_items[0]->quantity              = $quantity;
					$json_sample->fastsell_order_to_items[0]->fastsell_item->id     = $product_id;
					$json_sample->billing_address->id       = $address_id;

					// Encode JSON
					$new_json               = json_encode($json_sample);

					// Create new order
					$new_order              = $this->scrap_web->webserv_call('fastsellorders/.json', $new_json, 'put');

					// Redirect
					redirect($return_url);
				}
				else
				{
					redirect('fastsells');
				}
			}
		}
		else
		{
			redirect('fastsells');
		}
	}


	/*
	|--------------------------------------------------------------------------
	| FASTSELL DELETE PRODUCT
	|--------------------------------------------------------------------------
	*/
	function delete_product()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(TRUE);

		// Some variables
		$order_id                           = $this->input->post('hdOrderId');
		$product_id                         = $this->input->post('hdOrderItemIdSelect');
		$return_url                         = $this->input->post('hdReturnUrl');
		$fastsell_id                        = $this->session->userdata('sv_show_set');
		$customer_user_id                   = $this->scrap_web->get_customer_user_id();

		// Check if the order exists
		if(!empty($customer_user_id) && !empty($fastsell_id))
		{
			$url_orders                     = 'fastsellorders/.jsons?fastselleventid='.$fastsell_id.'&customeruserid='.$customer_user_id;
			$call_orders                    = $this->scrap_web->webserv_call($url_orders, FALSE, 'get', FALSE, FALSE);

			if($call_orders['error'] == FALSE)
			{
				// Current order
				$url_crt_order              = 'fastsellorders/.json?id='.$order_id;
				$call_crt_order             = $this->scrap_web->webserv_call($url_crt_order, FALSE, 'get', FALSE, FALSE);
				$json_crt_order             = $call_crt_order['result'];

				// Edit order
				$ar_items                   = array();

				// Keep existing items
				foreach($json_crt_order->fastsell_order_to_items as $order_item)
				{
					//echo $order_item->id.' -- '.$product_id.'<br>';
					if($order_item->id != $product_id)
					{
						$ar_item                = array
						(
							'id'                => $order_item->id,
							'quantity'          => $order_item->quantity,
							'fastsell_order'    => array('id' => $order_item->fastsell_order->id),
							'fastsell_item'     => array('id' => $order_item->fastsell_item->id)
						);
						array_push($ar_items, $ar_item);
					}
				}


				$json_crt_order->fastsell_order_to_items    = $ar_items;

				// Encode
				$update_json                = json_encode($json_crt_order);
				//echo $update_json;

				// Create new order
				$update_order               = $this->scrap_web->webserv_call('fastsellorders/.json', $update_json, 'post');

				// Redirect
				redirect($return_url);
			}
		}
		else
		{
			redirect('fastsells');
		}
	}


	/*
	|--------------------------------------------------------------------------
	| MY ORDER - Current logged in customer
	|--------------------------------------------------------------------------
	*/
	function my_order()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- SOME VARIABLES ------------------------------
		$acc_type                           = $this->session->userdata('sv_acc_type');
		$fastsell_id                        = $this->session->userdata('sv_show_set');


		// Get the event information
		$url_fastsell                       = 'fastsellevents/.json?id='.$fastsell_id;
		$call_fastsell                      = $this->scrap_web->webserv_call($url_fastsell, FALSE, 'get', FALSE, FALSE);


		if($call_fastsell['error'] == FALSE)
		{
			// FastSell information
			$fastsell_info                  = $call_fastsell['result'];
			$started                        = FALSE;
			if(date('Y-m-d His') >= $fastsell_info->event_start_date)
			{
				$started                    = TRUE;
			}

			// ----- HEADER ------------------------------------
			// Some variables
			$dt_header['title'] 	        = 'My Order';
			$dt_header['crt_page']	        = 'pageFastSellMyOrder';
			$dt_header['extra_css']         = array('fastsells', 'fastsell_my_order');
			$dt_header['extra_js']          = array('plugin_countdown', 'fastsell_my_order');

			// Load header
			$this->load->view('universal/header', $dt_header);


			// ----- CONTENT ------------------------------------
			if($acc_type == 'customer')
			{
				// Some variables
				$customer_user_id                   = $this->scrap_web->get_customer_user_id();
				$customer_org_id                    = $this->scrap_web->get_customer_org_id();

				// See if any orders exists
				$url_orders                         = 'fastsellorders/.jsons?fastselleventid='.$fastsell_id.'&customeruserid='.$customer_user_id;
				$call_orders                        = $this->scrap_web->webserv_call($url_orders, FALSE, 'get', FALSE, FALSE);
				if($call_orders['error'] == TRUE)
				{
					$dt_body['order']               = FALSE;
					$dt_nav['order']                = FALSE;
				}
				else
				{
					// Orders
					$json_orders                    = $call_orders['result'];
					$order_id                       = $json_orders->fastsell_orders[0]->id;

					// Current order
					$url_crt_order                  = 'fastsellorders/.json?id='.$order_id;
					$call_crt_order                 = $this->scrap_web->webserv_call($url_crt_order, FALSE, 'get', FALSE, FALSE);

					// Get current address
					$url_address                    = 'addresses/.jsons?customerid='.$customer_org_id;
					$call_address                   = $this->scrap_web->webserv_call($url_address, FALSE, 'get', FALSE, FALSE);

					$dt_body['address']             = $call_address['result'];
					$dt_body['order']               = TRUE;
					$dt_body['crt_order']           = $call_crt_order['result'];
					$dt_nav['order']                = TRUE;
					$dt_nav['crt_order']            = $call_crt_order['result'];
				}

				// Navigation view
				$dt_nav['app_page']	                = 'pageFastSellMyOrder';
				$dt_nav['fastsell_info']            = $fastsell_info;
				$dt_nav['started']                  = $started;
				$this->load->view('fastsells/customer_event_navigation', $dt_nav);

				// Load the view
				$this->load->view('orders/full_order', $dt_body);
			}


			// ----- FOOTER ------------------------------------
			$this->load->view('universal/footer');
		}
		else
		{
			redirect('fastsells');
		}
	}


	/*
	|--------------------------------------------------------------------------
	| SAVE FASTSELL EVENT CHANGES
	|--------------------------------------------------------------------------
	*/
	function save_event_changes()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- SOME VARIABLES ------------------------------
		$acc_type                           = $this->session->userdata('sv_acc_type');
		$show_name                          = $this->input->post('inpShowName');
		$show_description                   = $this->input->post('inpShowDescription');
		$start_date                         = $this->input->post('inpStartDate');
		$start_hour                         = $this->input->post('startHoursSelect');
		$start_minutes                      = $this->input->post('startMinutesSelect');
		$end_date                           = $this->input->post('inpEndDate');
		$end_hour                           = $this->input->post('endHoursSelect');
		$end_minutes                        = $this->input->post('endMinutesSelect');
		$fastsell_id                        = $this->input->post('hdEventId');
		$return_url                         = $this->input->post('hdReturnUrl');

		// Edit time
		if(strlen($start_hour) == 1)
		{
			$start_hour             = '0'.$start_hour;
		}
		if(strlen($start_minutes) == 1)
		{
			$start_minutes          = '0'.$start_minutes;
		}
		if(strlen($end_hour) == 1)
		{
			$end_hour               = '0'.$end_hour;
		}
		if(strlen($end_minutes) == 1)
		{
			$end_minutes            = '0'.$end_minutes;
		}

	   	// Get fastsell information
		$url_fastsell                       = 'fastsellevents/.json?id='.$fastsell_id;
		$call_fastsell                      = $this->scrap_web->webserv_call($url_fastsell, FALSE, 'get', FALSE, FALSE);

		if($call_fastsell['error'] == FALSE)
		{
			$json_fastsell                  = $call_fastsell['result'];

			// Edit the fastsell
			$json_fastsell->name                    = $show_name;
			$json_fastsell->description             = $show_description;
			$json_fastsell->event_start_date        = $start_date.' '.$start_hour.$start_minutes.'00';
			$json_fastsell->event_end_date          = $end_date.' '.$end_hour.$end_minutes.'00';

			// Encode
			$update_json                    = json_encode($json_fastsell);

			// Submit the update
			$update_fastsell                = $this->scrap_web->webserv_call('fastsellevents/.json', $update_json, 'post');
		}

		// Redirect
		redirect($return_url);
	}


	/*
	|--------------------------------------------------------------------------
	| FASTSELL PRODUCTS
	|--------------------------------------------------------------------------
	*/
	function products()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- SOME VARIABLES ------------------------------
		$acc_type                           = $this->session->userdata('sv_acc_type');
		$fastsell_id                        = $this->session->userdata('sv_show_set');


		// Get the event information
		$url_fastsell                       = 'fastsellevents/.json?id='.$fastsell_id;
		$call_fastsell                      = $this->scrap_web->webserv_call($url_fastsell, FALSE, 'get', FALSE, FALSE);

		if($call_fastsell['error'] == FALSE)
		{
			// Set the session variables
			$this->session->set_userdata('sv_show_set', $fastsell_id);

			// FastSell information
			$fastsell_info                  = $call_fastsell['result'];
			$started                        = FALSE;
			if(date('Y-m-d His') >= $fastsell_info->event_start_date)
			{
				$started                    = TRUE;
			}

			// ----- HEADER ------------------------------------
			// Some variables
			$dt_header['title'] 	        = 'FastSell';
			$dt_header['crt_page']	        = 'pageFastSellInfo';
			$dt_header['extra_css']         = array('fastsells', 'fastsell_event', 'fastsell_buy');
			$dt_header['extra_js']          = array('plugin_countdown', 'fastsell_event', 'fastsell_buy');

			// Load header
			$this->load->view('universal/header', $dt_header);

			// ----- CONTENT ------------------------------------
			if($acc_type == 'show_host')
			{
				// Some variables
				$show_host_id               = $this->scrap_web->get_show_host_id();

				// Navigation view
				$dt_nav['app_page']	        = 'pageFastSellProducts';
				$dt_nav['fastsell_info']    = $fastsell_info;
				$dt_nav['started']          = $started;
				$this->load->view('fastsells/event_navigation', $dt_nav);

				// Content
				$dt_body['fastsell_info']   = $fastsell_info;

				// Get the products
				$url_products               = 'fastsellitems/.jsons?fastselleventid='.$fastsell_id.'&includevalues=true&includecatalogvalues=true&offset=0&limit=20';
				$call_products              = $this->scrap_web->webserv_call($url_products, FALSE, 'get', FALSE, FALSE);
				$dt_body['products']        = $call_products;

				// Load the view
				//$this->load->view('fastsells/event_info', $dt_body);


				// ----- FOOTER ------------------------------------
				$this->load->view('universal/footer');
			}
		}
		else
		{
			redirect('fastsells');
		}
	}
}

/* End of file fastsells.php */
/* Location: scrap_engine/controllers/fastsells.php */