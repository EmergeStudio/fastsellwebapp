<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		
		// ----- LOGIN CHECK ----------------------------------------
		$this->scrap_wall->login_check('open');
	}


	/*
	|--------------------------------------------------------------------------
	| INDEX FUNCTION
	|--------------------------------------------------------------------------
	*/
	function index()
	{
		// Redirect
		redirect('signup/show_host');
	}


	/*
	|--------------------------------------------------------------------------
	| SHOW HOST SIGNUP
	|--------------------------------------------------------------------------
	*/
	function show_host()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);
		
		
		// ----- HEADER ------------------------------------		
		// Some variables
		$dt_header['title'] 	= 'Show Host Sign Up';
		$dt_header['crt_page']	= 'pageSignup';
		$dt_header['extra_css']	= array('signup');
		$dt_header['extra_js']	= array('signup_show_host');
		
		// Load header
		$this->load->view('universal/header', $dt_header);
		
		
		// ----- CONTENT ------------------------------------
		$this->load->view('signup/show_host');
	}
}

/* End of file signup.php */
/* Location: scrap_engine/controllers/signup.php */