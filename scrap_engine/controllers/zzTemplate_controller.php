<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Template_name extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		
		// ----- LOGIN CHECK ----------------------------------------
		$this->scrap_wall->login_check('check');
	}


	/*
	|--------------------------------------------------------------------------
	| FUNCTION NAME
	|--------------------------------------------------------------------------
	*/
	function index()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(TRUE);
		
		
		// ----- HEADER ------------------------------------		
		// Some variables
		$dt_header['title'] 	        = '';
		$dt_header['crt_page']	        = '';
		
		// Load header
		$this->load->view('universal/header', $dt_header);
		
		
		// ----- CONTENT ------------------------------------
		//$this->load->view();
		
		
		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}
}

/* End of file template_name.php */
/* Location: scrap_engine/controllers/template_name.php */