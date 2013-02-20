<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
	}


	/*
	|--------------------------------------------------------------------------
	| LOGOUT Function
	|--------------------------------------------------------------------------
	|
	| Here the users session data is destroyed before being navgated back to
	| the login page.
	|
	*/
	function index()
	{		
		// Destroy the session data
		$this->session->unset_userdata('sv_logged_in');
		$this->session->sess_destroy();
		
		// Redirect
		redirect('http://fastsellqa.emergestudio.net/');
	}
}

/* End of file logout.php */
/* Location: ./scrap_application/engine/controllers/logout.php */