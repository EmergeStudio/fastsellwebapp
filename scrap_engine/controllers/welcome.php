<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		
		// ----- LOGIN CHECK ----------------------------------------
		$this->scrap_wall->login_check('open');
	}


	/*
	|--------------------------------------------------------------------------
	| FUNCTION NAME
	|--------------------------------------------------------------------------
	*/
	function index()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);
		
		
		// ----- HEADER ------------------------------------		
		// Some variables
		$dt_header['title'] 	        = 'Welcome To FastSell';
		$dt_header['crt_page']	        = 'publicWelcome';
		
		// Load header
		$this->load->view('public/header', $dt_header);
		
		
		// ----- CONTENT ------------------------------------
		$this->load->view('public/welcome');
		
		
		// ----- FOOTER ------------------------------------
		$this->load->view('public/footer');
	}
}

/* End of file welcome.php */
/* Location: scrap_engine/controllers/welcome.php */