<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		
		// ----- LOGIN CHECK ----------------------------------------
		$this->scrap_wall->login_check('check');
	}


	/*
	|--------------------------------------------------------------------------
	| MAIN DASHBOARD PAGE
	|--------------------------------------------------------------------------
	*/
	function index()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FAlSE);
		$this->load->library('table');


		// ----- SOME VARIABLES ------------------------------
		$acc_type                       = $this->session->userdata('sv_acc_type');
		if($acc_type == 'show_host')
		{
			$show_host_id               = $this->scrap_web->get_show_host_id();
		}
		elseif($acc_type == 'customer')
		{
			$customer_id                = $this->scrap_web->get_customer_org_id();
			$customer_user_id           = $this->scrap_web->get_customer_user_id();
		}
		
		
		// ----- HEADER ------------------------------------		
		// Some variables
		$dt_header['title'] 	        = 'FastSell Dashboard';
		$dt_header['crt_page']	        = 'pageDashboard';
		$dt_header['extra_css']         = array('fastsells');
		$dt_header['extra_js']          = array('plugin_countdown', 'fastsells');
		
		// Load header
		$this->load->view('universal/header', $dt_header);
		
		
		// ----- CONTENT ------------------------------------
		if($acc_type == 'show_host')
		{
			redirect('fastsells');
//			// Get the fastsells
//			$url_fastsells                  = 'fastsellevents/.jsons?showhostid='.$show_host_id;
//			$call_fastsells                 = $this->scrap_web->webserv_call($url_fastsells, FALSE, 'get', FALSE, FALSE);
//			$dt_body['fastsells']           = $call_fastsells;
//
//			// Get the definitions
//			$url_definitions                = 'catalogitemdefinitions/.jsons?showhostid='.$show_host_id;
//			$call_definitions               = $this->scrap_web->webserv_call($url_definitions, FALSE, 'get', FALSE, FALSE);
//			$dt_body['definitions']         = $call_definitions;
//
//			// Orders
//			$dt_body['orders']['error']     = TRUE;
//
//			// Load the view
//			$this->load->view('dashboard/main_dashboard', $dt_body);
		}
		elseif($acc_type == 'customer')
		{
			// Get the fastsells
			$url_fastsells                  = 'fastsellevents/.jsons?customerid='.$customer_id;
			$call_fastsells                 = $this->scrap_web->webserv_call($url_fastsells, FALSE, 'get', FALSE, FALSE);
			$dt_body['fastsells']           = $call_fastsells;

			// Get current address
			$url_address                    = 'addresses/.jsons?customerid='.$customer_id;
			$call_address                   = $this->scrap_web->webserv_call($url_address, FALSE, 'get', FALSE, FALSE);
			$dt_body['address']             = $call_address;

			// Orders
			$url_orders                     = 'fastsellorders/.jsons?customeruserid='.$customer_user_id;
			$call_orders                    = $this->scrap_web->webserv_call($url_orders, FALSE, 'get', FALSE, FALSE);
			$dt_body['orders']              = $call_orders;

			// Load the view
			$this->load->view('dashboard/customer_dashboard', $dt_body);
		}
		
		
		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}
}

/* End of file dashboard.php */
/* Location: scrap_engine/controllers/dashboard.php */