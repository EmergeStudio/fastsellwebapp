<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends CI_Controller
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
		
		
		// ----- HEADER ------------------------------------		
		// Some variables
		$dt_header['title'] 	        = 'My Orders';
		$dt_header['crt_page']	        = 'pageOrders';
		
		// Load header
		$this->load->view('universal/header', $dt_header);
		
		
		// ----- CONTENT ------------------------------------
		// Navigation view
		$dt_nav['app_page']		        = 'pageOrders';
		$this->load->view('universal/navigation', $dt_nav);
		
		
		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}
}

/* End of file orders.php */
/* Location: scrap_engine/controllers/orders.php */