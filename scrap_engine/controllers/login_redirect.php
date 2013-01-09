<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login_redirect extends CI_Controller 
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
		$this->output->enable_profiler(TRUE);
		
		
		// ----- SOME VARIABLES ----------------------------------
// 		$landing_page			= $this->session->userdata('sv_landing_page');
		$landing_page			= 'dashboard';
		
		
		// ----- REDIRECT ------------------------------------
		redirect($landing_page);
	}
}

/* End of file login_redirect.php */
/* Location: scrap_engine/controllers/login_redirect.php */