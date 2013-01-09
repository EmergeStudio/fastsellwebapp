<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
	}


	/*
	|---------------------------------------------------------------------------
	| LOGIN
	|---------------------------------------------------------------------------
	*/
	function index()
	{		
		// ----- HEADER ------------------------------------
		// Some variables
		$dt_header['title'] 	= 'FastSell Login';
		$dt_header['crt_page']	= 'pageLogin';
		$dt_header['extra_js']	= array('plugin_enter_it', 'base_login');
		$dt_header['extra_css']	= array('login');
			
		// Load header
		$this->load->view('universal/header', $dt_header);
			
			
			
		// ----- CONTENT ------------------------------------
		$this->load->view('access/login');
	}


	/*
	|---------------------------------------------------------------------------
	| LOGIN REDIRECT
	|---------------------------------------------------------------------------
	|
	| Control the redirect after login
	|
	*/
	function redirect()
	{
		// Starting at the logn session should always destroy existing sessions
		// Destroy the session data
		$this->session->unset_userdata('sv_logged_in');
		$this->session->sess_destroy();
			
		// ----- HEADER ------------------------------------		
		// Some variables
		$dt_header['title'] 	= 'TradeShow Login';
		$dt_header['crt_page']	= 'pageLogin';
		$dt_header['extra_js']	= array('plugin_enter_it', 'base_login');
		$dt_header['extra_css']	= array('login');
		
		// Load header
		$this->load->view('universal/header', $dt_header);
		
		
		
		// ----- CONTENT ------------------------------------
		$this->load->view('access/login');
	}
}

/* End of file login.php */
/* Location: ./scrap_application/engine/controllers/login.php */