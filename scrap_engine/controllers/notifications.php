<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notifications extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		
		// ----- LOGIN CHECK ----------------------------------------
		$this->scrap_wall->login_check('check');
	}


	/*
	|--------------------------------------------------------------------------
	| MAIN INDEX PAGE
	|--------------------------------------------------------------------------
	*/
	function index()
	{
		// ----- APPLICATION PROFILER --------------------------------
		$this->output->enable_profiler(FALSE);
		
		
		// ----- HEADER ------------------------------------		
		// Some variables
		$dt_header['title'] 	= 'Notifications';
		$dt_header['crt_page']	= 'pageNotifications';
		$dt_header['extra_css']	= array('notifications');
		
		// Load header
		$this->load->view('universal/header', $dt_header);
		
		
		// ----- CONTENT ------------------------------------
		$this->load->view('universal/notifications');
		
		
		// ----- FOOTER ------------------------------------
		$this->load->view('universal/footer');
	}


	/*
	|--------------------------------------------------------------------------
	| REDIRECT PAGE
	|--------------------------------------------------------------------------
	*/
	function redirect()
	{
		// Redirect back to natofication page
		redirect('notifications');
	}
}

/* End of file template_name.php */
/* Location: scrap_engine/controllers/template_name.php */