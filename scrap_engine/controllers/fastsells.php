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
		
		
		// ----- HEADER ------------------------------------		
		// Some variables
		$dt_header['title'] 	        = 'FastSells';
		$dt_header['crt_page']	        = 'pageFastSells';
		
		// Load header
		$this->load->view('universal/header', $dt_header);
		
		
		// ----- CONTENT ------------------------------------
		// Navigation view
		$dt_nav['app_page']	            = 'pageFastSells';
		$this->load->view('universal/navigation', $dt_nav);
		
		
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


		// ----- SOME LOADS ------------------------------------
		$this->load->library('table');


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

		// Load the view
		$this->load->view('fastsell/create_fastsell_event');


		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}
}

/* End of file fastsells.php */
/* Location: scrap_engine/controllers/fastsells.php */