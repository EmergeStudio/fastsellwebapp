<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		
		// ----- LOGIN CHECK ----------------------------------------
		$this->scrap_wall->login_check('check');
	}


	/*
	|--------------------------------------------------------------------------
	| REDIRECT INDEX
	|--------------------------------------------------------------------------
	*/
	function index()
	{
		// Redirect
		redirect('reports/orders_summary');
	}


	/*
	|--------------------------------------------------------------------------
	| ORDERS SUMMARY
	|--------------------------------------------------------------------------
	*/
	function orders_summary()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- SOME VARiABLES ------------------------------
		$acc_type                           = $this->session->userdata('sv_acc_type');
		$event_id                           = $this->uri->segment(3);


		// ----- HEADER ------------------------------------
		// Some variables
		$dt_header['title'] 	            = 'Orders Summary';
		$dt_header['crt_page']	            = 'pageReports';
		$dt_header['extra_css']             = array('reports');
		$dt_header['extra_js']              = array('orders_summary');

		// Load header
		$this->load->view('universal/header', $dt_header);


		// ----- CONTENT ------------------------------------
		if($acc_type == 'show_host')
		{
			// Some variables
			$show_host_id                   = $this->scrap_web->get_show_host_id();

			// Navigation view
			$dt_nav['app_page']	            = 'pageReports';
			$this->load->view('universal/navigation', $dt_nav);

			// Check to see if the event id is set
			$url_fastsells              = 'fastsellevents/.jsons?showhostid='.$show_host_id;
			$call_fastsells             = $this->scrap_web->webserv_call($url_fastsells, FALSE, 'get', FALSE, FALSE);
			$dt_body['fastsells']       = $call_fastsells;
			$dt_body['header_text']     = 'Orders Summary By FastSell';

			// Load the view
			$this->load->view('reports/fastsells_select_list', $dt_body);
		}


		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}


	/*
	|--------------------------------------------------------------------------
	| CUSTOMER ORDERS
	|--------------------------------------------------------------------------
	*/
	function customer_orders()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- SOME VARiABLES ------------------------------
		$acc_type                           = $this->session->userdata('sv_acc_type');


		// ----- HEADER ------------------------------------
		// Some variables
		$dt_header['title'] 	            = 'Orders Summary';
		$dt_header['crt_page']	            = 'pageReports';
		$dt_header['extra_css']             = array('reports');
		$dt_header['extra_js']              = array('orders_customers_report');

		// Load header
		$this->load->view('universal/header', $dt_header);


		// ----- CONTENT ------------------------------------
		if($acc_type == 'customer')
		{
			// Some variables
			$customer_id                    = $this->scrap_web->get_customer_org_id();

			// Navigation view
			$dt_nav['app_page']	            = 'pageReports';
			$this->load->view('universal/customer_navigation', $dt_nav);

			// Get the fastsells
			$url_fastsells                  = 'fastsellevents/.jsons?customerid='.$customer_id;
			$call_fastsells                 = $this->scrap_web->webserv_call($url_fastsells, FALSE, 'get', FALSE, FALSE);
			$dt_body['fastsells']           = $call_fastsells;
			$dt_body['header_text']         = 'Orders By FastSell';

			// Load the view
			$this->load->view('reports/fastsells_select_list', $dt_body);
		}


		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}


	/*
	|--------------------------------------------------------------------------
	| ORDERS BY EVENT
	|--------------------------------------------------------------------------
	*/
	function orders_by_event()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- SOME VARiABLES ------------------------------
		$acc_type                           = $this->session->userdata('sv_acc_type');
		$event_id                           = $this->uri->segment(3);


		// ----- HEADER ------------------------------------
		// Some variables
		$dt_header['title'] 	            = 'Orders By FastSell';
		$dt_header['crt_page']	            = 'pageReports';
		$dt_header['extra_css']             = array('reports');
		$dt_header['extra_js']              = array('orders_by_event');

		// Load header
		$this->load->view('universal/header', $dt_header);


		// ----- CONTENT ------------------------------------
		if($acc_type == 'show_host')
		{
			// Some variables
			$show_host_id                   = $this->scrap_web->get_show_host_id();

			// Navigation view
			$dt_nav['app_page']	            = 'pageReports';
			$this->load->view('universal/navigation', $dt_nav);

			// Check to see if the event id is set
			if(!empty($event_id))
			{
				// Report
				$url_report                 = 'reports/fastsellorders/ordersbyshowhostandevent/.json?showhostid='.$show_host_id.'&fastselleventid='.$event_id;
				$call_report                = $this->scrap_web->webserv_call($url_report, FALSE, 'get', FALSE, FALSE);
				$dt_body['report']          = $call_report;

				// Fastsell information
				$url_fastsell               = 'fastsellevents/.json?id='.$event_id;
				$call_fastsell              = $this->scrap_web->webserv_call($url_fastsell, FALSE, 'get', FALSE, TRUE);
				$dt_body['fastsell']        = $call_fastsell;
			}
			else
			{
				// Get the fastsells
				$url_fastsells              = 'fastsellevents/.jsons?showhostid='.$show_host_id;
				$call_fastsells             = $this->scrap_web->webserv_call($url_fastsells, FALSE, 'get', FALSE, FALSE);
				$dt_body['fastsells']       = $call_fastsells;
				$dt_body['header_text']     = 'Orders By FastSell Event';

				// Load the view
				$this->load->view('reports/fastsells_select_list', $dt_body);
			}
		}


		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}


	/*
	|--------------------------------------------------------------------------
	| ORDERS BY DATE
	|--------------------------------------------------------------------------
	*/
	function orders_by_date()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);


		// ----- SOME VARiABLES ------------------------------
		$acc_type                           = $this->session->userdata('sv_acc_type');


		// ----- HEADER ------------------------------------
		// Some variables
		$dt_header['title'] 	            = 'Orders By Date';
		$dt_header['crt_page']	            = 'pageReports';
		$dt_header['extra_css']             = array('reports');
		$dt_header['extra_js']              = array('orders_by_date');

		// Load header
		$this->load->view('universal/header', $dt_header);


		// ----- CONTENT ------------------------------------
		if($acc_type == 'show_host')
		{
			// Navigation view
			$dt_nav['app_page']	            = 'pageReports';
			$this->load->view('universal/navigation', $dt_nav);

			// Some variables
			$dt_body['header_text']         = 'Orders By Date Selection';

			// Load the view
			$this->load->view('reports/fastsells_report_by_date', $dt_body);
		}


		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}
}

/* End of file template_name.php */
/* Location: scrap_engine/controllers/template_name.php */