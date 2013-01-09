<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Forgot_password extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		
		// ----- LOGIN CHECK ----------------------------------------
		$this->scrap_wall->login_check('open');
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
		$dt_header['title'] 		= 'Forgot Your Password?';
		$dt_header['crt_page']		= 'pageForgotPassword';
		$dt_header['extra_css']		= array('forgot_password');
		$dt_header['extra_js']		= array('forgot_password');
		
		// Load header
		$this->load->view('universal/header', $dt_header);
		
		
		// ----- CONTENT ------------------------------------
		$this->load->view('access/forgot_password');
	}


	/*
	|--------------------------------------------------------------------------
	| RESET PASSWORD
	|--------------------------------------------------------------------------
	*/
	function reset_password()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);
		
		
		// ----- SOME VARIABLES ------------------------------------
		$token						= $this->uri->segment(3);
		
		
		// ----- HEADER ------------------------------------		
		// Some variables
		$dt_header['title'] 		= 'Reset Your Password?';
		$dt_header['crt_page']		= 'pageForgotPassword';
		$dt_header['extra_css']		= array('forgot_password');
		$dt_header['extra_js']		= array('forgot_password');
		
		// Load header
		$this->load->view('universal/header', $dt_header);
		
		
		// ----- CONTENT ------------------------------------
		$dt_body['token']			= $token;
		$this->load->view('access/reset_password', $dt_body);
	}
}

/* End of file forgot_password.php */
/* Location: scrap_engine/controllers/forgot_password.php */