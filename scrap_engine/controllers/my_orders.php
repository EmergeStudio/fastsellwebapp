<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class My_orders extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		
		// ----- LOGIN CHECK ----------------------------------------
		$this->scrap_wall->login_check('check');
	}


	/*
	|--------------------------------------------------------------------------
	| MAIN ORDERS PAGE
	|--------------------------------------------------------------------------
	*/
	function index()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- SOME VARiABLES ------------------------------
		$acc_type                       = $this->session->userdata('sv_acc_type');
		$customer_org_id                = $this->scrap_web->get_customer_org_id();
		$customer_user_id               = $this->scrap_web->get_customer_user_id();


		// ----- HEADER ------------------------------------
		// Some variables
		$dt_header['title'] 	        = 'FastSells';
		$dt_header['crt_page']	        = 'pageMyOrders';
		$dt_header['extra_css']         = array('fastsells');
		$dt_header['extra_js']          = array('plugin_countdown', 'fastsells', 'fastsell_my_order');

		// Load header
		$this->load->view('universal/header', $dt_header);


		// ----- CONTENT ------------------------------------
		// Orders
		$url_orders                     = 'fastsellorders/.jsons?customeruserid='.$customer_user_id;
		$call_orders                    = $this->scrap_web->webserv_call($url_orders, FALSE, 'get', FALSE, FALSE);
		$dt_body['orders']              = $call_orders;

		// Load the view
		$this->load->view('orders/main_orders_page', $dt_body);


		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}


	/*
	|--------------------------------------------------------------------------
	| VIEW AN ORDER
	|--------------------------------------------------------------------------
	*/
	function view()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- SOME VARiABLES ------------------------------
		$acc_type                       = $this->session->userdata('sv_acc_type');
		$customer_org_id                = $this->scrap_web->get_customer_org_id();
		$customer_user_id               = $this->scrap_web->get_customer_user_id();
		$order_id                       = $this->uri->segment(3);


		// ----- HEADER ------------------------------------
		// Some variables
		$dt_header['title'] 	        = 'FastSells';
		$dt_header['crt_page']	        = 'pageMyOrders';
		$dt_header['extra_css']         = array('fastsells', 'fastsell_my_order');
		$dt_header['extra_js']          = array('fastsells', 'fastsell_my_order');

		// Load header
		$this->load->view('universal/header', $dt_header);


		// ----- CONTENT ------------------------------------
		// Current order
		$url_crt_order                  = 'fastsellorders/.json?id='.$order_id;
		$call_crt_order                 = $this->scrap_web->webserv_call($url_crt_order, FALSE, 'get', FALSE, FALSE);

		// Get current addresses
		$url_addresses                  = 'addresses/.jsons?customerid='.$customer_org_id;
		$call_addresses                 = $this->scrap_web->webserv_call($url_addresses, FALSE, 'get', FALSE, FALSE);

		// Parse variables
		$dt_body['order']               = TRUE;
		$dt_body['crt_order']           = $call_crt_order['result'];
		$dt_body['addresses']           = $call_addresses['result'];

		// Load the view
		$this->load->view('orders/full_order', $dt_body);


		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}
}

/* End of file my_orders.php */
/* Location: scrap_engine/controllers/my_orders.php */