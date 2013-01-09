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
		
		
		// ----- HEADER ------------------------------------		
		// Some variables
		$dt_header['title'] 	        = 'FastSell Dashboard';
		$dt_header['crt_page']	        = 'pageDashboard';
		
		// Load header
		$this->load->view('universal/header', $dt_header);
		
		
		// ----- CONTENT ------------------------------------
		// Navigation view
		$dt_nav['app_page']	            = 'pageDashboard';
		$this->load->view('universal/navigation', $dt_nav);

		// Load the view
		$this->load->view('dashboard/main_dashboard');
		
		
		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}
}

/* End of file dashboard.php */
/* Location: scrap_engine/controllers/dashboard.php */